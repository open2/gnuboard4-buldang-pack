<?
include_once("./_common.php");

$g4[title] = "$config[cf_title] - MyOn";

$head = (int) $head;
$rows = (int) $rows;

if ($member[mb_id]) 
    ;
else 
    alert("MyOn은 회원을 위한 서비스 입니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.", "./login.php?url=".urlencode("myon.php?head=$head"));

if ($head)
    include_once("./_head.php");
else
    include_once("../head.sub.php");

// 스킨을 $_GET으로 값을 넘겨준다
$myon_skin = strip_tags($myon_skin);
if ($myon_skin)
    $skin = $myon_skin;
else
    $skin = "basic";

if ($rows > 0) {
    if ($rows > 50)
        $rows = 50;
}
else
    $rows = 20;

/* 불당팩 1.2에서 모두 함수로 이동했습니다 */

$myon_skin_path = "$g4[path]/skin/myon/$skin";

include_once("$myon_skin_path/myon.skin.php");

if ($head)
    include_once("./_tail.php");
else
    include_once("../tail.sub.php");
?>
