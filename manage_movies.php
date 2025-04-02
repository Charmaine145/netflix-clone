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

  $stmt = $pdo->query("SELECT * FROM movies");

} catch (PDOException $e) {
  echo "DB Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Movies</title>
  <style>
    body { background: #141414; color: white; text-align: center; }
    table { width: 80%; margin: 20px auto; border-collapse: collapse; }
    th, td { padding: 10px; border: 1px solid white; }
    img { width: 100px; }
  </style>
</head>
<body>
  <h2>Manage Movies</h2>
  <a href="add_movie.php" style="color:green;">Add New Movie</a><br><br>
  <table>
    <tr>
      <th>ID</th>
      <th>Title</th>
      <th>Genre</th>
      <th>Image</th>
      <th>Actions</th>
    </tr>
    <?php while ($movie = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
    <tr>
      <td><?php echo $movie['id']; ?></td>
      <td><?php echo htmlspecialchars($movie['title']); ?></td>
      <td><?php echo htmlspecialchars($movie['genre']); ?></td>
      <td><img src="uploads/movies/<?php echo $movie['image']; ?>" /></td>
      <td><a href="delete_movie.php?id=<?php echo $movie['id']; ?>">Delete</a></td>
    </tr>
    <?php endwhile; ?>
  </table>
  <a href="admin_dashboard.php" style="color:red;">Back to Dashboard</a>
</body>
</html>
