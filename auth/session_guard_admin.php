<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    echo json_encode([
        "success" => false,
        "error" => "Admin not logged in"
    ]);
    exit;
}
?>
