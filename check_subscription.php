<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=netflix_clone", 'root', '');
$stmt = $pdo->prepare("SELECT subscription_expiry FROM users WHERE username=?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch();

if (!$user || !$user['subscription_expiry'] || strtotime($user['subscription_expiry']) < time()) {
  header('Location: plans.php');
  exit();
}
?>
