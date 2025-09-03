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
    <title>Privacy Policy - BoiLagbe!</title>
    
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'user_header.php'; ?>

<div class="policy-hero">
    <div class="hero-content">
        <h1>Privacy Policy</h1>
        <p>Your privacy is important to us. It is BoiLagbe!'s policy to respect your privacy regarding any information we may collect from you.</p>
    </div>
</div>

<div class="policy-container">
    <div class="policy-content">
        <p class="last-updated">Last updated: <?php echo date('F j, Y'); ?></p>

        <h2>1. Information We Collect</h2>
        <p>We only ask for personal information when we truly need it to provide a service to you. We collect it by fair and lawful means, with your knowledge and consent. We also let you know why we’re collecting it and how it will be used.</p>
        <ul>
            <li><strong>Log data:</strong> When you visit our website, our servers may automatically log the standard data provided by your web browser. This may include your device’s Internet Protocol (IP) address, your browser type and version, the pages you visit, the time and date of your visit, the time spent on each page, and other details.</li>
            <li><strong>Personal Information:</strong> We may ask for personal information, such as your: Name, Email, Shipping Address, and Payment Information.</li>
        </ul>

        <h2>2. How We Use Your Information</h2>
        <p>We use the information we collect in various ways, including to:</p>
        <ul>
            <li>Provide, operate, and maintain our website</li>
            <li>Process your transactions and manage your orders</li>
            <li>Improve, personalize, and expand our website</li>
            <li>Communicate with you, either directly or through one of our partners, including for customer service, to provide you with updates and other information relating to the website, and for marketing and promotional purposes</li>
            <li>Send you emails</li>
            <li>Find and prevent fraud</li>
        </ul>

        <h2>3. Security of Your Information</h2>
        <p>We value your trust in providing us your Personal Information, thus we are striving to use commercially acceptable means of protecting it. But remember that no method of transmission over the internet, or method of electronic storage is 100% secure and reliable, and we cannot guarantee its absolute security.</p>

        <h2>4. Your Rights</h2>
        <p>You are free to refuse our request for your personal information, with the understanding that we may be unable to provide you with some of your desired services. You have the right to access, update, or delete the information we have on you.</p>

        <h2>5. Links to Other Sites</h2>
        <p>Our Service may contain links to other sites that are not operated by us. If you click a third party link, you will be directed to that third party's site. We strongly advise you to review the Privacy Policy of every site you visit. We have no control over and assume no responsibility for the content, privacy policies or practices of any third party sites or services.</p>

        <h2>6. Changes to This Privacy Policy</h2>
        <p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page. You are advised to review this Privacy Policy periodically for any changes.</p>
        
        <div class="contact-info">
            <h3>Contact Us</h3>
            <p>If you have any questions about this Privacy Policy, you can contact us at <a href="mailto:support@boilagbe.com">support@boilagbe.com</a>.</p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="script.js"></script>

</body>
</html>