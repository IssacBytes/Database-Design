<?php
ob_clean();
header("Content-Type: application/json; charset=utf-8");

error_reporting(E_ERROR | E_PARSE);
require("db_connect_customer.php");

$regName  = $_POST["regName"]  ?? "";
$regPwd   = $_POST["regPwd"]   ?? "";
$regEmail = $_POST["regEmail"] ?? "";
$regPhone = $_POST["regPhone"] ?? "";

if ($regName=="" || $regPwd=="" || $regEmail=="" || $regPhone=="") {
    echo json_encode(["success" => false, "error" => "Missing field"]);
    exit;
}

// New SQL: c_name → name
$sql_name = "SELECT * FROM customers WHERE name='$regName' LIMIT 1";
$res_name = mysqli_query($connect, $sql_name);

if (mysqli_num_rows($res_name) > 0) {
    echo json_encode(["success" => false, "error" => "Username already exists"]);
    exit;
}

// New SQL: email check
$sql_email = "SELECT * FROM customers WHERE email='$regEmail' LIMIT 1";
$res_email = mysqli_query($connect, $sql_email);

if (mysqli_num_rows($res_email) > 0) {
    echo json_encode(["success" => false, "error" => "Email already used"]);
    exit;
}

// The new table uses UUID as the primary key and no longer auto-increments.
$nextID = uniqid("", true);

// New SQL: INSERT INTO customers (id, name, password, email, phone)
$sql_insert = "
    INSERT INTO customers (id, name, password, email, phone)
    VALUES ('$nextID', '$regName', '$regPwd', '$regEmail', '$regPhone')
";

if (mysqli_query($connect, $sql_insert)) {
    echo json_encode([
        "success" => true,
        "message" => "Register success",
        "assigned_id" => $nextID
    ]);
} else {
    echo json_encode([
        "success" => false,
        "error" => mysqli_error($connect)
    ]);
}

mysqli_close($connect);
?>
