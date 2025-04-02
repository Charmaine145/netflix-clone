<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$host = 'localhost';
$user = 'root';
$password = '';
$db = 'netflix_clone';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $username = $_POST['username'];
  $email = $_POST['email'];
  $password_input = $_POST['password'];
  $hashed_password = password_hash($password_input, PASSWORD_BCRYPT);
  $token = bin2hex(random_bytes(16));

  // Insert user
  $stmt = $pdo->prepare("INSERT INTO users (username, email, password, verification_token) VALUES (?, ?, ?, ?)");
  $stmt->execute([$username, $email, $hashed_password, $token]);

  // Send verification email
  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@gmail.com'; // Your Gmail address
    $mail->Password = 'your_email_password'; // Your Gmail password or App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('your_email@gmail.com', 'Netflix Clone');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Verify Your Email';
    $verify_link = "http://localhost/verify.php?token=$token";
    $mail->Body = "Click to verify your account: <a href='$verify_link'>$verify_link</a>";

    $mail->send();
    echo "<h3 style='color:green;'>Registration Successful! Check your email to verify.</h3>";

  } catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
  }

} catch (PDOException $e) {
  echo "DB Error: " . $e->getMessage();
}
?>
