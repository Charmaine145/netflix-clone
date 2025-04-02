<?php
session_start();
session_destroy();

// Clear cookies
setcookie('username', '', time() - 3600, "/");
setcookie('token', '', time() - 3600, "/");

header('Location: login.html');
exit();
?>
