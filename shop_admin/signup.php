<?php
ob_start();               // Buffer start
session_start();
require __DIR__ . '/config/dbconnect.php'; // DB connection

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'signup') {
        $fullName = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        $role = 'admin'; // default role
        $username = $email; // use email as username

        if ($password !== $confirmPassword) {
            $error = "⚠️ Passwords do not match!";
        } else {
            // check if username/email exists
            $stmt = $conn->prepare("SELECT id FROM admin WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows > 0) {
                $error = "⚠️ Email/Username already exists!";
            } else {
                $stmt2 = $conn->prepare("INSERT INTO admin (full_name, email, username, password, role) VALUES (?, ?, ?, ?, ?)");
                $stmt2->bind_param("sssss", $fullName, $email, $username, $password, $role);
                if ($stmt2->execute()) {
                    $_SESSION['signup_success'] = true;
                    header("Location: log.php?registered=1");
                    exit();
                } else {
                    $error = "⚠️ Error registering admin: " . $conn->error;
                }
                $stmt2->close();
            }
            $stmt->close();
        }
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
    <title>Admin Sign Up - Ishahiya</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../image/logo/ishahiya-logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../image/logo/ishahiya-logo.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            padding: 20px 30px;
            /* Tighter padding */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .logo img {
            width: 90px;
            /* Slightly smaller to save vertical space */
            height: auto;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 20px;
            /* Slightly smaller to save vertical space */
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
            text-align: center;
            letter-spacing: 0.5px;
        }

        form {
            width: 100%;
            max-width: 320px;
        }

        .input-group {
            margin-bottom: 12px;
            /* Tighter spacing */
            text-align: center;
        }

        .input-group label {
            display: block;
            font-size: 12px;
            /* Tighter label */
            margin-bottom: 4px;
            color: #555;
            text-align: center;
            font-weight: 500;
        }

        .input-group input {
            width: 100%;
            padding: 8px 20px;
            /* Tighter input padding */
            font-size: 13px;
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

        .btn {
            width: 100%;
            padding: 10px;
            /* Tighter button padding */
            border: none;
            border-radius: 25px;
            background: #2ecc71;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 5px;
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
                /* Optimized for mobile/tablet */
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

        <!-- Right Section with Signup Form -->
        <div class="right-section">
            <div class="logo">
                <img src="../image/logo/ishahiya-logo.png" alt="Ishahiya Logo">
            </div>
           
            <h2>REGISTER</h2>

            <?php if ($error): ?>
                <div style="background: <?php echo strpos($error, '✅') !== false ? '#d4edda' : '#ffd2d2'; ?>; 
                            color: <?php echo strpos($error, '✅') !== false ? '#155724' : '#d8000c'; ?>; 
                            padding: 10px; margin-bottom: 15px; border-radius: 5px; font-size: 14px; width: 100%; text-align: center;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="signup.php" method="POST">
                <input type="hidden" name="action" value="signup">
                <div class="input-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" placeholder="" required>
                </div>
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="" required>
                </div>
                <div class="input-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="" required>
                </div>
                <button type="submit" class="btn">SIGN UP</button>

                <p style="margin-top: 20px; font-size: 13px; color: #555; text-align: center;">
                    <a href="log.php" style="color: #27ae60; text-decoration: none; font-weight: 500;">Back to Login</a>
                </p>
            </form>
        </div>
    </div>
</body>

</html>