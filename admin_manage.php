<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) ||  $_SESSION['user_role'] != 'admin' ) {
    header("Location: index.html");
    exit();
}

require 'db_connector.php';
$_SESSION['current_page'] = "manage";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_manage.css">
</head>
<body>
    <header>
        <h1>CAS Building Room Reservation System</h1>
        <nav>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="adminHistory.php">Check History</a> 
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h2>Manage:</h2>
        <div id="admin-manage-icon-container" onclick="window.location.href='admin_manage_user.php'">
            <div class="manage-icon">
                <img src="user.png" alt="User Icon">
                <h3>Users</h3>
            </div>
            <div class="manage-icon" onclick="window.location.href='admin_manage_room.php'">
                <img src="room.png" alt="Room Icon">
                <h3>Rooms</h3>
            </div>
            <div class="manage-icon" onclick="window.location.href='admin_manage_reservation.php'">
                <img src="reserve.png" alt="Reservation Icon">
                <h3>Reservation</h3>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2023 Admin Dashboard. All rights reserved.</p>
    </footer>
</body> 
</html>