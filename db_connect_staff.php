<?php
$connect = mysqli_connect(
    "localhost",
    "hairdresser_app",
    "1234",
    "beauty_salon"
);

if (!$connect) {
    die("DB Connection Error (staff): " . mysqli_connect_error());
}
?>
