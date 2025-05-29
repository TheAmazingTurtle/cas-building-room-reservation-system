<?php
if (__FILE__ === realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('This file cannot be accessed directly.');
}

require 'db_connector.php';

$stmt = $conn -> prepare("SELECT * FROM student_reservation INNER JOIN student USING(student_number) WHERE is_active = 1 and faculty_id = ? and is_faculty_approved IS null");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$approvalRequestData = $stmt->get_result();

if ($approvalRequestData -> num_rows > 0){
    while ($approvalRequest = $approvalRequestData -> fetch_assoc()){

        $timeStart =  formatDateTime($approvalRequest['time_start']);
        $requestTime =  formatDate($approvalRequest['request_date']);

        echo    "<div class='scroll-item' onclick=\"window.location.href='reservation.php?res_id={$approvalRequest['student_reservation_id']}&action=approve&type=student'\">".
                    "<p>{$approvalRequest['student_reservation_id']}</p>".
                    "<p>{$approvalRequest['student_name']}</p>".
                    "<p>{$approvalRequest['room_name']}</p>".
                    "<p>$timeStart</p>".
                    "<p>$requestTime</p>".
                "</div>";
    }
}
else {
    "<div>".
        "<p>No Faculty-in-Charge request at the moment.</p>".
    "</div>";
}

$conn -> close();


?>