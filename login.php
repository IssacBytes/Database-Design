<?php
session_start();

header("Content-Type: application/json; charset=utf-8");
error_reporting(E_ERROR | E_PARSE);
require("db_connect_customer.php");

$regName  = $_POST["loginName"]  ?? "";
$regPwd   = $_POST["loginPwd"]   ?? "";

$sql_check = "SELECT * FROM customers WHERE name='$regName' AND password='$regPwd' LIMIT 1";
$res_check = mysqli_query($connect, $sql_check);

if (mysqli_num_rows($res_check) == 0) {
    echo json_encode(["success" => false, "error" => "Invalid username or password"]);
    exit;
} else {
    $row = mysqli_fetch_assoc($res_check);

    $_SESSION["c_id"]   = $row["id"];
    $_SESSION["c_name"] = $row["name"];

    echo json_encode([
        "success" => true,
        "c_name" => $row["name"]
    ]);
    exit;
}
?>
