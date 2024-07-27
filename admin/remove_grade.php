<?php
session_start();
include('../connection.php');
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
        $conn->close();
        exit;
    }
    $table_name = 'G' . "$grade" . 'S' . "$section" . "-attendence";
    $remove = $conn->prepare("DROP TABLE IF EXISTS $table_name");
    $remove->execute();
    $remove->close();
    $gather = $conn->prepare("SELECT user_id from stdata WHERE grade=? and section=?");
    $gather->bind_param('is', $grade, $section);
    $gather->execute();
    $gather->store_result();
    $gather->bind_result($user_id);
    if ($gather->num_rows > 0) {
        $grade_students = [];
        while ($gather->fetch()) {
            $grade_students[] = $user_id;
        }
        $gather->close();
        $remove = $conn->prepare("DELETE from stdata Where grade= ? and section= ?");
        $remove->bind_param('is', $grade, $section);
        $remove->execute();
        $remove = $conn->prepare("DELETE from classes Where grade= ? and section= ?");
        $remove->bind_param('is', $grade, $section);
        $remove->execute();
        $remove = $conn->prepare("DELETE FROM stdssn WHERE user_id= ?");
        foreach ($grade_students as $student) {
            $remove->bind_param('s', $student);
            $remove->execute();
        }
        $remove->close();
    }
}
$conn->close();
