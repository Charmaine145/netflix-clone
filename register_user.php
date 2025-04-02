<?php
// Database credentials
$host = 'localhost';
$user = 'root';
$password = '';
$db = 'netflix_clone';

// Connect using PDO
try {
  $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  // Test user data
  $username = 'admin';
  $plain_password = '123456';
  
  // Hash password
  $hashed_password = password_hash($plain_password, PASSWORD_BCRYPT);
  
  // Insert user using prepared statement
  $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
  $stmt->execute([$username, $hashed_password]);

  // Start session and redirect to home page
  session_start();
  $_SESSION['username'] = $username;
  header('Location: home.php');
  exit();

  
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
?>
