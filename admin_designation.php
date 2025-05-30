<?php
if (__FILE__ === realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('This file cannot be accessed directly.');
}

require 'db_connector.php';

$stmt = $conn->prepare("SELECT DISTINCT designation FROM admin");
$stmt->execute();
$adminResult = $stmt->get_result();

if ($adminResult -> num_rows > 0){
    while($admin = $adminResult -> fetch_assoc()){
        echo "<label><input type='checkbox' class='designation' value='". $admin['designation'] ."'>". $admin['designation'] ."</label>";
    }
}
else {
    echo "<h4>No Admin in Database</h4>";
}

$conn -> close();
?>