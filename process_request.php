<?php

require 'db_connector.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Error: Invalid request method.";
    exit();
}

$studentNumber = $_POST['student-number'] ?? '';
$studentName = $_POST['student-name'] ?? '';
$roomName = $_POST['room-name'] ?? '';
$facultyName = $_POST['faculty-name'] ?? '';
$reservationStart = $_POST['reservation-start'] ?? '';
$reservationEnd = $_POST['reservation-end'] ?? '';
$purpose = $_POST['purpose'] ?? '';

try {
    $conn -> begin_transaction();

    $reservationId = generateUniqueReservationId();

    $stmt = $conn->prepare("SELECT faculty_id FROM faculty WHERE faculty_name = ?");
    $stmt->bind_param("s", $facultyName);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) {
        throw new Exception("Faculty not found: $facultyName");
    }
    $stmt->bind_result($facultyId);
    $stmt->fetch();

    $stmt = $conn->prepare("INSERT INTO student_reservation (student_reservation_id, student_number, room_name, purpose, request_date, time_start, time_end, is_active, is_archived, faculty_id, is_faculty_approved, faculty_remark, admin_id, is_admin_approved, admin_remark) VALUES (?, ?, ?, ?, CURDATE(), ?, ?, 1, 0, ?, null, null, null, null, null)");
    $stmt->bind_param("sssssss", $reservationId, $studentNumber, $roomName, $purpose, $reservationStart, $reservationEnd, $facultyId);
    $stmt->execute();

    $conn -> commit();
    header("Location: student_dashboard.php");
    exit();
}
catch (Exception $e) {
    $conn -> rollback();
    echo "Error: " . $e->getMessage();
    exit();
}

function generateUniqueReservationId() {
    global $conn;

    while (true) {
        $randomId = 'RES-S-' . random_int(100000, 999999);

        $stmt = $conn->prepare("SELECT student_reservation_id FROM student_reservation WHERE student_reservation_id = ?");
        $stmt->bind_param("s", $randomId);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0){
            return $randomId;
        }
    }
}

?>