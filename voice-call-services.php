<?php
// PHP file: voice-call-services.php

$pageTitle = "Voice Call Services - BRINFO";
$companyName = "BRINFO";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>

    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            width: 85%;
            max-width: 1200px;
            margin: auto;
            overflow: hidden;
            background: #fff;
            padding: 20px 30px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        header {
            background: #007bff;
            color: #fff;
            padding: 15px 0;
            text-align: center;
            margin-bottom: 30px;
        }
        header h1 { margin: 0; font-size: 2.2em; }
        h2 {
            color: #333;
            border-bottom: 2px solid #ccc;
            padding-bottom: 8px;
            margin-top: 35px;
            font-size: 1.8em;
        }
        h3 { color: #007bff; font-size: 1.4em; margin-top: 25px; }

        .section p { margin-bottom: 15px; text-align: justify; }

        .features-list { list-style: disc; margin-left: 20px; padding-left: 0; }
        .features-list li { margin-bottom: 10px; }

        .important-note {
            background: #fff3cd;
            border-left: 5px solid #ffc107;
            padding: 15px;
            margin-top: 20px;
        }

        /* Pricing Table Styling (reuse from SMS page) */
        .pricing-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 0.95em;
        }
        .pricing-table th, .pricing-table td {
            border: 1px solid #ddd;
            padding: 12px 8px;
            text-align: center;
        }
        .pricing-table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }
        .pricing-table tr:nth-child(even) { background-color: #f2f2f2; }
        .pricing-table td:first-child {
            font-weight: bold;
            text-align: left;
            background-color: #eee;
        }

        .price-details { font-weight: bold; color: #dc3545; display: block; }
        .per-sms-rate { font-size: 0.9em; color: #555; }

        .contact-note {
            background: #e9f7ef;
            border: 1px solid #d4edda;
            color: #155724;
            padding: 15px;
            margin-top: 20px;
            text-align: center;
            border-radius: 5px;
        }

    </style>

</head>
<body>
<header>
    <h1><?php echo $pageTitle; ?></h1>
</header>

<div class="container">

    <!-- OVERVIEW -->
    <section class="section overview">
        <h2>Overview</h2>
        <p>Voice Call service enables businesses to reach their customers through **automated voice calls**. Customers receive a dialing ring and listen to a **pre-recorded voice message**, which may include product information, offers, alerts, or service-related announcements.</p>

        <p>This service is based on **Voice Group Call Service (VGCS)** technology, allowing half-duplex communication over a mobile network. VGCS supports multiple users within a defined area or network who have the required subscription to participate in voice group calls.</p>

        <p>With <?php echo $companyName; ?> Voice Call Services, businesses can deliver voice messages in bulk, ensuring maximum reach, including to **DND numbers**, making it a highly efficient and cost-effective tool for communication.</p>
    </section>

    <hr>

    <!-- FEATURES -->
    <section class="section features">
        <h2>Voice Call Service Features</h2>
        <ul class="features-list">
            <li><strong>Ideal For:</strong> Election Campaigns, Real Estate Promotions, Events, Promotions & Announcements.</li>
            <li>Reaches **DND numbers**, making it useful for schools and colleges.</li>
            <li>Credits are deducted **only for attended calls**, ensuring cost savings.</li>
            <li>Supports **.mp3, .wav, .amr** voice formats.</li>
            <li>100% guaranteed delivery within **5–10 seconds**.</li>
            <li>30-second pulse charging for each connected call.</li>
            <li>Service availability from **9 AM to 9 PM** (Promotional Route).</li>
            <li>Powerful and secure **HTTP API integration** for automation.</li>
        </ul>
    </section>

    <hr>

    <!-- USE CASES -->
    <section class="section use-cases">
        <h2>Bulk Voice Call Use Cases</h2>
        <p>Bulk Voice Call campaigns help organizations and businesses broadcast their message to a large audience instantly. They are widely used for:</p>

        <ul class="features-list">
            <li>Lead Generation & Marketing Campaigns</li>
            <li>Event Notifications & Invitations</li>
            <li>Political Campaign Promotions</li>
            <li>Public Announcements</li>
            <li>Meeting & Appointment Alerts</li>
            <li>Wake-Up Call Services</li>
            <li>Stock Alerts for Traders</li>
            <li>Medicine & Health Reminders</li>
        </ul>
    </section>

    <hr>

    <!-- PRICING TABLE -->
    <section class="section pricing">
        <h2>Voice Call Pricing Table</h2>

        <table class="pricing-table">
            <thead>
                <tr>
                    <th>Package</th>
                    <th>Voice Call Pricing</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2.5 Lakhs Calls</td>
                    <td>
                        <span class="price-details">₹37,500/-</span>
                        <span class="per-sms-rate">15 Paise Per Call</span>
                    </td>
                </tr>
                <tr>
                    <td>1 Lakh Calls</td>
                    <td>
                        <span class="price-details">₹16,000/-</span>
                        <span class="per-sms-rate">16 Paise Per Call</span>
                    </td>
                </tr>
                <tr>
                    <td>50,000 Calls</td>
                    <td>
                        <span class="price-details">₹9,000/-</span>
                        <span class="per-sms-rate">18 Paise Per Call</span>
                    </td>
                </tr>
                <tr>
                    <td>25,000 Calls</td>
                    <td>
                        <span class="price-details">₹4,750/-</span>
                        <span class="per-sms-rate">19 Paise Per Call</span>
                    </td>
                </tr>
                <tr>
                    <td>10,000 Calls</td>
                    <td>
                        <span class="price-details">₹2,100/-</span>
                        <span class="per-sms-rate">21 Paise Per Call</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="contact-note">
            <strong>Note:</strong> For **5 Lac and above** Voice Call purchase, please contact our sales team at 
            <strong>info@brinfo.in</strong> or <strong>+91-9974328904</strong>.
        </div>
    </section>

    <hr>

    <footer>
        <p style="text-align: center; margin-top: 30px; padding-top: 15px; border-top: 1px solid #ccc;">
            &copy; <?php echo date("Y"); ?> <?php echo $companyName; ?>. All rights reserved.
        </p>
    </footer>

</div>

</body>
</html>
