<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>
<script type="text/javascript">
var member_skin_path = "<?=$member_skin_path?>";
</script>
<script type="text/javascript" src="<?=$member_skin_path?>/jquery.ajax_register_form.js"></script>
<script type="text/javascript" src="<?=$g4[path]?>/js/md5.js"></script>

<form class="form-horizontal" role="form" name=fregisterform id=fregisterform method=post onsubmit="return fregisterform_submit(this);" enctype="multipart/form-data" autocomplete="off">
<input type=hidden name=w                id=w                   value="<?=$w?>">
<input type=hidden name=url              id=url                 value="<?=$urlencode?>">
<input type=hidden name=mb_id_enabled    id="mb_id_enabled"     value="" >
<input type=hidden name=mb_nick_enabled  id="mb_nick_enabled"   value="" >
<input type=hidden name=mb_email_enabled id="mb_email_enabled"  value="" >
<input type=hidden name=mb_name_enabled  id="mb_name_enabled"   value="" >
<input type=hidden name=ug_id            id="ug_id"             value="<?=$ug_id?>" >
<input type=hidden name=join_code        id="join_code"         value="<?=$join_code?>" >
<input type=hidden name=token            id="token"             value="<?=$token?>" >

<div class="panel panel-default">
    <div class="panel-heading"><h4><strong>회원정보</strong></h4>
    </div>
    <div class="panel-body">

        <div class="form-group">
            <label for="mb_id" class="col-sm-2 control-label">아이디</label>
            <div class="col-sm-6">
                <input class="form-control" placeholder="User id" maxlength=20 size=20 id='mb_id' name="mb_id" required style="ime-mode:disabled" value="<?=$member[mb_id]?>" 
                <? if ($w=='u') { echo "disabled"; } ?>
                <? if ($w=='') { echo "onblur='reg_mb_id_check()'"; } ?>>
                <? if ($w=='') { ?>
                    <p class="help-block">영문자, 숫자, _ 만 입력 가능. 3자이상 입력하세요.</p>
      	    				<p class="help-block"><span id="msg_mb_id"></span></p>
                <? } ?>
            </div>
        </div>
    
        <div class="form-group">
            <label for="mb_password" class="col-sm-2 control-label">패스워드</label>
            <div class="col-sm-6">
                <INPUT class="form-control" type="password" name="mb_password" id="mb_password" style="ime-mode:disabled" size=20 minlength=8 maxlength=20 <?=($w=="")?"required":"";?> itemname="패스워드" onblur="passwordStrength(this.value)" placeholder="Password">
            </div>
        </div>

        <div class="form-group">
            <label for="mb_password_re" class="col-sm-2 control-label">패스워드확인</label>
            <div class="col-sm-6">
                <INPUT class="form-control" type="password" name="mb_password_re" style="ime-mode:disabled" size=20 minlength=8 maxlength=20 <?=($w=="")?"required":"";?> itemname="패스워드 확인" placeholder="Password를 한번 더 입력">
                <p class="help-block">비밀번호는 8자 이상으로 쉽게 추정할 수 없게 숫자와 영문자를 섞어서 만들면 안전합니다.</p>
            </div>
        </div>

        <? if ($g4['nick_reg_only'] !== 1) { ?>
        <div class="form-group">
            <label for="mb_password_re" class="col-sm-2 control-label">이름</label>
            <div class="col-sm-6">
                <INPUT class="form-control" type="text" name=mb_name id=mb_name size=20 maxlength=20 required itemname="이름" value="<?=$member[mb_name]?>" placeholder="Name"
                <? if ($w=='u') { echo "disabled"; } ?>
                <? if ($w=='') { echo "onblur='reg_mb_name_check()'"; } ?>>
                <? if ($w=='') { ?>
                    <p class="help-block">공백없이 한글 또는 영문만 입력 가능합니다.</p>
      	    				<p class="help-block"><span id="msg_mb_name"></span></p>
                <? } ?>
            </div>
        </div>
        <? } ?>

        <? if ($member[mb_nick_date] <= date("Y-m-d", $g4[server_time] - ($config[cf_nick_modify] * 86400))) { // 별명수정일이 지났다면 수정가능 ?>
        <input type=hidden name=mb_nick_default value='<?=$member[mb_nick]?>'>
        <div class="form-group">
            <label for="mb_nick" class="col-sm-2 control-label">닉네임</label>
            <div class="col-sm-6">
                <input class="form-control" type="text" id='mb_nick' name='mb_nick' required hangulalphanumeric maxlength=20 value='<?=$member[mb_nick]?>' placeholder="Nick name" onblur="reg_mb_nick_check();">
                    <p class="help-block">공백없이 한글,영문,숫자만 입력 가능 (한글2자, 영문4자 이상)<br>
                    별명을 바꾸시면 앞으로 <?=(int)$config[cf_nick_modify]?>일 이내에는 변경 할 수 없습니다.</p>
      	    				<p class="help-block"><span id="msg_mb_nick"></span></p>
            </div>
        </div>
        <? } else { ?>
        <input type=hidden name="mb_nick_default" value='<?=$member[mb_nick]?>'>
        <div class="form-group">
            <label for="mb_nick" class="col-sm-2 control-label">닉네임</label>
            <div class="col-sm-6">
                <?
                $d_times = (int)(($config[cf_nick_modify] * 86400 - ( $g4[server_time] - strtotime($member[mb_nick_date]))) / 86400) + 1;
                ?>
                <input class="form-control" disabled type="text" id='mb_nick' name='mb_nick' value='<?=$member[mb_nick]?>'>
                    <p class="help-block">※ <?=$d_times?>일 후 변경이 가능 합니다.</p>
            </div>
        </div>
        <? } ?>

        <input type=hidden name='old_email' value='<?=$member[mb_email]?>'>
        <div class="form-group">
            <label for="mb_email" class="col-sm-2 control-label">E-mail</label>
            <div class="col-sm-6">
                <input class="form-control" type="text" id='mb_email' name='mb_email' required style="ime-mode:disabled" size=38 maxlength=100 value='<?=$member[mb_email]?>' placeholder="E-mail"
                    onblur="reg_mb_email_check()">
                <? if ($w=='') { ?>
                    <p class="help-block">아이디, 비밀번호 분실 시 본인확인용으로 사용되므로<br>유효한 이메일 계정으로 입력하시기 바랍니다.</p>
      	    				<p class="help-block"><span id="msg_mb_email"></span></p>
      	    		<?}?>
                <? if ($member[mb_email_certify]) {?>
                    <p class="help-block"><?=cut_str($member[mb_email_certify],10,"")?> 에 인증되었습니다.</p>
                <? } ?>
                <? if ($config[cf_use_email_certify]) { ?>
                    <? if ($w=='') { ?><p class="help-block">e-mail 로 발송된 내용을 확인한 후 인증하셔야 회원가입이 완료됩니다.</p><?}?>
                    <? if ($w=='u'){ ?><p class="help-block">e-mail 주소를 변경하시면 다시 인증하셔야 합니다.</p><?}?>
                <? } ?>
                <? if ($w=='u') {
                    if ($g4['email_certify_point'] || $config['cf_use_email_certify']) ?>
                        <p class="help-block"><a href='<?=$g4[bbs_path]?>/email_re_certify.php' target=new>이메일인증하러 가기</a>를 누르시면 인증창이 열립니다.</p>
                <?}?>
            </div>
        </div>

        <? if ($w=="") { ?>
            <? if ($config[cf_use_birthdate]) { ?>
            <div class="form-group">
                <label for="mb_birth" class="col-sm-2 control-label">생년월일</label>
                <div class="col-sm-6">
                    <div class="input-group">
                    <span class="input-group-btn">
                    <a class="btn btn-default" href="javascript:win_calendar('mb_birth', document.getElementById('mb_birth').value, '');"><i class="fa fa-calendar"></i></a>
                    </span>
                    <INPUT class="form-control" type="text" id="mb_birth" name='mb_birth' size=8 maxlength=8 minlength=8 required numeric itemname='생년월일' value='<?=$member[mb_birth]?>' readonly title='옆의 달력 아이콘을 클릭하여 날짜를 입력하세요.'>
                    </div>
                </div>
            </div>
            <? } ?>
        <? } ?>

        <? if ($member[mb_sex]) { ?>
        <input type=hidden name=mb_sex value='<?=$member[mb_sex]?>'>
        <div class="form-group">
            <label for="mb_sex" class="col-sm-2 control-label">성별</label>
            <div class="col-sm-6">
                <p class="help-block">
                    <? 
                    switch ($member[mb_sex]) {
                      case 'F' : echo "여자"; break;
                      case 'M' : echo "남자"; break;
                    }
                    ?>
                </p>
            </div>
        </div>
        <? } else { ?>
            <? if ($config[cf_use_sex]) { ?>
            <div class="form-group">
                <label for="mb_sex" class="col-sm-2 control-label">성별</label>
                <div class="col-sm-6">
                    <div class="radio-inline">
                        <label>
                        <input type="radio" name="mb_sex" id="mb_sex1" value="F" checked>여성
                        </label>
                    </div>
                    <div class="radio-inline">
                        <label>
                        <input type="radio" name="mb_sex" id="mb_sex2" value="M">남성
                        </label>
                    </div>
                </div>
            </div>
            <? } ?>
        <? } ?>

        <? if ($config[cf_use_hp]) { ?>
        <div class="form-group">
            <label for="mb_homepage" class="col-sm-2 control-label">핸드폰번호</label>
            <div class="col-sm-6">
                <?
                if ($member[mb_hp_certify_datetime] && $member[mb_hp_certify_datetime] !== '0000-00-00 00:00:00') {
                    echo "$member[mb_hp_certify_datetime] 에 인증하였습니다.<br>"; 
                    echo "<input type='hidden' name='mb_hp_old' value='$member[mb_hp]'>"; 
                } 
                ?>
                <?if ($w=='u') { ?>
                    <input type=text name='mb_hp' size=21 maxlength=20 <?=$config[cf_req_hp]?'required':'';?> itemname='핸드폰번호' value='<?=$member[mb_hp]?>'>
                    <? if ($config[cf_hp_certify]) { ?>
                        <input type=button value='인증번호 전송' onclick="hp_certify(this.form);">  
                        인증번호 : <input class=m_text type=text name='mb_hp_certify' size=6 maxlength=6> 6자리 숫자<br> 
                        <span> 
                            핸드폰으로 전송된 인증번호를 입력 후 회원정보를 수정(확인 버튼)하시기 바랍니다.<br> 
                        </span> 
                        <script> 
                        function hp_certify(f) { 
                            var pattern = /^01[0-9][-]{0,1}[0-9]{3,4}[-]{0,1}[0-9]{4}$/; 
                            if(!pattern.test(f.mb_hp.value)){  
                                alert("핸드폰 번호가 입력되지 않았거나 번호가 틀립니다.\n\n핸드폰 번호를 010-123-4567 또는 01012345678 과 같이 입력해 주십시오."); 
                                f.mb_hp.select(); 
                                f.mb_hp.focus(); 
                                return; 
                            } 
                            win_open("<?=$g4[sms_path]?>/hp_certify.php?hp="+f.mb_hp.value+"&token=<?=$token?>", "hiddenframe"); 
                        } 
                        </script>
                    <? } ?>
                <? } else { ?>
                    <input class="form-control" type=text name='mb_hp' size=21 maxlength=20 <?=$config[cf_req_hp]?'required':'';?> itemname='핸드폰번호' value='<?=$member[mb_hp]?>'>
                <? } ?>
            </div>
        </div>
        <? } ?>

        <? if ($config[cf_use_tel]) { ?>
        <div class="form-group">
            <label for="mb_tel" class="col-sm-2 control-label">전화번호</label>
            <div class="col-sm-6">
                <INPUT class="form-control" type="text" name="mb_tel" size=21 maxlength=20 <?=$config[cf_req_tel]?'required':'';?> itemname='전화번호' value='<?=$member[mb_tel]?>' placeholder="Telephone no.">
            </div>
        </div>
        <? } ?>

        <? if ($config[cf_use_homepage]) { ?>
        <div class="form-group">
            <label for="mb_homepage" class="col-sm-2 control-label">홈페이지</label>
            <div class="col-sm-6">
                <INPUT class="form-control" type="text" name="mb_homepage" id="mb_homepage" size=38 maxlength=255 <?=$config[cf_req_homepage]?'required':'';?> itemname='홈페이지' value='<?=$member[mb_homepage]?>' placeholder="Homepage URL">
            </div>
        </div>
        <? } ?>

        <? if ($config[cf_use_addr]) { ?>
        <div class="form-group">
            <label class="col-sm-2 control-label">주소</label>
            <div class="col-sm-6">
                <input class=m_text type=text name='mb_zip1' size=4 maxlength=3 readonly <?=$config[cf_req_addr]?'required':'';?> itemname='우편번호 앞자리' value='<?=$member[mb_zip1]?>'>
                - 
                <input class=m_text type=text name='mb_zip2' size=4 maxlength=3 readonly <?=$config[cf_req_addr]?'required':'';?> itemname='우편번호 뒷자리' value='<?=$member[mb_zip2]?>'>
                &nbsp;<a href="javascript:;" onclick="win_zip('fregisterform', 'mb_zip1', 'mb_zip2', 'mb_addr1', 'mb_addr2');"><img width="91" height="20" src="<?=$member_skin_path?>/img/post_search_btn.gif" border=0 align=absmiddle></a>
                <input class="form-control" type=text name='mb_addr1' size=60 readonly <?=$config[cf_req_addr]?'required':'';?> itemname='주소' value='<?=$member[mb_addr1]?>'>
                <input class="form-control" type=text name='mb_addr2' size=60 <?=$config[cf_req_addr]?'required':'';?> itemname='상세주소' value='<?=$member[mb_addr2]?>'>
            </div>
        </div>
        <? } ?>

        <div class="form-group">
            <label for="mb_mailling" class="col-sm-2 control-label">메일링서비스</label>
            <div class="col-sm-6">
            <div class="checkbox">
						<label>
                <input type="checkbox" name="mb_mailling" id="mb_mailling" value='1' <?=($w=='' || $member[mb_mailling])?'checked':'';?>>
                정보 메일을 받겠습니다.
						</label>
						</div>
            </div>
        </div>

        <? if ($config[cf_use_hp]) { ?>
        <div class="form-group">
            <label for="mb_sms" class="col-sm-2 control-label">SMS 수신여부</label>
            <div class="col-sm-6">
            <div class="checkbox">
						<label>
                <input type="checkbox" name="mb_sms" value='1' <?=($w=='' || $member[mb_sms])?'checked':'';?>>
                핸드폰 문자메세지를 받겠습니다.
						</label>
						</div>
            </div>
        </div>
        <? } ?>

        <? if ($member[mb_open_date] <= date("Y-m-d", $g4[server_time] - ($config[cf_open_modify] * 86400)) || !$member['mb_open']) { // 정보공개 수정일이 지났다면 수정가능 ?>
        <input type=hidden name=mb_open_default value='<?=$member[mb_open]?>'>
        <div class="form-group">
            <label for="mb_open" class="col-sm-2 control-label">정보공개</label>
            <div class="col-sm-6">
            <div class="checkbox">
						<label>
                <input type="checkbox" name="mb_open" id="mb_open" value='1' <?=($w=='' || $member[mb_open])?'checked':'';?>>
                다른 회원들이 나의 정보를 볼 수 있도록 합니다.
						</label>
						</div>
                <? if ($config[cf_open_modify]) { ?>
                <p class="help-block">정보공개를 바꾸시면 앞으로 <?=(int)$config[cf_open_modify]?>일 이내에는 변경이 안됩니다.</p>
                <? } ?>
            </div>
        </div>        
        <? } else { ?>
        <input type=hidden name="mb_open" value="<?=$member[mb_open]?>">
        <div class="form-group">
            <?
            $d_times = (int)(($config[cf_open_modify] * 86400 - ( $g4[server_time] - strtotime($member[mb_open_date]))) / 86400) + 1;
            if ($member[mb_open]) $msg="정보공개"; else $msg = "정보비공개";
            ?>
            <label for="mb_open" class="col-sm-2 control-label">정보공개</label>
            <div class="col-sm-6">
                <p class="help-block">
                현재 <?=$msg?> 상태이며, <?=$member[mb_open_date]?>일 정보를 수정했습니다.<br>
                정보공개는 수정후 <?=(int)$config[cf_open_modify]?>일 이내, <?=date("Y년 m월 j일", strtotime("$member[mb_open_date] 00:00:00") + ($config[cf_open_modify] * 86400))?> 까지는 변경이 안됩니다.<br> 
                </p>
            </div>
        </div>         
        <? } ?>

        <? if ($config[cf_use_signature]) { ?>
        <div class="form-group">
            <label for="mb_open" class="col-sm-2 control-label">서명</label>
            <div class="col-sm-6">
                <textarea name=mb_signature class="form-control" rows=3 style='width:100%;' <?=$config[cf_req_signature]?'required':'';?> itemname='서명'><?=$member[mb_signature]?></textarea>
        </div>
        <? } ?>

        <? if ($config[cf_use_profile]) { ?>
        <div class="form-group">
            <label for="mb_open" class="col-sm-2 control-label">자기소개</label>
            <div class="col-sm-6">
                <textarea name=mb_profile class="form-control" rows=3 style='width:100%;' <?=$config[cf_req_profile]?'required':'';?> itemname='자기 소개'><?=$member[mb_profile]?></textarea>            </div>
        </div>
        <? } ?>

        <? if ($member[mb_level] >= $config[cf_icon_level]) { ?>
        <div class="form-group">
            <label for="mb_open" class="col-sm-2 control-label">회원아이콘</label>
            <div class="col-sm-6">
                <INPUT type=file name='mb_icon' size=30>
    						<div>
                * 이미지 크기는 가로(<?=$config[cf_member_icon_width]?>픽셀)x세로(<?=$config[cf_member_icon_height]?>픽셀) 이하로 해주세요.<br>&nbsp;&nbsp;(gif/jpg/bmp/png만 가능 / 용량:<?=number_format($config[cf_member_icon_size]/1000)?>k 바이트 이하만 등록됩니다.)
                <? if ($w == "u" && file_exists($mb_icon)) { ?>
                    <br><img src='<?=$mb_icon?>' align=absmiddle> <input type=checkbox name='del_mb_icon' value='1'>삭제
                <? } ?>
                </div>
            </div>
        </div>
        <? } ?>

        <? if ($w == "" && $config[cf_use_recommend]) { ?>
        <div class="form-group">
            <label for="mb_recommend" class="col-sm-2 control-label">추천인아이디</label>
            <div class="col-sm-6">
                <? if ($mb_recommend) { ?>
                    <input type=hidden name=mb_recommend         id=mb_recommend            value="<?=$mb_recommend?>">
                    <?=$mb_recommend?>
                <? } else { ?>
                    <input  class="form-control" type=text name=mb_recommend maxlength=20 size=20 <?=$config[cf_req_recommend]?'required':'';?> itemname='추천인아이디'>
                <? } ?>
                <? if ($config[cf_recommend_point]) { ?>
                    *추천 회원에게 <?=$config[cf_recommend_point]?> 포인트를 지급합니다.
                <? } ?>
            </div>
        </div>
        <? } else if ($config[cf_use_recommend] && $member[mb_recommend]) {?>
        <div class="form-group">
            <label for="mb_recommend" class="col-sm-2 control-label">추천인아이디</label>
            <div class="col-sm-6">
                <? $mb=get_member($member['mb_recommend'], "mb_id, mb_nick")?>
                <?=get_sideview($mb['mb_id'], $mb['mb_nick'])?>
            </div>
        </div>
        <? } ?>

        <? if ($config[cf_use_norobot]) { ?>
        <div class="form-group">
            <label for="wr_key" class="col-sm-2 control-label">
                <script type="text/javascript" src="<?="$g4[path]/zmSpamFree/zmspamfree.js"?>"></script>
                <img id="zsfImg">
            </label>
            <div class="col-sm-6">
                <input class="form-control" type="input" size=10 name=wr_key id=wr_key itemname="자동등록방지" required placeholder="Captcha">
                <p class="help-block">왼쪽의 글자를 입력하세요.</p>
            </div>
        </div>        
        <? } ?>

        <? if ($w == "u") { ?>
        <div class="form-group">
            <label class="col-sm-2 control-label">가입일</label>
            <div class="col-sm-6">
                <p class="help-block"><?=$member[mb_datetime]?></p>
            </div>
        </div>
        <? } ?>

    </div>
    <div class="panel-footer">
    <div style="text-align:center">
    <? if ($w == "") { ?>
        <button class="btn btn-success">가 입</button>
    <? } else { ?>
        <button class="btn btn-success">수 정</button>
    <? } ?>
    </style>

    <? if ($is_member) { ?> 
    <a href="javascript:member_leave();" class="btn btn-default pull-right">회원탈퇴</a>
    <? } ?> 
    </div>

    </div>
