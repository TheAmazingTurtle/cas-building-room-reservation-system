<?php
if (__FILE__ === realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('This file cannot be accessed directly.');
}

require 'db_connector.php';

$stmt = $conn->prepare("SELECT DISTINCT division FROM faculty");
$stmt->execute();
$facultyResult = $stmt->get_result();

if ($facultyResult -> num_rows > 0){
    while($faculty = $facultyResult -> fetch_assoc()){
        echo "<label><input type='checkbox' class='division' value='". $faculty['division'] ."'>". $faculty['division'] ."</label>";
    }
}
else {
    echo "<h4>No Faculty in Database</h4>";
}

$conn -> close();
?>