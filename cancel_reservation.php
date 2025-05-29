<?php
session_start();
require 'db_connector.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
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

$redirectPage = $_SESSION['user_role'].$redirectPage;

$reservationId = $_POST['reservation-id'] ?? null;

try {
    $stmt = null;
    switch ($_SESSION['user_role']) {
        case 'student':
            $stmt = $conn -> prepare("UPDATE student_reservation
                                        SET is_active = 0
                                        WHERE student_reservation_id = ?");
            break;
        case 'faculty':
            $stmt = $conn -> prepare("UPDATE faculty_reservation
                                        SET is_active = 0
                                        WHERE faculty_reservation_id = ?");
            break;
        default:
            throw new Error("Unrecognized role used");
            break;
    }

    $stmt -> bind_param("s", $reservationId);
    $stmt -> execute();
    $conn -> commit();
    
    header("Location: $redirectPage");
    exit();
}
catch (Exception $e){
    $conn -> rollback();
    echo "Error: " . $e->getMessage();
    exit();
}

?>