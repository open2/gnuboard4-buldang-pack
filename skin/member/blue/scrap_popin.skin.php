<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

// ��ũ�� �޸𺰷� �����ϱ�
$sql = " select distinct ms_memo from $g4[scrap_table] where mb_id = '$member[mb_id]' and ms_memo != '' ";
$result = sql_query($sql);
$memo_str = "<select class='form-control' name='ms_memo' onchange=\"javascript:document.getElementById('wr_content').value=this.value;\">";
$memo_str .= "<option value=''>���� ����ϴ� ����޸� �����ϱ�</option>";
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $memo_str .= "<option value='$row[ms_memo]'";
        $memo_str .= ">" . cut_str($row[ms_memo],60,'') . "</option>";
    }
    $memo_str .= "</select>";
?>
<div class="container">
<div class="panel panel-default">
    <div class="panel-heading">
    <strong>��ũ���ϱ�</strong>
    </div>
    <div class="panel-body">
        <form name=f_scrap_popin method=post action="./scrap_popin_update.php" role="form" class="form-inline">
        <input type="hidden" name="bo_table" value="<?=$bo_table?>">
        <input type="hidden" name="wr_id"    value="<?=$wr_id?>">
        <input type="hidden" name="wr_mb_id" value="<?=$write[mb_id]?>">
        <input type="hidden" name="wr_subject" value="<?=$write[wr_subject]?>">

        <table class="table table-hover" width=100%>
        <tr class="success">
            <td class="col-sm-2">����</td>
            <td><?=get_text(cut_str($write[wr_subject], 255))?></td>
        </tr>
        <tr>
            <td>����޸�</td>
            <td>
                <?=$memo_str?>
                <textarea class="form-control" name="wr_content" id="wr_content" rows="3" style="width:90%;"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan=2>
            <ul>
                <li>����޸�� ��ũ���� �з� �� �˻��� �� ���� Ű���� �Դϴ� (��: ��ũ��).</li>
                <li>���ο� ����޸�� ���� �Է��ؾ� �մϴ�.</li>
                <li>����޸� �Է����� �ʾƵ� ������, ���� ����� ���ؼ� �Է��ϸ� �����ϴ�.</li>
            </ul>
            </td>
        </tr>
        </table>

        <div class="container"  style="display: inline-block;text-align: center;">
            <button type="submit" class="btn btn-default">Ȯ��</button>
            &nbsp;&nbsp;
            <a class="btn btn-default" href="javascript:window.close();" >�ݱ�</a>
        </div>
        </form>
    </div>
</div>
</div>