<?php
require_once("config.php");

//----------------------------------------------------------------------------
//
//
$tempfile = $_FILES['file']['tmp_name'];
$filename = $_FILES['file']['name'];

$type = substr($filename, strrpos($filename, ".")+1);
$found = false;
switch ($type) {
	case "jpg":
	case "jpeg":
	case "gif":
	case "png":
		$found = true;
}

if ($found != true) {
	exit;
}

// 저장 파일 이름: 년월일시분초_렌덤문자8자
// 20140327125959_abcdefghi.jpg
// 원본 파일 이름: $_POST["origname"]
$savefile = SAVE_DIR . '/' . $filename;

move_uploaded_file($tempfile, $savefile);
$imgsize = getimagesize($savefile);
$filesize = filesize($savefile);

if (!$imgsize) {
	$filesize = 0;
	$random_name = '-ERR';
	unlink($savefile);
};

$rdata = sprintf('{"fileUrl": "%s/%s", "filePath": "%s", "fileName": "%s", "fileSize": "%d" }',
	SAVE_URL,
	$filename,
	$savefile,
	$filename,
	$filesize );

echo $rdata;
?>
