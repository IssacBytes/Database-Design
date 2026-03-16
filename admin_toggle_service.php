<?php
header("Content-Type: application/json; charset=utf-8");

include("session_guard_admin.php");
include("db_connect_admin.php");

// 1) Get the service id (compatible writing to prevent inconsistencies in front-end variable names)
$serviceId = $_POST["id"] ?? $_POST["serviceId"] ?? null;

if (!$serviceId) {
    echo json_encode([
        "success" => false,
        "error"   => "Missing serviceId"
    ]);
    exit;
}

// 2) Query the current status
$sql_check = "
    SELECT status
    FROM services
    WHERE id = '$serviceId'
";
$res_check = mysqli_query($connect, $sql_check);

if (!$res_check || mysqli_num_rows($res_check) === 0) {
    echo json_encode([
        "success" => false,
        "error"   => "Service not found"
    ]);
    exit;
}

$row = mysqli_fetch_assoc($res_check);
$currentStatus = $row["status"];

// 3) Switch state
$newStatus = ($currentStatus === "active") ? "inactive" : "active";

// 4) Update the database
$sql_update = "
    UPDATE services
    SET status = '$newStatus'
    WHERE id = '$serviceId'
";
$res_update = mysqli_query($connect, $sql_update);

if (!$res_update) {
    echo json_encode([
        "success" => false,
        "error"   => mysqli_error($connect)
    ]);
    exit;
}

// 5) Return result
echo json_encode([
    "success"   => true,
    "newStatus" => $newStatus
]);
exit;
