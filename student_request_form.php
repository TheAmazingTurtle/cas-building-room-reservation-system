<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header("Location: index.html");
    exit();
}

require 'db_connector.php';

$userId = $_SESSION['user_id'];
$userName = $_SESSION['name'] ?? '';
$roomName = $_SESSION['room_name'];
$roomCapacity = $_SESSION['room_capacity'];

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
        <a href="javascript:history.back()">Back</a>
        <div>
            <h2>Room Request Form</h2>
            <div class="room-request-form-container">
                <form id='student-request-form' action="process_request.php" method="POST">

                    <div class="form-fields">
                        <label for="student-number">Student Number:</label>
                        <input type="text" id="student-number" name="student-number" value='<?php echo $userId; ?>' readonly>
                    </div>

                    <div class="form-fields">
                        <label for="student-name">Student Name:</label>
                        <input type="text" id="student-name" name="student-name" value='<?php echo $userName; ?>' readonly>
                    </div>
                    
                    <div class="form-fields">
                        <label for="room-name">Room Name:</label>
                        <input type="text" id="room-name" name="room-name" value='<?php echo $roomName; ?>' readonly>
                    </div>

                    <div class="form-fields">
                        <label for="faculty-input">Faculty-in-Charge:</label>
                        <input type="text" id="faculty-input" name="faculty-name" required>
                        <div><div id="suggestions" class="suggestions-box"></div></div>
                    </div>

                    <div class="form-fields">
                        <label for="head-count">Head Count:</label>
                        <p id="room-max-capacity" style="display:none;"><?php echo $roomCapacity; ?></p>
                        <input type="number" id="head-count" name="head-count" required>
                    </div>

                    <div class="form-fields">
                        <label for="reservation-start">Date & Time Start:</label>
                        <input type="datetime-local" id="reservation-start" name="reservation-start" required>
                    </div>

                    <div class="form-fields">
                        <label for="reservation-end">Date & Time End:</label>
                        <input type="datetime-local" id="reservation-end" name="reservation-end" required>
                    </div>

                    <div class="form-fields">
                        <label for="purpose">Purpose:</label>
                        <textarea id="purpose" name="purpose" rows="4" cols="40" placeholder="Enter your purpose here..." required></textarea>
                    </div>

                    <div>
                        <p id="error-prompt"></p>
                    </div>
                    
                    <button type="reset">Reset</button>
                    <button type="submit">Submit</button>
                </form>
                <div class="conflicts">
                    <h3>Conflicts</h3>
                    <div id="conflict-container"><div>
                </div>
            </div>
        </div>
    </main>
    <footer>

    </footer>
    <script src='request_form_script.js'></script>
</body>
</html>