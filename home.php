<?php
session_start();

// Auto-login using cookie
if (!isset($_SESSION['username']) && isset($_COOKIE['username'])) {
  $_SESSION['username'] = $_COOKIE['username'];
}

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
  $movies = $pdo->query("SELECT * FROM movies")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "DB Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home - Netflix Clone</title>
  <link rel="stylesheet" href="src/styles.css" />

  <style>
    body {
      background-color: #141414;
      color: white;
      text-align: center;
      padding: 50px;
    }
    .movies {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
    }
    .movie-card {
      margin: 10px;
      background: rgba(255, 255, 255, 0.1);
      padding: 10px;
      border-radius: 5px;
      width: 200px;
    }
    .movie-card img {
      width: 100%;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <nav>
    <ul>
      <li><a href="home.php">Home</a></li>
      <li><a href="plans.php">Plans</a></li>
      <li><a href="checkout.php">Checkout</a></li>
      <li>
        <a href="#">Profile</a>
        <ul class="dropdown">
          <li><a href="profile.php">View Profile</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </li>
    </ul>
  </nav>
  <h1>Welcome to Netflix Clone</h1>
  <script src="index.js"></script>

  <p>Hello, <?php echo $_SESSION['username']; ?>!</p>


  <h2>Available Movies</h2>
  <div class="movies">

  <div class="movies">
    <?php foreach ($movies as $movie): ?>
      <div class="movie-card">
        <img src="uploads/movies/<?php echo $movie['image']; ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>" />
        <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
        <p><?php echo htmlspecialchars($movie['genre']); ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>

</body>
</html>
