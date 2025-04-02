<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = '';
$db = 'netflix_clone';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $username = $_POST['username'];
  $password_input = $_POST['password'];

  // Check credentials
  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->execute([$username]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($password_input, $user['password'])) {
    if ($user['is_verified'] == 0) {
      echo "<h3 style='color:red;'>Please verify your email before logging in.</h3>";
      exit();
    }
    $_SESSION['username'] = $username;
    echo "<h2 style='color:green;'>Login Successful! Welcome, $username.</h2>";
    echo "<a href='home.php'>Go to Home Page</a>";
  } else {
    echo "<h2 style='color:red;'>Invalid Username or Password</h2>";
    echo "<a href='login.html'>Try Again</a>";
  }

} catch (PDOException $e) {
  echo "Database Error: " . $e->getMessage();
}
?>
