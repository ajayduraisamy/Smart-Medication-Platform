<?php
include 'db.php';
date_default_timezone_set("Asia/Kolkata");

$now = date("Y-m-d H:i:s");

$sql = "SELECT * FROM medicine_log 
        WHERE taken_datetime = DATE_ADD('$now', INTERVAL 2 MINUTE)
        LIMIT 1";

$result = mysqli_query($conn, $sql);

header('Content-Type: application/json');

if ($row = mysqli_fetch_assoc($result)) {
    $response = [
        $row['medicine_name'],
        date("d-m-Y H:i:s", strtotime($row['taken_datetime'])),
        $row['tablet_no']
    ];
    echo json_encode($response);
} else {
    echo json_encode("No upcoming reminders");
}
?>
