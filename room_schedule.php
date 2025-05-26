<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || !isset($_SESSION['room_name'])) {
    header("Location: index.html");
    exit();
}
require 'db_connect.php';


$stmt = $conn->prepare("SELECT * FROM academic_schedule WHERE room_name = ?");
$stmt->bind_param("s", $_SESSION['room_name']);
$stmt->execute();
$schedDataResult = $stmt->get_result();

$schedule = [];
if ($schedDataResult->num_rows > 0) {
    while ($schedData = $schedDataResult->fetch_assoc()) {
        $day = null;
        switch($schedData['day']){
            case 'Monday':
                $day = 0;
                break;
            case 'Tuesday':
                $day = 1;
                break;
            case 'Wednesday':
                $day = 2;
                break;
            case 'Thursday':
                $day = 3;
                break;
            case 'Friday':
                $day = 4;
                break;
            case 'Saturday':
                $day = 5;
                break;
            case 'Sunday':
                $day = 6;
                break;
            default:
                $day = null;
                break;
        }


        $schedule[] = ["day" => $day, "time_start" => $schedData['time_start'], "time_end" => $schedData['time_end']];
    }
}

echo json_encode($schedule);

$conn->close();
?>