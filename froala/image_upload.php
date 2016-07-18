<?php
include_once("_common.php");

$ymd = date("ymd", time());

// Allowed extentions.
$allowedExts = array("gif", "jpeg", "jpg", "png");

// Get filename.
$temp = explode(".", get_safe_filename($_FILES["file"]["name"]));

// Get extension.
$extension = end($temp);

// An image check is being done in the editor but it is best to
// check that again on the server side.
// Do not use $_FILES["file"]["type"] as it can be easily forged.
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $_FILES["file"]["tmp_name"]);

if ((($mime == "image/gif")
    || ($mime == "image/jpeg")
    || ($mime == "image/pjpeg")
    || ($mime == "image/x-png")
    || ($mime == "image/png"))
    && in_array(strtolower($extension), $allowedExts)) {

    // 디렉토리별로 분리해서 넣는다 (매번 만드는게 더 빠름)
    @mkdir("$g4[data_path]/froala/$bo_table");
    @mkdir("$g4[data_path]/froala/$bo_table/$ymd");

    // Generate new random name.
    $name = $ymd . "_" . $bo_table . "_" . sha1(microtime()) . "." . strtolower($extension);
    $savefile = "$g4[data_path]/froala/$bo_table/$ymd/" . $name;

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
