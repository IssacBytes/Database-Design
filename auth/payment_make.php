<?php
include("session_guard_customer.php");
include("db_connect_customer.php");

header("Content-Type: application/json; charset=utf-8");

$customerId = $_SESSION["c_id"] ?? "";

// POST data
$orderId = $_POST["orderId"] ?? "";
$amount  = $_POST["amount"] ?? "";
$method  = $_POST["method"] ?? "";
$score   = $_POST["score"] ?? null;

// Basic verification
if ($orderId === "" || $amount === "" || $method === "") {
    echo json_encode([
        "success" => false,
        "error" => "Missing payment information"
    ]);
    exit;
}

$amount = floatval($amount);
if ($amount <= 0) {
    echo json_encode([
        "success" => false,
        "error" => "Payment amount must be greater than 0"
    ]);
    exit;
}

// Generate payment id
$paymentId = uniqid();

// Insert payments (the field name strictly follows the table you provided)
$sql_pay = "
    INSERT INTO payments (id, type, orderId, amount, customerId, createdAt)
    VALUES ('$paymentId', '$method', '$orderId', '$amount', '$customerId', NOW())
";

if (!mysqli_query($connect, $sql_pay)) {
    echo json_encode([
        "success" => false,
        "error" => mysqli_error($connect)
    ]);
    exit;
}

//  Update reservation status & amount
$sql_update = "
    UPDATE reservations
    SET status = 'paid',
        amount = '$amount'
    WHERE id = '$orderId'
      AND customerId = '$customerId'
";

if (!mysqli_query($connect, $sql_update)) {
    echo json_encode([
        "success" => false,
        "error" => mysqli_error($connect)
    ]);
    exit;
}

//(Optional) You can handle the scoring logic later; it will not affect the amount.

echo json_encode([
    "success" => true
]);
exit;
