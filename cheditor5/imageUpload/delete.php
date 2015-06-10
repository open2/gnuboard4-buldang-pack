<?php

require_once("config.php");
// ---------------------------------------------------------------------------
$filepath = $_POST["filepath"];
$r = false;

if (file_exists($filepath)) {
	$r = unlink($filepath);
	if ($r) {
		$thumbPath = dirname($filepath) . DIRECTORY_SEPARATOR . "thumb_" . basename($filepath);
		if (file_exists($thumbPath)) {
			unlink($thumbPath);
		}
	}
}

echo $r ? true : false;

?>
