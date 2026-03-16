<?php
include("session_guard_customer.php"); // Ensure customer session exists
include("db_connect_customer.php");    // Database connection

// Get logged-in customer ID from session
$customerId = $_SESSION["c_id"];

// Read POST values
$serviceId     = $_POST["serviceId"] ?? "";
$hairdresserId = $_POST["hairdresserId"] ?? "";
$timePeriod    = $_POST["timePeriod"] ?? "";

// Check required fields
if ($serviceId == "" || $hairdresserId == "" || $timePeriod == "") {
    echo json_encode([
        "success" => false,
        "error" => "Missing required fields."
    ]);
    exit;
}

/* =========================
   Backend Defensive Checks
========================= */

// 1) Check service is active
$sql_service = "
    SELECT id
    FROM services
    WHERE id = '$serviceId'
      AND status = 'active'
";
$res_service = mysqli_query($connect, $sql_service);

if (!$res_service || mysqli_num_rows($res_service) === 0) {
    echo json_encode([
        "success" => false,
        "error" => "Service is not available."
    ]);
    exit;
}

// 2) Check hairdresser is active
$sql_hd = "
    SELECT id
    FROM hairdressers
    WHERE id = '$hairdresserId'
      AND status = 'active'
";
$res_hd = mysqli_query($connect, $sql_hd);

if (!$res_hd || mysqli_num_rows($res_hd) === 0) {
    echo json_encode([
        "success" => false,
        "error" => "Hairdresser is not available."
    ]);
    exit;
}

// 3) Check hairdresser offers this service
$sql_offer = "
    SELECT offer_id
    FROM offer
    WHERE hairdresserId = '$hairdresserId'
      AND serviceId = '$serviceId'
";
$res_offer = mysqli_query($connect, $sql_offer);

if (!$res_offer || mysqli_num_rows($res_offer) === 0) {
    echo json_encode([
        "success" => false,
        "error" => "Selected hairdresser does not provide this service."
    ]);
    exit;
}

/* =========================
   Create Reservation
========================= */

// Generate unique reservation ID
$reservationId = uniqid();

// Insert reservation record
$sql = "
    INSERT INTO reservations (id, customerId, hairdresserId, serviceId, timePeriod, status)
    VALUES ('$reservationId', '$customerId', '$hairdresserId', '$serviceId', '$timePeriod', 'pending')
";

if (!mysqli_query($connect, $sql)) {
    echo json_encode([
        "success" => false,
        "error" => mysqli_error($connect)
    ]);
    exit;
}

// Return success
echo json_encode([
    "success" => true,
    "reservationId" => $reservationId
]);
exit;
?>
