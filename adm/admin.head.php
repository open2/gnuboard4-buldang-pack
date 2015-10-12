<?
if (!defined("_GNUBOARD_")) exit;

$begin_time = get_microtime();

include_once("$g4[path]/head.sub.php");

function print_menu2($key, $no)
{
    global $menu, $auth_menu, $is_admin, $auth, $g4;

    $str = "<li class=\"dropdown\">";
    $str .= "<a class=\"dropdown-toggle\" href=\"#\" data-toggle=\"dropdown\">" . $menu[$key][0][1] . " <b class=\"caret\"></b></a>";
    $str .= "<ul class=\"dropdown-menu\">";

    for($i=1; $i<count($menu[$key]); $i++)
    {
        if ($is_admin != "super" && (!array_key_exists($menu[$key][$i][0],$auth) || !strstr($auth[$menu[$key][$i][0]], "r")))
            continue;

        if ($menu[$key][$i][0] == "-")
            $str .= "<li class=\"divider\"></li>";
        else
        {
            // target link가 있는 경우
            $span1 = "";

            if ($menu[$key][$i][3]) {
                $target_link = "target='$menu[$key][$i][3]'"; 
                $span1 = " <i class=\"fa fa-external-link\"></i>";
            } else 
                $target_link = ""; 
            $str .= "<li><a href='{$menu[$key][$i][2]}' {$target_link}>{$menu[$key][$i][1]}{$span1}</a></li>";
            $auth_menu[$menu[$key][$i][0]] = $menu[$key][$i][1];
        }
    }

    $str .= "</ul>";
    $str .= "</li>";

    return $str;
}

function print_menu1($key, $no, $sub_menu)
{
    global $menu, $auth_menu, $is_admin, $auth, $g4;

    $str .= "<ul class=\"nav nav-pills nav-stacked\" role=\"navigation\">";

    for($i=1; $i<count($menu[$key]); $i++)
    {
        if ($is_admin != "super" && (!array_key_exists($menu[$key][$i][0],$auth) || !strstr($auth[$menu[$key][$i][0]], "r")))
            continue;

        if ($menu[$key][$i][0] == "-")
            $str .= "<li class=\"nav-divider\"></li>";
        else
        {
            // target link가 있는 경우
            $span1 = "";

            if ($menu[$key][$i][3]) {
                $target_link = "target='$menu[$key][$i][3]'"; 
                $span1 = " <i class=\"fa fa-external-link\"></i>";
            } else 
                $target_link = "";
            // 선택된 메뉴는 active로
            if ($menu[$key][$i][0] == $sub_menu)
                $active = "active";
            else
                $active = "";

            // 조금 빽빽하게 보이게, 일부러 style을 바꿔준다. 요기만 하는거라 class 생략
            $str .= "<li style='margin-top:-5px;margin-bottom:-5px;' class='$active'><a href='{$menu[$key][$i][2]}' {$target_link}>{$menu[$key][$i][1]}{$span1}</a></li>";
            $auth_menu[$menu[$key][$i][0]] = $menu[$key][$i][1];
        }
    }

    $str .= "</ul>";

    return $str;
}
?>
<link rel="stylesheet" href="<?=$g4['admin_path']?>/admin.style.css" type="text/css">

<header class="header-wrapper"><!-- 상단 header 시작 -->
<div class="container">

<div class="navbar navbar-inverse" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header col-sm-2">
        <!-- collapse 되었을 때, 우측에 나오는 메뉴 버튼 -->
        <button type="button" class="btn navbar-toggle  btn-primary" data-toggle="collapse" data-target=".navbar-top-menu-collapse">
            <i class="glyphicon glyphicon-list"></i>
        </button>
        <a class="btn navbar-toggle" href="<?=$g4[admin_path]?>">Home</a>

        <!-- sm, md, lg에서 나오는 로고 -->
        <a class="navbar-brand hidden-xs" href="<?=$g4['admin_path']?>/">
        <img src="<?=$g4[path]?>/img/opencode_aaa.png" align=absmiddle alt="brand logo">
        </a>
        <!-- collapse 되었을 때 나오는 로고 -->
        <a class="navbar-brand navbar-toggle pull-left" href="<?=$g4['admin_path']?>/" style="border:0;margin-bottom:0;">
        <img src="<?=$g4[path]?>/img/opencode_aaa.png" alt="brand logo" style="width:120px;">
        </a>
    </div>

    <div class="collapse navbar-collapse navbar-top-menu-collapse col-sm-7">
    <ul class="nav navbar-nav">
        <?
        foreach($amenu as $key=>$value)
        {
            echo print_menu2("menu{$key}", 1);
        }
        ?>
    </ul>
    </div>

    <!-- 우측정렬 메뉴 -->
    <div class="collapse navbar-collapse pull-right col-sm-7">
    <ul class="nav navbar-nav">
        <p class="navbar-text">
        <a href='<?=$g4['admin_path']?>/'>Admin</a>
        <? 
        $tmp_menu = "";
        if (isset($sub_menu))
            $tmp_menu = substr($sub_menu, 0, 3);
        if (isset($menu["menu{$tmp_menu}"][0][1]))
        {
            if ($menu["menu{$tmp_menu}"][0][2])
            {
                echo "<a href='".$menu["menu{$tmp_menu}"][0][2]."'>";
                echo $menu["menu{$tmp_menu}"][0][1];
                echo "</a> > ";
            }
            else
                echo $menu["menu{$tmp_menu}"][0][1]." > ";
        }
        ?>
        > <?=$g4['title']?>
        :: <?=$member['mb_nick']?>님
        </p>
        <li class="pull-right"><a href="<?=$g4[path]?>"><i class="fa fa-home"></i> Home</a></li>
    </ul>
    </div>
</div><!-- navbar의 끝 -->

</div>
</header><!-- 상단 header 끝 -->


<!-- 중간의 메인부 시작 -->
<div role="main" class="container">
<div class="row">

<!-- 왼쪽 side 시작 -->
<div class="col-sm-2 visible-sm visible-md visible-lg">
    <?
    // 아웃로그인
    include_once("$g4[path]/lib/outlogin.lib.php");
    echo outlogin("basic");

    // menu 설정 - 아무것도 없으면 처음 나오는 key를 기본 key로 설정
    if ($tmp_menu == "") {
        $tmp_menu1 = key($menu);
    } else {
        $tmp_menu1 = "menu" . $tmp_menu;
    }
    $tmp_menu1_title = $menu[$tmp_menu1][0][1];
    ?>

    <div class="well" style="margin-bottom:5px;"><strong><?=$tmp_menu1_title?></strong></div>
    <div class="panel panel-default">
        <?
        echo print_menu1($tmp_menu1, 1, $sub_menu);
        ?>
    </div>

    <?
    // 방문자
    include_once("$g4[path]/lib/visit.lib.php");
    echo visit();

    // 현재접속자
    include_once("$g4[path]/lib/connect.lib.php");
    echo connect();
    ?>

</div><!-- 왼쪽 side 끝 -->

<div class="col-sm-10" id="main"><!-- 메인 content 시작 -->