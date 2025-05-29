<?php
if (__FILE__ === realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('This file cannot be accessed directly.');
}

require 'db_connector.php';

$stmt = $conn->prepare("SELECT DISTINCT year_level FROM student");
$stmt->execute();
$studentResult = $stmt->get_result();

if ($studentResult -> num_rows > 0){
    while($student = $studentResult -> fetch_assoc()){
        echo "<label><input type='checkbox' class='year-level-option' value='". $student['year_level'] ."'>". $student['year_level'] ."</label>";
    }
}
else {
    echo "<h4>No Students in Database</h4>";
}

$conn -> close();
?>