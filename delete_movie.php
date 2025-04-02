<?php
session_start();
if (!isset($_SESSION['username'])) { header('Location: login.html'); exit(); }

$host = 'localhost';
$user = 'root';
$password = '';
$db = 'netflix_clone';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $id = $_GET['id'] ?? null;
  if ($id) {
    // Delete image file
    $stmt = $pdo->prepare("SELECT image FROM movies WHERE id = ?");
    $stmt->execute([$id]);
    $movie = $stmt->fetch(PDO::FETCH_ASSOC);
    unlink('uploads/movies/' . $movie['image']);

    // Delete DB record
    $stmt = $pdo->prepare("DELETE FROM movies WHERE id = ?");
    $stmt->execute([$id]);
  }

  header('Location: manage_movies.php');

} catch (PDOException $e) {
  echo "DB Error: " . $e->getMessage();
}
?>
