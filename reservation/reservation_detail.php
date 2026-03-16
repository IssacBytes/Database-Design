<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

include("session_guard_customer.php");
include("db_connect_customer.php");

$rid = $_GET["rid"] ?? "";

$sql = "
    SELECT 
        r.id,
        s.name AS serviceName,
        s.basePrice,
        s.durationMins,
        h.name AS hdName,
        r.timePeriod,
        r.amount,
        r.status
    FROM reservations r
    JOIN services s ON r.serviceId = s.id
    LEFT JOIN hairdressers h ON r.hairdresserId = h.id
    WHERE r.id = '$rid'
    ";
$res = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($res);

if (!$row) {
    echo json_encode(["success" => false]);
    exit;
}

echo json_encode([
    "success"       => true,
    "id"            => $row["id"],
    "serviceName"   => $row["serviceName"],
    "basePrice"     => $row["basePrice"],
    "durationMins"  => $row["durationMins"],
    "hdName"        => $row["hdName"],
    "timePeriod"    => $row["timePeriod"],
    "amount"        => $row["amount"],
    "status"        => $row["status"]
]);
exit;
?>
