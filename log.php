<?php
session_start(); // Start the session

// Include your database connection
require 'db.php'; // Ensure the path to db.php is correct

$error_msg = "";
$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle login
    if ($_POST['action'] == 'login') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT id, email, password, role FROM user WHERE email = ?");
        if ($stmt === false) {
            die('Error preparing the SQL statement: ' . mysqli_error($conn));
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['role'] = 'user'; // Strictly enforce end-user role
                $_SESSION['username'] = $row['email'];
                unset($_SESSION['is_admin_logged_in']);

                // Merge cart logic...
                if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $product_id => $item) {
                        $qty = (int)$item['qty'];
                        $check = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
                        $check->bind_param("ii", $row['id'], $product_id);
                        $check->execute();
                        $check->store_result();
                        if ($check->num_rows > 0) {
                            $update = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
                            $update->bind_param("iii", $qty, $row['id'], $product_id);
                            $update->execute();
                        } else {
                            $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
                            $insert->bind_param("iii", $row['id'], $product_id, $qty);
                            $insert->execute();
                        }
                    }
                    unset($_SESSION['cart']);
                }

                header("Location: index.php");
                exit();
            } else {
                $error_msg = "Invalid password!";
            }
        } else {
            $error_msg = "No user found with this email!";
        }
        $stmt->close();
    }

    // Handle sign up
    if ($_POST['action'] == 'signup') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordConfirm = $_POST['passwordConfirm'];

        if ($password !== $passwordConfirm) {
            $error_msg = "Passwords do not match!";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("SELECT id FROM user WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error_msg = "Email is already registered!";
            } else {
                $stmt = $conn->prepare("INSERT INTO user (name, email, password, role, created_at) VALUES (?, ?, ?, 'user', NOW())");
                $stmt->bind_param("sss", $username, $email, $hashedPassword);
                if ($stmt->execute()) {
                    $success_msg = "Registration successful! Please login.";
                } else {
                    $error_msg = "Error: " . $stmt->error;
                }
            }
            $stmt->close();
        }
    }
}
$conn->close();

