<?php
if (__FILE__ === realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('This file cannot be accessed directly.');
}

require 'db_connector.php';

$stmt = $conn -> prepare("SELECT * FROM faculty_reservation INNER JOIN faculty USING(faculty_id) WHERE is_active = 1 and is_admin_approved IS NULL");
$stmt->execute();
$approvalRequestData = $stmt->get_result();

if ($approvalRequestData -> num_rows > 0){
    while ($approvalRequest = $approvalRequestData -> fetch_assoc()){

        $timeStart =  formatDateTime($approvalRequest['time_start']);
        $requestTime =  formatDate($approvalRequest['request_date']);

        echo    "<div class='scroll-item' onclick=\"window.location.href='reservation.php?res_id={$approvalRequest['faculty_reservation_id']}&action=approve&type=faculty'\">".
                    "<p>{$approvalRequest['faculty_reservation_id']}</p>".
                    "<p>{$approvalRequest['faculty_name']}</p>".
                    "<p>{$approvalRequest['room_name']}</p>".
                    "<p>$timeStart</p>".
                    "<p>$requestTime</p>".
                "</div>";
    }
}
else {
    "<div>".
        "<p>No <strong>admin</strong> approval request from <strong>students</strong> at the moment.</p>".
    "</div>";
}

$conn -> close();


?>