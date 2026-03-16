<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION["h_id"])) {
    echo json_encode(["success" => false, "error" => "Session expired"]);
    exit;
}

echo json_encode([
    "success" => true,
    "h_id" => $_SESSION["h_id"],
    "h_name" => $_SESSION["h_name"]
]);

exit;
?>
