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

  $title = $_POST['title'];
  $description = $_POST['description'];
  $genre = $_POST['genre'];
  $image = $_FILES['image'];

  // Image upload
  $target_dir = "uploads/movies/";
  if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
  }
  $filename = time() . "_" . basename($image["name"]);
  $target_file = $target_dir . $filename;
  move_uploaded_file($image["tmp_name"], $target_file);

  // Insert movie
  $stmt = $pdo->prepare("INSERT INTO movies (title, description, genre, image) VALUES (?, ?, ?, ?)");
  $stmt->execute([$title, $description, $genre, $filename]);

  header('Location: manage_movies.php');

} catch (PDOException $e) {
  echo "DB Error: " . $e->getMessage();
}
?>
