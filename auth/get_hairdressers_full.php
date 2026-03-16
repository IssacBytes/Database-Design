<?php
include("db_connect_customer.php");

// Inquire about the basic information of the barber
$sql_hd = "SELECT id, name, specialty, rating FROM hairdressers";
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

// Check the schedule
$sql_sc = "
    SELECT h_id, weekday, work_time
    FROM hairdresser_schedule
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
