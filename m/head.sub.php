<?php
if ( ! defined("_GNUBOARD_")) {
    exit;
} // 개별 페이지 접근 불가

// 게시판에서 중복 include 예방
if (defined('_G4_HEAD')) {
    return;
} else {
    define('_G4_HEAD', 1);
}
header("Content-Type: text/html; charset=$g4[charset]");
$gmnow = gmdate("D, d M Y H:i:s") . " GMT";
header("Expires: 0"); // rfc2616 - Section 14.21
header("Last-Modified: " . $gmnow);
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: pre-check=0, post-check=0, max-age=0"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
header("X-Content-Type-Options: nosniff");
?><!doctype html>
<html lang="ko">
<head>
    <meta charset="<?= $g4['charset'] ?>">
    <? if ($config['cf_meta_author']) { ?>
        <meta name="author" content="<?= get_text(clean_xss_tags($config['cf_meta_author'])) ?>"><? } ?>
    <? if ($config['cf_meta_keywords']) { ?>
        <meta name="keywords" content="<?= get_text(clean_xss_tags($config['cf_meta_keywords'])) ?>"><? } ?>
    <? if ($config['cf_meta_description']) { ?>
        <meta name="description" content="<?= get_text(clean_xss_tags($config['cf_meta_description'])) ?>"><? } ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta name="apple-mobile-web-app-title" content="2CPU">

    <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico">

    <meta property="og:title" content="2CPU">
    <meta property="og:url" content="http://2cpu.co.kr/">
    <meta property="og:image" content="/img/2cpu_200_60.jpg">
    <meta property="og:description" content="<?= get_text(clean_xss_tags($config['cf_meta_description'])) ?>">

    <title><?= e($g4['title']) ?></title>
    <link rel="stylesheet" href="/js/bootstrap/css/bootstrap.min.css?v=<?= app_version() ?>">
    <link rel="stylesheet" href="/js/bootstrap/css/bootstrap-theme.min.css?v=<?= app_version() ?>">
    <link rel="stylesheet" href="/m/vendor/material-icons/material-icons.css?v=<?= app_version() ?>">
    <link rel="stylesheet" href="/js/font-awesome/css/font-awesome.min.css?v=<?= app_version() ?>">
    <link rel="stylesheet" href="/m/vendor/admin-lte/css/AdminLTE.min.css?v=<?= app_version() ?>">
    <link rel="stylesheet" href="/m/vendor/admin-lte/css/skins/skin-black-light.min.css?v=<?= app_version() ?>">
    <link rel="stylesheet" href="/m/vendor/slide-push-menus/menu.css?v=<?= app_version() ?>">
    <link rel="stylesheet" href="/m/css/mobile.css?v=<?= app_version() ?>">

    <script>
        var app_debug = Boolean(0);
        var app_version = '<?= app_version() ?>';
        var in_app = Boolean(<?= (int)in_app() ?>);
    </script>

    <?php if (in_app()) { ?>
        <meta http-equiv="Content-Security-Policy"
              content="default-src *; style-src * 'unsafe-inline'; script-src * 'unsafe-inline' 'unsafe-eval'; img-src * data:;">
        <script src="/m/vendor/android/cordova.js?v=<?= app_version() ?>"></script>
    <?php } ?>

    <script src="/js/jquery.min.js?v=<?= app_version() ?>"></script>
    <script src="/js/bootstrap/js/bootstrap.min.js?v=<?= app_version() ?>"></script>

    <script type="text/javascript">
        // 자바스크립트에서 사용하는 전역변수 선언
        var g4_path = "<?=$g4['path']?>";
        var g4_bbs = "<?=$g4['bbs']?>";
        var g4_bbs_img = "<?=$g4['bbs_img']?>";
        var g4_url = "<?=$g4['url']?>";
        var g4_is_member = "<?=$is_member?>";
        var g4_is_admin = "<?=$is_admin?>";
        var g4_bo_table = "<?=isset($bo_table) ? $bo_table : '';?>";
        var g4_sca = "<?=isset($sca) ? $sca : '';?>";
        var g4_charset = "<?=$g4['charset']?>";
        var g4_cookie_domain = "<?=$g4['cookie_domain']?>";
        var g4_is_gecko = navigator.userAgent.toLowerCase().indexOf("gecko") != -1;
        var g4_is_ie = navigator.userAgent.toLowerCase().indexOf("msie") != -1;
        <? if ($is_admin) {
            echo "var g4_admin = \"{$g4['admin']}\";";
        } ?>
    </script>
    <script type="text/javascript" src="<?= $g4['path'] ?>/js/common.js"></script>
    <script type="text/javascript" src="<?= $g4['path'] ?>/js/b4.common.js"></script>
</head>
<body>
<?php if (is_popover()) { ?>
    <header class="header-popover">
        <h1><?= e(app_title($g4['title'])) ?></h1>

        <div class="header-right"><a href="/"><i class="material-icons">close</i></a></div>
    </header>
<?php } else { ?>
    <header id="top">
        <div class="header-left first">
            <a href="javascript:" id="c-button--slide-left"><i class="material-icons">&#xE5D2;</i></a>
        </div>
        <?php if ($_SERVER['PHP_SELF'] === '/index.php') { ?>
            <h1>2CPU</h1>
        <?php } else { ?>
            <div class="header-left"><a href="javascript:windowBack();"><i class="material-icons">&#xE5C4;</i></a></div>
            <h1><?= e(app_title($g4['title'])) ?></h1>
        <?php } ?>
        <div class="header-right">
            <a href="/bbs/myon.php"><i class="material-icons md-32">&#xE7F5;</i>
                <?php if ($member['mb_id']) { ?>
                    <span class="label label-primary" id="notification_count"><?php
                        include_once("$g4[path]/lib/whatson.lib.php");
                        echo whatson_count($member[mb_id]);
                        ?></span>
                <?php } ?>
            </a>
            <a href="javascript:windowReload();"><i class="material-icons md-32">&#xE5D5;</i></a>
        </div>
    </header>
    <?php require(__DIR__ . '/sidebar.php') ?>

    <div id="sidebar"></div>

    <div id="contents">

<?php } ?>
