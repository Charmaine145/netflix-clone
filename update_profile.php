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

  $username = $_POST['username'];
  $email = $_POST['email'];
  $password_input = $_POST['password'];
  $profile_pic = $_FILES['profile_picture'];

  // Handle profile picture upload
  if ($profile_pic['size'] > 0) {
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
      mkdir($target_dir, 0777, true);
    }
    $filename = basename($profile_pic["name"]);
    $target_file = $target_dir . time() . "_" . $filename;
    move_uploaded_file($profile_pic["tmp_name"], $target_file);

    $update_pic_sql = ", profile_picture = '" . basename($target_file) . "'";
  } else {
    $update_pic_sql = "";
  }

  // Update password if provided
  if (!empty($password_input)) {
    $hashed_password = password_hash($password_input, PASSWORD_BCRYPT);
    $update_pass_sql = ", password = '$hashed_password'";
  } else {
    $update_pass_sql = "";
  }

  // Update DB
  $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? $update_pass_sql $update_pic_sql WHERE username = ?");
  $stmt->execute([$username, $email, $_SESSION['username']]);

  $_SESSION['username'] = $username;
  header('Location: profile.php');

} catch (PDOException $e) {
  echo "DB Error: " . $e->getMessage();
}
?>
