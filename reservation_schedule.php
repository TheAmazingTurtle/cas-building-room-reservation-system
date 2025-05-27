<?php 
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || !isset($_SESSION['room_name'])) {
    header("Location: index.html");
    exit();
}

require 'db_connect.php';

$schedule = [];

$stmt = $conn->prepare("SELECT DISTINCT * FROM student_reservation INNER JOIN student USING(student_number) WHERE room_name = ? AND NOT (is_faculty_approved = 0 OR is_admin_approved = 0 OR is_active = 0)");
$stmt->bind_param("s", $_SESSION['room_name']);
$stmt->execute();
$schedDataResult = $stmt->get_result();

if ($schedDataResult->num_rows > 0) {
    while ($schedData = $schedDataResult->fetch_assoc()) {
        $schedule[] = [
            "requestee" => $schedData['student_name'],
            "role" => "student",
            "resId" => $schedData['student_reservation_id'],
            "start" => $schedData['time_start'],
            "end" => $schedData['time_end'],
            "isFacultyApproved" => $schedData['is_faculty_approved'],
            "isAdminApproved" => $schedData['is_admin_approved']
        ];
    }
}

$stmt = $conn->prepare("SELECT DISTINCT * FROM faculty_reservation INNER JOIN faculty USING(faculty_id) WHERE room_name = ? AND NOT (is_admin_approved = 0 OR is_active = 0)");
$stmt->bind_param("s", $_SESSION['room_name']);
$stmt->execute();
$schedDataResult = $stmt->get_result();

if ($schedDataResult->num_rows > 0) {
    while ($schedData = $schedDataResult->fetch_assoc()) {
        $schedule[] = [
            "requestee" => $schedData['faculty_name'],
            "role" => "faculty",
            "resId" => $schedData['faculty_reservation_id'],
            "start" => $schedData['time_start'],
            "end" => $schedData['time_end'],
            "isFacultyApproved" => 1,
            "isAdminApproved" => $schedData['is_admin_approved']
        ];
    }
}

echo json_encode($schedule);


?>