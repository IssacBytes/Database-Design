<?php
header("Content-Type: application/json; charset=utf-8");

include("admin_check_session.php");
include("db_connect_admin.php");

$sql = "
    SELECT
        id,
        name,
        description,
        basePrice,
        durationMins,
        status
    FROM services
    ORDER BY name
";

$res = mysqli_query($connect, $sql);

if (!$res) {
    echo json_encode([
        "success" => false,
        "error"   => mysqli_error($connect)
    ]);
    exit;
}

$services = [];

while ($row = mysqli_fetch_assoc($res)) {
    $services[] = [
        "id"           => $row["id"],
        "name"         => $row["name"],
        "description"  => $row["description"],
        "basePrice"    => $row["basePrice"],
        "durationMins" => $row["durationMins"],
        "status"       => $row["status"]
    ];
}

echo json_encode([
    "success"  => true,
    "services" => $services
]);
exit;
