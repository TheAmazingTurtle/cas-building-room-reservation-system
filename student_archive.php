<?php
session_start();
if (!isset($_SESSION['user_id'])  || !isset($_SESSION['user_role']) ||  $_SESSION['user_role'] != 'student' ) {
    header("Location: index.html");
    exit();
}

require 'db_connector.php';

$_SESSION['current_page'] = "archive";
$user_id = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Archive</title>
    <link rel="stylesheet" type="text/css" href="archiveStyle.css">
</head>
<body id="student-archive">
    <header>
        <h3>CAS Building Room Reservation System</h3>
        <nav>
            <a href="student_dashboard.php">Dashboard</a>
            <a href="student_request.php">Request</a>
            <a href="student_archive.php">Archive</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <div class="student-reservations">
            <h2>Archived Reservations</h2>
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