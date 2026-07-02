<?php
session_start();

// Database connection
$host = "localhost";
$user = "ishahiyaone";
$password = "BhaV@1437I";
$dbname = "ishahiyaone";

// Connect to the database using PDO
try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch user info if logged in
$userName = "User"; // Default user name
$userEmail = "Unknown"; // Default email
$userRole = "Unknown"; // Default role

if (isset($_SESSION["username"])) {
    $email = $_SESSION["username"];
    $stmt = $db->prepare("SELECT id, name, email, role FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $userId = $user['id'];
        $userName = htmlspecialchars($user['name']);
        $userEmail = htmlspecialchars($user['email']);
        $userRole = htmlspecialchars($user['role']);
    }
}

// Fetch wishlist and cart counts from session (or database)
$wishlist_count = isset($_SESSION["wishlist"]) ? count($_SESSION["wishlist"]) : 0;
$cart_count = isset($_SESSION["cart"]) ? count($_SESSION["cart"]) : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account | Ishahiya</title>
    <link rel="icon" type="image/png" sizes="32x32" href="image/logo/ishahiya-logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="image/logo/ishahiya-logo.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css">
    <style>
        :root {
            --gold: #d4af37;
            --gold-hover: #c49a2e;
            --dark-bg: #000000;
            --card-bg: #111111;
            --border-color: rgba(212, 175, 55, 0.2);
            --text-muted: #aaaaaa;
        }

        body {
            background-color: var(--dark-bg);
            color: #fff;
            font-family: 'Lato', sans-serif;
            overflow-x: hidden;
        }

        /* ================= COMPACT HERO ================= */
        .account-hero {
            background: #0a0a0a;
            padding: 30px 20px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 30px;
        }

        .account-hero h1 {
            color: var(--gold);
            font-size: 2rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }
        
        .account-hero .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 5px 0 0 0;
            font-size: 0.85rem;
        }

        .account-hero .breadcrumb a {
            color: var(--text-muted);
            text-decoration: none;
        }
        
        .account-hero .breadcrumb-item.active {
            color: #fff;
        }

        /* ================= DASHBOARD LAYOUT ================= */
        .dashboard-sidebar {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
        }

        .dashboard-sidebar .avatar {
            width: 80px;
            height: 80px;
            background: rgba(212, 175, 55, 0.1);
            border: 2px solid var(--gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: var(--gold);
            margin: 0 auto 15px auto;
        }

        .dashboard-sidebar h4 {
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 1.2rem;
        }

        .dashboard-sidebar p {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .dashboard-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            text-align: left;
        }

        .dashboard-menu li {
            border-top: 1px solid #222;
        }

        .dashboard-menu a {
            display: flex;
            align-items: center;
            padding: 12px 5px;
            color: #fff;
            text-decoration: none;
            transition: 0.2s;
            font-size: 0.95rem;
        }

        .dashboard-menu a i {
            width: 25px;
            color: var(--text-muted);
            transition: 0.2s;
        }

        .dashboard-menu a:hover, .dashboard-menu a.active {
            color: var(--gold);
        }

        .dashboard-menu a:hover i, .dashboard-menu a.active i {
            color: var(--gold);
        }

        .logout-btn {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            background: transparent;
            border: 1px solid #ff4d4d;
            color: #ff4d4d;
            border-radius: 6px;
            text-align: center;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #ff4d4d;
            color: #fff;
            text-decoration: none;
        }

        /* ================= ACTION CARDS ================= */
        .action-card {
            background: var(--card-bg);
            border: 1px solid #222;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            transition: 0.3s;
            text-decoration: none;
            color: #fff;
            height: 100%;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }

        .action-card:hover {
            border-color: var(--gold);
            background: #151515;
            color: #fff;
            text-decoration: none;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.15);
        }

        .action-card .icon-box {
            width: 60px;
            height: 60px;
            background: rgba(212, 175, 55, 0.05);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--gold);
            margin-right: 20px;
            flex-shrink: 0;
        }

        .action-card .card-content h5 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .action-card .card-content p {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin: 0;
            line-height: 1.4;
        }

        .badge-count {
            background: var(--gold);
            color: #000;
            font-size: 0.75rem;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 12px;
            margin-left: 10px;
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>

    <!-- Header & Navigation -->
    <?php include 'includes/header_nav.php'; ?>

    <!-- Compact Hero Section -->
    <section class="account-hero">
        <div class="container">
            <h1 class="fade-in">My Account</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb fade-in" style="animation-delay: 0.1s;">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">My Account</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Dashboard Section -->
    <section class="profile-section">
        <div class="container">
            <div class="row">
                
                <!-- Sidebar -->
                <div class="col-lg-3 col-md-4 mb-4">
                    <div class="dashboard-sidebar fade-in" style="animation-delay: 0.2s;">
                        <div class="avatar">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h4><?php echo $userName; ?></h4>
                        <p><?php echo $userEmail; ?></p>
                        
                        <ul class="dashboard-menu">
                            <li><a href="accounts.php" class="active"><i class="fas fa-th-large"></i> Dashboard</a></li>
                            <li><a href="my_orders.php"><i class="fas fa-box-open"></i> My Orders</a></li>
                            <li><a href="wishlist.php"><i class="fas fa-heart"></i> Wishlist <span class="badge-count"><?= $wishlist_count ?></span></a></li>
                            <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart <span class="badge-count"><?= $cart_count ?></span></a></li>
                            <li><a href="#"><i class="fas fa-map-marker-alt"></i> Addresses</a></li>
                            <li><a href="#" data-bs-toggle="modal" data-bs-target="#settingsModal"><i class="fas fa-cog"></i> Settings</a></li>
                        </ul>
                        
                        <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
                    </div>
                </div>

                <!-- Main Content / Cards -->
                <div class="col-lg-9 col-md-8">
                    <div class="row">
                        <div class="col-md-6 mb-4 fade-in" style="animation-delay: 0.3s;">
                            <a href="my_orders.php" class="action-card">
                                <div class="icon-box"><i class="fas fa-box-open"></i></div>
                                <div class="card-content">
                                    <h5>Your Orders</h5>
                                    <p>Track, return, or buy things again</p>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-6 mb-4 fade-in" style="animation-delay: 0.4s;">
                            <a href="shop.php" class="action-card">
                                <div class="icon-box"><i class="fas fa-shopping-bag"></i></div>
                                <div class="card-content">
                                    <h5>Continue Shopping</h5>
                                    <p>Browse our latest collections & offers</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 mb-4 fade-in" style="animation-delay: 0.5s;">
                            <a href="wishlist.php" class="action-card">
                                <div class="icon-box"><i class="fas fa-heart"></i></div>
                                <div class="card-content">
                                    <h5>Your Wishlist</h5>
                                    <p>View and manage your saved items (<?= $wishlist_count ?>)</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 mb-4 fade-in" style="animation-delay: 0.6s;">
                            <a href="#" class="action-card" data-bs-toggle="modal" data-bs-target="#securityModal">
                                <div class="icon-box"><i class="fas fa-shield-alt"></i></div>
                                <div class="card-content">
                                    <h5>Login & Security</h5>
                                    <p>Edit login, name, and mobile number</p>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-6 mb-4 fade-in" style="animation-delay: 0.7s;">
                            <a href="#" class="action-card" data-bs-toggle="modal" data-bs-target="#addressModal">
                                <div class="icon-box"><i class="fas fa-map-marker-alt"></i></div>
                                <div class="card-content">
                                    <h5>Your Addresses</h5>
                                    <p>Edit addresses for orders and gifts</p>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-6 mb-4 fade-in" style="animation-delay: 0.8s;">
                            <a href="#" class="action-card" data-bs-toggle="modal" data-bs-target="#supportModal">
                                <div class="icon-box"><i class="fas fa-headset"></i></div>
                                <div class="card-content">
                                    <h5>Customer Support</h5>
                                    <p>Contact us for help with your account</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Login & Security Modal -->
    <div class="modal fade" id="securityModal" tabindex="-1" aria-labelledby="securityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content" style="background: #111; border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 12px; color: #fff; box-shadow: 0 10px 40px rgba(0,0,0,0.8);">
                <div class="modal-header" style="border-bottom: 1px solid #222; padding: 15px 20px;">
                    <h6 class="modal-title" id="securityModalLabel" style="color: #d4af37; font-weight: 700; margin: 0; font-size: 1.05rem;"><i class="fas fa-shield-alt mr-2"></i> Login & Security</h6>
                    <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close" style="padding: 15px 20px; margin: -15px -20px -15px auto;">
                        <span aria-hidden="true" style="font-size: 1.2rem;">&times;</span>
                    </button>
                </div>
                <form id="securityForm">
                    <div class="modal-body" style="padding: 20px;">
                        <input type="hidden" id="editUserId" value="<?php echo isset($userId) ? $userId : ''; ?>">
                        
                        <div class="form-group mb-3">
                            <label for="editName" style="color: #aaa; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; margin-bottom: 5px;">Full Name</label>
                            <input type="text" class="form-control form-control-sm" id="editName" value="<?php echo $userName; ?>" style="background: #000; color: #fff; border: 1px solid #333; height: 38px; border-radius: 6px;" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="editEmail" style="color: #aaa; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; margin-bottom: 5px;">Email Address (Login)</label>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="background: #1a1a1a; border: 1px solid #333; border-right: none; color: #666;"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="email" class="form-control form-control-sm" id="editEmail" value="<?php echo $userEmail; ?>" style="background: #1a1a1a; color: #777; border: 1px solid #333; border-left: none; height: 38px; border-radius: 0 6px 6px 0; cursor: not-allowed;" readonly title="Email cannot be changed here">
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label for="editPassword" style="color: #aaa; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; margin-bottom: 5px;">New Password <span style="text-transform: none; font-weight: normal; font-size: 0.75rem; color: #666;">(Leave blank to keep current)</span></label>
                            <input type="password" class="form-control form-control-sm" id="editPassword" placeholder="••••••••" style="background: #000; color: #fff; border: 1px solid #333; height: 38px; border-radius: 6px;">
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #222; padding: 15px 20px;">
                        <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="background: transparent; color: #aaa; font-weight: 600;">Cancel</button>
                        <button type="submit" class="btn btn-sm px-4" id="saveSecurityBtn" style="background: #d4af37; color: #000; border: none; font-weight: 700; border-radius: 6px;">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Address Modal -->
    <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
            <div class="modal-content" style="background: #111; border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 12px; color: #fff; box-shadow: 0 10px 40px rgba(0,0,0,0.8);">
                <div class="modal-header" style="border-bottom: 1px solid #222; padding: 15px 20px;">
                    <h6 class="modal-title" id="addressModalLabel" style="color: #d4af37; font-weight: 700; margin: 0; font-size: 1.05rem;"><i class="fas fa-map-marker-alt mr-2"></i> Manage Default Address</h6>
                    <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close" style="padding: 15px 20px; margin: -15px -20px -15px auto;">
                        <span aria-hidden="true" style="font-size: 1.2rem;">&times;</span>
                    </button>
                </div>
                <form id="addressForm">
                    <div class="modal-body" style="padding: 20px;">
                        <div class="form-group mb-3">
                            <label for="addrLine" style="color: #aaa; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; margin-bottom: 5px;">Street Address</label>
                            <input type="text" class="form-control form-control-sm" id="addrLine" placeholder="House/Flat No., Building, Street" style="background: #000; color: #fff; border: 1px solid #333; height: 38px; border-radius: 6px;" required>
                        </div>

                        <div class="row">
                            <div class="col-6 form-group mb-3">
                                <label for="addrCity" style="color: #aaa; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; margin-bottom: 5px;">City</label>
                                <input type="text" class="form-control form-control-sm" id="addrCity" placeholder="City" style="background: #000; color: #fff; border: 1px solid #333; height: 38px; border-radius: 6px;" required>
                            </div>
                            <div class="col-6 form-group mb-3">
                                <label for="addrState" style="color: #aaa; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; margin-bottom: 5px;">State</label>
                                <input type="text" class="form-control form-control-sm" id="addrState" placeholder="State" style="background: #000; color: #fff; border: 1px solid #333; height: 38px; border-radius: 6px;" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 form-group mb-2">
                                <label for="addrPin" style="color: #aaa; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; margin-bottom: 5px;">Pincode</label>
                                <input type="text" class="form-control form-control-sm" id="addrPin" placeholder="e.g. 388325" style="background: #000; color: #fff; border: 1px solid #333; height: 38px; border-radius: 6px;" required>
                            </div>
                            <div class="col-6 form-group mb-2">
                                <label for="addrMobile" style="color: #aaa; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; margin-bottom: 5px;">Mobile</label>
                                <input type="tel" class="form-control form-control-sm" id="addrMobile" placeholder="10-digit number" style="background: #000; color: #fff; border: 1px solid #333; height: 38px; border-radius: 6px;" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #222; padding: 15px 20px;">
                        <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="background: transparent; color: #aaa; font-weight: 600;">Cancel</button>
                        <button type="submit" class="btn btn-sm px-4" id="saveAddressBtn" style="background: #d4af37; color: #000; border: none; font-weight: 700; border-radius: 6px;">Save Address</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Settings Modal -->
    <div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content" style="background: #111; border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 12px; color: #fff; box-shadow: 0 10px 40px rgba(0,0,0,0.8);">
                <div class="modal-header" style="border-bottom: 1px solid #222; padding: 15px 20px;">
                    <h6 class="modal-title" id="settingsModalLabel" style="color: #d4af37; font-weight: 700; margin: 0; font-size: 1.05rem;"><i class="fas fa-cog mr-2"></i> Account Settings</h6>
                    <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close" style="padding: 15px 20px; margin: -15px -20px -15px auto;">
                        <span aria-hidden="true" style="font-size: 1.2rem;">&times;</span>
                    </button>
                </div>
                <form id="settingsForm">
                    <div class="modal-body" style="padding: 20px;">
                        
                        <!-- Notifications Section -->
                        <h6 style="color: #ccc; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; margin-bottom: 15px; border-bottom: 1px solid #222; padding-bottom: 8px;">Notifications</h6>
                        
                        <div class="form-check mb-3" style="padding-left: 0;">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="whatsappNotif" checked>
                                <label class="custom-control-label" for="whatsappNotif" style="color: #aaa; font-size: 0.9rem; padding-left: 5px;">WhatsApp Order Updates</label>
                            </div>
                        </div>

                        <div class="form-check mb-4" style="padding-left: 0;">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="emailPromo">
                                <label class="custom-control-label" for="emailPromo" style="color: #aaa; font-size: 0.9rem; padding-left: 5px;">Promotional Emails & Offers</label>
                            </div>
                        </div>

                        <!-- Danger Zone Section -->
                        <h6 style="color: #ff4d4d; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; margin-bottom: 15px; border-bottom: 1px solid #222; padding-bottom: 8px;">Danger Zone</h6>
                        
                        <p style="color: #888; font-size: 0.8rem; margin-bottom: 10px;">Once you delete your account, there is no going back. Please be certain.</p>
                        <button type="button" class="btn btn-sm w-100" style="background: rgba(255, 77, 77, 0.1); color: #ff4d4d; border: 1px solid rgba(255, 77, 77, 0.3); font-weight: 600; border-radius: 6px;" onclick="requestAccountDeletion()">Request Account Deletion</button>

                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #222; padding: 15px 20px;">
                        <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="background: transparent; color: #aaa; font-weight: 600;">Cancel</button>
                        <button type="submit" class="btn btn-sm px-4" id="saveSettingsBtn" style="background: #d4af37; color: #000; border: none; font-weight: 700; border-radius: 6px;">Save Preferences</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Support Modal -->
    <div class="modal fade" id="supportModal" tabindex="-1" aria-labelledby="supportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
            <div class="modal-content" style="background: #111; border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 12px; color: #fff; box-shadow: 0 10px 40px rgba(0,0,0,0.8);">
                <div class="modal-header" style="border-bottom: 1px solid #222; padding: 15px 20px;">
                    <h6 class="modal-title" id="supportModalLabel" style="color: #d4af37; font-weight: 700; margin: 0; font-size: 1.05rem;"><i class="fas fa-headset mr-2"></i> Customer Support</h6>
                    <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close" style="padding: 15px 20px; margin: -15px -20px -15px auto;">
                        <span aria-hidden="true" style="font-size: 1.2rem;">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body" style="padding: 20px;">
                    <!-- Quick Contact Buttons -->
                    <div class="d-flex gap-2 mb-4" style="gap: 10px;">
                        <a href="https://wa.me/917201808176" target="_blank" class="btn btn-sm w-50 d-flex flex-column align-items-center justify-content-center" style="background: rgba(37, 211, 102, 0.1); color: #25D366; border: 1px solid rgba(37, 211, 102, 0.3); border-radius: 8px; padding: 12px 10px; font-weight: 600;">
                            <i class="fab fa-whatsapp mb-1" style="font-size: 1.3rem;"></i> WhatsApp Us
                        </a>
                        <a href="tel:+917201808176" class="btn btn-sm w-50 d-flex flex-column align-items-center justify-content-center" style="background: rgba(52, 152, 219, 0.1); color: #3498db; border: 1px solid rgba(52, 152, 219, 0.3); border-radius: 8px; padding: 12px 10px; font-weight: 600;">
                            <i class="fas fa-phone-alt mb-1" style="font-size: 1.2rem;"></i> Call Us
                        </a>
                    </div>

                    <div style="text-align: center; margin-bottom: 15px; position: relative;">
                        <hr style="border-color: #333; margin: 0;">
                        <span style="background: #111; padding: 0 10px; color: #888; font-size: 0.8rem; position: absolute; top: -10px; left: 50%; transform: translateX(-50%); font-weight: 600; text-transform: uppercase;">Or Send a Message</span>
                    </div>

                    <form id="supportForm" style="margin-top: 20px;">
                        <div class="form-group mb-3">
                            <label for="supportSubject" style="color: #aaa; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; margin-bottom: 5px;">Subject</label>
                            <select class="form-control form-control-sm" id="supportSubject" style="background: #000; color: #fff; border: 1px solid #333; height: 38px; border-radius: 6px;" required>
                                <option value="" disabled selected>Select an issue...</option>
                                <option value="Order Tracking">Order Tracking</option>
                                <option value="Return / Refund">Return / Refund</option>
                                <option value="Payment Issue">Payment Issue</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="supportMessage" style="color: #aaa; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; margin-bottom: 5px;">Message</label>
                            <textarea class="form-control" id="supportMessage" rows="3" placeholder="Describe your issue..." style="background: #000; color: #fff; border: 1px solid #333; border-radius: 6px; padding: 10px; font-size: 0.9rem;" required></textarea>
                        </div>
                        <div class="text-right mt-3">
                            <button type="submit" class="btn btn-sm px-4" id="sendSupportBtn" style="background: #d4af37; color: #000; border: none; font-weight: 700; border-radius: 6px; width: 100%;">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth reveal on load
            const fadeElements = document.querySelectorAll('.fade-in');
            fadeElements.forEach(el => {
                el.style.opacity = '0';
                el.style.transition = 'opacity 0.8s ease, margin-top 0.8s ease';
            });
            
            window.addEventListener('load', function() {
                fadeElements.forEach((el, i) => {
                    setTimeout(() => {
                        el.style.opacity = '1';
                        el.style.marginTop = '0px';
                    }, i * 200);
                });
            });

            // Security Form Submission
            const securityForm = document.getElementById('securityForm');
            if(securityForm) {
                securityForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const btn = document.getElementById('saveSecurityBtn');
                    const originalBtnText = btn.innerHTML;
                    
                    const userId = document.getElementById('editUserId').value;
                    const name = document.getElementById('editName').value;
                    const password = document.getElementById('editPassword').value;
                    
                    if(!name.trim()) {
                        alert("Name cannot be empty.");
                        return;
                    }

                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                    btn.disabled = true;

                    const formData = new FormData();
                    formData.append('user_id', userId);
                    formData.append('name', name);
                    if(password.trim() !== '') {
                        formData.append('password', password);
                    }

                    fetch('updateProfile.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        if(data.trim() === 'success') {
                            alert("Profile updated successfully!");
                            location.reload();
                        } else {
                            alert("Error updating profile: " + data);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Network error occurred.");
                    })
                    .finally(() => {
                        btn.innerHTML = originalBtnText;
                        btn.disabled = false;
                    });
                });
            }
            // Address Form Submission
            const addressForm = document.getElementById('addressForm');
            if(addressForm) {
                addressForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const btn = document.getElementById('saveAddressBtn');
                    const originalBtnText = btn.innerHTML;
                    
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                    btn.disabled = true;

                    // Simulate API Call for UI purposes
                    setTimeout(() => {
                        // Close modal
                        const modalEl = document.getElementById('addressModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if(modal) {
                            modal.hide();
                        } else {
                            // Fallback for bootstrap 4/5 mixed setups
                            document.querySelector('#addressModal .close').click();
                        }
                        
                        btn.innerHTML = originalBtnText;
                        btn.disabled = false;
                        
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Address Saved',
                                text: 'Your default address has been successfully updated.',
                                showConfirmButton: false,
                                timer: 3000,
                                background: '#111',
                                color: '#d4af37'
                            });
                        } else {
                            alert("Address saved successfully!");
                        }
                        
                        addressForm.reset();
                    }, 800);
                });
            }
            // Settings Form Submission
            const settingsForm = document.getElementById('settingsForm');
            if(settingsForm) {
                settingsForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const btn = document.getElementById('saveSettingsBtn');
                    const originalBtnText = btn.innerHTML;
                    
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                    btn.disabled = true;

                    // Simulate API Call for UI purposes
                    setTimeout(() => {
                        // Close modal
                        const modalEl = document.getElementById('settingsModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if(modal) {
                            modal.hide();
                        } else {
                            document.querySelector('#settingsModal .close').click();
                        }
                        
                        btn.innerHTML = originalBtnText;
                        btn.disabled = false;
                        
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Settings Saved',
                                text: 'Your account preferences have been updated.',
                                showConfirmButton: false,
                                timer: 3000,
                                background: '#111',
                                color: '#d4af37'
                            });
                        } else {
                            alert("Settings saved successfully!");
                        }
                    }, 600);
                });
            }
        });

        // Support Form Submission
        document.addEventListener('DOMContentLoaded', function() {
            const supportForm = document.getElementById('supportForm');
            if(supportForm) {
                supportForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const btn = document.getElementById('sendSupportBtn');
                    const originalBtnText = btn.innerHTML;
                    
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
                    btn.disabled = true;

                    // Simulate API Call
                    setTimeout(() => {
                        const modalEl = document.getElementById('supportModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if(modal) {
                            modal.hide();
                        } else {
                            document.querySelector('#supportModal .close').click();
                        }
                        
                        btn.innerHTML = originalBtnText;
                        btn.disabled = false;
                        supportForm.reset();
                        
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Message Sent!',
                                text: 'Our support team will get back to you shortly.',
                                background: '#111',
                                color: '#d4af37',
                                confirmButtonColor: '#d4af37'
                            });
                        } else {
                            alert("Message sent successfully!");
                        }
                    }, 800);
                });
            }
        });

        // Dummy function for Account Deletion
        function requestAccountDeletion() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You will lose access to all your order history and saved items.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ff4d4d',
                    cancelButtonColor: '#555',
                    confirmButtonText: 'Yes, delete it!',
                    background: '#111',
                    color: '#fff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Request Sent',
                            text: 'Your account deletion request has been submitted to administrators.',
                            icon: 'info',
                            background: '#111',
                            color: '#fff'
                        });
                    }
                })
            } else {
                if(confirm("Are you sure you want to delete your account? This action cannot be undone.")) {
                    alert("Account deletion request submitted.");
                }
            }
        }
    </script>
</body>
</html>