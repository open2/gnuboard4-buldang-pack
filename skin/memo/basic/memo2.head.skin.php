<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 
?>
<header class="header-wrapper"><!-- ��� header ���� -->
<div class="container">

<div class="navbar navbar-default" role="navigation">
<div class="navbar-header" style="margin-top:3px;margin-bottom:3px;">
    <div id="memo_bar" class="pull-left" style="margin-left:5px;">
        <a onclick="javascript:window.close();" class="btn btn-success navbar-toggle" style="margin-left:10px;" id="memo_close">�ݱ�</a>
        <a href="<?=$g4[memo_url]?>?kind=notice" class="btn btn-success navbar-toggle" id="notice">����</a>
        <a href="<?=$g4[memo_url]?>?kind=save" class="btn btn-success navbar-toggle" id="save">����</a>
        <a href="<?=$g4[memo_url]?>?kind=send" class="btn btn-success navbar-toggle" id="send"><strong>�߽�</strong></a>
        <a href="<?=$g4[memo_url]?>?kind=recv" class="btn btn-success navbar-toggle" id="recv"><strong>����</strong></a>
    </div>
    <button type="button" class="btn btn-success navbar-toggle" data-toggle="collapse" data-target=".navbar-top-menu-collapse">
        <i class="glyphicon glyphicon-list"></i>
    </button>
    <a href="<?=$g4[memo_url]?>?kind=write" class="btn btn-success navbar-toggle" id="write"><strong>����</strong></a>
</div>

<div class="collapse navbar-collapse navbar-top-menu-collapse">
    <ul class="nav navbar-nav hidden-xs">
        <li><a href="<?=$g4[memo_url]?>?kind=recv" id="recv"><strong>����</strong></a></li>
        <li><a href="<?=$g4[memo_url]?>?kind=send" id="send"><strong>�߽�</strong></a></li>
        <li><a href="<?=$g4[memo_url]?>?kind=write" id="write"><strong>����</strong></a></li>
        <li><a href="<?=$g4[memo_url]?>?kind=save" id="save">����</a></li>
        <li><a href="<?=$g4[memo_url]?>?kind=notice" id="notice">����</a></li>
    </ul>

    <ul class="nav navbar-nav pull-right">
        <li><a href="<?=$g4[memo_url]?>?kind=trash" id="trash">����</a></li>
        <li><a href="<?=$g4[memo_url]?>?kind=spam" id="spam">����</a></li>
        <li class="divider"></li>
        <li><a href="#">ģ��</a></li>
        <li><a href="#">�׷�</a></li>
        <li><a href="#">�ּҷ�</a></li>
        <li><a href="<?=$g4[memo_url]?>?kind=memo_config">����</a></li>
  </ul>
</div>
</div><!-- navbar �� -->

</div>
</header><!-- ��� header �� -->

<script type='text/javascript'>
// ���� Ŭ���� ��ư�� active��
$('#memo_bar #<?=$kind?>').addClass('active');
</script>

<!-- �޸� �޴� ���� ��Ű�� -->
<? if ($kind) { ?>
<script type="text/javascript">
$('#gnb #<?=$board[bo_table]?>').addClass('active');
</script>
<? } ?>

<!-- �߰��� ���κ� ���� -->
<div role="main">