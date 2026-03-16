<?php
header("Content-Type: application/json; charset=utf-8");

include("session_guard_staff.php");
include("db_connect_staff.php");

$hid = $_SESSION["h_id"] ?? "";


$sql = "
    SELECT
        r.id,
        c.name AS customerName,
        s.name AS serviceName,
        r.timePeriod,
        r.status
    FROM reservations r
    LEFT JOIN customers c ON r.customerId = c.id
    LEFT JOIN services s ON r.serviceId = s.id
    WHERE r.hairdresserId = '$hid'
    ORDER BY r.createdAt DESC
";

$res = mysqli_query($connect, $sql);

$reservations = [];

if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $reservations[] = $row;
    }
}

echo json_encode([
    "success" => true,
    "reservations" => $reservations
]);
exit;
