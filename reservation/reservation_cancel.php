<?php
include("session_guard_customer.php");  // check session
include("db_connect_customer.php");              // DB connection

$resId = $_POST["id"] ?? "";

if ($resId == "") {
    echo json_encode([
        "success" => false,
        "error"   => "Missing reservation id."
    ]);
    exit;
}

// ensure reservation belongs to this customer
$cid = $_SESSION["c_id"];

$sql_check = "
    SELECT * FROM reservations
    WHERE id = '$resId' AND customerId = '$cid'
    LIMIT 1
";

$result = mysqli_query($connect, $sql_check);

if (mysqli_num_rows($result) == 0) {
    echo json_encode([
        "success" => false,
        "error"   => "Reservation not found or not yours."
    ]);
    exit;
}

// only pending reservation can be canceled
$row = mysqli_fetch_assoc($result);
if ($row["status"] !== "pending") {
    echo json_encode([
        "success" => false,
        "error"   => "Only pending reservations can be canceled."
    ]);
    exit;
}

// update status to canceled
$sql_cancel = "
    UPDATE reservations
    SET status = 'canceled'
    WHERE id = '$resId'
";

if (!mysqli_query($connect, $sql_cancel)) {
    echo json_encode([
        "success" => false,
        "error"   => mysqli_error($connect)
    ]);
    exit;
}

echo json_encode([
    "success" => true,
    "message" => "Reservation canceled successfully."
]);
exit;
?>
