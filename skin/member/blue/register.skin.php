<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 
?>

<form name="fregister" method="POST" onsubmit="return fregister_submit(this);" autocomplete="off" role="form" class="form-horizontal">
<div class="panel panel-default">
    <div class="panel-heading"><strong>ȸ����� �� ����������޹�ħ�� ���뿡 �����ϼž� ȸ������ �Ͻ� �� �ֽ��ϴ�.</strong>
    </div>

    <div class="panel-body">

        <? if ($g4['member_suggest_join']) { // ��õ+�����������θ� ���԰����ϰ� ?>
        <p><?=$config['cf_title']?>�� ���� ȸ���� ��õ�� ���ؼ��� ȸ�� ������ ����</b>�ϸ�,<br>
        ��õ�� ���̵�� ������ȣ�� ȸ������ ������ ���Ŀ��� ����� �� �����ϴ� (1���� ��õ=1�� ����).<br>
        ȸ�� ���Թ��Ǵ� <?=$config['cf_title']?> ȸ���е鲲 �Ͻñ⸦ �ٶ��ϴ�.
        </p>

        <label for="mb_recommend" class="control-label">��õ�ξ��̵�</label>
        <input name=mb_recommend itemname="��õ�ξ��̵�" required placeholder="��õ�ξ��̵�" class="form-control">

        <label for="join_code" class="control-label">����������ȣ</label>
        <input name=join_code itemname="����������ȣ" required maxlength=6 placeholder="����������ȣ" class="form-control">

        <br>
        <? } ?>

        <label>ȸ�����</label>
        <textarea style="width:100%" class="form-control" rows=5 readonly><?=get_text($config[cf_stipulation])?></textarea>
        <div class="checkbox">
            <label>
            <input type="checkbox" value=1 name=agree id=agree>
            �����մϴ�.
            </label>
        </div>
        <br>

        <label>�������� ��޹�ħ</label>
        <ul class="nav nav-tabs">
            <li class="active"><a href="#cf_privacy_2" data-toggle="tab">���� �� �̿����</a></li>
            <li><a href="#cf_privacy_3" data-toggle="tab">����</a></li>
            <li><a href="#cf_privacy_1" data-toggle="tab">�����ϴ� �׸�</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="cf_privacy_2"><textarea style="width: 100%;" rows=5 readonly class="form-control"><?=get_text($config[cf_privacy_2])?></textarea></div>
            <div class="tab-pane" id="cf_privacy_3"><textarea style="width: 100%;" rows=5 readonly class="form-control"><?=get_text($config[cf_privacy_3])?></textarea></div>
            <div class="tab-pane" id="cf_privacy_1"><textarea style="width: 100%;" rows=5 readonly class="form-control"><?=get_text($config[cf_privacy_1])?></textarea></div>
        </div>

        <div class="checkbox">
            <label>
            <input type="checkbox" value=1 name=agree2 id=agree2>
            �����մϴ�.
            </label>
        </div>
        <br>

        <? if (trim($config[cf_privacy_4])) { ?>
        <table width="100%">
        <tr> 
           <td>
           <textarea style="width: 100%" rows=4 readonly class="form-control"><?=get_text($config[cf_privacy_4])?></textarea>
           </td>
        </tr>
        </table>
        <div class="checkbox">
            <label>
            <input type="checkbox" value=1 name=agree4 id=agree4>
            �����մϴ�.
            </label>
        </div>
        <? } ?>
    </div>

    <div class="panel-footer">
    <button class="btn btn-success">�����մϴ�</button>
    &nbsp;&nbsp;&nbsp;
    <a href="javascript:" class="btn btn-default" onClick="history.go(-1);">�������� �ʽ��ϴ�</a>
    </div>
</div>
</form>


<script type="text/javascript">
function fregister_submit(f) 
{
    var agree1 = document.getElementsByName("agree");
    if (!agree1[0].checked) {
        alert("ȸ�����Ծ���� ���뿡 �����ϼž� ȸ������ �Ͻ� �� �ֽ��ϴ�.");
        agree1[0].focus();
        return false;
    }

    var agree2 = document.getElementsByName("agree2");
    if (!agree2[0].checked) {
        alert("����������޹�ħ�� ���뿡 �����ϼž� ȸ������ �Ͻ� �� �ֽ��ϴ�.");
        agree2[0].focus();
        return false;
    }

    f.action = './register_form.php';
    return true;
}

if (typeof(document.fregister.mb_name) != "undefined")
    document.fregister.mb_name.focus();
</script>
