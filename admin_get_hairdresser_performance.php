<?php
header("Content-Type: application/json; charset=utf-8");

include("admin_check_session.php");
include("db_connect_admin.php");

$sql = "
    SELECT
        h.id,
        h.name,
        COUNT(r.id) AS reservationCount,
        COALESCE(SUM(r.amount), 0) AS totalIncome
    FROM hairdressers h
    LEFT JOIN reservations r
        ON h.id = r.hairdresserId
        AND r.status <> 'cancelled'
    GROUP BY h.id, h.name
    ORDER BY h.name
";

$res = mysqli_query($connect, $sql);

if (!$res) {
    echo json_encode([
        "success" => false,
        "error" => mysqli_error($connect)
    ]);
    exit;
}

$list = [];

while ($row = mysqli_fetch_assoc($res)) {
    $list[] = [
        "id" => $row["id"],
        "name" => $row["name"],
        "reservationCount" => intval($row["reservationCount"]),
        "totalIncome" => floatval($row["totalIncome"])
    ];
}

echo json_encode([
    "success" => true,
    "list" => $list
]);
exit;
