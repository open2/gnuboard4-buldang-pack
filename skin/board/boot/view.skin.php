<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

// ��Ų���� ����ϴ� lib �о���̱�
include_once("$g4[path]/lib/view.skin.lib.php");
?>

<div width="<?=$width?>" id="view_<?=$wr_id?>" name="view_<?=$wr_id?>">

<!-- ��ũ ��ư -->
<? ob_start(); // �ѹ� ���� �ι� ���ϴ� ?>
<div id="view_btn_top">
    <div class="btn-group">
        <? if ($search_href) { echo "<a href=\"$search_href\" class=\"btn btn-default btn-sm btn-search\">�˻�</a> "; } ?>
        <? echo "<a href=\"$list_href\" class=\"btn btn-default btn-sm btn-list\">���</a> "; ?>
    </div>
    <div class="btn-group">
        <? if ($write_href) { echo "<a href=\"$write_href\" class=\"btn btn-default btn-sm btn-write\">����</a> "; } ?>
    </div>
    <div class="btn-group">
        <? if ($reply_href) { echo "<a href=\"$reply_href\" class=\"btn btn-default btn-sm btn-reply\">�亯</a> "; } ?>
        <? if ($update_href) { echo "<a href=\"$update_href\" class=\"btn btn-default btn-sm btn-modify\">����</a> "; } ?>
        <? if ($delete_href) { echo "<a href=\"$delete_href\" class=\"btn btn-default btn-sm btn-delete\">����</a> "; } ?>
    </div>
    <div class="btn-group">
        <? if ($good_href) { echo "<a href=\"$good_href\" target='hiddenframe' class=\"btn btn-default btn-sm btn-good\">��õ</a> "; } ?>
        <? if ($nogood_href) { echo "<a href=\"$nogood_href\" target='hiddenframe' class=\"btn btn-default btn-sm btn-nogood\">����õ</a> "; } ?>
        <? if ($scrap_href) { echo "<a href=\"javascript:;\" onclick=\"win_scrap('$scrap_href');\" class=\"btn btn-default btn-sm btn-scrap\">��ũ��</a> "; } ?>
        <? if ($nosecret_href) { echo "<a href=\"$nosecret_href\" class=\"btn btn-default btn-sm btn-nosecret\">��б�����</a> "; } ?>
        <? if ($secret_href) { echo "<a href=\"$secret_href\" class=\"btn btn-default btn-sm btn-secret\">��б�</a> "; } ?>
    </div>
    <div class="btn-group pull-right">
        <? if ($prev_href) { echo "<a href=\"$prev_href\" title=\"$prev_wr_subject\" class=\"btn btn-default btn-sm btn-prev\">������</a>"; } ?>
        <? if ($next_href) { echo "<a href=\"$next_href\" title=\"$next_wr_subject\" class=\"btn btn-default btn-sm btn-next\">������</a>"; } ?>
 	      <a href="javascript:scaleFont(+1);" class="btn btn-default btn-sm btn-zoom-in"><span class="glyphicon glyphicon-zoom-in"></span></a>
        <a href="javascript:scaleFont(-1);" class="btn btn-default btn-sm btn-zoom-out"><span class="glyphicon glyphicon-zoom-out"></span></a>
        <a href="#commentContents" class="btn btn-default btn-sm"><i class="fa fa-chevron-down"></i></a>
        <? echo "<a href=\"$list_href\" class=\"btn btn-default btn-sm btn-list\">���</a> "; ?>
    </div>
    <div class="btn-group hidden-xs hidden-sm  pull-right">
        <? if ($copy_href) { echo "<a href=\"$copy_href\" class=\"btn btn-default btn-sm btn-copy\">����</a> "; } ?>
        <? if ($move_href) { echo "<a href=\"$move_href\" class=\"btn btn-default btn-sm btn-move\">�̵�</a> "; } ?>
        <? if ($now_href) { echo "<a href=\"$now_href\" class=\"btn btn-default btn-sm btn-update\">����</a> "; } ?>
    </div>
</div>
<?
$link_buttons = ob_get_contents();
ob_end_flush();
?>

