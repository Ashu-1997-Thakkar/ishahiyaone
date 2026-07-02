<?php
// PHP file: transactional-bulk-sms-services.php

$pageTitle = "Transactional Bulk SMS Services - BRINFO";
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
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        header {
            background: #007bff; /* Primary color for header */
            color: #fff;
            padding: 15px 0;
            text-align: center;
            margin-bottom: 30px;
        }
        header h1 {
            margin: 0;
            font-size: 2.2em;
        }
        h2 {
            color: #333;
            border-bottom: 2px solid #ccc;
            padding-bottom: 8px;
            margin-top: 35px;
            font-size: 1.8em;
        }
        h3 {
            color: #007bff;
            font-size: 1.4em;
            margin-top: 25px;
        }
        .section p {
            margin-bottom: 15px;
            text-align: justify;
        }
        .types-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .type-card {
            background: #f9f9f9;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .type-card strong {
            display: block;
            font-size: 1.1em;
            margin-bottom: 5px;
            color: #333;
        }
        .type-card .number {
            font-size: 2em;
            color: #007bff;
            font-weight: bold;
            display: inline-block;
            margin-right: 10px;
            vertical-align: top;
        }
        .features-list {
            list-style: disc;
            margin-left: 20px;
            padding-left: 0;
        }
        .features-list li {
            margin-bottom: 10px;
        }
        .important-note {
            background: #fff3cd;
            border-left: 5px solid #ffc107;
            padding: 15px;
            margin-top: 20px;
        }
        .card-content {
            margin-left: 50px; /* Space for the number */
        }
        
        /* --- Pricing Table Styling --- */
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
        .pricing-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .pricing-table td:first-child {
            font-weight: bold;
            text-align: left;
            background-color: #eee;
        }
        .price-details {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #dc3545; /* Highlight the total price */
        }
        .per-sms-rate {
            font-size: 0.9em;
            color: #555;
        }
        .setup-fee {
            font-size: 1.1em;
            font-weight: bold;
            color: #dc3545;
            margin-top: 15px;
            text-align: center;
        }
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
        
        <section class="section introduction">
            <h2>Overview</h2>
            <p>Transactional SMS have gained tremendous popularity in the recent times and transactional SMS services are best for sending **alerts/reminders/updates** to your obtainable clients. Transactional SMS India are extensively used by a number of companies for promotion, marketing and other business purposes. It is easy to send bulk SMS throughout the globe with a user-friendly web interface. **<?php echo $companyName; ?>** brings to you an easy and accessible transactional SMS gateway where you can register your firm and send bulk SMS in an extremely trouble-free manner.</p>
            <p>Sending transactional SMS is now at the tip of your thumb. Give your clients and customers instant minute-to-minute information on the delivery status of their purchase, transaction confirmations, updates on promotional offers, etc., with our affordable transactional bulk SMS service.</p>
        </section>
        
        <hr>

        <section class="section types-of-sms">
            <h2>Types of Transactional SMS in India</h2>
            <p>Transactional SMS services are essential for organizations that need to deliver important and time-sensitive information to their customers. These messages are strictly informational and comply with the **TRAI (Telecom Regulatory Authority of India)** guidelines to ensure transparency and reliability.</p>
            
            <div class="types-grid">
                
                <div class="type-card">
                    <span class="number">01</span>
                    <div class="card-content">
                        <h3>OTP (One-Time Password) SMS</h3>
                        <strong>Purpose:</strong> OTP SMS is primarily used for authentication purposes during transactions, logins, or secure access.
                        <strong>Applications:</strong> <ul><li>Secure online banking and payment gateways.</li><li>Verification of user accounts on mobile apps and websites.</li></ul>
                        <strong>Features:</strong> <ul><li>Real-time delivery within seconds.</li><li>High security with automated API integrations.</li></ul>
                    </div>
                </div>

                <div class="type-card">
                    <span class="number">02</span>
                    <div class="card-content">
                        <h3>Alert SMS</h3>
                        <strong>Purpose:</strong> To notify customers about account activities, payment reminders, or service updates.
                        <strong>Applications:</strong> <ul><li>Bank transaction alerts for debits, credits, or low balance notifications.</li><li>Utility bill reminders for electricity, water, and internet services.</li></ul>
                        <strong>Features:</strong> <ul><li>Customizable sender IDs.</li><li>Ensured delivery even to DND (Do Not Disturb) numbers.</li></ul>
                    </div>
                </div>

                <div class="type-card">
                    <span class="number">03</span>
                    <div class="card-content">
                        <h3>Reminder SMS</h3>
                        <strong>Purpose:</strong> To remind customers of important dates, appointments, or renewals.
                        <strong>Applications:</strong> <ul><li>Appointment reminders for healthcare and service providers.</li><li>Subscription renewal notices for SaaS and OTT platforms.</li></ul>
                        <strong>Features:</strong> <ul><li>Scheduled delivery for maximum convenience.</li><li>Time-sensitive and personalized templates.</li></ul>
                    </div>
                </div>
                
                <div class="type-card">
                    <span class="number">04</span>
                    <div class="card-content">
                        <h3>Informational SMS</h3>
                        <strong>Purpose:</strong> To provide non-promotional updates about an organization’s services or user accounts.
                        <strong>Applications:</strong> <ul><li>Fee due notifications for educational institutions.</li><li>Policy updates or changes for insurance and financial services.</li></ul>
                        <strong>Features:</strong> <ul><li>Bulk messaging capabilities for large audiences.</li><li>Easy integration with CRM systems.</li></ul>
                    </div>
                </div>

                <div class="type-card">
                    <span class="number">05</span>
                    <div class="card-content">
                        <h3>Verification SMS</h3>
                        <strong>Purpose:</strong> To confirm user details or validate user actions.
                        <strong>Applications:</strong> <ul><li>KYC (Know Your Customer) updates for banks and telecom companies.</li><li>Verification of email or phone number during registrations.</li></ul>
                        <strong>Features:</strong> <ul><li>Short yet impactful message templates.</li><li>Integration with verification workflows.</li></ul>
                    </div>
                </div>

                <div class="type-card">
                    <span class="number">06</span>
                    <div class="card-content">
                        <h3>Service-Related SMS</h3>
                        <strong>Purpose:</strong> To update customers about ongoing services or issues.
                        <strong>Applications:</strong> <ul><li>Service outage or maintenance notifications for telecom and utility providers.</li><li>Regular updates about repair or service requests.</li></ul>
                        <strong>Features:</strong> <ul><li>Multi-lingual support for better customer engagement.</li><li>Delivery assurance across all networks.</li></ul>
                    </div>
                </div>

            </div>
        </section>
        
        <hr>

        <section class="section service-benefits">
            <h2>Instant & Reliable Communication (Continued)</h2>
            <p>**<?php echo $companyName; ?>** offers Transactional SMS services to customers. Transactional SMS Gateways are highly tuned and we guarantee **six sigma quality of service**. Mission critical applications or services like On-line Stock trading, Market updates, Registration with SMS/E-Mail verification to share the verification codes for sign-in, Two-level authentication in Banking transactions, etc., prefers Transactional SMS services. At the outset, Transactional Gateways are highly applicable for the businesses which react on **instant delivery of information**.</p>
        </section>
        
        <hr>

        <section class="section pricing">
            <h2>Pricing Table & Setup Fee</h2>
            
            <table class="pricing-table">
                <thead>
                    <tr>
                        <th>Package</th>
                        <th>Promotional (Regular)</th>
                        <th>Transactional</th>
                        <th>Voice SMS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2.5 Lakhs SMS</td>
                        <td>
                            <span class="price-details">₹32,500/-</span>
                            <span class="per-sms-rate">13 Paise Per SMS</span>
                        </td>
                        <td>
                            <span class="price-details">₹35,000/-</span>
                            <span class="per-sms-rate">14 Paise Per SMS</span>
                        </td>
                        <td>
                            <span class="price-details">₹37,500/-</span>
                            <span class="per-sms-rate">15 Paise Per SMS</span>
                        </td>
                    </tr>
                    <tr>
                        <td>1 Lakh SMS</td>
                        <td>
                            <span class="price-details">₹14,000/-</span>
                            <span class="per-sms-rate">14 Paise Per SMS</span>
                        </td>
                        <td>
                            <span class="price-details">₹15,000/-</span>
                            <span class="per-sms-rate">15 Paise Per SMS</span>
                        </td>
                        <td>
                            <span class="price-details">₹16,000/-</span>
                            <span class="per-sms-rate">16 Paise Per SMS</span>
                        </td>
                    </tr>
                    <tr>
                        <td>50,000 SMS</td>
                        <td>
                            <span class="price-details">₹8,500/-</span>
                            <span class="per-sms-rate">17 Paise Per SMS</span>
                        </td>
                        <td>
                            <span class="price-details">₹8,500/-</span>
                            <span class="per-sms-rate">17 Paise Per SMS</span>
                        </td>
                        <td>
                            <span class="price-details">₹9,000/-</span>
                            <span class="per-sms-rate">18 Paise Per SMS</span>
                        </td>
                    </tr>
                    <tr>
                        <td>25,000 SMS</td>
                        <td>
                            <span class="price-details">₹4,750/-</span>
                            <span class="per-sms-rate">19 Paise Per SMS</span>
                        </td>
                        <td>
                            <span class="price-details">₹4,750/-</span>
                            <span class="per-sms-rate">19 Paise Per SMS</span>
                        </td>
                        <td>
                            <span class="price-details">₹4,750/-</span>
                            <span class="per-sms-rate">19 Paise Per SMS</span>
                        </td>
                    </tr>
                    <tr>
                        <td>10,000 SMS</td>
                        <td>
                            <span class="price-details">₹2,000/-</span>
                            <span class="per-sms-rate">20 Paise Per SMS</span>
                        </td>
                        <td>
                            <span class="price-details">₹2,000/-</span>
                            <span class="per-sms-rate">20 Paise Per SMS</span>
                        </td>
                        <td>
                            <span class="price-details">₹2,100/-</span>
                            <span class="per-sms-rate">21 Paise Per SMS</span>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <div class="contact-note">
                <strong>Note:</strong> For **5 Lac and above** purchase please contact our sales team either at **info@brinfo.in** or **+91-9974328904**.
            </div>
            
            
        
        <hr>

        <section class="section features">
            <h2>Transactional Bulk SMS Compliance Requirements</h2>
            <ul class="features-list">
                <li><strong>Company Compliance:</strong> We need details on how customers are subscribing or receiving registered messages, and one sample of the contribution form is compulsory.</li>
                <li><strong>Educational Institution Compliance:</strong> A letter stating that the SMS will be used for update to parents/teachers & students and **no Promotional activity** will be done from the particular account is required.</li>
                <li><strong>Government Organization Compliance:</strong> A letter stating to whom the messages will be sent and what kind of certificate records will be provided in case of any complaint is required, with one sample compulsory.</li>
                <li><strong>Template Requirement:</strong> Minimum 3 Sample SMS. (**70% fixed and 30% Variable**). Refer the Sample Template for details.</li>
                <li><strong>Agreement:</strong> A formal agreement is required for service activation.</li>
            </ul>
        </section>

        <footer>
            <p style="text-align: center; margin-top: 30px; padding-top: 15px; border-top: 1px solid #ccc;">&copy; <?php echo date("Y"); ?> <?php echo $companyName; ?>. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>