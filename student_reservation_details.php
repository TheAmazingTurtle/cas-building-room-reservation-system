<?php
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header("Location: index.html");
    exit();
}

require 'db_connector.php';

$stmt = $conn->prepare("SELECT *
                        FROM student_reservation 
                        INNER JOIN student USING(student_number) 
                        INNER JOIN faculty USING(faculty_id)
                        LEFT JOIN admin USING(admin_id) 
                        WHERE student_reservation_id = ?");
$stmt->bind_param("s", $reservationId);
$stmt->execute();
$reservationResult = $stmt->get_result();

if ($reservationResult->num_rows > 0) {
    $isEditable = true;
    if ($_SESSION['current_page'] === 'request'){
        $isEditable = false;
    }

    $reservation = $reservationResult->fetch_assoc();
    $status = null;
    $end = new DateTime($reservation['time_end']);
    $now = new DateTime();

    $isCancellable = FALSE;
    if (!$reservation["is_active"]) {
        $status = "Cancelled";
    } elseif (is_null($reservation["is_faculty_approved"])) {
        $status = "Awaiting FIC Approval";
        $isCancellable = TRUE;
    } elseif (!$reservation["is_faculty_approved"]) {
        $status = "Denied by FIC";
    } elseif (is_null($reservation["is_admin_approved"])) {
        $status = "Awaiting Admin Approval";
        $isCancellable = TRUE;
    } elseif (!$reservation["is_admin_approved"]) {
        $status = "Denied by Admin";
    } elseif ($end >= $now) {
        $status = "Approved";
        $isCancellable = TRUE;
    } else {
        $status = "Completed";
    }

    $dateTime = new DateTime($reservation['time_start']);
    $timeStart =  $dateTime -> format('F j, Y \a\t g:i A');

    $dateTime = new DateTime($reservation['time_end']);
    $timeEnd =  $dateTime -> format('F j, Y \a\t g:i A');

    $date = new DateTime($reservation['request_date']);
    $requestDate = $date->format('F j, Y');

    echo    "<p><strong>Reservation ID:</strong>".($reservation['student_reservation_id'])."</p>".
            "<p><strong>Requestee:</strong>".($reservation['student_name'])."</p>".
            "<p><strong>Room Name:</strong>".($reservation['room_name'])."</p>".
            "<p><strong>Head Count</strong>{$reservation['head_count']}</p>".
            "<p><strong>Faculty-in-Charge:</strong>".($reservation['faculty_name'])."</p>".
            "<p><strong>Faculty Remark:</strong>".($reservation['faculty_remark'] ?? 'N/A')."</p>".
            "<p><strong>Start Time:</strong>".($timeStart)."</p>".
            "<p><strong>End Time:</strong>".($timeEnd)."</p>".
            "<p><strong>Request Date:</strong>".($requestDate)."</p>".
            "<p><strong>Purpose:</strong>".$reservation['purpose']."</p>".
            "<p><strong>Approved by (Admin):</strong>".($reservation['admin_name'] ?? 'N/A')."</p>".
            "<p><strong>Admin Remark:</strong>".($reservation['admin_remark'] ?? 'N/A')."</p>".
            "<p><strong>Status:</strong>".$status."</p>";        
    
    if (!$isEditable) {
        exit();
    }

    echo "<div class='button-container'>";

    switch ($action) {
        case 'more_details':
            if ($isCancellable) {
                echo "<form action='cancel_reservation.php' method='post'>
                        <input type='hidden' name='reservation-id' value='$reservationId'>
                        <button type='submit'>Cancel</button>
                    </form>";
            }

            echo    "<form action='archive_reservation.php' method='post'>".
                        "<input type='text' style='display:none;' name='reservation-id' value='$reservationId'>".
                        "<input type='text' style='display:none;' name='action-mode' value='". (!$reservation['is_archived']) ."'>".
                        "<button type='submit'>".($reservation['is_archived'] ? 'Restore' : 'Archive')."</button>".
                    "</form>";
            break;
        case 'approve':
            echo    "<form action='approve_reservation.php' method='POST'>".
                        "<input class='hidden' name='reservation-id' value=$reservationId>".
                        "<label for='remarks'>Remarks:</label><br>".
                        "<textarea name='remarks' rows='4' cols='30' required style='resize: none;' placeholder='Put your remarks here...'></textarea><br>".
                        "<button type='submit' name='is-approved' value='1'>Approve</button>".
                        "<button type='submit' name='is-approved' value='0' >Deny</button>".
                    "</form>";
            break;
        default:
            echo "<p>Invalid user role.</p>";
            break;
    }

    echo "</div>";

} else {
    echo "<h4>No reservation found with Student Reservation ID: " . htmlspecialchars($reservationId) . "</h4>";
    exit();
}

$conn->close();

function insertStudentExclusiveActions($isCancellable, $reservationId){
    
}

function insertFacultyExclusiveActions($reservationId){
    
}

?>