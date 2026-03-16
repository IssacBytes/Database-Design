<?php
session_start();
//use for included files that need to check if hairdresser is logged in.
if (!isset($_SESSION["h_id"])) {
    http_response_code(403);
    exit("Not logged in");
}

return;
?>
