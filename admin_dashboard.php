<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) ||  $_SESSION['user_role'] != 'admin' ) {
    header("Location: index.html");
    exit();
}

require 'db_connector.php';
$_SESSION['current_page'] = "dashboard";
$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT * FROM admin WHERE admin_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$facultyData = $result->fetch_assoc();

if (!$facultyData) {
    echo "Error: Admin not found.";
    exit();
}

$adminId = $facultyData['admin_id'];
$name = $facultyData['admin_name'];
$designation = $facultyData['designation'];

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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashstyle.css">
</head>
<body>
    <header>
        <h1>CAS Building Room Reservation System</h1>
        <nav>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_manage.php">Manage</a>
            <a href="admin_archive.php">Archive</a> 
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <div class="user-info">
            <img src="profile.jpg" alt="Profile Picture" class="profile-pic">
            <div>
                <p>Admin ID: <?php echo $adminId; ?></p>
                <p>Name: <?php echo $name; ?></p>
                <p>Designation: <?php echo $designation; ?></p>
            </div>
        </div>
        <div class="admin-container">
            <div id="admin-approval-requests">
                <h2>Approval Requests</h2>
                <div id="admin-request-container">
                    <div id="admin-faculty-request">
                        <h3>Faculty</h3>
                        <div class="admin-header">
                            <h4>Reservation ID</h4>
                            <h4>Requestee</h4>
                            <h4>Room Name</h4>
                            <h4>Schedule Start</h4>
                            <h4>Date Requested</h4>
                        </div>
                        <div class="admin-scrollable">
                            <?php
                                include 'admin_approval_faculty_requests.php';
                            ?>
                        </div>
                    </div>
                    <div id="admin-student-request">
                        <h3>Student</h3>
                        <div class="admin-header">
                            <h4>Reservation ID</h4>
                            <h4>Requestee</h4>
                            <h4>Faculty-in-Charge</h4>
                            <h4>Room Name</h4>
                            <h4>Schedule Start</h4>
                            <h4>Date Requested</h4>
                        </div>
                        <div class="admin-scrollable">
                            <?php
                                include 'admin_approval_student_requests.php';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2023 Admin Dashboard. All rights reserved.</p>
    </footer>

    <script src="script.js"></script>
</body> 
</html>