<?php
session_start();

header("Content-Type: application/json; charset=utf-8");
error_reporting(E_ERROR | E_PARSE);

require("db_connect_staff.php");

$regname  = $_POST["hdId"]  ?? "";
$regPwd = $_POST["hdPwd"] ?? "";

// modify SQL：Hairdresser → hairdressers；h_id → id；h_name → name
$sql_check = "SELECT * FROM hairdressers WHERE name='$regname' AND password='$regPwd' LIMIT 1";
$res_check = mysqli_query($connect, $sql_check);

if (mysqli_num_rows($res_check) == 0) {
    echo json_encode([
        "success" => false,
        "error"   => "Invalid hairdresser ID or password"
    ]);
    exit;
}

$row = mysqli_fetch_assoc($res_check);

$h_name = $row["name"];
$h_id   = $row["id"];

if ($h_id == 1) {
    $redirect = "hairdresser_home.html";
} else {
    $redirect = "hairdresser_home.html";
}

$_SESSION["h_id"]   = $h_id;
$_SESSION["h_name"] = $h_name;

echo json_encode([
    "success"  => true,
    "h_name"   => $h_name,
    "h_id"     => $h_id,
    "redirect" => $redirect
]);

exit;
?>
