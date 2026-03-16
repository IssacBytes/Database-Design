<?php
session_start();
header("Content-Type: application/json");
include("db_connect_admin.php");

if (!isset($_SESSION["admin_id"])) {
    echo json_encode(["success" => false]);
    exit;
}

$cid = $_POST["customerId"] ?? "";
$hid = $_POST["hairdresserId"] ?? "";
$sid = $_POST["serviceId"] ?? "";
$tp  = $_POST["timePeriod"] ?? "";
$st  = $_POST["status"] ?? "pending";
$amt = $_POST["amount"] ?? "0";

$newId = uniqid();

$sql = "
    INSERT INTO reservations (id, customerId, hairdresserId, serviceId, timePeriod, status, amount, createdAt)
    VALUES ('$newId', '$cid', '$hid', '$sid', '$tp', '$st', '$amt', NOW())
";

if (!mysqli_query($connect, $sql)) {
    echo json_encode(["success" => false, "error" => mysqli_error($connect)]);
    exit;
}

echo json_encode(["success" => true, "reservationId" => $newId]);
exit;
?>
