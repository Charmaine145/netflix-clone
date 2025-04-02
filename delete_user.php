<?php
session_start();
if (!isset($_SESSION['username'])) {
  header('Location: login.html');
  exit();
}

$host = 'localhost';
$user = 'root';
$password = '';
$db = 'netflix_clone';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $id = $_GET['id'] ?? null;

  if ($id) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
  }

  header('Location: admin_dashboard.php');

} catch (PDOException $e) {
  echo "DB Error: " . $e->getMessage();
}
?>
