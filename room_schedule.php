<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || !isset($_SESSION['room_name'])) {
    header("Location: index.html");
    exit();
}
require 'db_connector.php';


$stmt = $conn->prepare("SELECT * FROM academic_schedule INNER JOIN faculty USING(faculty_id) WHERE room_name = ?");
$stmt->bind_param("s", $_SESSION['room_name']);
$stmt->execute();
$schedDataResult = $stmt->get_result();

$schedule = [[], [], [], [], [], [], []]; // Initialize schedule for each day of the week
if ($schedDataResult->num_rows > 0) {
    while ($schedData = $schedDataResult->fetch_assoc()) {
        $day = null;
        switch($schedData['day']){
            case 'Sunday':
                $day = 0;
                break;
            case 'Monday':
                $day = 1;
                break;
            case 'Tuesday':
                $day = 2;
                break;
            case 'Wednesday':
                $day = 3;
                break;
            case 'Thursday':
                $day = 4;
                break;
            case 'Friday':
                $day = 5;
                break;
            case 'Saturday':
                $day = 6;
                break;
            default:
                $day = null;
                break;
        }


        $schedule[$day][] = ["subject" => $schedData['subject'],"faculty" => $schedData['faculty_name'], "time_start" => $schedData['time_start'], "time_end" => $schedData['time_end']];
    }
}

echo json_encode($schedule);

$conn->close();
?>