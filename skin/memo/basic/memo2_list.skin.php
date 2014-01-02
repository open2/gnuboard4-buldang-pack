<form name=fsearch method=get role="form" class="form-inline">
<input type='hidden' name='kind' value='<?=$kind?>'>
<div class="container">
    <strong><a href="<?=$memo_url?>?kind=<?=$kind?>"><?=$memo_title?></a>&nbsp;
    ( <? if ($kind == "recv") echo "<a href='$memo_url?kind=recv&unread=only' title='안읽은쪽지'><font color=red>$total_count_recv_unread</font></a> / "?><a href='<?=$memo_url?>?kind=$kind'><?=number_format($total_count)?></a>
    </strong>
    /&nbsp;<a href="<?=$memo_url?>?kind=<?=$kind?>&sfl=me_file&stx=me_file"><i class="fa fa-file"></i></a>
    )

    <a class="btn btn-navbar btn-xs pull-right" data-toggle="collapse" data-target=".navbar-ex4-collapse">
        <i class="glyphicon glyphicon-search"></i>
    </a>

    <div class="pull-right collapse navbar-collapse navbar-ex4-collapse">
        <div class="form-group" role="search">
        <select name='sfl' id='sfl' class="form-control">
            <option value="me_subject_memo">제목+내용</option>
            <option value="me_subject">제목</option>
            <option value="me_memo">내용</option>
        <? if ($kind == "recv" or $kind == "spam" or $kind == "notice") { ?>
            <option value="me_send_mb_nick">보낸<? if ($config['cf_memo_mb_name']) echo "(이름)"; else echo "(별명)"; ?></option>
            <option value="me_send_mb_id">보낸(아이디)</option>
        <? } else if ($kind == "send") { ?>
            <option value="me_recv_mb_nick">받은<? if ($config['cf_memo_mb_name']) echo "(이름)"; else echo "(별명)"; ?></option>
            <option value="me_recv_mb_id">받은(아이디)</option>
        <? } else if ($kind == "save" or $kind == "trash") { ?>
            <option value="me_send_mb_nick">받은<? if ($config['cf_memo_mb_name']) echo "(이름)"; else echo "(별명)"; ?></option>
            <option value="me_recv_mb_id">받은(아이디)</option>
            <option value="me_send_mb_nick">보낸<? if ($config['cf_memo_mb_name']) echo "(이름)"; else echo "(별명)"; ?></option>
            <option value="me_send_mb_id">보낸(아이디)</option>
        <? } ?>
        </select>
        </div>
        <div class="form-group">
            <input  class="form-control" name="stx" type="text" value='<?=$stx?>' maxlength=15 size="15" itemname="검색어" required />
        </div>
        <button class="btn btn-primary">검색</button>
    </div>
</div>
</form>

<form name="fboardlist" method="post" role="form" class="form-inline">
<input type=hidden name=kind value="<?=$kind?>">

<div class="container">
<table class="table table-hover table-condensed table-borderless" width="100%">
<colgroup> 
    <col width="35">
    <col width="110">
    <col width="">
    <col width="80">
    <col width="80">
</colgroup> 
<thead>
<tr class="success">
    <th>
    <!-- 공지쪽지함은 삭제 선택이 없게... -->
    <input name="chk_me_id_all" type="checkbox" onclick="if (this.checked) all_checked(true); else all_checked(false);" />
    </th>
    <th><?=$list_title ?></th>
    <th>제 목</th>
    <th>발신</th>
    <th>
    <? if ($kind == 'notice') {
        if ($is_admin=='super' || $member['mb_id']==$view['me_send_mb_id']) { ?>  
            수신레벨
        <? } ?>
    <? } else { ?>
        수신
    <? } ?>
    </th>
</tr>
</thead>

