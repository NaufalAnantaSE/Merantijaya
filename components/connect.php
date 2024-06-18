<?php

$db_name = 'mysql:host=db;dbname=merantijaya'; // Ganti dengan alamat IP container MySQL
$user_name = 'merantijaya';
$user_password = 'merantijaya';

try {
    $conn = new PDO($db_name, $user_name, $user_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>
