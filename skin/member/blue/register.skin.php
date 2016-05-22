<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>

<form name="fregister" method="POST" onsubmit="return fregister_submit(this);" autocomplete="off" role="form" class="form-horizontal">
<div class="panel panel-default">
    <div class="panel-heading"><strong>회원약관 및 개인정보취급방침의 내용에 동의하셔야 회원가입 하실 수 있습니다.</strong>
    </div>

    <div class="panel-body">

        <? if ($g4['member_suggest_join']) { // 추천+가입인증으로만 가입가능하게 ?>
        <p><?=$config['cf_title']?>는 기존 회원의 추천을 통해서만 회원 가입이 가능</b>하며,<br>
        추천인 아이디와 인증번호는 회원으로 가입한 이후에는 사용할 수 없습니다 (1번의 추천=1번 가입).<br>
        회원 가입문의는 <?=$config['cf_title']?> 회원분들께 하시기를 바랍니다.
        </p>

        <label for="mb_recommend" class="control-label">추천인아이디</label>
        <input name=mb_recommend itemname="추천인아이디" required placeholder="추천인아이디" class="form-control">

        <label for="join_code" class="control-label">가입인증번호</label>
        <input name=join_code itemname="가입인증번호" required maxlength=6 placeholder="가입인증번호" class="form-control">

        <br>
        <? } ?>

        <label>회원약관</label>
        <textarea style="width:100%" class="form-control" rows=5 readonly><?=get_text($config[cf_stipulation])?></textarea>
        <div class="checkbox">
            <label>
            <input type="checkbox" value=1 name=agree id=agree>
            동의합니다.
            </label>
        </div>
        <br>

        <label>개인정보 취급방침</label>
        <ul class="nav nav-tabs">
            <li class="active"><a href="#cf_privacy_2" data-toggle="tab">수집 및 이용목적</a></li>
            <li><a href="#cf_privacy_3" data-toggle="tab">보유</a></li>
            <li><a href="#cf_privacy_1" data-toggle="tab">수집하는 항목</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="cf_privacy_2"><textarea style="width: 100%;" rows=5 readonly class="form-control"><?=get_text($config[cf_privacy_2])?></textarea></div>
            <div class="tab-pane" id="cf_privacy_3"><textarea style="width: 100%;" rows=5 readonly class="form-control"><?=get_text($config[cf_privacy_3])?></textarea></div>
            <div class="tab-pane" id="cf_privacy_1"><textarea style="width: 100%;" rows=5 readonly class="form-control"><?=get_text($config[cf_privacy_1])?></textarea></div>
        </div>

        <div class="checkbox">
            <label>
            <input type="checkbox" value=1 name=agree2 id=agree2>
            동의합니다.
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
            동의합니다.
            </label>
        </div>
        <? } ?>
    </div>

    <div class="panel-footer">
    <button class="btn btn-success">동의합니다</button>
    &nbsp;&nbsp;&nbsp;
    <a href="javascript:" class="btn btn-default" onClick="history.go(-1);">동의하지 않습니다</a>
    </div>
</div>
</form>


<script type="text/javascript">
function fregister_submit(f) 
{
    var agree1 = document.getElementsByName("agree");
    if (!agree1[0].checked) {
        alert("회원가입약관의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
        agree1[0].focus();
        return false;
    }

    var agree2 = document.getElementsByName("agree2");
    if (!agree2[0].checked) {
        alert("개인정보취급방침의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
        agree2[0].focus();
        return false;
    }

    f.action = './register_form.php';
    return true;
}

if (typeof(document.fregister.mb_name) != "undefined")
    document.fregister.mb_name.focus();
</script>
