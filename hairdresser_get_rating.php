<?php
header("Content-Type: application/json; charset=utf-8");

include("session_guard_staff.php");
include("db_connect_staff.php");

$hid = $_SESSION["h_id"] ?? "";


$sql = "
    SELECT rating
    FROM hairdressers
    WHERE id = '$hid'
    LIMIT 1
";

$res = mysqli_query($connect, $sql);

if (!$res || mysqli_num_rows($res) === 0) {
    echo json_encode([
        "success" => true,
        "rating" => null
    ]);
    exit;
}

$row = mysqli_fetch_assoc($res);

echo json_encode([
    "success" => true,
    "rating" => $row["rating"]
]);
exit;
