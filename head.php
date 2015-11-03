<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ�

include_once("$g4[path]/head.sub.php");

// ��ʰ��� lib
include_once("$g4[path]/lib/banner.lib.php");

// ����� ȭ�� ��ܰ� ������ ����ϴ� �������Դϴ�.
// ���, ���� ȭ���� �ٹ̷��� �� ������ �����մϴ�.
?>
<?
// ��ܺο� alert �˾��� ��� �մϴ�.
include_once("$g4[path]/lib/popup.lib.php");
echo popup("alert", "popup_alert");

// ȸ���� ��� �Ӝ�~�� �о, ������ ������ ����� �Ӵϴ�.
// �Ӝ��� �Ⱦ��� ��쿡�� �׳� �׷��Ŷ� ���� �����ϰ� head.php�� �ֽ��ϴ�.
if ($member[mb_id]) {
    include_once("$g4[path]/lib/whatson.lib.php");
    $g4['whatson_unread'] = whatson_count($member[mb_id]);
}

// ��ư�� �˶���??? btn-info-navbar class�� style.css�� �ֽ��ϴ�.
if ($member['mb_memo_unread'] > 0)
    $memo_btn = "btn-info btn-info-navbar";
else
    $memo_btn = "btn-default";
if ($g4['whatson_unread'] > 0)
    $whatson_btn = "btn-info btn-info-navbar";
else
    $whatson_btn = "btn-default";
?>

<header class="header-wrapper"><!-- ��� header ���� -->
<div class="container">

<div class="row">
<div class="hidden-xs hidden-sm col-md-2 col-lg-2">
<?
        echo get_banner("top_github", "basic", "github");
        echo get_banner("top", "basic", "", 1);
?>
</div>

<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
���۱���...
</div>

</div>

