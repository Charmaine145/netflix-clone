<?php
session_start();
if (!isset($_SESSION['username'])) { header('Location: login.html'); exit(); }
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Movie</title>
  <style>
    body { background: #141414; color: white; text-align: center; }
    input, textarea, button { padding: 10px; margin: 10px; width: 300px; }
  </style>
</head>
<body>
  <h2>Add New Movie</h2>
  <form action="process_movie.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Movie Title" required /><br>
    <textarea name="description" placeholder="Description"></textarea><br>
    <input type="text" name="genre" placeholder="Genre" /><br>
    <input type="file" name="image" required /><br>
    <button type="submit">Add Movie</button>
  </form>
  <a href="admin_dashboard.php" style="color:red;">Back to Dashboard</a>
</body>
</html>
