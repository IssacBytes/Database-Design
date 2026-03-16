<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json; charset=utf-8");

include("db_connect_admin.php");

/* Administrator session detection (consistent with your other admin interfaces) */
if (!isset($_SESSION["admin_id"])) {
    echo json_encode([
        "success" => false,
        "error" => "Admin not logged in"
    ]);
    exit;
}

$reservationId = $_POST["id"] ?? "";

if ($reservationId === "") {
    echo json_encode([
        "success" => false,
        "error" => "Missing reservation id"
    ]);
    exit;
}

/* Only pending → completed is allowed */
$sql = "
    UPDATE reservations
    SET status = 'completed'
    WHERE id = '$reservationId'
      AND status = 'pending'
";

$res = mysqli_query($connect, $sql);

if (!$res || mysqli_affected_rows($connect) === 0) {
    echo json_encode([
        "success" => false,
        "error" => "Update failed or status not pending"
    ]);
    exit;
}

echo json_encode([
    "success" => true
]);
exit;
