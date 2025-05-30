<?php
if (__FILE__ === realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('This file cannot be accessed directly.');
}

require 'db_connector.php';

$stmt = $conn->prepare("SELECT * FROM admin");
$stmt->execute();
$adminResult = $stmt->get_result();

if ($adminResult -> num_rows > 0){
    while($admin = $adminResult -> fetch_assoc()){

        echo    "<div class='student-profile'>".
                    "<p class='profile-admin-id'>{$admin['admin_id']}</p>".
                    "<p class='profile-admin-name'>{$admin['admin_name']}</p>".
                    "<p class='profile-designation'>{$admin['designation']}</p>".
                    "<div><button>Edit</button><button>Delete</button></div>".
                "</div>";     
    }
}
else {
    echo    "<div>".
                "<p class='no-faculty'>No Admin Registered in Database</p>".
            "</div>";
}

$conn -> close();
?>