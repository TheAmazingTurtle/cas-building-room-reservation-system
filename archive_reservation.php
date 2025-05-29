<?php
session_start();
if (!isset($_SESSION['user_id'])  || !isset($_SESSION['user_role'])) {
    header("Location: index.html");
    exit();
}

$redirectPage = null;
switch($_SESSION['current_page']){
    case 'dashboard':
        $redirectPage = "_dashboard.php";
        break;
    case 'archive':
        $redirectPage = "_archive.php";
        break;
    default:
        echo "Improper waygate used";
        exit();
        break;
}

require 'db_connector.php';

$redirectPage = $_SESSION['user_role'].$redirectPage;

$resId = $_POST['reservation-id'];
$archiveMode = $_POST['action-mode'];


try {
    $conn->begin_transaction();

    

    $updateReservationSql = null;
    switch($_SESSION['user_role']){
        case 'student':
            $updateReservationSql = "UPDATE student_reservation SET is_archived = ? WHERE student_reservation_id = ? ;";
            break;
        case 'faculty':
            $updateReservationSql = "UPDATE faculty_reservation SET is_archived = ? WHERE faculty_reservation_id = ? ;";
            break;
    }

    $stmt = $conn->prepare($updateReservationSql);
    $stmt->bind_param("is", $archiveMode, $resId);
    $stmt->execute();

    $conn->commit();
    header("Location: $redirectPage");
    exit();
} 
catch (ExceptionType $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
    exit();
}



?>