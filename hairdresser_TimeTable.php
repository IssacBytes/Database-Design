<?php
include("session_guard.php");

include("db_connect_staff.php");

header("Content-Type: application/json; charset=utf-8");
error_reporting(E_ERROR | E_PARSE);

$sql = "SELECT weekday, work_time 
        FROM hairdresser_schedule 
        WHERE h_id = '{$_SESSION["h_id"]}'";

$result = mysqli_query($connect, $sql);

$schedule = [];

while ($row = mysqli_fetch_assoc($result)) {
    $schedule[] = $row;   
}

if (count($schedule) == 0) {
    echo json_encode(["success" => false, "error" => "No schedule or hairdresser found."]);
} else {
    echo json_encode(["success" => true, "schedule" => $schedule]);
}

exit;
?>
