<?php
session_start();

if(!isset($_SESSION["c_id"])){
    echo json_encode(["success" => false, "error" => "Customer session expired"]);
    exit;
}
else{
    echo json_encode([
        "success" => true,
        "c_id" => $_SESSION["c_id"],
        "c_name" => $_SESSION["c_name"]
    ]);
    exit;
}
?>