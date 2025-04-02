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

  // Fetch all users
  $stmt = $pdo->query("SELECT id, username, email, is_verified, is_admin FROM users");

} catch (PDOException $e) {
  echo "DB Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel</title>
  <style>
    body { background: #141414; color: white; text-align: center; }
    table { width: 80%; margin: 20px auto; border-collapse: collapse; }
    th, td { padding: 10px; border: 1px solid white; }
    a { color: red; }
  </style>
</head>
<body>
  <h2>Admin Dashboard</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Username</th>
      <th>Email</th>
      <th>Verified</th>
      <th>Admin</th>
      <th>Actions</th>
    </tr>
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
    <tr>
      <td><?php echo $row['id']; ?></td>
      <td><?php echo htmlspecialchars($row['username']); ?></td>
      <td><?php echo htmlspecialchars($row['email']); ?></td>
      <td><?php echo $row['is_verified'] ? 'Yes' : 'No'; ?></td>
      <td><?php echo $row['is_admin'] ? 'Yes' : 'No'; ?></td>
      <td>
        <?php if ($row['is_admin'] == 0): ?>
        <a href="delete_user.php?id=<?php echo $row['id']; ?>">Delete</a>
        <?php endif; ?>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
  <a href="logout.php" style="color: #e50914;">Logout</a>
</body>
</html>
