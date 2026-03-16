<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["admin_id"])) {
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode([
        "success" => false,
        "error" => "Admin not logged in"
    ]);
    exit;
}
