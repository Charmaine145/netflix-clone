<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit();
}

require 'path/to/payment-gateway-sdk.php'; // Include payment gateway SDK

// Get the selected plan from the query parameter
$plan = $_GET['plan'];

// Implement the payment processing logic using Stripe
$paymentIntent = \Stripe\PaymentIntent::create([
    'amount' => calculateAmount($plan), // Function to calculate amount based on plan
    'currency' => 'usd',
    'payment_method_types' => ['card'],
]);

// After successful payment, update the user's subscription status in the database
$pdo = new PDO("mysql:host=localhost;dbname=netflix_clone", 'root', '');
$stmt = $pdo->prepare("INSERT INTO subscriptions (user_id, plan_type, subscription_expiry) VALUES (?, ?, ?)");
$stmt->execute([$_SESSION['user_id'], $plan, date('Y-m-d H:i:s', strtotime('+1 month'))]);

// Redirect to confirmation page
header('Location: confirmation.php');
exit();


// Function to calculate amount based on plan
function calculateAmount($plan) {
    switch ($plan) {
        case 'basic':
            return 899; // $8.99
        case 'standard':
            return 1399; // $13.99
        case 'premium':
            return 1799; // $17.99
        default:
            return 0;
    }
}
?>
