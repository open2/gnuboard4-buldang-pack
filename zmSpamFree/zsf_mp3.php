<?php
include_once("_common.php");
include_once("$g4[path]/zmSpamFree/zsfCfg.php");

function make_mp3()
{
    global $config, $g4, $zsfCfg;

    $number = $_SESSION['captcha_keystringQ'];

    // 읽어줄 것이 없다면 return 한다
    if ($number == "") return;

    $mp3s = array();
    for($i=0;$i<strlen($number);$i++){
        $file = $g4[path] .'/zmSpamFree/mp3/' . $zsfCfg[mp3] .'/'.strtolower($number[$i]).'.mp3';
        $mp3s[] = $file;
    }

    $contents = '';
    foreach ($mp3s as $mp3) {
        $contents .= file_get_contents($mp3);
    }

    // open a temporary file handle in memory
    $tmp_handle = fopen('php://temp', 'r+');
    fwrite($tmp_handle, $contents);

    return $tmp_handle;
}

echo make_mp3();
?>