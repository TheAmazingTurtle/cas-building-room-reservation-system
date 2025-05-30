<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header("Location: index.html");
    exit();
}

unset($_SESSION['room_data']);
$_SESSION['current_page'] = "request";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Request</title>
    <link rel="stylesheet" type="text/css" href="requestStyle.css">
</head>
<body id="student-request">
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
        
        <div class='request-search-body'>
            <div>
                <search>
                    <form>
                        <input class="request-search-bar" placeholder="Search a Room">
                        <button type="submit">Search</button>
                    </form>
                </search>
                <div class="request-room-option-container">
                     <div class="header-category">
                        <h1 class="room-name">Room Number</h1>
                        <h1 class="room-type">Room Type</h1>
                        <h1 class="room-floor">Floor Number</h1>
                        <h1 class="room-capacity">Capacity</h1>
                    </div>
                    <?php
                        include 'room_options.php';
                    ?>
                    <div class="no-room-option hidden">
                        <h4>No Rooms Available</h4>
                    </div>
                </div>
            </div>
            <div class="request-filter-container">
                <h2>Filter</h2>
                <div class="request-filter-categories">
                    <div>
                        <label for="room-type">Type:</label>
                        <?php
                            include 'room_type_options.php';
                        ?>
                    </div>
                    <div>
                        <label>Building Floor:</label>
                        <?php
                            include 'room_floor_options.php';
                        ?>
                    </div>
                    <div>
                        <label>Capacity:</label>
                        <input type='number' class='capacity-input' placeholder="Enter Head Count">
                    </div>
                </div>
                <button onclick="filterRoomOptions()">Filter</button>
            </div>
        </div>
    </main>

    <footer>
        <p>2023 Student Dashboard. All rights reserved.</p>
    </footer>

    <script src="request_script.js"></script>
</body> 
</html>