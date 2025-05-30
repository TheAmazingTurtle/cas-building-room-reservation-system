<?php 
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || !isset($_SESSION['room_name'])) {
    header("Location: index.html");
    exit();
}

require 'db_connector.php';

$schedule = [];

$stmt = $conn->prepare("SELECT * FROM student_reservation INNER JOIN student USING(student_number) WHERE room_name = ? AND (is_faculty_approved IS NULL OR is_faculty_approved = 1) AND (is_admin_approved IS NULL OR is_admin_approved = 1) AND is_active = 1;");
$stmt->bind_param("s", $_SESSION['room_name']);
$stmt->execute();
$schedDataResult = $stmt->get_result();

if ($schedDataResult->num_rows > 0) {

    while ($schedData = $schedDataResult->fetch_assoc()) {

        $end = new DateTime($schedData['time_end']);
        $now = new DateTime();

        $status = null;
        if (!$schedData["is_active"]) {
            $status = "Cancelled";
        } elseif (is_null($schedData["is_faculty_approved"])) {
            $status = "Awaiting FIC Approval";
            $isCancellable = TRUE;
        } elseif (!$schedData["is_faculty_approved"]) {
            $status = "Denied by FIC";
        } elseif (is_null($schedData["is_admin_approved"])) {
            $status = "Awaiting Admin Approval";
            $isCancellable = TRUE;
        } elseif (!$schedData["is_admin_approved"]) {
            $status = "Denied by Admin";
        } elseif ($end >= $now) {
            $status = "Approved";
            $isCancellable = TRUE;
        } else {
            $status = "Completed";
        }

        $schedule[] = [
            "requestee" => $schedData['student_name'],
            "role" => "student",
            "resId" => $schedData['student_reservation_id'],
            "requestDate" => $schedData['request_date'],
            "start" => $schedData['time_start'],
            "end" => $schedData['time_end'],
            "status" => $status
        ];
    }
}

$stmt = $conn->prepare("SELECT DISTINCT * FROM faculty_reservation INNER JOIN faculty USING(faculty_id) WHERE room_name = ?  AND (is_admin_approved IS NULL OR is_admin_approved = 1) AND is_active = 1");
$stmt->bind_param("s", $_SESSION['room_name']);
$stmt->execute();
$schedDataResult = $stmt->get_result();

if ($schedDataResult->num_rows > 0) {
    while ($schedData = $schedDataResult->fetch_assoc()) {
        $status = null;
        $end = new DateTime($schedData['time_end']);
        $now = new DateTime();

        $isCancellable = FALSE;
        if (!$schedData["is_active"]) {
            $status = "Cancelled";
        } elseif (is_null($schedData["is_admin_approved"])) {
            $status = "Awaiting Admin Approval";
        } elseif (!$schedData["is_admin_approved"]) {
            $status = "Denied by Admin";
        } elseif ($end >= $now) {
            $status = "Approved";
        } else {
            $status = "Completed";
        }

        $schedule[] = [
            "requestee" => $schedData['faculty_name'],
            "role" => "faculty",
            "resId" => $schedData['faculty_reservation_id'],
            "requestDate" => $schedData['request_date'],
            "start" => $schedData['time_start'],
            "end" => $schedData['time_end'],
            "status" => $status
        ];
    }
}

echo json_encode($schedule);


?>