<?php
header("Content-Type: application/json; charset=utf-8");

include("db_connect_customer.php");

$sql = "
    SELECT
        id,
        name,
        specialty,
        rating
    FROM hairdressers
    WHERE status = 'active'
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

$list = [];

while ($row = mysqli_fetch_assoc($res)) {
    $list[] = [
        "id"        => $row["id"],
        "name"      => $row["name"],
        "specialty" => $row["specialty"],
        "rating"    => $row["rating"]
    ];
}

echo json_encode([
    "success"     => true,
    "hairdressers"=> $list
]);
exit;
?>
