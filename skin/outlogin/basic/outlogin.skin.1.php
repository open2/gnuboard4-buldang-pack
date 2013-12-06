<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

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

<!-- 로그인 전 외부로그인 시작 -->
<form name="fhead" method="post" onsubmit="return fhead_submit(this);" autocomplete="off" role="form" class="form-inline">
<input type="hidden" name="url" value="<?=$outlogin_url?>">
<input type="text" class="form-control" name="mb_id" maxlength="20" itemname="아이디" placeholder="login id">

<div class="input-group">
    <input type="password" class="form-control" name="mb_password" id="outlogin_mb_password" maxlength="20" itemname="패스워드" placeholder="password">
    <span class="input-group-addon">
        <div class="checkbox">
        <label>
        <input type="checkbox" name="auto_login" title="자동로그인" value="1" onclick="if (this.checked) { if (confirm('자동로그인을 사용하시면 다음부터 회원아이디와 패스워드를 입력하실 필요가 없습니다.\n\n\공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?')) { this.checked = true; } else { this.checked = false; } }">
        Auto
        </label>
        </div>
    </span>
</div>
<button type="submit" class="btn btn-default btn-group-justified" >Login</button>
    <div class="btn-group btn-group-justified">
        <a class="btn btn-default" style="border-color: #ffffff;" title="회원가입" href="<?=$g4[bbs_path]?>/register.php">회원가입</a>
        <a class="btn btn-default" style="border-color: #ffffff;" title="회원 id, password 찾기" href="javascript:win_password_lost();">아이디찾기</a>
    </div>

</form>

<script type="text/javascript">
function fhead_submit(f)
{
    if (!f.mb_id.value)
    {
        alert("회원아이디를 입력하십시오.");
        f.mb_id.focus();
        return false;
    }

    if (!f.mb_password.value)
    {
        alert("패스워드를 입력하십시오.");
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
<!-- 로그인 전 외부로그인 끝 -->
