<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header("Location: index.html");
    exit();
}

$_SESSION['current_page'] = "room-details";

require 'db_connector.php';

$roomName = $_GET['room_name'] ?? null;

$stmt = $conn->prepare("SELECT * FROM room WHERE room_name = ?");
$stmt->bind_param("s", $roomName);
$stmt->execute();
$result = $stmt->get_result();
$roomData = $result->fetch_assoc();

if (!$roomData) {
    echo "Error: room not found.";
    exit();
}

$conn->close();

$requestFormLink = null;
switch($_SESSION['user_role']){
    case 'student':
        $requestFormLink = 'student_request_form.php';
        break;
    case 'faculty':
        $requestFormLink = 'faculty_request_form.php';
        break;
}


$roomName = $roomData['room_name'];
$roomType = $roomData['room_type'];
$roomFloor = $roomData['floor_number'];
$roomCapacity = $roomData['capacity'];

$_SESSION['room_name'] = $roomName;
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body id="room-details">
    <header>
        <h3>CAS Building Room Reservation System</h3>
    </header>
    <main>
        <a href="javascript:history.back()">Back</a>
        <table class="room-information-container">
            <tr>
                <th colspan=2>Room Information</th>
            </tr>
            <tr>
                <td><strong>Room Name:</strong><?php echo $roomName ?></td>
                <td><strong>Room Type:</strong><?php echo $roomFloor ?></td>
            </tr>
            <tr>
                <td><strong>Floor Number:</strong><?php echo $roomFloor ?></td>
                <td><strong>Capacity:</strong><?php echo $roomCapacity ?></td>
            </tr>
            <tr>
                <td colspan=2><a href='<?php echo $requestFormLink; ?>'><button>Request Room</button></a></td>
            </tr>
        </table>

        <table class="room-asset-container">
            <tr>
                <th colspan=2>Assets</th>
            </tr>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
            </tr>
            <?php
                include 'room_assets.php';
            ?>
        </table>

        <table class="class-schedule-container">
            <tr>
                <th colspan=8>Class Schedule</th>
            </tr>
            <tr>
                <th>Time</th>
                <th>Monday</th>
                <th>Tuesday</th>
                <th>Wednesday</th>
                <th>Thursday</th>
                <th>Friday</th>
                <th>Saturday</th>
                <th>Sunday</th>
            </tr>
            <?php
                include 'class_schedule.php';            
            ?>
        </table>
    </main>
    <footer>

    </footer>
</body>
</html>