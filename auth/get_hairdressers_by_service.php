<?php
include("db_connect_customer.php");

$serviceId = $_GET["serviceId"];

// 1) Get the list of barber IDs that provide this service
$sql_offer = "
    SELECT hairdresserId 
    FROM offer 
    WHERE serviceId = '$serviceId'
";
$result_offer = mysqli_query($connect, $sql_offer);

$hdIds = [];
while ($row = mysqli_fetch_assoc($result_offer)) {
    $hdIds[] = $row["hairdresserId"];
}

// If there is no barber providing this service
if (count($hdIds) === 0) {
    echo json_encode([
        "success" => true,
        "hairdressers" => []
    ]);
    exit;
}

$idList = "'" . implode("','", $hdIds) . "'";

// 2) Query the basic information of these barbers (only display active ones)
$sql_hd = "
    SELECT id, name, specialty, rating 
    FROM hairdressers
    WHERE id IN ($idList)
      AND status = 'active'
";
$result_hd = mysqli_query($connect, $sql_hd);

$hairdressers = [];
while ($row = mysqli_fetch_assoc($result_hd)) {
    $hairdressers[$row["id"]] = [
        "id"       => $row["id"],
        "name"     => $row["name"],
        "specialty"=> $row["specialty"],
        "rating"   => $row["rating"],
        "schedule" => []
    ];
}

// If the number of active barbers is 0 (an important safeguard)
if (count($hairdressers) === 0) {
    echo json_encode([
        "success" => true,
        "hairdressers" => []
    ]);
    exit;
}

$activeIds = "'" . implode("','", array_keys($hairdressers)) . "'";

// 3) Check the corresponding barber's schedule
$sql_sc = "
    SELECT h_id, weekday, work_time
    FROM hairdresser_schedule
    WHERE h_id IN ($activeIds)
    ORDER BY h_id, weekday
";
$result_sc = mysqli_query($connect, $sql_sc);

while ($row = mysqli_fetch_assoc($result_sc)) {
    $id = $row["h_id"];
    if (isset($hairdressers[$id])) {
        $hairdressers[$id]["schedule"][] = [
            "weekday"   => $row["weekday"],
            "work_time" => $row["work_time"]
        ];
    }
}

echo json_encode([
    "success" => true,
    "hairdressers" => array_values($hairdressers)
]);
exit;
?>
