<?php
if (__FILE__ === realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('This file cannot be accessed directly.');
}

require 'db_connector.php';

$stmt = $conn->prepare("SELECT DISTINCT floor_number FROM room");
$stmt->execute();
$roomTypeResult = $stmt->get_result();

if ($roomTypeResult -> num_rows > 0){
    while($roomType = $roomTypeResult -> fetch_assoc()){
        echo "<label><input type='checkbox' class='room-floor-option' value='". $roomType['floor_number'] ."'>". $roomType['floor_number'] ."</label>";
    }
}
else {
    echo "<h4>No Rooms Available</h4>";
}

$conn -> close();
?>