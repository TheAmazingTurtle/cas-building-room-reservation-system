<?php
$host = 'localhost';
$db   = 'cmsc127_room_reservation_system';
$user = 'root';
$pass = ''; // change this if you have a MySQL password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>