</div>
</form> 

<script type="text/javascript">
// 회원가입시 -> mb_id에 focus, 수정시 -> mb_password에 focus
$(document).ready(function(){
    if ($('#w').val() == '')
        $('#mb_id').focus();
    else {
        $('#mb_password').focus();
    }
});

// submit 최종 폼체크
function fregisterform_submit(f) 
{
    // 회원아이디 검사
    if (f.w.value == "") {

        reg_mb_id_check();

        if (f.mb_id_enabled.value != '000') {
            alert('회원아이디를 입력하지 않았거나 입력에 오류가 있습니다.');
            f.mb_id.focus();
            return false;
        }
    }

    if (f.w.value == '') {
        if ($.trim(f.mb_password.value).length < 8) {
            alert('패스워드를 8글자 이상 입력하십시오.');
            f.mb_password.focus();
            return false;
        }
    }

    if ($.trim(f.mb_password.value).length > 0) {
        if ($.trim(f.mb_password_re.value).length < 8) {
            alert('패스워드를 8글자 이상 입력하십시오.');
            f.mb_password_re.focus();
            return false;
        }
    }

    if (f.mb_password.value != f.mb_password_re.value) {
        alert('패스워드가 같지 않습니다.');
        f.mb_password_re.focus();
        return false;
    }

    <? if ($g4['nick_reg_only'] !== 1) { ?>
    // 이름 검사
    if (f.w.value == "") {

        reg_mb_name_check();

        if (f.mb_name_enabled.value != '000') {
            alert('이름을 입력하지 않았거나 입력에 오류가 있습니다.');
            f.mb_name.focus();
            return false;
        }
    }
    <? } ?>

    // 별명 검사
    if ((f.w.value == "") ||
        (f.w.value == "u" && f.mb_nick.defaultValue != f.mb_nick.value)) {

        reg_mb_nick_check();

        if (f.mb_nick_enabled.value != '000') {
            alert('별명을 입력하지 않았거나 입력에 오류가 있습니다.');
            f.mb_nick.focus();
            return false;
        }
    }

    // E-mail 검사
    if ((f.w.value == "") ||
        (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {

        reg_mb_email_check();

        if (f.mb_email_enabled.value != '000') {
            alert('E-mail을 입력하지 않았거나 입력에 오류가 있습니다.');
            f.mb_email.focus();
            return false;
        }
    }

    if (typeof(f.mb_birth) != 'undefined') {
        if ($.trim(f.mb_birth.value).length < 1) {
            alert('달력 버튼을 클릭하여 생일을 입력하여 주십시오.');
            //f.mb_birth.focus();
            return false;
        }

        var todays = <?=date("Ymd", $g4['server_time']);?>;
        // 오늘날짜에서 생일을 빼고 거기서 140000 을 뺀다.
        // 결과가 0 이상의 양수이면 만 14세가 지난것임
        var n = todays - parseInt(f.mb_birth.value) - 140000;
        if (n < 0) {
            alert("만 14세가 지나지 않은 어린이는 정보통신망 이용촉진 및 정보보호 등에 관한 법률\n\n제 31조 1항의 규정에 의하여 법정대리인의 동의를 얻어야 하므로\n\n법정대리인의 이름과 연락처를 '자기소개'란에 별도로 입력하시기 바랍니다.");
            return false;
        }
    }

    if (typeof(f.mb_sex) != 'undefined') {
        if (f.mb_sex.value == '') {
            alert('성별을 선택하여 주십시오.');
            f.mb_sex.focus();
            return false;
        }
    }

    if (typeof f.mb_icon != 'undefined') {
        if (f.mb_icon.value) {
            if (!f.mb_icon.value.toLowerCase().match(/.(gif|bmp|jpg|png)$/i)) {
                alert('회원아이콘이 gif|jpg|bmp|png 파일이 아닙니다.');
                f.mb_icon.focus();
                return false;
            }
        }
    }

    if (typeof(f.mb_recommend) != 'undefined') {
        if (f.mb_id.value == f.mb_recommend.value) {
            alert('본인을 추천할 수 없습니다.');
            f.mb_recommend.focus();
            return false;
        }
    }

    if (typeof(f.wr_key) != 'undefined') {
        if (!checkFrm()) {
            alert ("스팸방지코드(Captcha Code)가 틀렸습니다. 다시 입력해 주세요.");
            return false;
        }
    }

    <?
    if ($g4[https_url])
        echo "f.action = '$g4[https_url]/$g4[bbs]/register_form_update.php';";
    else
        echo "f.action = './register_form_update.php';";
    ?>

    // 보안인증관련 코드로 반드시 포함되어야 합니다.
    set_cookie("<?=md5($token)?>", "<?=base64_encode($token)?>", 1, "<?=$g4['cookie_domain']?>");

    return true;
}

// 회원 탈퇴 
function member_leave() 
{ 
    if (confirm("정말 회원에서 탈퇴 하시겠습니까?")) 
            location.href = "<?=$g4[bbs_path]?>/mb_leave.php"; 
}
</script>
