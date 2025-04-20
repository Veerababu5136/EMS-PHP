<?php


session_start();

include 'connection.php';

if (!isset($_SESSION['admin_id'])) 
{
    echo "alert('session expired..')";
    header("Location: index.php");
    exit;
}

?>