<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header("Location: index.html");
    exit();
}

require 'db_connector.php';

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
        <a href="javascript:history.back()">Back</a>
        <div>
            <h2>Room Request</h2>
            <div class="room-request-form-container">
                <form id='faculty-request-form' action="process_request.php" method="POST">
                    <h3>Form</h3>

                    <div>
                        <label for="faculty-id">Faculty ID:</label>
                        <input type="text" id="faculty-id" name="faculty-id" value='<?php echo $userId; ?>' readonly>
                    </div>

                    <div>
                        <label for="faculty-name">Faculty Name:</label>
                        <input type="text" id="faculty-name" name="faculty-name" value='<?php echo $userName; ?>' readonly>
                    </div>
                    
                    <div>
                        <label for="room-name">Room Name:</label>
                        <input type="text" id="room-name" name="room-name" value='<?php echo $roomName; ?>' readonly>
                    </div>

                    <div>
                        <label for="reservation-start">Date & Time Start:</label>
                        <input type="datetime-local" id="reservation-start" name="reservation-start" required>
                    </div>

                    <div>
                        <label for="reservation-end">Date & Time End:</label>
                        <input type="datetime-local" id="reservation-end" name="reservation-end" required>
                    </div>

                    <div>
                        <label for="purpose">Purpose:</label>
                        <textarea id="purpose" name="purpose" rows="4" cols="40" placeholder="Enter your purpose here..." required></textarea>
                    </div>

                    <div>
                        <p id="error-prompt"></p>
                    </div>
                    
                    <button type="reset">Reset</button>
                    <button type="submit">Submit</button>
                </form>
                <div>
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