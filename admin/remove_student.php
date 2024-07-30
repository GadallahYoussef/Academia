<?php
session_start();
include('../connection.php');
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SESSION['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'];
    $grade = $data['grade'];
    $section = $data['section'];
    if (!preg_match('/^\s*$/', $name)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Input']);
        $conn->close();
        exit;
    }

    // Validate `grade` to be an integer
    if (!filter_var($grade, FILTER_VALIDATE_INT)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Input']);
        $conn->close();
        exit;
    }

    // Validate `section` to be either an integer or a single letter
    if (!preg_match('/^\d$|^[a-zA-Z]$/', $section)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Input']);
        $conn->close();
        exit;
    }
    $remove = $conn->prepare("SELECT user_id from stdata where student_name = ? and grade = ? and section = ? LIMIT 1");
    $remove->bind_param('sis', $name, $grade, $section);
    $remove->execute();
    $remove->store_result();
    $remove->bind_result($user_id);
    $remove->fetch();
    $remove = $conn->prepare("DELETE FROM stdata where student_name = ? and grade = ? and section = ?");
    $remove->bind_param("sis", $name, $grade, $section);
    $remove->execute();
    $remove = $conn->prepare("DELETE FROM stdssn where user_id = ?");
    $remove->bind_param('s', $user_id);
    $remove->execute();
    $table_name = 'G' . "$grade" . 'S' . "$section" . "-attendence";
    $remove = $conn->prepare("DELETE FROM $table_name WHERE user_id = ?");
    $remove->bind_param('s', $user_id);
    $remove->execute();
    $remove->close();
    $conn->close();
}
