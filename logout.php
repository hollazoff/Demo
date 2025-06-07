<?php
    session_start();
include 'db.php';

session_destroy();
header('Location: index.php');
exit();
?>