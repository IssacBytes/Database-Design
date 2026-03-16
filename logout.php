<?php
ob_clean();   

session_unset();     
session_destroy();   


echo json_encode([
    "success" => true,
    "message" => "Session ended. Logout successful."
]);
exit;
?>
