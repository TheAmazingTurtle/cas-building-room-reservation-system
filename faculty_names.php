<?php

require 'db_connect.php';

$stmt = $conn->prepare("SELECT * FROM faculty");
$stmt->execute();
$facultyResult = $stmt->get_result();

$facultyNames = [];
if ($facultyResult->num_rows > 0) {
    while ($faculty = $facultyResult->fetch_assoc()) {
        $facultyNames[] = ["name" => $faculty['faculty_name'], "is_available" => $faculty['is_available']];
    }
}

echo json_encode($facultyNames);

$conn->close();
?>