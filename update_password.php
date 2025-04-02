<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db = 'netflix_clone';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $token = $_POST['token'];
  $new_password = $_POST['new_password'];

  // Verify token
  $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ?");
  $stmt->execute([$token]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    // Update password
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE id = ?");
    $stmt->execute([$hashed_password, $user['id']]);

    echo "<h3 style='color:green;'>Password updated successfully! <a href='login.html'>Login Now</a></h3>";
  } else {
    echo "<h3 style='color:red;'>Invalid or expired token.</h3>";
  }

} catch (PDOException $e) {
  echo "DB Error: " . $e->getMessage();
}
?>
