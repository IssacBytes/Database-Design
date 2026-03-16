<?php
include("admin_check_session.php");
include("db_connect_admin.php");

header("Content-Type: application/json; charset=utf-8");

/* =========================
   Measure Query Time (NEW)
========================= */
$startTime = microtime(true);

/* =========================
   Original SQL Query
========================= */
$sql = "
    SELECT
        r.id,
        r.customerId,
        r.hairdresserId,
        r.serviceId,
        r.timePeriod,
        r.status,
        r.amount,
        r.createdAt,
        r.rated,
        c.name AS customerName,
        h.name AS hdName,
        s.name AS serviceName
    FROM reservations r
    JOIN customers c ON r.customerId = c.id
    JOIN hairdressers h ON r.hairdresserId = h.id
    JOIN services s ON r.serviceId = s.id
    ORDER BY r.createdAt DESC
";

$result = mysqli_query($connect, $sql);

if (!$result) {
    echo json_encode([
        "success" => false,
        "error" => mysqli_error($connect)
    ]);
    exit;
}

$reservations = [];
while ($row = mysqli_fetch_assoc($result)) {
    $reservations[] = $row;
}


$endTime = microtime(true);
$queryTime = round($endTime - $startTime, 6); // seconds


echo json_encode([
    "success" => true,
    "queryTime" => $queryTime,   
    "reservations" => $reservations
]);
exit;
