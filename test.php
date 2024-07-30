<?php
// include('connection.php');
// $gg = 1;
// $ss = 'a';
// $name = 'Youssef';
// $marks = 0;
// $schedule = $conn->prepare("SELECT day, start, end from schedule WHERE grade = ? and section = ?");
// $schedule->bind_param('is', $gg, $ss);
// $schedule->execute();
// $schedule->store_result();
// if ($schedule->num_rows > 0) {
//     $schedule_day = [];
//     $schedule_start = [];
//     $schedule_end = [];
//     $schedule->bind_result($day, $start, $end);
//     while ($schedule->fetch()) {
//         $schedule_day[] = $day;
//         $schedule_start[] = $start;
//         $schedule_end[] =  $end;
//     }
//     echo '<pre>';
//     echo json_encode([
//         'status' => 'OK', 'authenticated' => true, 'student_marks' => $marks,
//         'student_name' => $name, 'student_grade' => $gg, 'schedule' => [$schedule_day, $schedule_start, $schedule_end]
//     ]);
//     echo '</pre>';
// // }
// $grade = 1;
// $section = 'a';
// $table_name = 'G' . "$grade" . 'S' . "$section" . "-attendence";
// echo $table_name;
// $password = 'user_password';
// $hash = password_hash($password, PASSWORD_BCRYPT); // or PASSWORD_ARGON2I
// echo $hash;
// $name = "Youssef Mustafa Gadallah";
// $grade = 9;
// $section = 'a';
// $separated_name = explode(' ', $name);
// $first_name = $separated_name[0];
// $second_name = $separated_name[1];
// $student_id = uniqid('student' . "$grade" . "$section" . "_", true);
// $suffix = (time() % 1000000);
// $student_username = "$first_name" . "$grade" . "$section" . "_" . "$suffix";
// echo $student_id . "<br>" . $student_username;

use function PHPSTORM_META\type;

$message = "هلااااااا";
echo type($message);
