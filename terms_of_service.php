<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - Boi Lagbe!</title>
    
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'user_header.php'; ?>

<div class="policy-hero">
    <div class="hero-content">
        <h1>Terms of Service</h1>
        <p>Please read these terms of service carefully before using our website operated by BoiLagbe!.</p>
    </div>
</div>

<div class="policy-container">
    <div class="policy-content">
        <p class="last-updated">Last updated: <?php echo date('F j, Y'); ?></p>

        <h2>1. Agreement to Terms</h2>
        <p>By accessing or using our Service, you agree to be bound by these Terms. If you disagree with any part of the terms then you may not access the Service. These Terms of Service create a legally binding agreement between you and BoIBD.</p>
        
        <h2>2. User Accounts</h2>
        <p>When you create an account with us, you must provide us information that is accurate, complete, and current at all times. Failure to do so constitutes a breach of the Terms, which may result in immediate termination of your account on our Service. You are responsible for safeguarding the password that you use to access the Service and for any activities or actions under your password.</p>

        <h2>3. Purchases and Payment</h2>
        <p>If you wish to purchase any product or service made available through the Service ("Purchase"), you may be asked to supply certain information relevant to your Purchase including, without limitation, your credit card number, the expiration date of your credit card, your billing address, and your shipping information. We reserve the right to refuse or cancel your order at any time for certain reasons including but not limited to: product or service availability, errors in the description or price of the product or service, error in your order or other reasons.</p>
        
        <h2>4. Intellectual Property</h2>
        <p>The Service and its original content, features and functionality are and will remain the exclusive property of BoIBD and its licensors. The Service is protected by copyright, trademark, and other laws of both the local and foreign countries. Our trademarks and trade dress may not be used in connection with any product or service without the prior written consent of BoIBD.</p>

        <h2>5. Termination</h2>
        <p>We may terminate or suspend your account immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms. Upon termination, your right to use the Service will immediately cease.</p>

        <h2>6. Limitation of Liability</h2>
        <p>In no event shall BoIBD, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from your access to or use of or inability to access or use the Service.</p>

        <h2>7. Governing Law</h2>
        <p>These Terms shall be governed and construed in accordance with the laws of the jurisdiction, without regard to its conflict of law provisions.</p>
        
        <div class="contact-info">
            <h3>Contact Us</h3>
            <p>If you have any questions about these Terms, please contact us at <a href="mailto:legal@boilagbe.com">legal@boilagbe.com</a>.</p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="script.js"></script>

</body>
</html>