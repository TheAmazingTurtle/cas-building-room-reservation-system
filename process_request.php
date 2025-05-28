<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header("Location: index.html");
    exit();
}

require 'db_connector.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Error: Invalid request method.";
    exit();
}

try {
    $conn -> begin_transaction();

    switch($_SESSION['user_role']){
        case 'student':
            $studentNumber = $_POST['student-number'] ?? '';
            $studentName = $_POST['student-name'] ?? '';
            $roomName = $_POST['room-name'] ?? '';
            $facultyName = $_POST['faculty-name'] ?? '';
            $reservationStart = $_POST['reservation-start'] ?? '';
            $reservationEnd = $_POST['reservation-end'] ?? '';
            $purpose = $_POST['purpose'] ?? '';

            $reservationId = generateUniqueReservationId("S");
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

            break;
        case 'faculty':
            $facultyId = $_POST['faculty-id'] ?? '';
            $facultyName = $_POST['faculty-name'] ?? '';
            $roomName = $_POST['room-name'] ?? '';
            $reservationStart = $_POST['reservation-start'] ?? '';
            $reservationEnd = $_POST['reservation-end'] ?? '';
            $purpose = $_POST['purpose'] ?? '';

            $reservationId = generateUniqueReservationId("F");

            $stmt = $conn->prepare("INSERT INTO faculty_reservation (faculty_reservation_id, faculty_id, room_name, purpose, request_date, is_active, is_archived, time_start, time_end, admin_id, is_admin_approved, admin_remark) VALUES (?, ?, ?, ?, CURDATE(), 1, 0, ?, ?, null, null, null)");
            $stmt->bind_param("ssssss", $reservationId, $facultyId, $roomName, $purpose, $reservationStart, $reservationEnd);
            $stmt->execute();

            break;
    }

    

    $conn -> commit();
    header("Location: {$_SESSION['user_role']}_dashboard.php");
    exit();
}
catch (Exception $e) {
    $conn -> rollback();
    echo "Error: " . $e->getMessage();
    exit();
}

function generateUniqueReservationId($key) {
    global $conn;

    while (true) {
        $randomId = "RES-$key-" . random_int(100000, 999999);

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