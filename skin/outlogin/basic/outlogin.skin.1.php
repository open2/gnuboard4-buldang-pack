<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

if ($g4['https_url']) {
    $outlogin_url = $_GET['url'];
    if ($outlogin_url) {
        if (preg_match("/^\.\.\//", $outlogin_url)) {
            $outlogin_url = urlencode($g4[url]."/".preg_replace("/^\.\.\//", "", $outlogin_url));
        }
        else {
            $purl = parse_url($g4[url]);
            if ($purl[path]) {
                $path = urlencode($purl[path]);
                $urlencode = preg_replace("/".$path."/", "", $urlencode);
            }
            $outlogin_url = $g4[url].$urlencode;
        }
    }
    else {
        $outlogin_url = $g4[url];
    }
}
else {
    $outlogin_url = $urlencode;
}
?>

<!-- �α��� �� �ܺηα��� ���� -->
<form name="fhead" method="post" onsubmit="return fhead_submit(this);" autocomplete="off" role="form" class="form-inline">
<input type="hidden" name="url" value="<?=$outlogin_url?>">

<input type="text" class="form-control" style="width:100%" name="mb_id" id="mb_id" maxlength="20" itemname="���̵�" placeholder="login id">
<label for="mb_id" class="sr-only">member_id</label>
<input type="password" class="form-control" style="width:100%;margin-top:-1px;" name="mb_password" id="outlogin_mb_password" maxlength="20" itemname="�н�����" placeholder="password">
<label for="outlogin_mb_password" class="sr-only">password</label>

<div class="input-group" style="margin-top:-1px;width:100%">
    <span class="input-group-addon">
        <div class="checkbox custom">
        <label>
        <input type="checkbox" name="auto_login" title="�ڵ��α���" value="1" onclick="if (this.checked) { if (confirm('�ڵ��α����� ����Ͻø� �������� ȸ�����̵�� �н����带 �Է��Ͻ� �ʿ䰡 �����ϴ�.\n\n\������ҿ����� ���������� ����� �� ������ ����� �����Ͽ� �ֽʽÿ�.\n\n�ڵ��α����� ����Ͻðڽ��ϱ�?')) { this.checked = true; } else { this.checked = false; } }">
        Auto
        </label>
        </div>
    </span>
    <button type="submit" class="btn btn-default btn-group-justified">Login</button>
</div>
    
<div class="btn-group btn-group-justified" style="margin-bottom:3px;">
    <a class="btn btn-default" style="border-color: #ffffff;" title="ȸ������" href="<?=$g4[bbs_path]?>/register.php">ȸ������</a>
    <a class="btn btn-default" style="border-color: #ffffff;" title="ȸ�� id, password ã��" href="javascript:win_password_lost();">���̵�ã��</a>
</div>

</form>

<script type="text/javascript">
function fhead_submit(f)
{
    if (!f.mb_id.value)
    {
        alert("ȸ�����̵� �Է��Ͻʽÿ�.");
        f.mb_id.focus();
        return false;
    }

    if (!f.mb_password.value)
    {
        alert("�н����带 �Է��Ͻʽÿ�.");
        f.mb_password.focus();
        return false;
    }

    <?
    if ($g4[https_url])
        echo "f.action = '$g4[https_url]/$g4[bbs]/login_check.php';";
    else
        echo "f.action = '$g4[bbs_path]/login_check.php';";
    ?>

    return true;
}
</script>
<!-- �α��� �� �ܺηα��� �� -->
