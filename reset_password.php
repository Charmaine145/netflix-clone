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

    // Get the token and new password from the form
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate the token
    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires > NOW()");
    $stmt->execute([$token]);
    $reset_request = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reset_request) {
        // Check if passwords match
        if ($new_password === $confirm_password) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

            // Update the user's password in the database
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
            $stmt->execute([$hashed_password, $reset_request['email']]);

            // Optionally, delete the token from the database
            $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
            $stmt->execute([$token]);

            echo "<h2 style='color:green;'>Password has been reset successfully!</h2>";
            echo "<a href='login.html'>Login</a>";
        } else {
            echo "<h2 style='color:red;'>Passwords do not match.</h2>";
        }
    } else {
        echo "<h2 style='color:red;'>Invalid or expired token.</h2>";
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>
