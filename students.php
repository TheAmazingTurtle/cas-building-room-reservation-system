<?php
if (__FILE__ === realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('This file cannot be accessed directly.');
}

require 'db_connector.php';

$stmt = $conn->prepare("SELECT * FROM student");
$stmt->execute();
$studentResult = $stmt->get_result();

if ($studentResult -> num_rows > 0){
    while($student = $studentResult -> fetch_assoc()){
        echo    "<div class='student-profile'>".
                    "<p class='profile-student-number'>{$student['student_number']}</p>".
                    "<p class='profile-student-name'>{$student['student_name']}</p>".
                    "<p class='profile-degree-program'>{$student['degree_program']}</p>".
                    "<p class='profile-year-level'>{$student['year_level']}</p>".
                    "<p class='profile-college'>{$student['college']}</p>".
                    "<div><button>Edit</button><button>Delete</button></div>".
                "</div>";     
    }
}
else {
    echo    "<div>".
                "<p class='no-student'>No Students Registered in Database/p>".
            "</div>";
}

$conn -> close();
?>