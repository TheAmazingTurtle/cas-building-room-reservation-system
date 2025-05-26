<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header("Location: index.html");
    exit();
}

$_SESSION['current_page'] = "reservation-details";


// Handle POST submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'db_connect.php';

    $roomName = $_POST['room-name'];

    $stmt = $conn->prepare("SELECT * FROM room WHERE room_name = ?");
    $stmt->bind_param("s", $roomName);
    $stmt->execute();
    $result = $stmt->get_result();
    $roomData = $result->fetch_assoc();

    if (!$roomData) {
        echo "Error: room not found.";
        exit();
    }

    $_SESSION['room_data'] = $roomData;

    $conn->close();

    // Redirect after storing session data
    header("Location: room_details.php");
    exit();
}

$roomData = $_SESSION['room_data'] ?? null;

if (!$roomData) {
    echo "No room selected.";
    exit();
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
                <th colspan=4>Room Information</th>
            </tr>
            <tr>
                <td>Room Name:</td>
                <td><?php echo $roomName ?></td>
                <td>Room Type:</td>
                <td><?php echo $roomFloor ?></td>
            </tr>
            <tr>
                <td>Floor Number:</td>
                <td><?php echo $roomFloor ?></td>
                <td>Capacity:</td>
                <td><?php echo $roomCapacity ?></td>
            </tr>
            <tr>
                <td colspan=4><a href='student_request_form.php'><button>Request Room</button></a></td>
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