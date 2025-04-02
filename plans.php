<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Plans</title>
    <style>
        body { background: #141414; color: white; text-align: center; }
        .plan { margin: 20px; padding: 20px; border: 1px solid #e50914; display: inline-block; }
        button { padding: 10px; background: #e50914; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Select a Subscription Plan</h2>
    <div class="plan">
        <h3>Basic Plan</h3>
        <p>Price: $8.99/month</p>
        <button onclick="location.href='process_payment.php?plan=basic'">Subscribe</button>
    </div>
    <div class="plan">
        <h3>Standard Plan</h3>
        <p>Price: $13.99/month</p>
        <button onclick="location.href='process_payment.php?plan=standard'">Subscribe</button>
    </div>
    <div class="plan">
        <h3>Premium Plan</h3>
        <p>Price: $17.99/month</p>
        <button onclick="location.href='process_payment.php?plan=premium'">Subscribe</button>
    </div>
    <a href="admin_dashboard.php" style="color:red;">Back to Dashboard</a>
</body>
</html>
