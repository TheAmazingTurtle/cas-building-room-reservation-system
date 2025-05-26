<?php
session_start();
require 'db_connect.php';


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Error";
    // header("Location: index.html");
    // exit();
}

$role = $_POST['user_role'];
$username = $_POST['username'];
$password = $_POST['password'];
// $hashed = hash('sha256', $password);             TO BE IMPLEMENTED


try {
    $stmt = null;

    switch ($role) {
        case 'admin':
            $stmt = $conn->prepare("SELECT admin_id FROM admin WHERE username = ? AND password = ?");
            break;
        case 'faculty':
            $stmt = $conn->prepare("SELECT faculty_id FROM faculty WHERE username = ? AND password = ?");
            break;
        case 'student':
            $stmt = $conn->prepare("SELECT student_number FROM student WHERE username = ? AND password = ?");
            break;
        default:
            throw new Exception("Unrecognized user role: $role");
            break;
    }


    
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id);
        $stmt->fetch();
        $_SESSION['user_role'] = $role;
        $_SESSION['user_id'] = $id;

        header("Location: ".$role."_dashboard.php");
    } else {
        $error = "Invalid username or password.";
        echo $error;
        //header("Location: ".$role."_login.html");
    }

    // exit();
}
catch (Exception $e) {
    echo $e;
    // header("Location: ".$role."_login.html");
    // exit();
}
?>