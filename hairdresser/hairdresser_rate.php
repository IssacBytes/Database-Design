<?php
header("Content-Type: application/json; charset=utf-8");

include("session_guard_customer.php");
include("db_connect_admin.php");

$customerId     = $_SESSION["c_id"] ?? "";
$reservationId  = $_POST["reservationId"] ?? "";
$hairdresserId  = $_POST["hairdresserId"] ?? "";
$rating         = $_POST["rating"] ?? "";

if ($customerId === "" || $reservationId === "" || $hairdresserId === "" || $rating === "") {
    echo json_encode([
        "success" => false,
        "error"   => "Missing required fields"
    ]);
    exit;
}

$rating = floatval($rating);
if ($rating < 0 || $rating > 5) {
    echo json_encode([
        "success" => false,
        "error"   => "Rating must be between 0 and 5"
    ]);
    exit;
}

/* =============================
   Verify the validity of the order
   ============================= */
$sqlCheck = "
    SELECT rated
    FROM reservations
    WHERE id = '$reservationId'
      AND customerId = '$customerId'
      AND hairdresserId = '$hairdresserId'
      AND status = 'paid'
    LIMIT 1
";
$resCheck = mysqli_query($connect, $sqlCheck);

if (!$resCheck || mysqli_num_rows($resCheck) === 0) {
    echo json_encode([
        "success" => false,
        "error"   => "Invalid reservation"
    ]);
    exit;
}

$row = mysqli_fetch_assoc($resCheck);
if ($row["rated"] == 1) {
    echo json_encode([
        "success" => false,
        "error"   => "This reservation has already been rated"
    ]);
    exit;
}

/* =============================
   Read the old rating
   ============================= */
$sqlOldRating = "
    SELECT rating
    FROM hairdressers
    WHERE id = '$hairdresserId'
    LIMIT 1
";
$resOld = mysqli_query($connect, $sqlOldRating);

$oldRating = 0;
if ($resOld && mysqli_num_rows($resOld) > 0) {
    $r = mysqli_fetch_assoc($resOld);
    if ($r["rating"] !== null) {
        $oldRating = floatval($r["rating"]);
    }
}

/* =============================
   Count the number of times ratings have been given
   ============================= */
$sqlCount = "
    SELECT COUNT(*) AS cnt
    FROM reservations
    WHERE hairdresserId = '$hairdresserId'
      AND rated = 1
";
$resCount = mysqli_query($connect, $sqlCount);
$rowCount = mysqli_fetch_assoc($resCount);
$cnt = intval($rowCount["cnt"]);

if ($cnt == 0 && $oldRating > 0) {
    $cnt = 1;
}

/* =============================
   Calculate the new average rating
   ============================= */
$newRating = round(
    ($oldRating * $cnt + $rating) / ($cnt + 1),
    2
);

/* =============================
   Update the barber's rating
   ============================= */
$sqlUpdateHairdresser = "
    UPDATE hairdressers
    SET rating = '$newRating'
    WHERE id = '$hairdresserId'
";
if (!mysqli_query($connect, $sqlUpdateHairdresser)) {
    echo json_encode([
        "success" => false,
        "error"   => "Failed to update hairdresser rating"
    ]);
    exit;
}

/* =============================
   Mark the order as rated
   ============================= */
$sqlUpdateReservation = "
    UPDATE reservations
    SET rated = 1
    WHERE id = '$reservationId'
";
mysqli_query($connect, $sqlUpdateReservation);

echo json_encode([
    "success"    => true,
    "newRating" => $newRating
]);
exit;
?>
