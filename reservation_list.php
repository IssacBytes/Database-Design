<?php
header("Content-Type: application/json; charset=utf-8");

include("session_guard_customer.php");
include("db_connect_customer.php");

$cid = $_SESSION["c_id"] ?? "";
if ($cid === "") {
    echo json_encode(["success" => false]);
    exit;
}

/* latest reservation */
$sqlLast = "
    SELECT 
        r.id,
        s.name AS serviceName,
        h.name AS hdName,
        r.timePeriod,
        r.status
    FROM reservations r
    LEFT JOIN services s ON r.serviceId = s.id
    LEFT JOIN hairdressers h ON r.hairdresserId = h.id
    WHERE r.customerId = '$cid'
      AND r.status <> 'cancelled'
    ORDER BY r.createdAt DESC
    LIMIT 1
";
$resLast = mysqli_query($connect, $sqlLast);
$last = $resLast && mysqli_num_rows($resLast) > 0
    ? mysqli_fetch_assoc($resLast)
    : null;

/* finished reservations */
$sqlFinished = "
    SELECT 
        r.id,
        r.hairdresserId,
        s.name AS serviceName,
        h.name AS hdName,
        r.timePeriod,
        r.amount,
        r.status,
        r.rated
    FROM reservations r
    LEFT JOIN services s ON r.serviceId = s.id
    LEFT JOIN hairdressers h ON r.hairdresserId = h.id
    WHERE r.customerId = '$cid'
      AND r.status IN ('completed','paid')
    ORDER BY r.createdAt DESC
";
$resFinished = mysqli_query($connect, $sqlFinished);

$finished = [];
if ($resFinished) {
    while ($row = mysqli_fetch_assoc($resFinished)) {
        $finished[] = $row;
    }
}

echo json_encode([
    "success"  => true,
    "last"     => $last,
    "finished" => $finished
]);
exit;
