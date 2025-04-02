<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Load Composer's autoloader

$host = 'localhost';
$user = 'root';
$password = '';
$db = 'netflix_clone';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $username = $_POST['username'];

  // Check if user exists
  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->execute([$username]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    // Generate token
    $token = bin2hex(random_bytes(16));

    // Save token in DB
    $stmt = $pdo->prepare("UPDATE users SET reset_token = ? WHERE username = ?");
    $stmt->execute([$token, $username]);

    // Send email with PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@gmail.com'; // Your Gmail address
    $mail->Password = 'your_gmail_password_or_app_password'; // Your Gmail password or App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('your_email@gmail.com', 'Netflix Clone');
    $mail->addAddress($user['email']); // Fetch user email from DB

    // Content
    $reset_link = "http://localhost/reset_password.php?token=$token";
    $mail->isHTML(true);
    $mail->Subject = 'Password Reset Request';
    $mail->Body    = "Click here to reset your password: <a href='$reset_link'>Reset Password</a>";

    $mail->send();
    echo "<h3 style='color:green;'>Reset link has been sent to your email.</h3>";
  } else {
    echo "<h3 style='color:red;'>Username not found.</h3>";
  }

} catch (PDOException $e) {
  echo "DB Error: " . $e->getMessage();
}
?>
