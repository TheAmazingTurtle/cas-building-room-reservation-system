<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) ||  $_SESSION['user_role'] != 'faculty' ) {
    header("Location: index.html");
    exit();
}

require 'db_connector.php';
$_SESSION['current_page'] = "dashboard";
$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT * FROM faculty WHERE faculty_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$facultyData = $result->fetch_assoc();

if (!$facultyData) {
    echo "Error: Faculty not found.";
    exit();
}

$facultyId = $facultyData['faculty_id'];
$name = $facultyData['faculty_name'];
$division = $facultyData['division'];

$_SESSION['name'] = $name;

$conn -> close();

function formatDate($dateTime){
    return (new DateTime($dateTime)) -> format('F j, Y');
}

function formatDateTime($dateTime){
    return (new DateTime($dateTime)) -> format('F j, Y \<\b\r\> g:i A');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" type="text/css" href="faculty_dashstyle.css">
</head>
<body> 
    <header>
        <h3>CAS Building Room Reservation System</h3>
        <nav>
            <a href="faculty_dashboard.php">Dashboard</a>
            <a href="faculty_request.php">Request</a>
            <a href="faculty_archive.php">Archive</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <div class="user-info">
            <img src="profile.jpg" alt="Profile Picture" class="profile-pic">
            <div>
                <p>Faculty ID: <?php echo $facultyId; ?></p>
                <p>Name: <?php echo $name; ?></p>
                <p>Division: <?php echo $division; ?></p>
            </div>
        </div>
        <div class="main-content"> 
            <div class="faculty-container">
                <div class="faculty-reservations">
                    <h2>Upcoming Reservations</h2>
                    <div class="faculty-header">
                        <h3>Reservation ID</h3>
                        <h3>Room Name</h3>
                        <h3>Schedule Start</h3>
                        <h3>Date Requested</h3>
                        <h3>Status</h3>
                        <h3>Action</h3>
                    </div>
                    <div class="faculty-scrollable">
                        <?php
                            include 'faculty_reservations.php';
                        ?>
                    </div>
                </div>

                <div class="faculty-approval-requests">
                    <h2>Approval Requests</h2>
                    <div class="faculty-header">
                        <h3>Reservation ID</h3>
                        <h3>Requestee</h3>
                        <h3>Room Name</h3>
                        <h3>Schedule Start</h3>
                        <h3>Date Requested</h3>
                    </div>
                    <div class="faculty-scrollable">
                        <?php
                            include 'faculty_approval_request.php';
                        ?>
                    </div>
                </div>
            </div>
        </div> 
    </main>

    <footer>
        <p>2023 Student Dashboard. All rights reserved.</p>
    </footer>
</body> 
</html>