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
        $facultyStatus = $faculty['is_available'] ? "Yes" : "No";

        echo    "<div class='student-profile'>".
                    "<p class='profile-faculty-id'>{$faculty['faculty_id']}</p>".
                    "<p class='profile-faculty-name'>{$faculty['faculty_name']}</p>".
                    "<p class='profile-division'>{$faculty['division']}</p>".
                    "<p class='profile-available'>$facultyStatus</p>".
                    "<div><button>Edit</button><button>Delete</button></div>".
                "</div>";     
    }
}
else {
    echo    "<div>".
                "<p class='no-faculty'>No Faculty Registered in Database</p>".
            "</div>";
}

$conn -> close();
?>