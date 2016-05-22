<?
if ( ! defined("_GNUBOARD_")) {
    exit;
} // 개별 페이지 접근 불가

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
            <label for="mb_id" class="col-sm-2 control-label">아이디</label>
            <div class="col-sm-4">
                <input type="text" name="mb_id" id="mb_id" class="form-control" maxLength=20 minlength="2" size=15
                       itemname="아이디" placeholder="user id">
            </div>
            <span class="form-control-feedback"><i class="material-icons">&#xE853;</i></span>
        </div>
        <div class="form-group-lg has-feedback">
            <label for="mb_password" class="col-sm-2 control-label">패스워드</label>
            <div class="col-sm-4">
                <input type="password" name="mb_password" id="mb_password" class="form-control" maxLength=20 size=15
                       itemname="패스워드" placeholder="password">
            </div>
            <span class="form-control-feedback"><i class="material-icons">&#xE88D;</i></span>
        </div>
        <div class="form-group-lg">
            <div class="col-sm-4">
                <input type="checkbox" id="auto_login" name="auto_login" style="display: none"
                    <?php if (is_mobile()) {     // 모바일은 기본 활성화 ?>
                        checked="checked"
                    <? } ?>
                       onclick="if (this.checked) { if (confirm('자동로그인을 사용하시면 다음부터 회원아이디와 패스워드를 입력하실 필요가 없습니다.\n\n\공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?')) { this.checked = true; } else { this.checked = false;} }">
                <label for="auto_login">로그인 상태 유지</label>
            </div>
        </div>
        <div style="margin-bottom: 30px">
            <button type="submit" accesskey="s" class="btn btn-primary btn-lg btn-block">
                로그인
            </button>
        </div>
        <div class="text-center" style="margin-bottom: 30px">
            <a href="javascript:    ;" onclick="win_password_lost();" class="btn btn-default">아이디/비밀번호 찾기</a>
            <a href="./register.php" class="btn btn-default">회원가입</a>
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