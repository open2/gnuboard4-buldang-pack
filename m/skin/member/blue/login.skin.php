<?
if ( ! defined("_GNUBOARD_")) {
    exit;
} // ���� ������ ���� �Ұ�

if ($g4['https_url']) {
    $login_url = $_GET['url'];
    if ($login_url) {
        if (preg_match("/^\.\.\//", $url)) {
            $login_url = urlencode($g4[url] . "/" . preg_replace("/^\.\.\//", "", $login_url));
        } else {
            $purl = parse_url($g4[url]);
            if ($purl[path]) {
                $path      = urlencode($purl[path]);
                $urlencode = preg_replace("/" . $path . "/", "", $urlencode);
            }
            $login_url = $g4[url] . $urlencode;
        }
    } else {
        $login_url = $g4[url];
    }
} else {
    $login_url = $urlencode;
}
?>
<form name="flogin" class="form-horizontal" method="post" onsubmit="return flogin_submit(this);" autocomplete="off">
    <input type="hidden" name="url" value='<?= $login_url ?>'>

    <div style="margin: 10px">
        <div class="form-group-lg has-feedback">
            <label for="mb_id" class="col-sm-2 control-label">���̵�</label>
            <div class="col-sm-4">
                <input type="text" name="mb_id" id="mb_id" class="form-control" maxLength=20 minlength="2" size=15
                       itemname="���̵�" placeholder="user id">
            </div>
            <span class="form-control-feedback"><i class="material-icons">&#xE853;</i></span>
        </div>
        <div class="form-group-lg has-feedback">
            <label for="mb_password" class="col-sm-2 control-label">�н�����</label>
            <div class="col-sm-4">
                <input type="password" name="mb_password" id="mb_password" class="form-control" maxLength=20 size=15
                       itemname="�н�����" placeholder="password">
            </div>
            <span class="form-control-feedback"><i class="material-icons">&#xE88D;</i></span>
        </div>
        <div class="form-group-lg">
            <div class="col-sm-4">
                <input type="checkbox" id="auto_login" name="auto_login" style="display: none"
                    <?php if (is_mobile()) {     // ������� �⺻ Ȱ��ȭ ?>
                        checked="checked"
                    <? } ?>
                       onclick="if (this.checked) { if (confirm('�ڵ��α����� ����Ͻø� �������� ȸ�����̵�� �н����带 �Է��Ͻ� �ʿ䰡 �����ϴ�.\n\n\������ҿ����� ���������� ����� �� ������ ����� �����Ͽ� �ֽʽÿ�.\n\n�ڵ��α����� ����Ͻðڽ��ϱ�?')) { this.checked = true; } else { this.checked = false;} }">
                <label for="auto_login">�α��� ���� ����</label>
            </div>
        </div>
        <div style="margin-bottom: 30px">
            <button type="submit" accesskey="s" class="btn btn-primary btn-lg btn-block">
                �α���
            </button>
        </div>
        <div class="text-center" style="margin-bottom: 30px">
            <a href="javascript:    ;" onclick="win_password_lost();" class="btn btn-default">���̵�/��й�ȣ ã��</a>
            <a href="./register.php" class="btn btn-default">ȸ������</a>
        </div>
    </div>
</form>

<script type='text/javascript'>
    document.flogin.mb_id.focus();

    function flogin_submit(f) {
        <?
        if ($g4[https_url]) {
            echo "f.action = '$g4[https_url]/$g4[bbs]/login_check.php';";
        } else {
            echo "f.action = '$g4[bbs_path]/login_check.php';";
        }
        ?>

        return true;
    }
</script>