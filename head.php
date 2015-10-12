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
<div class="row hidden-xs">
        <div class="pull-right">
        <?
        echo get_banner("top_github", "basic", "github");
        echo get_banner("top", "basic", "", 1);
        ?>
        </div>
</div>

<div class="navbar navbar-default" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header col-md-2 col-lg-2">
        <!-- collapse �Ǿ��� ��, ������ ������ �޴� ��ư -->
        <button type="button" class="btn btn-default navbar-toggle" data-toggle="collapse" data-target=".navbar-top-menu-collapse">
            <i class="glyphicon glyphicon-list"></i>
        </button>
        <? if ($member['mb_id'] == "") { 
        $login_url = "$g4[bbs_path]/login.php?url=".urlencode($lo_url);
        ?>
        <a class="btn btn-default navbar-toggle" value="Page" onclick="location.href='<?=$login_url?>';">
            <i class="glyphicon glyphicon-user"></i>
        </a>
        <? } else {
        $login_url = "$g4[bbs_path]/myon.php?head=1";
        ?>
        <a class="btn <?=$whatson_btn?> navbar-toggle" value="Page" onclick="location.href='<?=$login_url?>';">
            <i class="glyphicon glyphicon-shopping-cart"><sup style="margin-left:3px;"><?=$g4['whatson_unread']?></sup></i>
        </a>
        <a class="btn <?=$memo_btn?> navbar-toggle" value="Page" href="javascript:win_memo('', '<?=$member[mb_id]?>', '<?=$_SERVER[SERVER_NAME]?>');" onfocus="this.blur()">
            <i class="glyphicon glyphicon-envelope"><sup style="margin-left:3px;"><?=$member[mb_memo_unread]?></sup></i>
        </a>
        <? } ?>
        <button type="button" class="btn btn-default navbar-toggle" data-toggle="collapse" data-target=".navbar-search-top-collapse">
            <i class="glyphicon glyphicon-search"></i>
        </button>
        <!-- sm, md, lg���� ������ �ΰ� -->
        <a class="navbar-brand hidden-xs hidden-sm" href="<?=$g4['path']?>/">
        <img src="<?=$g4[path]?>/img/opencode_aaa.png" align=absmiddle alt="brand logo">
        </a>
        <!-- collapse �Ǿ��� �� ������ �ΰ� -->
        <a class="navbar-brand navbar-toggle pull-left" href="<?=$g4['path']?>/" style="border:0;margin-bottom:0;">
        <img src="<?=$g4[path]?>/img/opencode_aaa.png" alt="brand logo" style="width:120px;">
        </a>
    </div>

    <div class="collapse navbar-collapse navbar-top-menu-collapse col-sm-9 col-md-7 col-lg-7">
    <ul class="nav navbar-nav">
        <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=qna">�����Խ���</a></li>
        <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=test">�����̾߱�</a></li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">��ũ <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=notice">����</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=g4_100">�״�����100�Ͽϼ�</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=g4_books">�״���������</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=sitetips">����Ʈ���߿</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=biz">����Ͻ������ڷ�</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/good_list.php">����Ʈ��</a></li>
                <li><a href="<?=$g4[bbs_path]?>/new.php">�ֱٰԽñ�</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=test">�׽�Ʈ</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=test2">�׽�Ʈ2</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">������ <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=linux_tips">Linux</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=virtual">����ȭ</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=apache_tips">Apache</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=mysql_tips">MySQL</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=mariadb_tips">Maria DB</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=nosql">NoSQL</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=css">CSS/��Ʈ��Ʈ��</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=php_tips">PHP</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=jquery_tips">jQuery</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=javascript_tips">Java Script</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=ajax">AJAX</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=html_tips">HTML</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=html5_tips">HTML5</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=other_tips">��Ÿ ����</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=cheditor">cheditor(���)</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">�״�4 <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_turning">�״�����4 Ʃ��</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_turning2">�״�����4 Ʃ��(�����)</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=memo4">����5</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=thumb">�Ҵ��/Resize</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=layout">�Ҵ����(100%��������)</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=g4_recycle">������/Recycle</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_unicro">����ũ������/�Խ���</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_skin">�״����彺Ų</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_tips">�״�������</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_qna">�״����� ���� ���ϱ�</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">App <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=and_talk">�ȵ���̵� �Խ���</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=and_tip">�ȵ���̵� ��</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=and_pds">�ȵ���̵� �ڷ��</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=webapp">����</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">�Ҵ��� <b class="caret"></b></a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_pack">�Ҵ��Ѵٿ�ε�</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_pack_book">�Ҵ��� �Ŵ���</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_pack_skin">�Ҵ��� ��Ų</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_pack_req">�Ҵ��� ���� �� ����</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gnu4_pack_qna">�Ҵ��� ������ϱ�</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=gblog">gblog �Ҵ����</a></li>
                <li><a href="<?=$g4[path]?>/blog/" target=_blank>gblog �׽�Ʈ</a></li>
                <li class="divider"></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=club2">Ŭ��2</a></li>
                <li><a href="$g4[path]?>/club/">Ŭ��2 �׽�Ʈ</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">��īƮ5 <b class="caret"></b></a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=yc4_pack_download">��īƮ5 �Ҵ���</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=yc4_tips">��īƮ5 ��</a></li>
                <li><a href="<?=$g4[bbs_path]?>/board.php?bo_table=yc4_pack_qna">��īƮ5 ������ϱ�</a></li>
                <li><a href="<?=$g4[path]?>/shop/index.php">��īƮ5 �׽�Ʈ</a></li>
            </ul>
        </li>
        <li><a href="<?=$g4[plugin_path]?>/attendance/attendance.php">�⼮</a></li>
    </ul>
    </div>

    <form class="navbar-form collapse navbar-collapse navbar-search-top-collapse" role="search" method="get" onsubmit="return fsearchbox_submit(this);" >
    <input type="hidden" name="sfl" value="wr_subject||wr_content">
    <input type="hidden" name="sop" value="and">
    <div class="col-sm-3 col-md-3 col-lg-3 pull-right">
    <div class="input-group" id="search-bar">
        <input type="text" class="form-control pull-right" placeholder="�˻���� 2�ܾ����" name="stx" id="stx" maxlength="20" value="<?=$stx;?>">
        <label for="stx" class="sr-only">search</label>
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit" >�˻� <i class="glyphicon glyphicon-search"></i></button>
        </span>
    </div><!-- /input-group -->
    </div>
    </form>

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

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- 2CPU - �������� -->
<ins class="adsbygoogle"
     style="display:inline-block;width:200px;height:200px"
     data-ad-client="ca-pub-2309139745261135"
     data-ad-slot="1974345174"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>

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