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

switch($_SESSION['user_role']){
    case 'student':
        $redirectPage = $_SESSION['user_role'].$redirectPage;
        manage_student_archive();
        break;
    default:
        echo 'Unrecognized role';
        header('Location: index.html');
        exit();
}

function manage_student_archive(){
    require 'db_connect.php';

    global $redirectPage;
    $resId = $_POST['reservation-id'];
    $archiveMode = $_POST['action-mode'];

    try {
        $conn->begin_transaction();

        $stmt = $conn->prepare("UPDATE student_reservation SET is_archived = ? WHERE student_reservation_id = ? ;");
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
}



?>