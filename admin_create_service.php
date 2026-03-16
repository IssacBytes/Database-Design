<?php
// ===============================
// Admin Create Service API
// ===============================

// 1. Session check (admin)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["admin_id"])) {
    echo json_encode([
        "success" => false,
        "error" => "Admin not logged in"
    ]);
    exit;
}

// 2. DB connection (admin)
include("db_connect_admin.php");

// 3. Read POST data
$name         = trim($_POST["name"] ?? "");
$description  = trim($_POST["description"] ?? "");
$basePrice    = $_POST["basePrice"] ?? "";
$durationMins = $_POST["durationMins"] ?? "";

// 4. Backend validation
if ($name === "" || !is_numeric($basePrice) || !is_numeric($durationMins)) {
    echo json_encode([
        "success" => false,
        "error" => "Invalid input data"
    ]);
    exit;
}

if ($basePrice <= 0 || $durationMins <= 0) {
    echo json_encode([
        "success" => false,
        "error" => "Price and duration must be positive"
    ]);
    exit;
}

// 5. Create service
$serviceId = uniqid();

$sql = "
    INSERT INTO services (
        id,
        name,
        description,
        basePrice,
        durationMins,
        status
    ) VALUES (
        '$serviceId',
        '$name',
        " . ($description === "" ? "NULL" : "'$description'") . ",
        '$basePrice',
        '$durationMins',
        'active'
    )
";

if (!mysqli_query($connect, $sql)) {
    echo json_encode([
        "success" => false,
        "error" => mysqli_error($connect)
    ]);
    exit;
}

// 6. Success response
echo json_encode([
    "success" => true,
    "serviceId" => $serviceId
]);
exit;
