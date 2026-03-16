<?php
header("Content-Type: application/json; charset=utf-8");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ===== Admin login check ===== */
if (!isset($_SESSION["admin_id"])) {
    echo json_encode([
        "success" => false,
        "error" => "Admin not logged in"
    ]);
    exit;
}

include("db_connect_admin.php");

/* ===== Input ===== */
$reservationId = $_POST["id"] ?? "";

if ($reservationId === "") {
    echo json_encode([
        "success" => false,
        "error" => "Missing reservation id"
    ]);
    exit;
}

/* ===== Update status ===== */
$sql = "
    UPDATE reservations
    SET status = 'completed'
    WHERE id = '$reservationId'
      AND status = 'pending'
";

$result = mysqli_query($connect, $sql);

if (!$result) {
    echo json_encode([
        "success" => false,
        "error" => mysqli_error($connect)
    ]);
    exit;
}

if (mysqli_affected_rows($connect) === 0) {
    echo json_encode([
        "success" => false,
        "error" => "Reservation not pending or not found"
    ]);
    exit;
}

echo json_encode([
    "success" => true
]);
exit;
