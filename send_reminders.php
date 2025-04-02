<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

$pdo = new PDO("mysql:host=localhost;dbname=netflix_clone", 'root', '');
$stmt = $pdo->query("SELECT username, email, subscription_expiry FROM users WHERE subscription_expiry IS NOT NULL");

while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $days_left = (strtotime($user['subscription_expiry']) - time()) / (60 * 60 * 24);

  if ($days_left <= 3 && $days_left >= 0) {
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@gmail.com'; // Your email
    $mail->Password = 'your_email_password'; // Your email password or app password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('your_email@gmail.com', 'Netflix Clone');
    $mail->addAddress($user['email'], $user['username']);
    $mail->Subject = 'Your Subscription is Expiring Soon!';
    $mail->Body = "Hello {$user['username']},\n\nYour subscription will expire in {$days_left} day(s).\nPlease renew it to continue enjoying content.\n\nThank you!";

    if (!$mail->send()) {
      echo "Failed to send to {$user['email']}\n";
    } else {
      echo "Reminder sent to {$user['email']}\n";
    }
  }
}
?>
