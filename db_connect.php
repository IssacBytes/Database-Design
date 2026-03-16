<?php
$host = "localhost";
$user = "root";
$db = "beauty_salon";   // Changed from BeautySalon_DB to beauty_salon

$connect = mysqli_connect($host, $user, "", $db);

if (!$connect) {
    die(json_encode(["success"=>false, "error"=>"DB connect error"]));
}
?>
