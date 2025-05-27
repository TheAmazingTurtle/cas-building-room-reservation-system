<?php

if (__FILE__ === realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('This file cannot be accessed directly.');
}

require 'db_connect.php';

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

$stmt = $conn->prepare("SELECT student_reservation_id, student_name, room_name, faculty_name, time_start, time_end, request_date, is_active, is_faculty_approved, is_admin_approved 
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
            $status = "Awaiting FIC Approval";
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

        $actionButtons =    "<a href='reservation.php?res_id=". $reservation["student_reservation_id"] ."'><button>More Details</button></a>";


        switch ($_SESSION['current_page']){
            case 'dashboard':
                $actionButtons =    $actionButtons."<form action='archive_reservation.php' method='post'>".
                                        "<input type='text' style='display:none;' name='reservation-id' value='".$reservation["student_reservation_id"]."'>".
                                        "<input type='text' style='display:none;' name='action-mode' value='1'>".
                                        "<button type='submit'>Archive</button>".
                                    "</form>";
                break;
            case 'archive':
                $actionButtons =    $actionButtons."<form action='archive_reservation.php' method='post'>".
                                        "<input type='text' style='display:none;' name='reservation-id' value='".$reservation["student_reservation_id"]."'>".
                                        "<input type='text' style='display:none;' name='action-mode' value='0'>".
                                        "<button type='submit'>Restore</button>".
                                    "</form>";
                break;
            default:
                break;
        }
        
        if ($isCancellable){
            $actionButtons =   $actionButtons."<form action='cancel_reservation.php' method='post'>".
                                    "<input type='text' style='display:none;' name='reservation-id' value='".$reservation["student_reservation_id"]."'>".
                                    "<button type='submit'>Cancel</button>".
                                "</form>";
        }


        echo    "<tr>".
                    "<td>".$reservation["student_reservation_id"]."</td>".
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
    echo '<tr><td colspan="8">No reservations found.</td></tr>';
}

$conn -> close();
?>