<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 
?>
<div class="container">
<div class="panel panel-default">
    <div class="panel-heading">
    <strong>�Ű�����</strong>
    </div>
    <div class="panel-body">
        <form name="fsingo" method="post" action="unsingo_popin_update.php" role="form" class="form-inline">
        <input type="hidden" name="bo_table"    value="<?=$bo_table?>">
        <input type="hidden" name="wr_id"       value="<?=$wr_id?>">
        <input type="hidden" name="wr_parent"   value="<?=$wr_parent?>">

        <table class="table table-hover" width=100%>
        <tr class="success">
            <td class="col-sm-2">����</td>
            <td><?=get_text(cut_str($wr_subject, 255))?></td>
        </tr>
        <tr>
            <td>��������</td>
            <td>
                <input class="form-control" type="text" name="unsg_reason" required itemname='�Ű���������' placeholder="�Ű����� ��û ����">
            </td>
        </tr>
        </table>
        
        <div class="well">
           �Ű� ������ �δ��ϴٰ� �ǴܵǴ� ��� �Ű������� �� �� �ֽ��ϴ�.<br>
           �Ű� �����ϰ� �� ������ �ڼ��� ���ֽø� ����� ���� ������ ������ �˴ϴ�.<br>
           <? if ($config[cf_singo_point_send]) { ?>
           <BR>�Ű������� ��û�ϴ� ȸ���� <?=$config[cf_singo_point_send]?> ����Ʈ�� �����˴ϴ�.
           <? } ?>
        </div>

        <div class="container pull-right" style="display:inline-block;text-align:center;">
            <button type="submit" class="btn btn-default">Ȯ��</button>
            &nbsp;&nbsp;
            <a class="btn btn-default" href="javascript:window.close();" >�ݱ�</a>
        </div>

        </form>
    </div>
</div>