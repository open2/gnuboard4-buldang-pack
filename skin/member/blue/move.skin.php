<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ�
?>

<div class="container">
<div class="panel panel-default">
    <div class="panel-heading">
    <strong>�Խù�<?=$act?></strong>
    <span class="pull-right"><?=$act?>�� �Խ����� �Ѱ� �̻� �����Ͽ� �ֽʽÿ�.</span>
    </div>
    <div class="panel-body">
        <form name="fboardmoveall" method="post" onsubmit="return fboardmoveall_submit(this);">
        <input type=hidden name=sw          value='<?=$sw?>'>
        <input type=hidden name=bo_table    value='<?=$bo_table?>'>
        <input type=hidden name=wr_id_list  value="<?=$wr_id_list?>">
        <input type=hidden name=sfl         value='<?=$sfl?>'>
        <input type=hidden name=stx         value='<?=$stx?>'>
        <input type=hidden name=spt         value='<?=$spt?>'>
        <input type=hidden name=page        value='<?=$page?>'>
        <input type=hidden name=act         value='<?=$act?>'>

        <table class="table table-condensed table-hover" width=100%>
        <? for ($i=0; $i<count($list); $i++) { ?>
        <tr>
            <td width="39px"><input type=checkbox id='chk<?=$i?>' name='chk_bo_table[]' value="<?=$list[$i][bo_table]?>"></td>
            <td>
                <span style="cursor:pointer;" onclick="document.getElementById('chk<?=$i?>').checked=document.getElementById('chk<?=$i?>').checked?'':'checked';">
                <?
                if ($save_gr_subject==$list[$i][gr_subject])
                    echo "<span style='color:#cccccc;'>";
                else
                    echo "<span>";
                echo $list[$i][gr_subject] . " > ";
                echo "</span>";
                $save_gr_subject = $list[$i][gr_subject];
                ?>
                <?=$list[$i][bo_subject]?> (<?=$list[$i][bo_table]?>)
                </span>
            </td>
        </tr>
        <? } ?>
        </table>

        <div class="container pull-right" style="display: inline-block;text-align: center;">
            <button type="submit" class="btn btn-default">Ȯ��</button>
            &nbsp;&nbsp;
            <a class="btn btn-default" href="javascript:window.close();" >�ݱ�</a>
        </div>
      
        </form>
    </div>
</div>
</div>

<script type="text/javascript">
function fboardmoveall_submit(f)
{
    var check = false;

    if (typeof(f.elements['chk_bo_table[]']) == 'undefined') 
        ;
    else {
        if (typeof(f.elements['chk_bo_table[]'].length) == 'undefined') {
            if (f.elements['chk_bo_table[]'].checked) 
                check = true;
        } else {
            for (i=0; i<f.elements['chk_bo_table[]'].length; i++) {
                if (f.elements['chk_bo_table[]'][i].checked) {
                    check = true;
                    break;
                }
            }
        }
    }

    if (!check) {
        alert('�Խù��� '+f.act.value+'�� �Խ����� �Ѱ� �̻� ������ �ֽʽÿ�.');
        return false;
    }

    f.action = "./move_update.php";
    return true;
}
</script>