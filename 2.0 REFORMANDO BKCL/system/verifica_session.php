<?php 
session_start();
if (!$_SESSION['usuario']) {
    header('Location: ../feed.php');
    exit();
}
