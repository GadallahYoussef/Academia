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
    $table_name = 'G' . "$grade" . 'S' . "$section" . "-attendence";
    $deactve = $conn->prepare("UPDATE stdata SET status='inactive' WHERE student_name=? and grade=? and section=?");
    $deactve->bind_param('sis', $name, $grade, $section);
    $deactve->execute();
    $deactve = $conn->prepare("UPDATE $table_name SET student_status='inactive' WHERE student_name=?");
    $deactve->bind_param('s', $name);
    $deactve->execute();
    $deactve->close();
    $conn->close();
}
