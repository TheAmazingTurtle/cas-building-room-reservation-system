<?php
session_start();
if (!isset($_SESSION['user_id'])  || !isset($_SESSION['user_role']) ||  $_SESSION['user_role'] != 'student' ) {
    header("Location: index.html");
    exit();
}

require 'db_connector.php';

$_SESSION['current_page'] = "dashboard";
$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT * FROM student WHERE student_number = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$studentData = $result->fetch_assoc();

if (!$studentData) {
    echo "Error: Student not found.";
    exit();
}

$studentNumber = $studentData['student_number'];
$name = $studentData['student_name'];
$degreeProgram = $studentData['degree_program'];
$yearLevel = $studentData['year_level'];
$college = $studentData['college'];

$_SESSION['name'] = $name;

$conn -> close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" type="text/css" href="dashboardStyle.css">
</head>
<body>
    <header>
        <h3>CAS Building Room Reservation System</h3>
        <nav>
            <a href="student_request.php">Request</a>
            <a href="student_archive.php">Archive</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <div class="user-info">
            <img src="profile.jpg" alt="Profile Picture" class="profile-pic">
            <div>
                <p>Student Number: <?php echo $studentNumber; ?></p>
                <p>Name: <?php echo $name; ?></p>
                <p>Degree Program: <?php echo $degreeProgram; ?></p>
                <p>Year Level: <?php echo $yearLevel; ?></p>
            </div>
        </div>
        <div class="student-reservations">
            <h2>Upcoming Reservations</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Requestee</th>
                        <th>Room Name</th>
                        <th>Faculty-in-Charge</th>
                        <th>Schedule</th>
                        <th>Date Requested</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        include 'student_reservations.php';
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <p>2023 Student Dashboard. All rights reserved.</p>
    </footer>
</body> 
</html>