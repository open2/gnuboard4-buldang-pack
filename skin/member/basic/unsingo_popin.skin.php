<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>
<div class="container">
<div class="panel panel-default">
    <div class="panel-heading">
    <strong>신고해제</strong>
    </div>
    <div class="panel-body">
        <form name="fsingo" method="post" action="unsingo_popin_update.php" role="form" class="form-inline">
        <input type="hidden" name="bo_table"    value="<?=$bo_table?>">
        <input type="hidden" name="wr_id"       value="<?=$wr_id?>">
        <input type="hidden" name="wr_parent"   value="<?=$wr_parent?>">

        <table class="table table-hover" width=100%>
        <tr class="success">
            <td class="col-sm-2">제목</td>
            <td><?=get_text(cut_str($wr_subject, 255))?></td>
        </tr>
        <tr>
            <td>해제사유</td>
            <td>
                <input class="form-control" type="text" name="unsg_reason" required itemname='신고해제이유' placeholder="신고해제 요청 사유">
            </td>
        </tr>
        </table>
        
        <div class="well">
           신고가 사유가 부당하다고 판단되는 경우 신고해제를 할 수 있습니다.<br>
           신고를 해제하게 된 이유를 자세히 써주시면 운영자의 관련 결정에 도움이 됩니다.<br>
           <? if ($config[cf_singo_point_send]) { ?>
           <BR>신고해제를 신청하는 회원은 <?=$config[cf_singo_point_send]?> 포인트가 차감됩니다.
           <? } ?>
        </div>

        <div class="container pull-right" style="display:inline-block;text-align:center;">
            <button type="submit" class="btn btn-default">확인</button>
            &nbsp;&nbsp;
            <a class="btn btn-default" href="javascript:window.close();" >닫기</a>
        </div>

        </form>
    </div>
</div>