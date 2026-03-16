<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

include("session_guard_customer.php");
include("db_connect_customer.php");

// Customer ID
$cid = $_SESSION["c_id"] ?? "";

if ($cid === "") {
    echo json_encode(["success" => false, "error" => "No customer session"]);
    exit;
}

// 1) Check membership level
$sql1 = "
    SELECT membershipLevel
    FROM customers
    WHERE id = '$cid'
    LIMIT 1
";
$res1 = mysqli_query($connect, $sql1);
$row1 = mysqli_fetch_assoc($res1);

$levelName = $row1["membershipLevel"] ?? "Bronze";

// Convert the rank names into numerical grades (your original system is 0-5)
$levelMap = [
    "Bronze" => 0,
    "Silver" => 1,
    "Gold"   => 2,
    "Platinum" => 3
];

$level = $levelMap[$levelName] ?? 0;


// 2) Query the total consumption amount (the type must match your trigger)
$sql2 = "
    SELECT COALESCE(SUM(amount), 0) AS totalSpent
    FROM payments
    WHERE customerId = '$cid'
      AND type IN ('vip-deduction','qr','cash','card')
";
$res2 = mysqli_query($connect, $sql2);
$row2 = mysqli_fetch_assoc($res2);

$totalSpent = $row2["totalSpent"] ?? 0;


// Final output JSON
echo json_encode([
    "success"     => true,
    "level"       => $level,
    "totalSpent"  => number_format($totalSpent, 2)
]);

exit;
?>
