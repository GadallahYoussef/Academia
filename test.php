<?php
include('connection.php');
//header('Content-Type: application/json charset=utf-8');
$authenticated = false;
$grade = 1;
$section = 'a';
$current = time();
function is_arabic($text)
{
    return preg_match('/\p{Arabic}/u', $text);
}
function sanitize_input($input)
{
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $due = time() + (24 * 60 * 60);
    $message = sanitize_input($_POST['message']);
    $conn->begin_transaction();
    try {
        $push = $conn->prepare("INSERT INTO notifications (notification, grade, section, due) VALUES (?, ?, ?, ?)");
        $push->bind_param('ssss', $message, $grade, $section, $due);
        if ($push->execute()) {
            $push->close();
            $conn->commit();
            echo json_encode(['status' => 'OK', 'message' => 'notification pushed']);
            exit;
        } else {
            $push->close();
            throw new Exception("database error");
        }
    } catch (Exception $e) {
        $conn->rollback();
        $conn->close();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form method="post"><textarea name="message"></textarea><input type="submit"></form>
</body>

</html>