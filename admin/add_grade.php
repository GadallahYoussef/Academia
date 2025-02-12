<?php
session_start();
include('../connection.php');
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
function closeConnections($conn)
{
    $conn->close();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input", true));
    $grade = $data['grade'];
    $section = $data['section'];
    if (!is_numeric($grade) || !preg_match('/^[a-zA-Z0-9_]+$/', $section)) {
        echo json_encode(['error' => 'Invalid grade or section']);
        closeConnections($conn);
        exit;
    }
    $table_name = 'G' . "$grade" . 'S' . "$section" . "-attendence";
    $conn->begin_transaction();
    try {
        $verify = $conn->prepare("SELECT * from classes WHERE grade= ? and section= ?");
        $verify->bind_param('is', $grade, $section);
        $verify->execute();
        $verify->store_result();
        if ($verify->num_rows == 0) {
            $verify->close();
            $table = $conn->prepare("CREATE TABLE IF NOT EXISTS $table_name (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id VARCHAR(40) NOT NULL,
            student_name VARCHAR(100) NOT NULL,
            student_status VARCHAR(10) NOT NULL,
            session_day VARCHAR(255) NOT NULL,
            day_value INT(10) NOT NULL,
            attendence TINYINT(1) NOT NULLs
            )");
            if ($table->execute()) {
                $table->close();
                $add = $conn->prepare("INSERT INTO classes (grade, section) VALUES (?, ?)");
                $add->bind_param('is', $grade, $section);
                if ($add->execute()) {
                    $add->close();
                    $conn->commit();
                    echo json_encode(['status' => 'ok', 'message' => 'added successfully']);
                    exit;
                } else {
                    $add->close();
                    throw new Exception("Failed to add the grade");
                }
            } else {
                $table->close();
                throw new Exception("Failed to make attendence table");
            }
        } else {
            $verify->close();
            throw new Exception("Already Exists");
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        closeConnections($conn);
        exit;
    }
} else {
    closeConnections($conn);
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
    exit;
}
