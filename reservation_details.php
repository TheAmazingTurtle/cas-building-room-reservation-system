<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header("Location: index.html");
    exit();
}

require 'db_connect.php';

$reservationId = $_GET['res_id'] ?? null;

$stmt = $conn->prepare("SELECT *
                        FROM student_reservation 
                        INNER JOIN student USING(student_number) 
                        INNER JOIN faculty USING(faculty_id)
                        LEFT JOIN admin USING(admin_id) 
                        WHERE student_reservation_id = ?");
$stmt->bind_param("s", $reservationId);
$stmt->execute();
$reservationResult = $stmt->get_result();

if ($reservationResult->num_rows > 0) {
    $reservation = $reservationResult->fetch_assoc();
    $status = null;
    $end = new DateTime($reservation['time_end']);
    $now = new DateTime();

    $isCancellable = FALSE;
    if (!$reservation["is_active"]) {
        $status = "Cancelled";
    } elseif (is_null($reservation["is_faculty_approved"])) {
        $status = "Awaiting FIC Approval";
        $isCancellable = TRUE;
    } elseif (!$reservation["is_faculty_approved"]) {
        $status = "Denied by FIC";
    } elseif (is_null($reservation["is_admin_approved"])) {
        $status = "Awaiting Admin Approval";
        $isCancellable = TRUE;
    } elseif (!$reservation["is_admin_approved"]) {
        $status = "Denied by Admin";
    } elseif ($end >= $now) {
        $status = "Approved";
        $isCancellable = TRUE;
    } else {
        $status = "Completed";
    }

    $dateTime = new DateTime($reservation['time_start']);
    $timeStart =  $dateTime -> format('F j, Y \a\t g:i A');

    $dateTime = new DateTime($reservation['time_end']);
    $timeEnd =  $dateTime -> format('F j, Y \a\t g:i A');

    $date = new DateTime($reservation['request_date']);
    $requestDate = $date->format('F j, Y');


} else {
    echo "Reservation not found.";
    exit();
}

$conn->close();

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body id="reservation-details">
    <header>
        <h3>CAS Building Room Reservation System</h3>
    </header>
    <main>
        <a href="javascript:history.back()">Back</a>
        <h1>Reservation Details</h1>
        <div class="reservation-details-container">
            <p><strong>Reservation ID:</strong> <?php echo htmlspecialchars($reservation['student_reservation_id']); ?></p>
            <p><strong>Requestee:</strong> <?php echo htmlspecialchars($reservation['student_name']); ?></p>
            <p><strong>Room Name:</strong> <?php echo htmlspecialchars($reservation['room_name']); ?></p>
            <p><strong>Faculty-in-Charge:</strong> <?php echo htmlspecialchars($reservation['faculty_name']); ?></p>
            <p><strong>Faculty Remark:</strong> <?php echo htmlspecialchars($reservation['faculty_remark'] ?? 'N/A'); ?></p>
            <p><strong>Start Time:</strong> <?php echo htmlspecialchars($timeStart); ?></p>
            <p><strong>End Time:</strong> <?php echo htmlspecialchars($timeEnd); ?></p>
            <p><strong>Request Date:</strong> <?php echo htmlspecialchars($requestDate); ?></p>
            <p><strong>Purpose:</strong> <?php echo $reservation['purpose']; ?></p>
            <p><strong>Approved by (Admin):</strong> <?php echo htmlspecialchars($reservation['admin_name'] ?? 'N/A'); ?></p>
            <p><strong>Admin Remark:</strong> <?php echo htmlspecialchars($reservation['admin_remark'] ?? 'N/A'); ?></p>
            <p><strong>Status:</strong> <?php echo $status; ?></p>
            <div class="button-container">
                <?php
                    if ($isCancellable) {
                        echo "<form action='cancel_reservation.php' method='post'>
                                <input type='hidden' name='reservation_id' value='" . htmlspecialchars($reservation['student_reservation_id']) . "'>
                                <button type='submit'>Cancel</button>
                            </form>";
                    }

                    switch ($_SESSION['current_page']){
                        case 'dashboard':
                            echo    "<form action='archive_reservation.php' method='post'>".
                                        "<input type='text' style='display:none;' name='reservation-id' value='".$reservation["student_reservation_id"]."'>".
                                        "<input type='text' style='display:none;' name='action-mode' value='1'>".
                                        "<button type='submit'>Archive</button>".
                                    "</form>";
                            break;
                        case 'archive':
                            echo    "<form action='archive_reservation.php' method='post'>".
                                        "<input type='text' style='display:none;' name='reservation-id' value='".$reservation["student_reservation_id"]."'>".
                                        "<input type='text' style='display:none;' name='action-mode' value='0'>".
                                        "<button type='submit'>Restore</button>".
                                    "</form>";
                            break;
                        default:
                            break;
                    }
                ?>
            </div>
        </div>
        
        
    </main>
    <footer>
    </footer>
</body>
</html>