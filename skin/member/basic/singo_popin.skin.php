<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>
<div class="container">
<div class="panel panel-default">
    <div class="panel-heading">
    <strong>신고</strong>
    </div>
    <div class="panel-body">
        <form role="form" class="form-inline" name="fsingo" method="post" action="singo_popin_update.php" style="margin:0px;">
        <input type="hidden" name="bo_table"    value="<?=$bo_table?>">
        <input type="hidden" name="wr_id"       value="<?=$wr_id?>">
        <input type="hidden" name="wr_parent"   value="<?=$wr_parent?>">

        <table class="table table-hover" width=100%>
        <tr class="success">
            <td class="col-sm-2">제목</td>
            <td><?=get_text(cut_str($wr_subject, 255))?></td>
        </tr>
        <tr>
            <td>신고이유</td>
            <td>
                <?
                $sql = " select sg_reason, sg_print from $g4[singo_reason_table] where sg_use = 0 "; // 사용하는 신고 사유 목록을 가져 옵니다.
                $result = sql_query($sql);
                ?>
                <select class="form-control" name=sg_reason_select onchange="this.form.sg_reason.value=this.value;">
                    <option value="">신고이유를 선택하십시오.</option>
                    <?
                    for ($i=0; $row=sql_fetch_array($result); $i++) {
                        $str .= "<option value='$row[sg_reason]'";
                        $str .= ">$row[sg_reason]";
                        if ($row[sg_print] == 1)
                            $str .= " (*신고이유출력)"; // 메시지 수정시 하단부 경고문도 같이 수정해주세욤~!
                        $str .= "</option>";
                    }
                    echo $str;
                    ?>
                </select>
                <input class="form-control" type=text name="sg_reason" required itemname='신고이유'>
            </td>
        </tr>
        </table>

        <div class="container pull-right" style="display:inline-block;text-align:center;">
            <button type="submit" class="btn btn-default">확인</button>
            &nbsp;&nbsp;
            <a class="btn btn-default" href="javascript:window.close();" >닫기</a>
        </div>

        </form>

        <div class="container"><small>정상적인 게시물을 신고하시는 경우 본인이 제재를 당할 수 있습니다.<br>
        신고하게 된 이유를 자세히 써주시면 운영자의 관련 결정에 도움이 됩니다.<br>
        (*신고이유출력)으로 표시된 이유를 선택하면 게시글에 신고이유가 출력 됩니다.
        <? if ($config[cf_singo_point_send]) { ?>
        <BR>신고한 회원은 <?=$config[cf_singo_point_send]?> 포인트가 차감됩니다.
        <? } ?>
        <? if ($config[cf_singo_point_recv]) { ?>
        <BR>신고된 회원은 <?=$config[cf_singo_point_recv]?> 포인트가 차감됩니다.
        <? } ?>
        </small>
        </div>
    </div>
</div>
</div>