<?php
// Do NOT call session_start() again if already started.
// Prevent duplicate session_start warnings.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check customer session
if (!isset($_SESSION["c_id"])) {
    echo json_encode([
        "success" => false,
        "error" => "Customer session expired"
    ]);
    exit;
}

// Silence, no output
?>
