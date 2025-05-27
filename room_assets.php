<?php
if (__FILE__ === realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('This file cannot be accessed directly.');
}

require 'db_connector.php';

$stmt = $conn->prepare("SELECT asset_name, quantity FROM room INNER JOIN contains USING(room_name) INNER JOIN asset USING(asset_id) WHERE room_name = ?");
$stmt->bind_param("s", $roomName);
$stmt->execute();
$assetResult = $stmt->get_result();

if ($assetResult -> num_rows > 0){
    while($asset = $assetResult -> fetch_assoc()){
        echo    "<tr>".
                    "<td>".$asset['asset_name']."</td>".
                    "<td>".$asset['quantity']."</td>".
                "</tr>";     
    }
}
else {
    echo    "<tr>".
                "<td colspan='2'>This room does not have any.</td>".
            "</tr>";
}

$conn -> close();




?>