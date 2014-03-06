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

header("Content-Type: text/html; charset=$g4[charset]");
$gmnow = gmdate("D, d M Y H:i:s") . " GMT";
header("Expires: 0"); // rfc2616 - Section 14.21
header("Last-Modified: " . $gmnow);
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: pre-check=0, post-check=0, max-age=0"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
header("X-Content-Type-Options: nosniff");
?>
<!DOCTYPE HTML>
<html lang="ko">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1">
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

<link rel="stylesheet" href="<?=$g4['path']?>/js/bootstrap/css/bootstrap.min.css?bver=<?=$g4[bver]?>" type="text/css" media="screen" title="no title" charset="<?=$g4[charset]?>">
<link rel="stylesheet" href="<?=$g4['path']?>/js/font-awesome/css/font-awesome.min.css?aver=<?=$g4[aver]?>" type="text/css" media="screen" title="no title" charset="<?=$g4[charset]?>">
<!--[if lt IE 7]>
    <script src="<?=$g4['path']?>/js/font-awesome/css/font-awesome-ie7.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?=$g4['path']?>/style.css?sver=<?=$g4[sver]?>" type="text/css">

<? // canonical link by 말러83, http://sir.co.kr/bbs/board.php?bo_table=g4_tiptech&wr_id=20826
if(stristr($_SERVER[PHP_SELF], "/bbs/board.php") == true && $bo_table) {
    if ($wr_id)
        echo "<link rel=\"canonical\" href=\"$_SERVER[PHP_SELF]?bo_table=$bo_table&wr_id=$wr_id\" />";
    else
        echo "<link rel=\"canonical\" href=\"$_SERVER[PHP_SELF]?bo_table=$bo_table\" />";
}
?>
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="<?=$g4['path']?>/js/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=$g4['path']?>/js/hammer/jquery.hammer-full.min.js"></script>

<!--[if lt IE 9]>
    <script src="<?=$g4['path']?>/js/html5shiv/html5shiv.js"></script>
    <script src="<?=$g4['path']?>/js/respond/respond.min.js"></script>
<![endif]-->

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
<? if ($is_admin) { echo "var g4_admin = \"{$g4['admin']}\";"; } ?>
</script>
<script type="text/javascript" src="<?=$g4['path']?>/js/common.js"></script>
<script type="text/javascript" src="<?=$g4['path']?>/js/b4.common.js"></script>
<? if ($is_test || $is_admin || ($member['mb_id'] && $write['mb_id'] && $member['mb_id'] == $write['mb_id'])) {} else { ?>
<script type="text/javascript" src="<?=$g4['path']?>/js/desktop.js"></script>
<? } ?>
</head>

<body>
<a class="sr-only" href="#content">Skip navigation</a>
<a name="g4_head"></a>
<div id="desktopTest_md_lg" class="visible-md visible-lg"></div>