<?php
function new_grade($conn, $grade, $section)
{
    $table_name = 'G' . "$grade" . 'S' . "$section" . "-attendence";
    $table = "CREATE TABLE IF NOT EXISTS $table_name (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(20) NOT NULL,
    student_name VARCHAR(100) NOT NULL,
    student_status VARCHAR(10) NOT NULL,
    session_day VARCHAR(255) NOT NULL,
    day_value INT(10) NOT NULL,
    attendence TINYINT(1) NOT NULL
)";

    if ($conn->query($table) === TRUE) {
        $conn->close();
        return true;
    } else {
        $conn->close();
        return false;
    }
}
