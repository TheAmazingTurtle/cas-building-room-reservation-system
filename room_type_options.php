<?php
if (__FILE__ === realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('This file cannot be accessed directly.');
}

require 'db_connect.php';

$stmt = $conn->prepare("SELECT DISTINCT room_type FROM room");
$stmt->execute();
$roomTypeResult = $stmt->get_result();

if ($roomTypeResult -> num_rows > 0){
    while($roomType = $roomTypeResult -> fetch_assoc()){
        echo "<label><input type='checkbox' class='room-type-option' value='". $roomType['room_type'] ."'>". $roomType['room_type'] ."</label>";
    }
}
else {
    echo "<h4>No Rooms Available</h4>";
}

$conn -> close();
?>