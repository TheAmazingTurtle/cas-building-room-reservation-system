<?php
if (__FILE__ === realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('This file cannot be accessed directly.');
}

require 'db_connector.php';

$stmt = $conn->prepare("SELECT * FROM room WHERE is_available = 1");
$stmt->execute();
$roomResult = $stmt->get_result();

if ($roomResult -> num_rows > 0){
    while($room = $roomResult -> fetch_assoc()){
        echo    "<div class='room-option' onclick=\"window.location.href='room_details.php?room_name={$room['room_name']}'\">".
                    "<input type='hidden' name='room-name' value='".$room['room_name']."'>".
                    "<p class='room-name'>".$room['room_name']."</p>".
                    "<p class='room-type'>".$room['room_type']."</p>".
                    "<p class='room-floor'>".$room['floor_number']."</p>".
                    "<p class='room-capacity'>".$room['capacity']."</p>".
                "</div>";     
    }
}
else {
    echo    "<div>".
                "<p class='room-not-found'>No Available Room Found</p>".
            "</div>";
}

$conn -> close();
?>