<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json; charset=utf-8");

include("db_connect_admin.php");

/* Exactly the same as the customer / barber */
if (!isset($_SESSION["admin_id"])) {
    echo json_encode([
        "success" => false,
        "error" => "Admin not logged in"
    ]);
    exit;
}

$adminId = $_SESSION["admin_id"];

$sql = "
    SELECT id, username
    FROM admin_users
    WHERE id = '$adminId'
    LIMIT 1
";

$res = mysqli_query($connect, $sql);

if (!$res || mysqli_num_rows($res) === 0) {
    echo json_encode([
        "success" => false,
        "error" => "Admin not found"
    ]);
    exit;
}

$row = mysqli_fetch_assoc($res);

echo json_encode([
    "success" => true,
    "admin" => [
        "id"   => $row["id"],
        "name" => $row["username"]
    ]
]);

exit;
