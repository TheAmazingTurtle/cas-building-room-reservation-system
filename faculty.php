<?php
if (__FILE__ === realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('This file cannot be accessed directly.');
}

require 'db_connector.php';

$stmt = $conn->prepare("SELECT * FROM faculty");
$stmt->execute();
$facultyResult = $stmt->get_result();

if ($facultyResult -> num_rows > 0){
    while($faculty = $facultyResult -> fetch_assoc()){
        echo    "<div class='student-profile'>".
                    "<p class='profile-faculty-id'>{$faculty['student_number']}</p>".
                    "<p class='profile-faculty-name'>{$faculty['student_name']}</p>".
                    "<p class='profile-division'>{$faculty['degree_program']}</p>".
                    "<p class='profile-available'>{$faculty['year_level']}</p>".
                    "<div><button>Edit</button><button>Delete</button></div>".
                "</div>";     
    }
}
else {
    echo    "<div>".
                "<p class='no-faculty'>No Available Room Found</p>".
            "</div>";
}

$conn -> close();
?>