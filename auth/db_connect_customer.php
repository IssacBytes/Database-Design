<?php
$connect = mysqli_connect(
    "localhost",
    "customer_app",
    "1234",
    "beauty_salon"
);

if (!$connect) {
    die("DB Connection Error (customer): " . mysqli_connect_error());
}
?>