// Counts for header
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) { $cart_count += (int)$item['quantity']; }
}
$wishlist_count = isset($_SESSION["wishlist"]) ? count($_SESSION["wishlist"]) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Signup | Ishahiya</title>
    <!-- Use Bootstrap 5 for grid and tabs, but override styles for premium feel -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

        :root {
            --primary: hsl(176, 88%, 27%);
            --primary-hover: hsl(176, 88%, 20%);
            --bg-color: #f4f7f6;
            --text-main: #2d3436;
            --text-muted: #636e72;
            --card-bg: #ffffff;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            background-image: radial-gradient(circle at top right, #e2f2e5 0%, #f4f7f6 50%);
        }

        .auth-wrapper {
            width: 100%;
            max-width: 1000px;
            margin: 2rem;
            display: flex;
            background: var(--card-bg);
            border-radius: 24px;
            box-shadow: 0 24px 48px rgba(0,0,0,0.06);
            overflow: hidden;
            position: relative;
        }

        /* Left Side: Branding / Image */
        .auth-banner {
            flex: 1;
            background: linear-gradient(135deg, var(--primary) 0%, #065e57 100%);
            color: #fff;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .auth-banner::before {
            content: '';
            position: absolute;
            top: -50%; right: -50%; bottom: -50%; left: -50%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
            animation: pulse 10s infinite alternate;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            100% { transform: scale(1.2); }
        }

        .auth-banner img {
            width: 140px;
            margin-bottom: 2rem;
            z-index: 1;
        }

        .auth-banner h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            z-index: 1;
            font-family: 'Outfit', sans-serif;
        }

        .auth-banner p {
            font-size: 1.1rem;
            opacity: 0.9;
            z-index: 1;
        }

        /* Right Side: Form */
        .auth-content {
            flex: 1;
            padding: 4rem 3rem;
            background: var(--card-bg);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .nav-pills {
            background: #f1f2f6;
            border-radius: 50px;
            padding: 5px;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
        }

        .nav-pills .nav-item {
            flex: 1;
            text-align: center;
        }

        .nav-pills .nav-link {
            border-radius: 50px;
            color: var(--text-muted);
            font-weight: 600;
            padding: 12px 0;
            transition: all 0.3s ease;
        }

        .nav-pills .nav-link.active {
            background: var(--card-bg);
            color: var(--primary);
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .form-label {
            font-weight: 500;
            color: var(--text-main);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-group-custom i {
            position: absolute;
            left: 16px;
            top: 42px; /* Positioned below the label */
            color: #a4b0be;
            transition: 0.3s;
        }

        .form-control {
            background: #f8f9fa;
            border: 1px solid #dfe4ea;
            border-radius: 12px;
            padding: 14px 14px 14px 45px;
            font-size: 1rem;
            color: var(--text-main);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(8, 129, 120, 0.1);
        }

        .form-control:focus + i, .input-group-custom:focus-within i {
            color: var(--primary);
        }

        .btn-main {
            background: var(--primary);
            color: #fff;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 14px;
            border: none;
            border-radius: 12px;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 1rem;
            box-shadow: 0 8px 20px rgba(8, 129, 120, 0.2);
        }

        .btn-main:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(8, 129, 120, 0.3);
        }

        .alert {
            border-radius: 12px;
            font-weight: 500;
            border: none;
        }

        .back-home {
            text-align: center;
            margin-top: 2rem;
        }
        
        .back-home a {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .back-home a:hover {
            color: var(--primary);
        }

        @media (max-width: 768px) {
            .auth-wrapper {
                flex-direction: column;
                margin: 1rem;
            }
            .auth-banner {
                padding: 2rem;
                display: none; /* Hide banner on very small screens to focus on login */
            }
            .auth-content {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>

    <div class="auth-wrapper">
        <!-- Left Side: Branding Banner -->
        <div class="auth-banner d-none d-md-flex">
            <img src="image/logo/logo8.png" alt="Ishahiya Logo">
            <h2>Welcome to Ishahiya</h2>
            <p>Discover the latest trends in fashion and shop our exclusive collections. Step in to elevate your style!</p>
        </div>

        <!-- Right Side: Forms -->
        <div class="auth-content">
            <!-- Alerts (Unchanged PHP logic triggers these) -->
            <?php if(!empty($error_msg)): ?>
                <div class="alert alert-danger mb-4"><i class="fas fa-exclamation-circle me-2"></i><?= $error_msg ?></div>
            <?php endif; ?>
            <?php if(!empty($success_msg)): ?>
                <div class="alert alert-success mb-4"><i class="fas fa-check-circle me-2"></i><?= $success_msg ?></div>
            <?php endif; ?>

            <!-- Tabs -->
            <ul class="nav nav-pills" id="authTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="login-tab" data-bs-toggle="pill" data-bs-target="#login" type="button" role="tab">Login</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="signup-tab" data-bs-toggle="pill" data-bs-target="#signup" type="button" role="tab">Sign Up</button>
                </li>
            </ul>

            <div class="tab-content" id="authTabsContent">
                <!-- Login Form -->
                <div class="tab-pane fade show active" id="login" role="tabpanel">
                    <form action="log.php" method="POST">
                        <input type="hidden" name="action" value="login">
                        
                        <div class="input-group-custom">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required placeholder="Enter your email">
                            <i class="fas fa-envelope"></i>
                        </div>

                        <div class="input-group-custom">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required placeholder="Enter your password">
                            <i class="fas fa-lock"></i>
                        </div>

                        <button type="submit" class="btn-main">Sign In</button>
                    </form>
                </div>

                <!-- Signup Form -->
                <div class="tab-pane fade" id="signup" role="tabpanel">
                    <form action="log.php" method="POST">
                        <input type="hidden" name="action" value="signup">

                        <div class="input-group-custom">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="username" class="form-control" required placeholder="John Doe">
                            <i class="fas fa-user"></i>
                        </div>

                        <div class="input-group-custom">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required placeholder="name@example.com">
                            <i class="fas fa-envelope"></i>
                        </div>

                        <div class="input-group-custom">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required placeholder="Min 8 characters">
                            <i class="fas fa-lock"></i>
                        </div>

                        <div class="input-group-custom">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="passwordConfirm" class="form-control" required placeholder="Repeat password">
                            <i class="fas fa-lock"></i>
                        </div>

                        <button type="submit" class="btn-main">Create Account</button>
                    </form>
                </div>
            </div>

            <!-- Return to Store Link -->
            <div class="back-home">
                <a href="index.php"><i class="fas fa-arrow-left me-2"></i>Return to Store</a>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
