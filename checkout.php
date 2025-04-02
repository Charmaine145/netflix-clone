<?php
session_start();
require 'vendor/autoload.php'; // Stripe PHP SDK

\Stripe\Stripe::setApiKey('sk_test_YOUR_SECRET_KEY'); // Replace with your key

$plan = $_GET['plan'];
$price = $_GET['price'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $token = $_POST['stripeToken'];

  try {
    // Charge
    $charge = \Stripe\Charge::create([
      'amount' => $price * 100,
      'currency' => 'usd',
      'description' => "$plan Plan Subscription",
      'source' => $token,
    ]);

    // Update DB
    $pdo = new PDO("mysql:host=localhost;dbname=netflix_clone", 'root', '');
    $expiry = date('Y-m-d', strtotime('+1 month'));
    $stmt = $pdo->prepare("UPDATE users SET subscription_plan=?, subscription_expiry=? WHERE username=?");
    $stmt->execute([$plan, $expiry, $_SESSION['username']]);

    header('Location: profile.php');

  } catch (Exception $e) {
    echo "Payment Failed: " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Checkout</title>
  <style>
    body { background: #141414; color: white; text-align: center; }
  </style>
</head>
<body>
  <h2>Checkout for <?php echo $plan; ?> - $<?php echo $price; ?></h2>
  <form action="" method="POST">
    <script
      src="https://checkout.stripe.com/checkout.js" class="stripe-button"
      data-key="pk_test_YOUR_PUBLISHABLE_KEY"
      data-amount="<?php echo $price * 100; ?>"
      data-name="Netflix Clone"
      data-description="<?php echo $plan; ?> Plan"
      data-currency="usd">
    </script>
  </form>
</body>
</html>