<div class="navbar navbar-default" role="navigation" style="margin-top:3px;margin-bottom:3px;">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header col-md-2 col-lg-2">

        <? if ($member['mb_id']) { ?>
        <!-- collapse �Ǿ��� ��, ������ ������ ���θ޴� ��ư -->
        <button type="button" class="btn btn-default navbar-toggle pull-right" data-toggle="collapse" data-target=".navbar-top-menu-collapse_my" style="border:none">
            <i class="glyphicon glyphicon-check"></i>
        </button>
        <? } ?>

        <!-- collapse �Ǿ��� ��, ������ ������ �޴� ��ư -->
        <button type="button" class="btn btn-default navbar-toggle pull-right" data-toggle="collapse" data-target=".navbar-top-menu-collapse" style="border:none">
            <i class="glyphicon glyphicon-list"></i>
        </button>

        <? if ($member['mb_id'] == "") { 
        $login_url = "$g4[bbs_path]/login.php?url=".urlencode($lo_url);
        ?>
        <a class="btn btn-default navbar-toggle" value="Page" onclick="location.href='<?=$login_url?>';" style="border:none">
            <i class="glyphicon glyphicon-user"></i>
        </a>
        <? } else {
        $login_url = "$g4[bbs_path]/myon.php?head=1";
        ?>
        <a class="btn <?=$whatson_btn?> navbar-toggle" value="Page" onclick="location.href='<?=$login_url?>';" style="border:none">
            <i class="glyphicon glyphicon-shopping-cart"><sup style="margin-left:3px;"><?=$g4['whatson_unread']?></sup></i>
        </a>
        <a class="btn <?=$memo_btn?> navbar-toggle" value="Page" href="javascript:win_memo('', '<?=$member[mb_id]?>', '<?=$_SERVER[SERVER_NAME]?>');" onfocus="this.blur()" style="border:none">
            <i class="glyphicon glyphicon-envelope"><sup style="margin-left:3px;"><?=$member[mb_memo_unread]?></sup></i>
        </a>
        <? } ?>
        <!-- sm, md, lg���� ������ �ΰ� -->
        <a class="navbar-brand hidden-xs hidden-sm" href="<?=$g4['path']?>/" style="border:none">
        <img src="<?=$g4[path]?>/images/opencode_aaa.png" align=absmiddle alt="brand logo" style="height:38px;border:none;margin-top:-10px;">
        </a>
        <!-- collapse �Ǿ��� �� ������ �ΰ� -->
        <a class="navbar-brand navbar-toggle pull-left" href="<?=$g4['path']?>/" style="margin-bottom:0;border:none;">
        <img src="<?=$g4[path]?>/images/opencode_aaa.png" alt="brand logo" style="height:30px">
        </a>

        <!-- navbar toggle�� �ƴϹǷ�, 2�� �־���� �մϴ� -->
        <button type="button" class="btn btn-default navbar-toggle visible-sm visible-xs" data-toggle="collapse" data-target="#top_search" style="border:none;float:right">
            <i class="glyphicon glyphicon-search"></i>
        </button>
    </div>

    <?
    $my_menu = array();
    $sql = "select m.bo_table, b.bo_subject from $g4[my_menu_table] as m left join $g4[board_table] as b on m.bo_table = b.bo_table where mb_id = '$member[mb_id]'";
    $qry = sql_query($sql);
    while ($row = sql_fetch_array($qry)) {
        $my_menu[] = $row;
    }
    ?>

    <div class="collapse navbar-collapse navbar-top-menu-collapse_my pull-right">

        <button type="button" class="btn btn-success" data-toggle="collapse" data-target="#top_search" style="border:0px;float:right; background:none;">
            <i class="glyphicon glyphicon-search"></i>
        </button>

    <ul class="nav navbar-nav hidden-lg hidden-md" id="gnb_my">
        <? for ($i=0; $i<count($my_menu); $i++) { ?>
            <li id="qna_my"><a href="<?=$g4[bbs_path]?>/board.php?bo_table=<?=$my_menu[$i][bo_table]?>"><?=$my_menu[$i][bo_subject]?></a></li>
        <? } ?>
        <li id="qna_my"><a href="<?=$g4[bbs_path]?>/my_menu_edit.php">�ٷΰ�������</a></li>
    </ul>
    </div>

    <div class="collapse navbar-collapse navbar-top-menu-collapse col-sm-9 col-md-7 col-lg-7">
    <ul class="nav navbar-nav" id="gnb">
        <li id="qna"><a href="<?=$g4[path]?>/qna">�����Խ���</a></li>
        <li id="test"><a href="<?=$g4[path]?>/test">�����̾߱�</a></li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">��ũ <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li id="notice"><a href="<?=$g4[path]?>/notice">����</a></li>
                <li class="divider"></li>
                <li id="g4_100"><a href="<?=$g4[path]?>/g4_100">�״�����100�Ͽϼ�</a></li>
                <li id="g4_books"><a href="<?=$g4[path]?>/g4_books">�״���������</a></li>
                <li id="sitetips"><a href="<?=$g4[path]?>/sitetips">����Ʈ���߿</a></li>
                <li id="biz"><a href="<?=$g4[path]?>/biz">����Ͻ������ڷ�</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/good_list.php">����Ʈ��</a></li>
                <li><a href="<?=$g4[bbs_path]?>/new.php">�ֱٰԽñ�</a></li>
                <li class="divider"></li>
                <li id="test"><a href="<?=$g4[path]?>/test">�׽�Ʈ</a></li>
                <li id="test2"><a href="<?=$g4[path]?>/test2">�׽�Ʈ2</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">������ <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="<?=$g4[path]?>/linux_tips">Linux</a></li>
                <li><a href="<?=$g4[path]?>/virtual">����ȭ</a></li>
                <li><a href="<?=$g4[path]?>/apache_tips">Apache</a></li>
                <li><a href="<?=$g4[path]?>/mysql_tips">MySQL</a></li>
                <li><a href="<?=$g4[path]?>/mariadb_tips">Maria DB</a></li>
                <li><a href="<?=$g4[path]?>/nosql">NoSQL</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[path]?>/css">CSS/��Ʈ��Ʈ��</a></li>
                <li><a href="<?=$g4[path]?>/php_tips">PHP</a></li>
                <li><a href="<?=$g4[path]?>/jquery_tips">jQuery</a></li>
                <li><a href="<?=$g4[path]?>/javascript_tips">Java Script</a></li>
                <li><a href="<?=$g4[path]?>/ajax">AJAX</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[path]?>/html_tips">HTML</a></li>
                <li><a href="<?=$g4[path]?>/html5_tips">HTML5</a></li>
                <li><a href="<?=$g4[path]?>/other_tips">��Ÿ ����</a></li>
                <li><a href="<?=$g4[path]?>/cheditor">cheditor(���)</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">�״�4 <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="<?=$g4[path]?>/gnu4_turning">�״�����4 Ʃ��</a></li>
                <li><a href="<?=$g4[path]?>/gnu4_turning2">�״�����4 Ʃ��(�����)</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[path]?>/memo4">����5</a></li>
                <li><a href="<?=$g4[path]?>/thumb">�Ҵ��/Resize</a></li>
                <li><a href="<?=$g4[path]?>/layout">�Ҵ����(100%��������)</a></li>
                <li><a href="<?=$g4[path]?>/g4_recycle">������/Recycle</a></li>
                <li><a href="<?=$g4[path]?>/gnu4_unicro">����ũ������/�Խ���</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[path]?>/gnu4_skin">�״����彺Ų</a></li>
                <li><a href="<?=$g4[path]?>/gnu4_tips">�״�������</a></li>
                <li><a href="<?=$g4[path]?>/gnu4_qna">�״����� ���� ���ϱ�</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">App <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="<?=$g4[path]?>/and_talk">�ȵ���̵� �Խ���</a></li>
                <li><a href="<?=$g4[path]?>/and_tip">�ȵ���̵� ��</a></li>
                <li><a href="<?=$g4[path]?>/and_pds">�ȵ���̵� �ڷ��</a></li>
                <li><a href="<?=$g4[path]?>/webapp">����</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">�Ҵ��� <b class="caret"></b></a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="<?=$g4[path]?>/gnu4_pack">�Ҵ��Ѵٿ�ε�</a></li>
                <li><a href="<?=$g4[path]?>/gnu4_pack_book">�Ҵ��� �Ŵ���</a></li>
                <li><a href="<?=$g4[path]?>/gnu4_pack_skin">�Ҵ��� ��Ų</a></li>
                <li><a href="<?=$g4[path]?>/gnu4_pack_req">�Ҵ��� ���� �� ����</a></li>
                <li><a href="<?=$g4[path]?>/gnu4_pack_qna">�Ҵ��� ������ϱ�</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[path]?>/gblog">gblog �Ҵ����</a></li>
                <li><a href="<?=$g4[path]?>/blog/" target=_blank>gblog �׽�Ʈ</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[path]?>/club2">Ŭ��2</a></li>
                <li><a href="$g4[path]?>/club/">Ŭ��2 �׽�Ʈ</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">��īƮ5 <b class="caret"></b></a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="<?=$g4[path]?>/yc4_pack_download">��īƮ5 �Ҵ���</a></li>
                <li><a href="<?=$g4[path]?>/yc4_tips">��īƮ5 ��</a></li>
                <li><a href="<?=$g4[path]?>/yc4_pack_qna">��īƮ5 ������ϱ�</a></li>
                <li><a href="<?=$g4[path]?>/shop/index.php">��īƮ5 �׽�Ʈ</a></li>
            </ul>
        </li>
        <li><a href="<?=$g4[plugin_path]?>/attendance/attendance.php">�⼮</a></li>
        <li class="dropdown">
            <a class="dropdown-toggle hidden-xs hidden-sm" href="#" data-toggle="dropdown">�ٷΰ��� <b class="caret"></b></a>
            <ul class="dropdown-menu" role="menu">
                <? for ($i=0; $i<count($my_menu); $i++) { ?>
                    <li id="my_$i_m"><a href="<?=$g4[path]?>/<?=$my_menu[$i][bo_table]?>"><?=$my_menu[$i][bo_subject]?></a></li>
                <? } ?>
                <li class="divider"></li>
                <li id="qna_my"><a href="<?=$g4[bbs_path]?>/my_menu_edit.php">�ٷΰ�������</a></li>
            </ul>
        </li>
    </ul>
    </div>

    <div class="col-sm-3 col-md-3 col-lg-3 pull-right collapse" id="top_search">
    <form role="search" method="get" onsubmit="return fsearchbox_submit(this);">
    <input type="hidden" name="sfl" value="wr_subject||wr_content">
    <input type="hidden" name="sop" value="and">
    <div class="input-group" id="search-bar">
        <input type="text" class="form-control pull-right" placeholder="�˻���� 2�ܾ����" name="stx" id="stx" maxlength="20" value="<?=$stx;?>">
        <label for="stx" class="sr-only">search</label>
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit" >�˻� <i class="glyphicon glyphicon-search"></i></button>
        </span>
    </div><!-- /input-group -->
    </form>
    </div>

