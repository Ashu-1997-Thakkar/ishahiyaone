<?php
ob_start();               // Buffer start
session_start();
require __DIR__ . '/config/dbconnect.php'; // DB connection

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'reset_password') {
        $identity = trim($_POST['identity'] ?? '');
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($identity) || empty($newPassword) || empty($confirmPassword)) {
            $error = "⚠️ All fields are required!";
        } elseif ($newPassword !== $confirmPassword) {
            $error = "⚠️ New passwords do not match!";
        } elseif (strlen($newPassword) < 4) {
            $error = "⚠️ Password must be at least 4 characters long!";
        } else {
            // Check if admin exists by username or email
            $stmt = $conn->prepare("SELECT id FROM admin WHERE username = ? OR email = ?");
            if (!$stmt) {
                die('SQL Error: ' . $conn->error);
            }
            $stmt->bind_param("ss", $identity, $identity);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows > 0) {
                $row = $res->fetch_assoc();
                $adminId = $row['id'];
                $stmt->close();

                // Update password
                $stmtUpdate = $conn->prepare("UPDATE admin SET password = ? WHERE id = ?");
                $stmtUpdate->bind_param("si", $newPassword, $adminId);
                if ($stmtUpdate->execute()) {
                    $success = "✅ Password reset successfully! You can now login.";
                } else {
                    $error = "⚠️ Error updating password: " . $conn->error;
                }
                $stmtUpdate->close();
            } else {
                $error = "⚠️ No admin account found with this Username or Email!";
            }
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
    <title>Admin Forgot Password - Ishahiya</title>
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
            width: 90px;
            height: auto;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 20px;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            letter-spacing: 0.5px;
        }

        form {
            width: 100%;
            max-width: 320px;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: center;
        }

        .input-group label {
            display: block;
            font-size: 13px;
            margin-bottom: 6px;
            color: #555;
            text-align: center;
            font-weight: 500;
        }

        .input-group input {
            width: 100%;
            padding: 10px 20px;
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
            font-size: 15px;
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
            padding: 11px;
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

        <!-- Right Section with Reset Form -->
        <div class="right-section">
            <div class="logo">
                <img src="../image/logo/ishahiya-logo.png" alt="Ishahiya Logo">
            </div>
            <h2>RESET PASSWORD</h2>

            <?php if ($success): ?>
                <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px; font-size: 13px; width: 100%; text-align: center;">
                    <?= htmlspecialchars($success) ?>
                    <div style="margin-top: 8px;">
                        <a href="log.php" style="color: #155724; font-weight: bold; text-decoration: underline;">Click here to Login</a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div style="background: #ffd2d2; color: #d8000c; padding: 10px; margin-bottom: 15px; border-radius: 5px; font-size: 13px; width: 100%; text-align: center;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if (!$success): ?>
            <form action="forgot_password.php" method="POST">
                <input type="hidden" name="action" value="reset_password">
                <div class="input-group">
                    <label for="identity">Registered Username or Email</label>
                    <input type="text" id="identity" name="identity" placeholder="e.g. admin@ishahiya.com" required>
                </div>
                <div class="input-group">
                    <label for="new_password">New Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="new_password" name="new_password" placeholder="Enter new password" required>
                        <span class="toggle-password" onclick="togglePasswordVisibility('new_password', this)"><i class="fa-regular fa-eye"></i></span>
                    </div>
                </div>
                <div class="input-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>
                        <span class="toggle-password" onclick="togglePasswordVisibility('confirm_password', this)"><i class="fa-regular fa-eye"></i></span>
                    </div>
                </div>
                <button type="submit" class="btn">RESET PASSWORD</button>
            </form>
            <?php endif; ?>

            <p style="margin-top: 20px; font-size: 13px; color: #555; text-align: center;">
                <a href="log.php" style="color: #27ae60; text-decoration: none; font-weight: 500;">Back to Login</a>
            </p>
        </div>
    </div>
    <script>
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