<div id="view_header" class="panel panel-default">
<div class="panel-heading" style="padding-bottom:0px;">

		<p>
        <? if ($is_category) { echo ($category_name ? "[$view[ca_name]] " : ""); } ?>
        <strong><?=cut_hangul_last(get_text($view[wr_subject]))?></strong>
		</p>
		<p class="pull-left">
        <font style="color:#BABABA;">
        <?=$view[name]?><? if ($is_ip_view) { echo "&nbsp;($ip)"; } ?>&nbsp;&nbsp;
	  		<div class="hidden-md hidden-lg pull-left"><?=get_datetime($view['wr_datetime'])?></div>
	  		<div class="hidden-xs hidden-sm pull-left"><?=$view['wr_datetime']?></div>&nbsp;&nbsp;
        ��ȸ <?=$view[wr_hit]?>&nbsp;&nbsp;
        <? if ($is_good) { ?>��õ  <?=$view[wr_good]?>&nbsp;&nbsp;&nbsp;&nbsp;<?}?>
        <? if ($is_nogood) { ?>����õ  <?=$view[wr_nogood]?>&nbsp;&nbsp;&nbsp;&nbsp;<?}?>
        </font>
    <!-- �Խñ� �ּҸ� �����ϱ� ���� �ϱ� ���ؼ� �Ʒ� �κ��� ���� -->
    <span class="pull-right">
    <a href="javascript:clipboard_trackback('<?=$posting_url?>');" style="letter-spacing:0;" title='�� ���� �Ұ��� ���� �� �ּҸ� ����ϼ���'><i class="fa fa-link"></i></a>
    <? if ($g4[use_bitly]) { ?>
        <? if ($view[bitly_url]) { ?>
        &nbsp;<span id="bitly_url"><a href=<?=$view[bitly_url]?> target=new><?=$view[bitly_url]?></a></span>
        <? } else { ?>
        &nbsp;<span id="bitly_url"></span>
        <script type="text/javascript">
        // encode �� ���� �Ѱ��ָ�, �˾Ƽ� decode�ؼ� ����� return ���ش�.
        // encode �ϱ� ���� url�� �־�� ����� ���� �� �ֱ� ������, �ᱹ 2���� �Ѱ��ش�.
        // ��? java script������ urlencode, urldecode�� �����ϱ�. ����
        // ���� �̰Ŵ� �������� �ؾ� �Ѵ�. ��??? �׷��� ������ html page�� ������Ʈ ����~!
        get_bitly_g4('#bitly_url', '<?=$bo_table?>', '<?=$wr_id?>');
        </script>
        <?}?>
    <?}?>
    </span>
    </p>

    <p>
        <?
        // ���� ����
        $cnt = 0;
        for ($i=0; $i<count($view[file]); $i++) {
            if ($view[file][$i][source] && !$view[file][$i][view]) {
                $cnt++;
                echo "<i class=\"fa fa-file-o\" title='attached file'></i> <a href=\"javascript:file_download('{$view[file][$i][href]}', '{$view[file][$i][source]}');\" title='{$view[file][$i][content]}'>{$view[file][$i][source]}<font style='font-weight:normal;color:#B8B8B8;'> ({$view[file][$i][size]}), Down : {$view[file][$i][download]}, " . get_datetime($view[file][$i][datetime]) . "</font></a><br>";
            }
        }

        // ��ũ
        $cnt = 0;
        for ($i=1; $i<=$g4[link_count]; $i++) {
            if ($view[link][$i]) {
                $cnt++;
                $link = cut_str($view[link][$i], 70);
                echo "<a href='{$view[link_href][$i]}' target=_blank>{$link} ({$view[link_hit][$i]})</a></BR>";
            }
        }
    ?>
    </p>
</div>

<div id="view_main" class="panel-body">

    <?
    // �̹��� ������ ��� �Ǿ�����...
    $view_img_file = 0;

    // ���� ���
    for ($i=0; $i<=$view[file][count]; $i++) {
        if ($view[file][$i][view]) {
            echo resize_dica($view[file][$i][view],250,300) . "<p>" . $view[file][$i][content] . "</p>";
            $view_img_file = 1;
        }
    }

    // �Ű�� �Խñ��� �̹����� �����Ͽ� ����ϱ�
    if ($view['wr_singo'] and trim($file_viewer)) {
        $singo = "<div id='singo_file_title{$view[wr_id]}' class='singo_title'><font color=gray>�Ű� ������ �Խù��Դϴ�. ";
        $singo .= "<span class='singo_here' style='cursor:pointer;font-weight:bold;' onclick=\"document.getElementById('singo_file{$view[wr_id]}').style.display=(document.getElementById('singo_file{$view[wr_id]}').style.display=='none'?'':'none');\"><font color=red>����</font></span>�� Ŭ���Ͻø� ÷�� �̹����� �� �� �ֽ��ϴ�.</font></div>";
        $singo .= "<div id='singo_file{$view[wr_id]}' style='display:none;'><p>";
        $singo .= $file_viewer;
        $singo .= "</div>";
        echo $singo;
    } else {
        echo $file_viewer;
    }
    ?>

    <!-- ���� ��� -->
    <? if ($view_img_file == 0) { ?>
    <div style="float:right;margin-left:10px;margin-bottom:10px;" class="">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- 2CPU-������ ���ι�� - ���簢�� -->
<ins class="adsbygoogle"
     style="display:inline-block;width:125px;height:125px"
     data-ad-client="ca-pub-2309139745261135"
     data-ad-slot="4040356375"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
    </div>
    <? } ?>

    <span id="writeContents" style="word-wrap:break-word;">
    <?
        $write_contents=resize_dica($view[content],400,300);
        echo $write_contents;
    ?>
    </span>

    <? if ($is_signature && $signature) { echo "<div style='margin-top:30px;margin-bottom:30px;text-align:center;'>$signature</div>"; } // ���� ��� ?>

</div>

<?
// ���� �ִ� ��� ���� ����
if (file_exists("$g4[path]/adsense/adsense_view_comment.php"))
    include_once("$g4[path]/adsense/adsense_view_comment.php");
?>

</div>

<?
// �ڸ�Ʈ �����
if (!$board['bo_comment_read_level'])
  include_once("./view_comment.php");
else if ($member['mb_level'] >= $board['bo_comment_read_level'])
  include_once("./view_comment.php");
?>
<?=$link_buttons?>
<? include_once("$g4[path]/adsense/adsense_page_bottom.php"); ?>

</div>

<script type="text/javascript"  src="<?="$g4[path]/js/board.js"?>"></script>
<script type="text/javascript">
window.onload=function() {
    resizeBoardImage($(view_main).width()-20);
    OnclickCheck(document.getElementById("writeContents"), '<?=$config[cf_link_target]?>'); 
}
</script>
<!-- �Խñ� ���� �� -->