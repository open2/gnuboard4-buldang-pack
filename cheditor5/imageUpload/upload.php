<?php
require_once("config.php");

//----------------------------------------------------------------------------
//
//
$ymd = date("ymd", time());

$filename = null;
$savefile = null;
$filesize = 0;

if (isset($_POST["filehtml5"])) {
	//$filename = $_POST["randomname"];
	$filename = $ymd . "_" .  md5($_SERVER['REMOTE_ADDR']) . '_' . $_POST["randomname"];
	$savefile = SAVE_DIR . '/' . $filename;
	$fh = fopen($savefile, "w");
	fwrite($fh, base64_decode($_POST["filehtml5"]));
	fclose($fh);
}
else {
	$tempfile = $_FILES['file']['tmp_name'];
	$filename = $_FILES['file']['name'];

	$type = substr($filename, strrpos($filename, "."));
	$found = false;
	switch ($type) {
	case ".jpg":
	case ".jpeg":
	case ".gif":
	case ".png":
		$found = true;
	}

	if ($found != true) {
		exit;
	}

	//$filename = $_POST["randomname"];
	$filename = $ymd . "_" .  md5($_SERVER['REMOTE_ADDR']) . '_' . $_POST["randomname"];
	$savefile = SAVE_DIR . '/' . $filename;

	move_uploaded_file($tempfile, $savefile);
	$imgsize = getimagesize($savefile);
	
	if (!$imgsize) {
		$filesize = 0;
		$filename = '-ERR';
		unlink($savefile);
	}
}

// 올라간 파일의 퍼미션을 변경합니다.
chmod($savefile, 0606);

// 저장 파일 이름: 년월일시분초_렌덤문자8자
// 20140327125959_abcdefghi.jpg
// 원본 파일 이름: $_POST["origname"]
$filesize = filesize($savefile);

$rdata = sprintf('{"fileUrl": "%s/%s", "filePath": "%s", "fileName": "%s", "fileSize": "%d" }',
	SAVE_URL,
	$filename,
	$savefile,
	$filename,
	$filesize );

echo $rdata;

// 불당팩 - 올라가는 모든 image 파일을 체크, 게시판에 올라가는거만 처리.
if ($bo_table !== "") {
    $bc_url = SAVE_URL . "/" . $filename;
    $sql = " insert into $g4[board_cheditor_table]
            SET
                mb_id = '$member[mb_id]',
                bc_dir = '" . SAVE_DIR . "',
                bc_file = '$filename',
                bc_url = '$bc_url',
                bc_filesize = '" . filesize2bytes($filesize)/1024 . "',
                bc_source = '" . mysql_real_escape_string($_POST['origname']) . "',
                bc_ip = '$remote_addr',
                bc_datetime = '$g4[time_ymdhis]',
                bo_table = '$bo_table'
        ";
    sql_query($sql);
}
?>
