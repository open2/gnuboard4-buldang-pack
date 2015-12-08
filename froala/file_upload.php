<?php
include_once("_common.php");

$ymd = date("ymd", time());

// Allowed extentions.
$allowedExts = array("txt", "pdf", "doc", "xls", "hwp", "ppt");

// Get filename.
$temp = explode(".", get_safe_filename($_FILES["file"]["name"]));

// Get extension.
$extension = end($temp);

// Validate uploaded files.
// Do not use $_FILES["file"]["type"] as it can be easily forged.
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $_FILES["file"]["tmp_name"]);

if ((($mime == "text/plain")
    || ($mime == "application/pdf")
    || ($mime == "application/x-pdf")
    || ($mime == "application/msword")
    || ($mime == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")
    || ($mime == "application/vnd.ms-excel")
    || ($mime == "application/vnd.ms-powerpoint")
    || ($mime == "application/vnd.openxmlformats-officedocument.presentationml.presentation")
    || ($mime == "application/haansofthwp")
    || ($mime == "application/x-hwp")
    || ($mime == "application/vnd.hancom.hwp"))
    && in_array(strtolower($extension), $allowedExts)) {

    // 디렉토리별로 분리해서 넣는다 (매번 만드는게 더 빠름)
    @mkdir("$g4[data_path]/froala_file/$bo_table");
    @mkdir("$g4[data_path]/froala_file/$bo_table/$ymd");

    // Generate new random name.
    $name = $ymd . "_" . $bo_table . "_" . sha1(microtime()) . "." . strtolower($extension);
    $savefile = "$g4[data_path]/froala_file/$bo_table/$ymd/" . $name;

    // Save file in the uploads folder.
    move_uploaded_file($_FILES["file"]["tmp_name"], $savefile);

    // 올라간 파일의 퍼미션을 변경합니다.
    chmod($savefile, 0606);

    // Generate response.
    $response = new StdClass;
    $response->link = $savefile;
    echo stripslashes(json_encode($response));
}
?>