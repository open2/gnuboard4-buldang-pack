<?php
include_once("_common.php");

// Get src.
$src = $_POST["src"];

// Check if file exists.
if (file_exists($src)) {
    // Delete file.
    unlink($src);
}
?>