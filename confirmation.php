<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit();
}

// Fetch user subscription details from the database
$pdo = new PDO("mysql:host=localhost;dbname=netflix_clone", 'root', '');
$stmt = $pdo->prepare("SELECT plan_type, subscription_expiry FROM subscriptions WHERE user_id = (SELECT id FROM users WHERE username = ?)");
$stmt->execute([$_SESSION['username']]);
$subscription = $stmt->fetch();

if (!$subscription) {
    echo "No active subscription found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Confirmation</title>
    <style>
        body { background: #141414; color: white; text-align: center; }
    </style>
</head>
<body>
    <h2>Subscription Confirmation</h2>
    <p>Thank you for subscribing to the <?php echo htmlspecialchars($subscription['plan_type']); ?> plan!</p>
    <p>Your subscription is active until <?php echo htmlspecialchars($subscription['subscription_expiry']); ?>.</p>
    <a href="plans.php" style="color:red;">Back to Plans</a>
</body>
</html>
