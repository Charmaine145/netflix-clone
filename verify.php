<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db = 'netflix_clone';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $token = $_GET['token'] ?? '';

  $stmt = $pdo->prepare("SELECT * FROM users WHERE verification_token = ?");
  $stmt->execute([$token]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    $stmt = $pdo->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?");
    $stmt->execute([$user['id']]);

    echo "<h3 style='color:green;'>Email Verified! <a href='login.html'>Login Now</a></h3>";
  } else {
    echo "<h3 style='color:red;'>Invalid verification link.</h3>";
  }

} catch (PDOException $e) {
  echo "DB Error: " . $e->getMessage();
}
?>
