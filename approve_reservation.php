<?php
session_start();
require 'db_connector.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header("Location: index.html");
    exit();
}

$role = $_SESSION['user_role'];

$reservationId = $_POST['reservation-id'] ?? null;
$remarks = $_POST['remarks'] ?? null;
$isApproved = $_POST['is-approved'] ?? null;


if (!$reservationId){
    echo "No reservation ID provided.";
    exit();
}


$stmt = $conn->prepare("SELECT 'student' AS source FROM student_reservation WHERE student_reservation_id = ?
                        UNION
                        SELECT 'faculty' AS source FROM faculty_reservation WHERE faculty_reservation_id = ?
                        LIMIT 1");

$stmt->bind_param("ss", $reservationId, $reservationId);
$stmt->execute();
$result = $stmt->get_result();

$requestSource = null;
if ($row = $result->fetch_assoc()) {
    $requestSource = $row['source']; // 'student' or 'faculty'
}

try {
    switch ($requestSource){
        case 'student':
            $stmt = $conn-> prepare("UPDATE student_reservation
                                    SET is_{$role}_approved = ?, {$role}_id = ?, {$role}_remark = ?
                                    WHERE student_reservation_id = ?");
            $stmt->bind_param("isss", $isApproved, $_SESSION['user_id'], $remarks, $reservationId);
            break;
        case 'faculty':
            $stmt = $conn-> prepare("UPDATE faculty_reservation
                                    SET is_{$role}_approved = ?, {$role}_id = ?, {$role}_remark = ?
                                    WHERE faculty_reservation_id = ?");
            $stmt->bind_param("isss", $isApproved, $_SESSION['user_id'], $remarks, $reservationId);
            break;
        
        default:
            echo"Unauthorized source. Bye bye.";
            exit();
    }

    $stmt->execute();
    header("Location: {$_SESSION['user_role']}_dashboard.php");
    exit();
}
catch (Exception $e) {
    $conn -> rollback();
    echo "Error: " . $e->getMessage();
    exit();
}

$conn->close();
?>
