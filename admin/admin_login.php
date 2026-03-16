<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

include("db_connect_admin.php");
$username = $_POST["username"] ?? "";
$password = $_POST["password"] ?? "";


$sql = "
    SELECT id, username 
    FROM admin_users
    WHERE username='$username' AND password='$password'
    LIMIT 1
";

$res = mysqli_query($connect, $sql);

if (!$res || mysqli_num_rows($res) === 0) {
    echo json_encode([
        "success" => false,
        "error" => "Invalid username or password."
    ]);
    exit;
}


$row = mysqli_fetch_assoc($res);
$_SESSION["admin_id"] = $row["id"];
$_SESSION["admin_name"] = $row["username"]; 

echo json_encode([
    "success" => true,
    "admin_id" => $row["id"],
    "admin_name" => $row["username"]
]);
exit;
?>
