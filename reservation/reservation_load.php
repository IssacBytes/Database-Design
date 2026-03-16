<?php
include("session_guard_customer.php");
include("db_connect_customer.php");

$cid = $_SESSION["c_id"];

$sql = "
    SELECT r.id, r.serviceId, r.hairdresserId, r.timePeriod, r.status,
           s.name AS serviceName,
           h.name AS hdName
    FROM reservations r
    LEFT JOIN services s ON r.serviceId = s.id
    LEFT JOIN hairdressers h ON r.hairdresserId = h.id
    WHERE r.customerId = '$cid'
    ORDER BY r.createdAt DESC
";

$result = mysqli_query($connect, $sql);

$unfinished = [];
$finished   = [];

while ($row = mysqli_fetch_assoc($result)) {

    if ($row["status"] === "pending") {
        $unfinished[] = $row;
    }
    if ($row["status"] === "completed") {
        $finished[] = $row;
    }
}

echo json_encode([
    "success" => true,
    "unfinished" => $unfinished,
    "finished" => $finished
]);
exit;
?>
