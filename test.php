<?php
include('connection.php');
header('Content-Type: application/json charset=utf-8');
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
$notify = $conn->prepare("SELECT notification, creation from notifications WHERE ((grade=? and section =?) 
    OR (grade='all' and section='all') OR (grade=? and section='all')) and (due > ?) ORDER BY creation");
$notify->bind_param('isii', $grade, $section, $grade, $current);
if ($notify->execute()) {
    $notify->store_result();
    if ($notify->num_rows > 0) {
        $message = [];
        $notify->bind_result($notification, $creation_time);
        while ($notify->fetch()) {
            if (is_arabic($notification)) {
                $rtl_notification = "\u{202B}" . $notification . "\u{202C}";
                $message[$creation_time] = $rtl_notification;
            } else {
                $message[$creation_time] = $notification;
            }
        }
        $notify->close();
        $conn->close();
        echo json_encode([
            'status' => 'OK', 'authenticated' => $authenticated, 'found' => true, 'notification' => $message
        ], JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        $notify->close();
        $conn->close();
        echo json_encode(['status' => 'OK', 'authenticated' => $authenticated, 'found' => false]);
        exit;
    }
} else {
    $notify->close();
    $conn->close();
    echo json_encode(['status' => 'error', 'authenticated' => $authenticated, 'message' => 'database error']);
    exit;
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
    <form method="post"><input type="text" name="message"><input type="submit"></form>
</body>

</html>