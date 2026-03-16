<?php
$connect = mysqli_connect(
    "localhost",
    "admin_app",
    "1234",
    "beauty_salon"
);




if (!$connect) {
    die("DB Connection Error (admin): " . mysqli_connect_error());
}
?>