</div><!-- navbar�� �� -->

</div>
</header><!-- ��� header �� -->


<!-- �߰��� ���κ� ���� -->
<div role="main" class="container">
<div class="row">

<!-- ���� side ���� -->
<div class="hidden-xs hidden-sm col-md-2 col-lg-2">
<?
// �ƿ��α���
include_once("$g4[path]/lib/outlogin.lib.php");
echo outlogin("basic");
?>

<div style="overflow:hidden">
���۱���...
</div>

<!-- �α��ιڽ����� ���� -->
    <table><tr><td height="1px"></td></tr></table>
    <?
    if ($member[mb_id]) {
        echo whatson("basic", 10, 14);
    }
    ?>
    <?
    // ��ǥ
    include_once("$g4[path]/lib/poll.lib.php");
    echo poll();
    ?>
    <?
    // �湮��
    include_once("$g4[path]/lib/visit.lib.php");
    echo visit();
    ?>
    <?
    include_once("$g4[path]/lib/popular.lib.php");
    echo board_popular("board","", 14, 5);
    ?>
    <? // ����������
    include_once("$g4[path]/lib/connect.lib.php");
    echo connect();
    ?>

    <table><tr><td height="1px"></td></tr></table>
    <center>
    <a href="http://idc.gabia.com/colo/" target=_blank><img src="<?=$g4[path]?>/img/banner/gabia.gif" alt=""></a>
    <a href="http://idc.kinx.net/" target=_blank><img src="<?=$g4[path]?>/img/banner/kinx.gif" alt=""></a>
    <a href="http://worknet.co.kr" target=_blank><img src="<?=$g4[path]?>/img/banner/worknet.gif" alt=""></a>
    <a href="http://jobnet.co.kr" target=_blank><img src="<?=$g4[path]?>/img/banner/jobnet.gif" alt=""></a>
    <a href="http://bugsboard.co.kr" target=_blank><img src="<?=$g4[path]?>/img/banner/bugs4_logo.gif" alt=""></a>
    <a href="http://peoplenjob.com" target=_blank><img src="<?=$g4[path]?>/img/banner/peoplenjob.gif" alt=""></a>
    </center>

</div><!-- ���� side �� -->

<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10" id="main_content"><!-- ���� content ���� -->

<script type="text/javascript">
function fsearchbox_submit(f)
{
    if (f.stx.value.length < 2) {
        alert("�˻���� �α��� �̻� �Է��Ͻʽÿ�.");
        f.stx.select();
        f.stx.focus();
        return false;
    }

    // �˻��� ���� ���ϰ� �ɸ��� ��� �� �ּ��� �����ϼ���.
    var cnt = 0;
    for (var i=0; i<f.stx.value.length; i++) {
        if (f.stx.value.charAt(i) == ' ')
            cnt++;
    }

    if (cnt > 1) {
        alert("���� �˻��� ���Ͽ� �˻�� ������ �Ѱ��� �Է��� �� �ֽ��ϴ�.");
        f.stx.select();
        f.stx.focus();
        return false;
    }

    f.action = "<?=$g4['bbs_path']?>/search.php";
    return true;
}
</script>

<!-- �Խ��� �޴� ���� ��Ű�� -->
<? if ($board['bo_table']) { ?>
<script type="text/javascript">
$('#gnb #<?=$board[bo_table]?>').addClass('active');
</script>
<? } ?>