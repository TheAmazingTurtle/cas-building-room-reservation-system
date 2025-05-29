<?php
session_start();
require 'db_connector.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header("Location: index.html");
    exit();
}

$reservationId = $_POST['reservation-id'] ?? null;

if (!$reservationId){
    echo "No reservation ID provided.";
    exit();
}


$isFromStudent = false;
$isFacultyApproved = null;
$roleApproved = null; 

$stmt = $conn->prepare("SELECT student_number, is_faculty_approved 
                        FROM student_reservation 
                        WHERE student_reservation_id = ?");
$stmt->bind_param("s", $reservationId);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0){
    $reservation = $result->fetch_assoc();
    $isFromStudent = true;
    $isFacultyApproved = $reservation['is_faculty_approved'];
} else {
    $stmt = $conn->prepare("SELECT faculty_reservation_id
                            FROM faculty_reservation 
                            WHERE faculty_reservation_id = ?");
    $stmt->bind_param("s", $reservationId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0){
        echo"Reservation not found.";
        exit();
    }

    $isFromStudent = false;
}


switch ($_SESSION['user_role']){
    case 'faculty':
        if (!$isFromStudent){
            exit();
        }

        $stmt = $conn-> prepare("UPDATE student_reservation
                                 SET is_faculty_approved = 1, faculty_id = ?
                                 WHERE student_reservation_id = ?");
        $stmt->bind_param("ss", $_SESSION['user_id'], $reservationId);
        $roleApproved = 'student';
        break;

    case 'admin':
        if($isFromStudent){ 
            if ($isFacultyApproved != 1){
                exit();
            }
            
            $stmt = $conn-> prepare("UPDATE student_reservation
                                 SET is_admin_approved = 1, admin_id = ?
                                 WHERE student_reservation_id = ?");
            $roleApproved = 'student';
        } else {
            $stmt = $conn->prepare("UPDATE faculty_reservation 
                            SET is_admin_approved = 1, admin_id = ?
                            WHERE faculty_reservation_id = ?");
            $roleApproved = 'faculty';

        }
        $stmt->bind_param("ss",$_SESSION['user_id'], $reservationId);
        break;
    
    default:
        echo"Unauthorized user. Bye bye.";
        exit();
}

if ($stmt->execute()) {
    header("Location: reservation.php?res_id=$reservationId&action=approve_{$roleApproved}");
    exit();

} else {
    echo "Failed to approve reservation.";
}

$conn->close();
?>
