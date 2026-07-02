<?php
$pageTitle = "Short Code Services Features - BRINFO";
$companyName = "BRINFO";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pageTitle; ?></title>

    <!-- SEO -->
    <meta name="description" content="Short Code Services by BRINFO. Receive SMS on short numbers, use keywords, auto replies, sub-keyword routing, alerts, URL forwarding and more.">
    <meta name="keywords" content="Short Code Services, SMS Short Code India, PULL SMS, Keyword SMS, BRINFO">
    <meta name="author" content="BRINFO">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f7f8fa;
            color: #333;
            line-height: 1.6;
        }
        header {
            background: #007bff;
            color: #fff;
            padding: 20px 10px;
            text-align: center;
        }
        header h1 {
            margin: 0;
            font-size: 2rem;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
            background: #fff;
            padding: 25px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-radius: 8px;
            margin-top: 25px;
            margin-bottom: 30px;
        }
        h2 {
            font-size: 1.7rem;
            color: #222;
            border-left: 4px solid #007bff;
            padding-left: 10px;
            margin-top: 40px;
        }
        h3 {
            font-size: 1.3rem;
            color: #007bff;
            margin-top: 25px;
        }
        p {
            text-align: justify;
        }
        ul {
            padding-left: 20px;
        }
        ul li {
            margin-bottom: 10px;
        }

        /* Feature Cards */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .feature-card {
            background: #fafafa;
            padding: 18px;
            border-radius: 8px;
            border: 1px solid #e2e2e2;
            transition: 0.3s ease-in-out;
        }
        .feature-card:hover {
            box-shadow: 0 2px 12px rgba(0,0,0,0.12);
            transform: translateY(-3px);
        }
        .feature-card h4 {
            color: #007bff;
            margin-bottom: 10px;
            font-size: 1.2rem;
        }

        /* Pricing Table */
        .pricing-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .pricing-table th {
            background: #007bff;
            color: #fff;
            padding: 12px;
            font-size: 1rem;
        }
        .pricing-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
            background: #fafafa;
        }
        .note {
            margin-top: 15px;
            font-style: italic;
            background: #fff8e6;
            padding: 12px;
            border-left: 4px solid #ff9800;
        }

        footer {
            text-align: center;
            padding: 20px 0;
            font-size: 0.9rem;
            color: #555;
            border-top: 1px solid #ddd;
            margin-top: 40px;
            background: #f9f9f9;
        }
    </style>
</head>
<body>

<header>
    <h1><?php echo $pageTitle; ?></h1>
</header>

<div class="container">

    <section>
        <h2>What is a Short Code?</h2>
        <p>A short code is a special, easy-to-remember number shorter than a regular mobile number. Short codes work with <strong>keywords</strong>. A keyword is a unique word assigned to the short code.</p>

        <p>Example: If your company is <strong>XYZ Ltd</strong> and you select the keyword <strong>XYZ</strong>, any customer sending an SMS like:</p>

        <p><strong>XYZ <span style="color:#007bff;">&lt;space&gt;</span> message</strong></p>

        <p>will instantly receive an automated reply configured by you.</p>
    </section>

    <section>
        <h2>How Short Code Services Work</h2>
        <p>Short Code service is a <strong>PULL SMS service</strong> where your customers reach you using a short 5-digit number along with your registered keyword.</p>

        <p>Short Codes are widely used for:</p>
        <ul>
            <li>Polling</li>
            <li>Campaigning</li>
            <li>Customer Feedback</li>
            <li>Alerts & Notifications</li>
            <li>IVR Routing</li>
            <li>Lead Generation</li>
        </ul>

        <p>Short codes are easy to recall and help in boosting customer engagement. With our service, you can collect customer numbers for business leads, automate replies, and track responses in real-time.</p>
    </section>

    <section>
        <h2>Short Code Service Features</h2>

        <div class="features-grid">

            <div class="feature-card">
                <h4>✔ Receive SMS from All Operators</h4>
                <p>Supports incoming SMS from every telecom operator across India.</p>
            </div>

            <div class="feature-card">
                <h4>✔ Customized Auto Reply</h4>
                <p>Create personalized auto-response messages for all incoming SMS.</p>
            </div>

            <div class="feature-card">
                <h4>✔ Unlimited Sub-Keywords</h4>
                <p>Add unlimited sub-keywords under your main keyword and define separate replies.</p>
            </div>

            <div class="feature-card">
                <h4>✔ SMS Alerts</h4>
                <p>Receive instant SMS notifications for every incoming message.</p>
            </div>

            <div class="feature-card">
                <h4>✔ Email Alerts</h4>
                <p>Receive email reports for each keyword and sub-keyword separately.</p>
            </div>

            <div class="feature-card">
                <h4>✔ URL Forwarding</h4>
                <p>Forward incoming messages to your server/API for real-time processing.</p>
            </div>

        </div>
    </section>

    <!-- PRICING TABLE SECTION -->
    <section>
        <h2>Pricing Table</h2>

        <table class="pricing-table">
            <tr>
                <th>Package</th>
                <th>Promotional (Regular)</th>
                <th>Transactional</th>
                <th>Voice SMS</th>
            </tr>

            <tr>
                <td>2.5 Lakhs SMS</td>
                <td>₹32,500/-<br>13 Paise Per SMS</td>
                <td>₹35,000/-<br>14 Paise Per SMS</td>
                <td>₹37,500/-<br>15 Paise Per SMS</td>
            </tr>

            <tr>
                <td>1 Lakh SMS</td>
                <td>₹14,000/-<br>14 Paise Per SMS</td>
                <td>₹15,000/-<br>15 Paise Per SMS</td>
                <td>₹16,000/-<br>16 Paise Per SMS</td>
            </tr>

            <tr>
                <td>50,000 SMS</td>
                <td>₹8,500/-<br>17 Paise Per SMS</td>
                <td>₹8,500/-<br>17 Paise Per SMS</td>
                <td>₹9,000/-<br>18 Paise Per SMS</td>
            </tr>

            <tr>
                <td>25,000 SMS</td>
                <td>₹4,750/-<br>19 Paise Per SMS</td>
                <td>₹4,750/-<br>19 Paise Per SMS</td>
                <td>₹4,750/-<br>19 Paise Per SMS</td>
            </tr>

            <tr>
                <td>10,000 SMS</td>
                <td>₹2,000/-<br>20 Paise Per SMS</td>
                <td>₹2,000/-<br>20 Paise Per SMS</td>
                <td>₹2,100/-<br>21 Paise Per SMS</td>
            </tr>
        </table>

        <div class="note">
            Note: For 5 Lac SMS and above, please contact our sales team at 
            <strong>info@brinfo.in</strong> or <strong>+91-9974328904</strong>.
        </div>
    </section>

</div>

<footer>
    &copy; 2024 IshahiyaOne.shop — Owned & operated by BR IT SOLUTION AND BR CATTLE FEED  
    <br>
    GSTIN: 24AXGPP5413P1Z3 · Reg. No.: PENO20001052
</footer>

</body>
</html>
