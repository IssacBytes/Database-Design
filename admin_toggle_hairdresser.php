<?php
include("session_guard_admin.php");
include("db_connect_admin.php");

$hairdresserId = $_POST["id"] ?? $_POST["hairdresserId"] ?? null;

if (!$hairdresserId) {
    echo json_encode([
        "success" => false,
        "error" => "Missing hairdresserId"
    ]);
    exit;
}

// 1) Read the current state
$sql_check = "
    SELECT status
    FROM hairdressers
    WHERE id = '$hairdresserId'
";
$res_check = mysqli_query($connect, $sql_check);

if (!$res_check || mysqli_num_rows($res_check) === 0) {
    echo json_encode([
        "success" => false,
        "error" => "Hairdresser not found"
    ]);
    exit;
}

$row = mysqli_fetch_assoc($res_check);
$currentStatus = $row["status"];

// 2) switch
$newStatus = ($currentStatus === "active") ? "inactive" : "active";

// 3) Update
$sql_update = "
    UPDATE hairdressers
    SET status = '$newStatus'
    WHERE id = '$hairdresserId'
";
mysqli_query($connect, $sql_update);

// 4) return
echo json_encode([
    "success" => true,
    "newStatus" => $newStatus
]);
exit;
