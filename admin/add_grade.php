<?php
session_start();
include('../connection.php');
include('new_table.php');
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input", true));
    $grade = $data['grade'];
    $section = $data['section'];
    if (!is_numeric($grade) || !preg_match('/^[a-zA-Z0-9_]+$/', $section)) {
        echo json_encode(['error' => 'Invalid grade or section']);
        exit;
    }
    $verfy = $conn->prepare("SELECT * from classes WHERE grade= ? and section= ?");
    $verfy->bind_param('is', $grade, $section);
    $verfy->execute();
    $verfy->store_result();
    if ($verfy->num_rows == 0) {
        $verfy->close();
        $add = $conn->prepare("INSERT INTO classes (grade, section) VALUES (?, ?)");
        $add->bind_param('is', $grade, $section);
        $test = new_grade($conn, $grade, $section);
        if ($test) {
            $add->execute();
            $add->close();
            echo json_encode(['status' => 'ok', 'message' => 'added successfully']);
        } else {
            $add->close();
            echo json_encode(['status' => 'error', 'message' => 'database error']);
        }
    } else {
        $verfy->close();
        echo json_encode(['status' => 'error', 'message' => 'already exists']);
    }
}
