<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 
?>
<div class="container">
<div class="panel panel-default">
    <div class="panel-heading">
        <strong>�Ű�</strong>
    </div>
    <div class="panel-body">
        <form role="form" class="form-inline" name="fsingo" method="post" action="singo_popin_update.php">
        <input type="hidden" name="bo_table"    value="<?=$bo_table?>">
        <input type="hidden" name="wr_id"       value="<?=$wr_id?>">
        <input type="hidden" name="wr_parent"   value="<?=$wr_parent?>">
        <input type="hidden" name="wr_subject"  value="<?=$wr_subject?>">
        <input type="hidden" name="wr_ip"       value="<?=$wr_ip?>">
        <input type="hidden" name="wr_datetime" value="<?=$wr_datetime?>">

        <table class="table table-hover" width=100%>
        <tr class="success">
            <td class="col-sm-2">����</td>
            <td><?=get_text(cut_str($wr_subject, 255))?></td>
        </tr>
        <tr>
            <td>�Ű�����</td>
            <td>
                <?
                $sql = " select sg_reason, sg_print from $g4[singo_reason_table] where sg_use = 1 "; // ����ϴ� �Ű� ���� ����� ���� �ɴϴ�.
                $result = sql_query($sql);
                ?>
                <select class="form-control" name=sg_reason_select onchange="this.form.sg_reason.value=this.value;">
                    <option value="">�Ű������� �����Ͻʽÿ�.</option>
                    <?
                    for ($i=0; $row=sql_fetch_array($result); $i++) {
                        $str .= "<option value='$row[sg_reason]'>";
                        //if ($row[sg_print] == 1)
                        //    $str .= "(*�Ű��������) "; // �޽��� ������ �ϴܺ� ����� ���� �������ּ���~!
                        // ������ ������ ���� ���ؼ�... hidden
                        $str .= "$row[sg_reason]";
                        $str .= "</option>";
                    }
                    echo $str;
                    ?>
                </select>
                <input class="form-control" type=text name="sg_reason" required itemname='�Ű�����' placeholder="�Ű�����">
            </td>
        </tr>
        </table>

        <div class="container pull-right" style="display:inline-block;text-align:center;">
            <button type="submit" class="btn btn-default">Ȯ��</button>
            &nbsp;&nbsp;
            <a class="btn btn-default" href="javascript:window.close();" >�ݱ�</a>
        </div>

        </form>

        <div class="container">�������� �Խù��� �Ű��Ͻô� ��� ������ ���縦 ���� �� �ֽ��ϴ�.<br>
        �Ű��ϰ� �� ������ �ڼ��� ���ֽø� ����� ���� ������ ������ �˴ϴ�.<br>
        <? if ($config[cf_singo_point_send]) { ?>
        <BR>�Ű��� ȸ���� <?=$config[cf_singo_point_send]?> ����Ʈ�� �����˴ϴ�.
        <? } ?>
        <? if ($config[cf_singo_point_recv]) { ?>
        <BR>�Ű�� ȸ���� <?=$config[cf_singo_point_recv]?> ����Ʈ�� �����˴ϴ�.
        <? } ?>
        </div>
    </div>
</div>
</div>