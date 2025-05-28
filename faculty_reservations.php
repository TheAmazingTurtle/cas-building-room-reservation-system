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

$stmt = $conn->prepare("SELECT faculty_reservation_id, room_name, time_start, time_end, request_date, is_active, is_admin_approved
                        FROM faculty_reservation
                        WHERE faculty_id = ?$restriction");

$stmt->bind_param("s", $user_id);
$stmt->execute();
$facultyReservationResult = $stmt->get_result();


if ($facultyReservationResult -> num_rows > 0){

    $now = new DateTime();
    while ($reservation = $facultyReservationResult -> fetch_assoc()){
        $reservationId = $reservation['faculty_reservation_id'];

        $status = null;
        $end = new DateTime($reservation['time_end']);


        $isCancellable = FALSE;
        if (!$reservation["is_active"]){
            $status = "Cancelled";
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

        $actionButtons = "";


        switch ($_SESSION['current_page']){
            case 'dashboard':
                $actionButtons =    $actionButtons."<form action='archive_reservation.php' method='post'>".
                                        "<input type='text' style='display:none;' name='reservation-id' value='$reservationId'>".
                                        "<input type='text' style='display:none;' name='action-mode' value='1'>".
                                        "<button type='submit'>Archive</button>".
                                    "</form>";
                break;
            case 'archive':
                $actionButtons =    $actionButtons."<form action='archive_reservation.php' method='post'>".
                                        "<input type='text' style='display:none;' name='reservation-id' value='$reservationId'>".
                                        "<input type='text' style='display:none;' name='action-mode' value='0'>".
                                        "<button type='submit'>Restore</button>".
                                    "</form>";
                break;
            default:
                break;
        }
        
        if ($isCancellable){
            $actionButtons =   $actionButtons."<form action='cancel_reservation.php' method='post'>".
                                    "<input type='text' style='display:none;' name='reservation-id' value='$reservationId'>".
                                    "<button type='submit'>Cancel</button>".
                                "</form>";
        }


        $timeStart =  formatDateTime($reservation["time_start"]);
        $requestDate = formatDate($reservation["request_date"]);

        echo    "<div class='scroll-item' onclick=\"window.location.href='reservation.php?res_id={$reservation['faculty_reservation_id']}&action=more_details'\">".
                    "<p>$reservationId</p>".
                    "<p>".$reservation["room_name"]."</p>".
                    "<p>$timeStart</p>".
                    "<p>$requestDate</p>".
                    "<p>$status</p>".
                    "<div>$actionButtons</div>".
                "</div>"; 
    }
}
else {
    echo    "<div class='no-item-prompt'>".
                "<p>Table Cleared</p>".
            "</div>";
}

$conn -> close();

?>