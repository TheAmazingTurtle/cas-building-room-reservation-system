<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header("Location: index.html");
    exit();
}

$reservationId = $_GET['res_id'] ?? null;
$action = $_GET['action'] ?? null;
$type = $_GET['type'] ?? null;

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Details</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body id="reservation-details">
    <header>
        <h3>CAS Building Room Reservation System</h3>
    </header>
    <main>
        <a href="javascript:history.back()">Back</a>
        <h1>Reservation Details</h1>
        <div class='reservation-details-container'>
            <?php
                switch ($type) {
                    case 'student':
                        include 'student_reservation_details.php';
                        break;
                    case 'faculty':
                        include 'faculty_reservation_details.php';
                        break;
                    case 'admin':
                        break;
                    default:    
                        break;
                }
            ?>
        </div>
    </main>
    <footer>
    </footer>
</body>
</html>