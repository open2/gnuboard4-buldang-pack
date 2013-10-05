<?
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

$begin_time = get_microtime();

if (!$g4['title'])
    $g4['title'] = $config['cf_title'];

// 쪽지를 받았나?
if (trim($member['mb_memo_call'])) {
    $mb_memo_nick = check_memo_call();
    if ($mb_memo_nick !== "")
        alert($mb_memo_nick."님으로부터 쪽지가 전달되었습니다.", $_SERVER[REQUEST_URI]);
}

// 현재 접속자
// 게시판 제목에 ' 포함되면 오류 발생
$lo_location = addslashes($g4['title']);
if (!$lo_location)
    $lo_location = $_SERVER['REQUEST_URI'];
$lo_url = $_SERVER['REQUEST_URI'];
if (strstr($lo_url, "/$g4[admin]/") || $is_admin == "super") $lo_url = "";

// sms4 적용을 위한 설정
if ($is_admin || ($config[cf_sms4_member] && $member[mb_level] >= $config[cf_sms4_level])) {
    $g4_sms4 = "1";
} else
    $g4_sms4 = "";

header("Content-Type: text/html; charset=$g4[charset]");
$gmnow = gmdate("D, d M Y H:i:s") . " GMT";
header("Expires: 0"); // rfc2616 - Section 14.21
header("Last-Modified: " . $gmnow);
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: pre-check=0, post-check=0, max-age=0"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
?>
<!DOCTYPE HTML>
<html lang="ko">
<head>
<meta http-equiv="content-type" content="text/html; charset=<?=$g4['charset']?>">
<? if ($config['cf_meta_author']) { ?><meta name="author" content="<?=$config['cf_meta_author']?>"><? } ?>
<?
// 비회원일때만 SEO를 생성합니다. 회원들은 이미 들어와 있는거라, 굳이 할 이유가 없겠죠?
if ($member['mb_id'] == "" && $g4['keyword_seo'])
    seo_keyword();
?>
<? if ($config['cf_meta_keywords']) { ?><meta name="keywords" content="<?=$config['cf_meta_keywords']?>"><? } ?>
<? if ($config['cf_meta_description']) { ?><meta name="description" content="<?=$config['cf_meta_description']?>"><? } ?>
<? if ($g4['ie_ua']) { ?><meta http-equiv="X-UA-Compatible" content="IE=<?=$g4[ie_ua]?>" /><? } ?>
<meta http-equiv="Imagetoolbar" content="no">

<title><?=$g4['title']?></title>

<link rel="stylesheet" href="<?=$g4['path']?>/style.css" type="text/css">
<? // canonical link by 말러83, http://sir.co.kr/bbs/board.php?bo_table=g4_tiptech&wr_id=20826
if(stristr($_SERVER[PHP_SELF], "/bbs/board.php") == true && $bo_table) {
    if ($wr_id)
        echo "<link rel=\"canonical\" href=\"$_SERVER[PHP_SELF]?bo_table=$bo_table&wr_id=$wr_id\" />";
    else
        echo "<link rel=\"canonical\" href=\"$_SERVER[PHP_SELF]?bo_table=$bo_table\" />";
}
?>
</head>

<script type="text/javascript">
// 자바스크립트에서 사용하는 전역변수 선언
var g4_path      = "<?=$g4['path']?>";
var g4_bbs       = "<?=$g4['bbs']?>";
var g4_bbs_img   = "<?=$g4['bbs_img']?>";
var g4_url       = "<?=$g4['url']?>";
var g4_is_member = "<?=$is_member?>";
var g4_is_admin  = "<?=$is_admin?>";
var g4_bo_table  = "<?=isset($bo_table)?$bo_table:'';?>";
var g4_sca       = "<?=isset($sca)?$sca:'';?>";
var g4_charset   = "<?=$g4['charset']?>";
var g4_cookie_domain = "<?=$g4['cookie_domain']?>";
var g4_is_gecko  = navigator.userAgent.toLowerCase().indexOf("gecko") != -1;
var g4_is_ie     = navigator.userAgent.toLowerCase().indexOf("msie") != -1;
var g4_sms4      = "<?=$g4_sms4?>";
var bitly_id     = '<?=$g4[bitly_id]?>';
var bitly_key    = '<?=$g4[bitly_key]?>';
<? if ($is_admin) { echo "var g4_admin = '{$g4['admin']}';"; } ?>
</script>
<script type="text/javascript" src="<?=$g4['path']?>/js/common.js"></script>
<script type="text/javascript" src="<?=$g4['path']?>/js/b4.common.js"></script>
<script type="text/javascript" src="<?=$g4['path']?>/js/jquery.js"></script>

<? if ($is_test || $is_admin || ($member['mb_id'] && $write['mb_id'] && $member['mb_id'] == $write['mb_id'])) {} else { ?>
<script type="text/javascript">
<!-- http://blueb.co.kr/bbs.php?table=JS_15&query=view&uid=10
var clickmessage="그림에는 오른쪽마우스버튼을 사용할 수 없습니다."
function disableclick(e) {
    if (document.all) {
    if (event.button==2||event.button==3) {
    if (event.srcElement.tagName=="IMG"){
        alert(clickmessage);
        return false;
        }
    }
}
    else if (document.layers) {
    if (e.which == 3) {
        alert(clickmessage);
        return false;
    }
}
    else if (document.getElementById){
    if (e.which==3&&e.target.tagName=="IMG"){
        alert(clickmessage)
        return false
        }
    }
}
function associateimages(){
    for(i=0;i<document.images.length;i++)
        document.images[i].onmousedown=disableclick;
}
    if (document.all)
        document.onmousedown=disableclick
    else if (document.getElementById)
        document.onmouseup=disableclick
    else if (document.layers)
        associateimages()
// -->
</script>
<? } ?>

<script type="text/javascript"> 
<!-- F5키를 금지하기, http://phpschool.com/gnuboard4/bbs/board.php?bo_table=tipntech&wr_id=68565
document.onkeydown = function(e) { 
  var evtK = (e) ? e.which : window.event.keyCode; 
  var isCtrl = ((typeof isCtrl != 'undefined' && isCtrl) || ((e && evtK == 17) || (!e && event.ctrlKey))) ? true : false; 

  if ((isCtrl && evtK == 82) || evtK == 116) { 
    if (e) { evtK = 505; } else { event.keyCode = evtK = 505; } 
  } 
  if (evtK == 505) { 
    return false; 
  } 
}
//-->
</script> 

<?
//sms4 적용여부를 설정 (관리자 또는 회원간 sms보내기가 허용될 때)
if ($is_admin || ($config[cf_sms4_member] && $member[mb_level] >= $config[cf_sms4_level])) {
    include_once("$g4[path]/lib/sms.lib.php");
}
?>
<body>
<a name="g4_head"></a>
<? if (!$cb_id and !stristr($_SERVER[REQUEST_URI],'club_')) { ?>
<!-- 디자인 코드 code --->
<? } ?>