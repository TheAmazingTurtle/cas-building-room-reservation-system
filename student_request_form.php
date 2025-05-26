<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header("Location: index.html");
    exit();
}

require 'db_connect.php';

$userId = $_SESSION['user_id'];
$userName = $_SESSION['name'] ?? '';
$roomName = $_SESSION['room_name'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Request</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header>
        <h3>CAS Building Room Reservation System</h3>
    </header>
    <main>
        <div class="room-request-form-container">
            <h2>Room Request Form</h2>
            <form action="process_request.php" method="POST">
                <div>
                    <label for="room-name">Student Number:</label>
                    <input type="text" name="student-number" value='<?php echo $userId; ?>' readonly>
                </div>

                <div>
                    <label for="room-name">Student Name:</label>
                    <input type="text" name="student-name" value='<?php echo $userName; ?>' readonly>
                </div>
                
                <div>
                    <label for="room-name">Room Name:</label>
                    <input type="text" name="room-name" value='<?php echo $roomName; ?>' readonly>
                </div>

                <div>
                    <label for="faculty-name">Faculty-in-Charge:</label>
                    <input type="text" id="faculty-input" name="faculty-name" required>
                    <div><div id="suggestions" class="suggestions-box"></div></div>
                </div>

                <div>
                    <label for="reservation-date">Date & Time Start:</label>
                    <input type="datetime-local" id="reservation-date" name="reservation-date" required>
                </div>

                <div>
                    <label for="reservation-time">Date & Time End:</label>
                    <input type="datetime-local" id="reservation-time" name="reservation-time" required>
                </div>

                <div>
                    <label for="purpose">Purpose:</label>
                    <textarea id="purpose" name="purpose" rows="4" cols="40" placeholder="Enter your purpose here..." required></textarea>
                </div>
                
                <button type="submit">Submit</button>
            </form>
        </div>
    </main>
    <footer>

    </footer>
    <script src='request_form_script.js'></script>
</body>
</html>