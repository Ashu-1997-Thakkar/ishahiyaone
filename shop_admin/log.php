<?php
ob_start();               // Buffer start
session_start();
require __DIR__ . '/config/dbconnect.php'; // DB connection

$error = '';
$success = '';

if (isset($_GET['registered']) && $_GET['registered'] == 1 && isset($_SESSION['signup_success'])) {
    $success = "✅ Sign Up successful! Your account is PENDING Super Admin approval. You can login once permission is granted.";
    unset($_SESSION['signup_success']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'login') {
        $username = trim($_POST['username']);
        $password1 = $_POST['password'];

        // Ensure status column exists in admin table
        $colCheck = $conn->query("SHOW COLUMNS FROM admin LIKE 'status'");
        if ($colCheck && $colCheck->num_rows === 0) {
            $conn->query("ALTER TABLE admin ADD COLUMN status VARCHAR(20) NOT NULL DEFAULT 'approved'");
        }

        $stmt = $conn->prepare("SELECT id, username, password, role, COALESCE(status, 'approved') as status FROM admin WHERE username = ?");
        if (!$stmt) {
            die('SQL Error: ' . $conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();

        $res = $stmt->get_result();
        if ($res->num_rows === 1) {
            $row = $res->fetch_assoc();

            if ($password1 === $row['password']) {
                if ($row['status'] === 'pending') {
                    $error = "⏳ Access Denied: Your admin account is currently PENDING approval from the Super Admin.";
                } elseif ($row['status'] === 'revoked' || $row['status'] === 'inactive' || $row['status'] === 'disabled') {
                    $error = "🚫 Access Revoked: Your administrative permissions have been revoked.";
                } else {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['admin_status'] = $row['status'];
                    $_SESSION['is_admin_logged_in'] = true;

                    header("Location: index.php");
                    exit();
                }
            } else {
                $error = "⚠️ Invalid password!";
            }
        } else {
            $error = "⚠️ No admin found with username!";
        }

        $stmt->close();
    }
}

$conn->close();
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login - Ishahiya</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../image/logo/ishahiya-logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/logo/ishahiya-logo.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: url('../image/etc/back.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            display: flex;
            width: 90%;
            max-width: 1200px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            overflow: hidden;
            background: #fff;
        }

        .left-section {
            flex: 3.0;
            background-image: url('../image/etc/promo_image_of_Ishahiya.png');
            background-size: cover;
            background-position: right center;
        }

        .right-section {
            flex: 1;
            background-color: #ffffff;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .logo img {
            width: 100px;
            height: auto;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 22px;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            letter-spacing: 0.5px;
        }

        form {
            width: 100%;
            max-width: 320px;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: center;
        }

        .input-group label {
            display: block;
            font-size: 13px;
            margin-bottom: 8px;
            color: #555;
            text-align: center;
            font-weight: 500;
        }

        .input-group input {
            width: 100%;
            padding: 12px 20px;
            font-size: 14px;
            background: #edf2f7;
            border: 1px solid #d2d6dc;
            border-radius: 25px;
            outline: none;
            transition: border 0.3s;
            box-sizing: border-box;
        }

        .input-group input:focus {
            border: 1px solid #2ecc71;
            background: #fff;
        }

        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .password-wrapper input {
            padding-right: 45px !important;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
            font-size: 16px;
            transition: color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .toggle-password:hover {
            color: #2ecc71;
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 25px;
            background: #2ecc71;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
            letter-spacing: 0.5px;
        }

        .btn:hover {
            background: #27ae60;
        }

        @media (max-width: 900px) {
            .container {
                flex-direction: column;
                margin: 20px;
                max-width: 500px;
            }

            .left-section {
                height: 250px;
                flex: none;
                background-position: center;
            }

            .right-section {
                padding: 40px 30px;
                flex: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Left Section with Image -->
        <div class="left-section"></div>

        <!-- Right Section with Login Form -->
        <div class="right-section">
            <div class="logo">
                <img src="../image/logo/ishahiya-logo.png" alt="Ishahiya Logo">
            </div>
            <h2>WELCOME</h2>

            <?php if ($success): ?>
                <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px; font-size: 14px; width: 100%; text-align: center;">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <?php if ($error || (isset($_GET['error']) && $_GET['error'] === 'rbac_denied')): ?>
                <div style="background: <?php echo strpos($error, '✅') !== false ? '#d4edda' : '#ffd2d2'; ?>; 
                            color: <?php echo strpos($error, '✅') !== false ? '#155724' : '#d8000c'; ?>; 
                            padding: 10px; margin-bottom: 15px; border-radius: 5px; font-size: 14px; width: 100%; text-align: center;">
                    <?= htmlspecialchars($error ?: "🛑 Access Denied: Admin authentication required to access this resource.") ?>
                </div>
            <?php endif; ?>

            <form action="log.php" method="POST">
                <input type="hidden" name="action" value="login">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="" required>
                        <span class="toggle-password" onclick="togglePasswordVisibility('password', this)"><i class="fa-regular fa-eye"></i></span>
                    </div>
                </div>
                <button type="submit" class="btn">LOGIN</button>
                <div style="margin-top: 15px; text-align: center;">
                    <a href="forgot_password.php" style="color: #666; text-decoration: none; font-size: 13px; font-weight: 500; transition: color 0.3s;">Forgot Password?</a>
                </div>
                <div style="margin-top: 12px; text-align: center;">
                    <a href="signup.php" style="color: #27ae60; text-decoration: none; font-size: 13px; font-weight: 600; transition: color 0.3s;">Create an Ishahiya Admin Account</a>
                </div>
            </form>
        </div>
    </div>
    <script>
        // Clean URL hash fragments left over from admin dashboard redirects
        if (window.location.hash) {
            history.replaceState(null, null, window.location.pathname + window.location.search);
        }

        function togglePasswordVisibility(inputId, el) {
            const input = document.getElementById(inputId);
            const icon = el.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>