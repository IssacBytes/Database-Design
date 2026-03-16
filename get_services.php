<?php
header("Content-Type: application/json; charset=utf-8");

include("db_connect_customer.php");

// Only return active services
$sql = "
    SELECT 
        id, 
        name, 
        description, 
        basePrice, 
        durationMins 
    FROM services
    WHERE status = 'active'
";

$result = mysqli_query($connect, $sql);

if (!$result) {
    echo json_encode([
        "success" => false,
        "error" => "Service query failed."
    ]);
    exit;
}

$services = [];

while ($row = mysqli_fetch_assoc($result)) {
    $services[] = [
        "id"       => $row["id"],
        "name"     => $row["name"],
        "description" => $row["description"],
        "price"    => $row["basePrice"],
        "duration" => $row["durationMins"]
    ];
}

echo json_encode([
    "success"  => true,
    "services" => $services
]);
exit;
?>
