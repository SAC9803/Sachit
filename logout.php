<?php
// logout.php - Handle user logout
session_start();
session_destroy();
header('Location: index.php');
exit;
?>