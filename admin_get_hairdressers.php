<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

include("db_connect_admin.php");


if (!isset($_SESSION["admin_id"])) {
    echo json_encode([
        "success" => false,
        "error"   => "Admin not logged in"
    ]);
    exit;
}


$sql = "
    SELECT
        id,
        name,
        specialty,
        rating,
        COALESCE(status, 'active') AS status
    FROM hairdressers
    ORDER BY createdAt DESC
";

$res = mysqli_query($connect, $sql);

if (!$res) {
    echo json_encode([
        "success" => false,
        "error"   => mysqli_error($connect)
    ]);
    exit;
}


$hairdressers = [];

while ($row = mysqli_fetch_assoc($res)) {
    $hairdressers[] = [
        "id"        => $row["id"],
        "name"      => $row["name"],
        "specialty" => $row["specialty"],
        "rating"    => $row["rating"],
        "status"    => $row["status"]
    ];
}


echo json_encode([
    "success"      => true,
    "hairdressers" => $hairdressers
]);

exit;
