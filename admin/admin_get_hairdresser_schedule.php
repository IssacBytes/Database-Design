<?php
header("Content-Type: application/json; charset=utf-8");

include("admin_check_session.php");
include("db_connect_admin.php");

$hairdresserId = $_GET["hairdresserId"] ?? "";

if ($hairdresserId === "") {
    echo json_encode([
        "success" => false,
        "error" => "Missing hairdresserId"
    ]);
    exit;
}

$sql = "
    SELECT
        weekday,
        work_time
    FROM hairdresser_schedule
    WHERE h_id = '$hairdresserId'
    ORDER BY
        FIELD(weekday,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'),
        work_time
";

$res = mysqli_query($connect, $sql);

$list = [];

if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $list[] = $row;
    }
}

echo json_encode([
    "success" => true,
    "schedule" => $list
]);
exit;
