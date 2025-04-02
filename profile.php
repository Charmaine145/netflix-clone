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

  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->execute([$_SESSION['username']]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
  echo "DB Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Profile</title>
  <style>
    body { background-color: #141414; color: white; text-align: center; }
    .container { padding: 30px; }
    img { width: 100px; height: 100px; border-radius: 50%; margin: 20px; }
    input, button { padding: 10px; margin: 10px; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
    <img src="uploads/<?php echo $user['profile_picture']; ?>" alt="Profile Picture" />
    
    <form action="update_profile.php" method="POST" enctype="multipart/form-data">
      <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required /><br>
      <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required /><br>
      <input type="password" name="password" placeholder="New Password (leave blank if unchanged)" /><br>
      <input type="file" name="profile_picture" /><br>
      <button type="submit">Update Profile</button>
    </form>

    <br>
    <a href="logout.php" style="color: #e50914;">Logout</a>
  </div>
</body>
</html>