<? for ($i=0; $i<count($list); $i++) { // 목록을 출력 합니다. ?>
<tr>
    <td>
        <!-- 공지쪽지함은 삭제 선택이 없게... -->
        <? if ($kind !== 'notice') { ?>
        <input name="chk_me_id[]" type="checkbox" value="<?=$list[$i][me_id]?>" />
        <? } ?>
    </td>
    <td><?=$list[$i]['name']?></td>
    <td align="left">
        <?
        if ($list[$i]['read_datetime'] == '읽지 않음' or $list[$i]['read_datetime'] == '수신 않음') {
            $style1 = "<strong>";
            $style2 = "</strong>";
        } else {
            $style1 = "";
            $style2 = "";
        }
        ?>
        <? if ($list[$i]['me_file']) { ?><i class="fa fa-file"></i>&nbsp;<?}?><a href='<?=$list[$i]['view_href']?>&page=<?=$page?>&sfl=<?=$sfl?>&stx=<?=$stx?>&unread=<?=$unread?>' title='<?=$list[$i]['subject']?>'><?=$style1?><?=cut_str($list[$i]['subject'],27)?><?=$style2?></a>
        </td>
        <td <?=$style?> ><?=$list[$i]['send_datetime']?></td>
        <?
        // 공지쪽지의 읽은 날짜는???
        if ($kind == 'notice') { 
            if ($is_admin=='super' || $member[mb_id]==$view[me_send_mb_id])
                $list[$i]['read_datetime'] = $list[$i]['me_recv_mb_id'];
            else 
                $list[$i]['read_datetime'] = "";
        }
        ?>
        <td <?=$style?> ><?=$list[$i]['read_datetime']?></td>
    </tr>
    <? } ?>
    <? if ($i==0) { ?>
    <tr>
        <td align=center height=100 colspan=5>자료가 없습니다.</td>
    </tr>
    <? } ?>
    <tfoot>
    <tr>
        <td colspan=5>
        <div class="btn-group">
            <? if ($i > 0 and $kind !='notice') { ?>
            <a href="javascript:select_delete();" class="btn btn-default">Delete</a>
            <? } ?>
            <? if ($i > 0 and $kind == "trash") { ?>
            <a href="javascript:all_delete_trash();" class="btn btn-default">Empty Trash</a>
            <? } ?>
        </div>
        <div class="pull-right">
            <ul class="pagination" style="margin-top:0;margin-bottom:0;" >
            <?
            $page = get_paging($config['cf_write_pages'], $page, $total_page, "?&kind=$kind&sfl=$sfl&stx=$stx&unread=$unread&page="); 
            echo "$page";
            ?>
            </ul>
        </div>
        </td>
    </tr>
    </tfoot>
</table>
</div>
</form>

<?
// 하단부에 내보내는 기본 정보사항
$msg = "";
if ($kind == "write") { // 쓰기 일때만 메시지를 출력 합니다.
    $msg .= "<li>여러명에게 쪽지 발송시 컴마(,)로 구분 합니다.";
    if ($config['cf_memo_use_file'] && $config['cf_memo_file_size']) {
        $msg .= "<li>첨부가능한 파일의 최대 용량은 " .$config['cf_memo_file_size'] . "M(메가) 입니다.";
    }
    if ($config['cf_memo_send_point']) 
        $msg .= "<li>쪽지 보낼때 회원당 ".number_format($config['cf_memo_send_point'])."점의 포인트를 차감합니다.";
}
if ($kind == "send") { // 보낸쪽지함 일때만 메시지를 출력 합니다.
    $msg .= "<li>읽지 않은 쪽지를 삭제하면, 발신이 취소(수신자 쪽지함에서 삭제) 됩니다.";
}
if ($kind == "send" || $kind == "recv") { // 보낸쪽지함 일때만 메시지를 출력 합니다.
    $msg .= "<li>보관안된 쪽지는 " . $config['cf_memo_del'] . "일 후 삭제되므로 중요한 쪽지는 보관하시기 바랍니다.";
}
if ($msg !== "") { 
    echo '<div class="container"><div class="panel panel-default"><div class="panel-heading">';
    echo "<ul>$msg</ul>";
    echo '</div></div></div>';
} ?>

<?
// 구글 광고를 include
$ad_file = "$memo_skin_path/memo2_adsense.php";
if (file_exists($ad_file)) {
    include_once($ad_file);
}
?>

<script type="text/javascript">
if ('<?=$stx?>') {
    document.fsearch.sfl.value = '<?=$sfl?>';
}
</script>