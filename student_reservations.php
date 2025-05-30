<?php

if (__FILE__ === realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('This file cannot be accessed directly.');
}

require 'db_connector.php';

$restriction = "";

switch ($_SESSION['current_page']){
    case 'dashboard':
        $restriction = " and is_archived = 0";
        break;
    case 'archive':
        $restriction = " and is_archived = 1";
        break;
    default:
        break;
}

$stmt = $conn->prepare("SELECT *
                        FROM student_reservation 
                        INNER JOIN student USING(student_number) 
                        INNER JOIN faculty USING(faculty_id) 
                        WHERE student_number = ?$restriction");

$stmt->bind_param("s", $user_id);
$stmt->execute();
$studentReservationResult = $stmt->get_result();


if ($studentReservationResult -> num_rows > 0){

    $now = new DateTime();
    while ($reservation = $studentReservationResult -> fetch_assoc()){

        $reservationId = $reservation["student_reservation_id"];

        $status = null;
        $end = new DateTime($reservation['time_end']);


        $isCancellable = FALSE;
        if (!$reservation["is_active"]){
            $status = "Cancelled";
        }
        else if (is_null($reservation["is_faculty_approved"])){
            $status = "Awaiting FIC Approval";
            $isCancellable = TRUE;
        }
        else if (!$reservation["is_faculty_approved"]){
            $status = "Denied by FIC";
        }
        else if (is_null($reservation["is_admin_approved"])){
            $status = "Awaiting Admin Approval";
            $isCancellable = TRUE;
        }
        else if (!$reservation["is_admin_approved"]){
            $status = "Denied by Admin";
        }
        else if ($end >= $now){
            $status = "Approved";
            $isCancellable = TRUE;
        }
        else {
            $status = "Completed";
        }

        $actionButtons =    "<a href='reservation.php?res_id=$reservationId&action=more_details&type=student'><button>More Details</button></a>";


        $actionButtons =   $actionButtons."<form action='archive_reservation.php' method='post'>".
                                "<input type='text' style='display:none;' name='reservation-id' value='$reservationId'>".
                                "<input type='text' style='display:none;' name='action-mode' value='". (!$reservation['is_archived']) ."'>".
                                "<button type='submit'>".($reservation['is_archived'] ? 'Restore' : 'Archive')."</button>".
                            "</form>";
        
        if ($isCancellable){
            $actionButtons =   $actionButtons."<form action='cancel_reservation.php' method='post'>".
                                    "<input type='text' style='display:none;' name='reservation-id' value='$reservationId'>".
                                    "<button type='submit'>Cancel</button>".
                                "</form>";
        }


        echo    "<tr>".
                    "<td>$reservationId</td>".
                    "<td>".$reservation["student_name"]."</td>".
                    "<td>".$reservation["room_name"]."</td>".
                    "<td>".$reservation["faculty_name"]."</td>".
                    "<td>".$reservation["time_start"]." -> ".$reservation["time_end"]."</td>".
                    "<td>".$reservation["request_date"]."</td>".
                    "<td>$status</td>".
                    "<td>$actionButtons</td>".
                "</tr>";
    }
}
else {
    echo "<tr><td class='no-reservations' colspan='8'>No reservations found.</td></tr>";
}

$conn -> close();
?>