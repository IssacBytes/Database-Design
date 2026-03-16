<?php
header("Content-Type: application/json; charset=utf-8");

include("session_guard_admin.php");
include("db_connect_admin.php");

/*
  Statistical logic:
  - Total consumption amount of each customer
  - Number of consumption times
  - Membership level
  - Excluding Last Payment (as per your request)
*/

$sql = "
    SELECT
        c.id AS customerId,
        c.name AS customerName,
        c.membershipLevel,
        COUNT(p.id) AS paymentCount,
        COALESCE(SUM(p.amount), 0) AS totalSpent
    FROM customers c
    LEFT JOIN payments p
        ON c.id = p.customerId
        AND p.type IN ('vip-deduction','qr','cash','card')
    GROUP BY c.id, c.name, c.membershipLevel
    ORDER BY totalSpent DESC
";

$res = mysqli_query($connect, $sql);

if (!$res) {
    echo json_encode([
        "success" => false,
        "error"   => mysqli_error($connect)
    ]);
    exit;
}

$list = [];

while ($row = mysqli_fetch_assoc($res)) {
    $list[] = [
        "customerId"     => $row["customerId"],
        "customerName"   => $row["customerName"],
        "membershipLevel"=> $row["membershipLevel"],
        "paymentCount"   => intval($row["paymentCount"]),
        "totalSpent"     => floatval($row["totalSpent"])
    ];
}

echo json_encode([
    "success" => true,
    "list"    => $list
]);
exit;
?>
