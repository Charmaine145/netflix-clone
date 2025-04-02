<?php
// Database credentials
$host = 'localhost';
$user = 'root';
$password = '';
$db = 'netflix_clone';

// Connect using PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the email from the form
    $email = $_POST['email'];

    // Check if the email exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Generate a unique token
        $token = bin2hex(random_bytes(16));
        $expires = date("U") + 3600; // Token valid for 1 hour

        // Store the token in the database (you may want to create a separate table for tokens)
        $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires) VALUES (?, ?, ?)");
        $stmt->execute([$email, $token, $expires]);

        // Send the reset link via email
        $reset_link = "http://localhost/reset_password.php?token=" . $token;
        $subject = "Password Reset Request";
        $message = "Click the link to reset your password: " . $reset_link;
        mail($email, $subject, $message);

        echo "<h2 style='color:green;'>Reset link has been sent to your email.</h2>";
    } else {
        echo "<h2 style='color:red;'>No account found with that email address.</h2>";
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>
