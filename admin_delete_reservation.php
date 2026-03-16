<?php
session_start();
header("Content-Type: application/json");
include("db_connect_admin.php");

if (!isset($_SESSION["admin_id"])) {
    echo json_encode(["success" => false]);
    exit;
}

$id = $_POST["id"] ?? "";

if ($id === "") {
    echo json_encode(["success" => false, "error" => "Missing id"]);
    exit;
}

$sql = "DELETE FROM reservations WHERE id='$id'";

if (!mysqli_query($connect, $sql)) {
    echo json_encode(["success" => false, "error" => mysqli_error($connect)]);
    exit;
}

echo json_encode(["success" => true]);
exit;
?>
