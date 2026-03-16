<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

include("admin_check_session.php");  // Ensure session is valid
include("db_connect_admin.php");

$sql = "
    SELECT
        h.id,
        h.name,
        h.rating,
        COUNT(r.id) AS reservationCount,
        COALESCE(SUM(r.amount), 0) AS totalIncome
    FROM hairdressers h
    LEFT JOIN reservations r
        ON r.hairdresserId = h.id
        AND r.status = 'paid'
    GROUP BY h.id, h.name, h.rating
";

$res = mysqli_query($connect, $sql);

$hairdressers = [];
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $hairdressers[] = [
            "id" => $row["id"],
            "name" => $row["name"],
            "rating" => $row["rating"] ?? "N/A",  // Default to "N/A" if no rating
            "reservationCount" => $row["reservationCount"],
            "totalIncome" => $row["totalIncome"]
        ];
    }
}

echo json_encode([
    "success" => true,
    "hairdressers" => $hairdressers
]);
exit;
?>
