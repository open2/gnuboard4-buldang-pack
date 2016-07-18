<?
$sub_menu = "200100";
include_once("./_common.php");

auth_check($auth[$sub_menu], "w");

$token = get_token();

if ($w == "") 
{
    $required_mb_id = "required minlength=3 alphanumericunderline itemname='회원아이디'";
    $required_mb_password = "required itemname='패스워드'";

    $mb[mb_mailling] = 1;
    $mb[mb_open] = 1;
    $mb[mb_level] = $config[cf_register_level];
    $html_title = "등록";
}
else if ($w == "u") 
{
    $mb = get_member($mb_id);
    if (!$mb[mb_id])
        alert("존재하지 않는 회원자료입니다."); 

    if ($is_admin != 'super' && $mb[mb_level] >= $member[mb_level])
        alert("자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.");

    $required_mb_id = "readonly style='background-color:#dddddd;'";
    $required_mb_password = "";
    $html_title = "수정";

    $mb[mb_name]        = get_text($mb[mb_name]);
    $mb[mb_nick]        = get_text($mb[mb_nick]);
    $mb[mb_email]       = get_text($mb[mb_email]);
    $mb[mb_homepage]    = get_text($mb[mb_homepage]);
    $mb[mb_birth]       = get_text($mb[mb_birth]);
    $mb[mb_tel]         = get_text($mb[mb_tel]);
    $mb[mb_hp]          = get_text($mb[mb_hp]);
    $mb[mb_addr1]       = get_text($mb[mb_addr1]);
    $mb[mb_addr2]       = get_text($mb[mb_addr2]);
    $mb[mb_signature]   = get_text($mb[mb_signature]);
    $mb[mb_recommend]   = get_text($mb[mb_recommend]);
    $mb[mb_profile]     = get_text($mb[mb_profile]);
    $mb[mb_1]           = get_text($mb[mb_1]);
    $mb[mb_2]           = get_text($mb[mb_2]);
    $mb[mb_3]           = get_text($mb[mb_3]);
    $mb[mb_4]           = get_text($mb[mb_4]);
    $mb[mb_5]           = get_text($mb[mb_5]);
    $mb[mb_6]           = get_text($mb[mb_6]);
    $mb[mb_7]           = get_text($mb[mb_7]);
    $mb[mb_8]           = get_text($mb[mb_8]);
    $mb[mb_9]           = get_text($mb[mb_9]);
    $mb[mb_10]          = get_text($mb[mb_10]);
} 
else 
    alert("제대로 된 값이 넘어오지 않았습니다.");

if ($mb[mb_mailling]) $mailling_checked = "checked"; // 메일 수신
if ($mb[mb_sms])      $sms_checked = "checked"; // SMS 수신
if ($mb[mb_open])     $open_checked = "checked"; // 정보 공개

$g4[title] = "회원정보 " . $html_title;
include_once("./admin.head.php");
?>

<form name=fmember method=post onsubmit="return fmember_submit(this);" enctype="multipart/form-data" autocomplete="off">
<input type=hidden name=w     value='<?=$w?>'>
<input type=hidden name=sfl   value='<?=$sfl?>'>
<input type=hidden name=stx   value='<?=$stx?>'>
<input type=hidden name=sst   value='<?=$sst?>'>
<input type=hidden name=sod   value='<?=$sod?>'>
<input type=hidden name=page  value='<?=$page?>'>
<input type=hidden name=token value='<?=$token?>'>

<table width=100% class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<colgroup width=120px>
<colgroup>
<tr>
    <td colspan=4 class="success"><?=$g4[title]?></td>
</tr>
<tr>
    <td>아이디</td>
    <td>
        <input type=text class=ed name='mb_id' size=20 maxlength=20 minlength=2 <?=$required_mb_id?> itemname='아이디' value='<? echo $mb[mb_id] ?>'>
        <?if ($w=="u"){?><a href='./boardgroupmember_form.php?mb_id=<?=$mb[mb_id]?>'>접근가능그룹보기</a><?}?>
    </td>
</tr>
<tr>
    <td>패스워드</td>
    <td><input type=password class=ed name='mb_password' size=20 maxlength=20 <?=$required_mb_password?> itemname='암호'></td>
</tr>
<tr>
    <td>이름(실명)</td>
    <td><input type=text class=ed name='mb_name' maxlength=20 minlength=2 required itemname='이름(실명)' value='<? echo $mb[mb_name] ?>'></td>
</tr>
<tr>
    <td>별명</td>
    <td><input type=text class=ed name='mb_nick' maxlength=20 minlength=2 required itemname='별명' value='<? echo $mb[mb_nick] ?>'> <? if ($mb['mb_nick_date'] && $mb['mb_nick_date']!="0000-00-00") { ?>(<?=$mb['mb_nick_date']?>)<? } ?></td>
</tr>
<tr>
    <td>회원 권한</td>
    <td><?=get_member_level_select("mb_level", 1, $member[mb_level], $mb[mb_level])?></td>
</tr>
<tr>
    <td>포인트</td>
    <td><a href='./point_list.php?sfl=mb_id&stx=<?=$mb[mb_id]?>' class='bold'><?=number_format($mb[mb_point])?></a> 점</td>
</tr>
<tr>
    <td>사용자그룹</td>
    <td>
    <input type=text class=ed name='ug_id' id='ug_id' size=20 maxlength=40 value='<? echo $mb[ug_id] ?>' itemname='사용자그룹' readonly>
    <? 
    // 사용자그룹 목록
    $sql = " select * from $g4[user_group_table] ";
    $result = sql_query($sql);
    $ug_str = "<select name='ug_str' onchange=\"javascript:document.getElementById('ug_id').value=this.value;\">";
    $ug_str .= "<option value=''>사용자그룹 선택하기</option>";
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $ug_str .= "<option value='$row[ug_id]'";
        $ug_str .= ">" . cut_str($row[ug_subject],60,'') . "</option>";
    }
    $ug_str .= "</select>";
    echo $ug_str;
    ?>
    </td>
</tr>
<tr>
    <td>E-mail</td>
    <td><input type=text class=ed name='mb_email' size=40 maxlength=100 required email itemname='e-mail' value='<? echo $mb[mb_email] ?>'></td>
</tr>
<tr>
    <td>홈페이지</td>
    <td><input type=text class=ed name='mb_homepage' size=40 maxlength=255 itemname='홈페이지' value='<? echo $mb[mb_homepage] ?>'></td>
</tr>
<tr>
    <td>전화번호</td>
    <td><input type=text class=ed name='mb_tel' maxlength=20 itemname='전화번호' value='<? echo $mb[mb_tel] ?>'></td>
</tr>
<tr>
    <td>핸드폰번호</td>
    <td><input type=text class=ed name='mb_hp' maxlength=20 itemname='핸드폰번호' value='<? echo $mb[mb_hp] ?>'></td>
</tr>
<tr>
    <td>주소</td>
    <td>
        <input type=text class=ed name='mb_zip1' size=4 maxlength=3 readonly itemname='우편번호 앞자리' value='<? echo $mb[mb_zip1] ?>'> -
        <input type=text class=ed name='mb_zip2' size=4 maxlength=3 readonly itemname='우편번호 뒷자리' value='<? echo $mb[mb_zip2] ?>'>
        <a href="javascript:;" onclick="win_zip('fmember', 'mb_zip1', 'mb_zip2', 'mb_addr1', 'mb_addr2');"><img src='<?=$g4[bbs_img_path]?>/btn_zip.gif' align=absmiddle border=0></a>
        <br><input type=text class=ed name='mb_addr1' size=40 readonly value='<? echo $mb[mb_addr1] ?>'>
        <br><input type=text class=ed name='mb_addr2' size=25 itemname='상세주소' value='<? echo $mb[mb_addr2] ?>'> 상세주소 입력</td>
</tr>
<tr>
    <td>회원아이콘</td>
    <td colspan=3>
        <input type=file name='mb_icon' class=ed><br>이미지 크기는 <?=$config[cf_member_icon_width]?>x<?=$config[cf_member_icon_height]?>으로 해주세요.
        <?
        $mb_dir = substr($mb[mb_id],0,2);
        $icon_file = "$g4[data_path]/member/$mb_dir/$mb[mb_id].gif";
        if (file_exists($icon_file)) {
            echo "<br><img src='$icon_file' align=absmiddle>";
            echo " <input type=checkbox name='del_mb_icon' value='1' class='csscheck'>삭제";
        }   
        ?>
    </td>
</tr>
<tr>
    <td>생년월일</td>
    <td><input type=text class=ed name=mb_birth size=9 maxlength=8 value='<? echo $mb[mb_birth] ?>'></td>
</tr>
<tr>
    <td>남녀</td>
    <td>
        <select name=mb_sex><option value=''>----<option value='F'>여자<option value='M'>남자</select>
        <script type="text/javascript"> document.fmember.mb_sex.value = "<?=$mb[mb_sex]?>"; </script></td>
</tr>
<tr>
    <td>메일 수신</td>
    <td><input type=checkbox name=mb_mailling value='1' <?=$mailling_checked?>> 정보 메일을 받음</td>
</tr>
<tr>
    <td>SMS 수신</td>
    <td><input type=checkbox name=mb_sms value='1' <?=$sms_checked?>> 문자메세지를 받음</td>
</tr>
<tr>
    <td>정보 공개</td>
    <td><input type=checkbox name=mb_open value='1' <?=$open_checked?>> 타인에게 자신의 정보를 공개</td>
</tr>
<tr>
    <td>서명</td>
    <td><textarea class=ed name=mb_signature rows=5 style='width:99%; word-break:break-all;'><? echo $mb[mb_signature] ?></textarea></td>
</tr>
<tr>
    <td>자기 소개</td>
    <td><textarea class=ed name=mb_profile rows=5 style='width:99%; word-break:break-all;'><? echo $mb[mb_profile] ?></textarea></td>
</tr>
<tr>
    <td>메모</td>
    <td><textarea class=ed name=mb_memo rows=5 style='width:99%; word-break:break-all;'><? echo $mb[mb_memo] ?></textarea></td>
</tr>

<? if ($w == "u") { ?>
<tr>
    <td>회원가입일</td>
    <td><?=$mb[mb_datetime]?></td>
</tr>
<tr>
    <td>최근접속일</td>
    <td><?=$mb[mb_today_login]?></td>
</tr>
<tr>
    <td>IP</td>
    <td><?=$mb[mb_ip]?></td>
</tr>
<tr>
    <td>본인인증</td>
    <td><?=$mb[mb_realcheck]?></td>
</tr>
<? if ($config[cf_use_email_certify] || $mb[mb_email_certify] !== "0000-00-00 00:00:00") { ?>
<tr>
    <td>이메일인증</td>
    <td><?=$mb[mb_email_certify]?> 
        <? if ($mb[mb_email_certify] == "0000-00-00 00:00:00") { echo "<input type=checkbox name=passive_certify>수동인증"; } ?>
    </td>
</tr>
<? } ?>
<? } ?>

<? if ($config[cf_use_recommend]) { // 추천인 사용 ?>
<tr>
    <td>추천인</td>
    <td><?=($mb[mb_recommend] ? get_text($mb[mb_recommend]) : "없음"); // 081022 : CSRF 보안 결함으로 인한 코드 수정 ?></td>
</tr>
<? } ?>

<tr>
    <td>탈퇴일자</td>
    <td><input type=text class=ed name=mb_leave_date size=9 maxlength=8 value='<? echo $mb[mb_leave_date] ?>'></td>
</tr>
<tr>
    <td>접근차단일자</td>
    <td><input type=text class=ed name=mb_intercept_date size=9 maxlength=8 value='<? echo $mb[mb_intercept_date] ?>'> <input type=checkbox value='<? echo date("Ymd"); ?>' onclick='if (this.form.mb_intercept_date.value==this.form.mb_intercept_date.defaultValue) { this.form.mb_intercept_date.value=this.value; } else { this.form.mb_intercept_date.value=this.form.mb_intercept_date.defaultValue; } '>오늘</td>
</tr>

<? for ($i=1; $i<=10; $i++) { ?>
<tr>
    <td>여분 필드 <?=$i?></td>
    <td><input type=text class=ed style='width:99%;' name='mb_<?=$i?>' maxlength=255 value='<?=$mb["mb_$i"]?>'></td>
</tr>
<? } ?>

<tr class="success">
    <td>
        관리자 패스워드
    </td>
    <td colspan=3>
        <input class='ed' type='password' name='admin_password' itemname="관리자 패스워드" required>
        <?=help("관리자 권한을 빼앗길 것에 대비하여 로그인한 관리자의 패스워드를 한번 더 묻는것 입니다.");?>
    </td>
</tr>
</table>

<p align=center>
    <input type=submit class="btn btn-default" accesskey='s' value='  확    인  '>&nbsp;
    <input type=button class="btn btn-default" value='  목  록  ' onclick="document.location.href='./member_list.php?<?=$qstr?>';">&nbsp;
    
    <? if ($w != '') { ?>
    <input type=button class="btn btn-default" value='  삭  제  ' onclick="post_delete('./member_delete.php','<?=$mb[mb_id]?>');">&nbsp;
    <? } ?>
</form>

<script type='text/javascript'>
if (document.fmember.w.value == "")
    document.fmember.mb_id.focus();
else if (document.fmember.w.value == "u")
    document.fmember.mb_password.focus();

if (typeof(document.fmember.mb_level) != "undefined") 
    document.fmember.mb_level.value   = "<?=$mb[mb_level]?>"; 

function fmember_submit(f)
{
    if (!f.mb_icon.value.match(/\.(gif|jp[e]g|png)$/i) && f.mb_icon.value) {
        alert('아이콘이 이미지 파일이 아닙니다. (bmp 제외)');
        return false;
    }

    f.action = './member_form_update.php';
    return true;
}
</script>

<script type='text/javascript'>
// POST 방식으로 삭제
function post_delete(action_url, val)
{
	var f = document.fpost;

	if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
    f.mb_id.value = val;
    f.admin_password.value = document.fmember.admin_password.value;
		f.action         = action_url;
		f.submit();
	}
}
</script>

<form name='fpost' method='post'>
<input type='hidden' name='sst'   value='<?=$sst?>'>
<input type='hidden' name='sod'   value='<?=$sod?>'>
<input type='hidden' name='sfl'   value='<?=$sfl?>'>
<input type='hidden' name='stx'   value='<?=$stx?>'>
<input type='hidden' name='page'  value='<?=$page?>'>
<input type='hidden' name='token' value='<?=$token?>'>
<input type='hidden' name='w'     value='d'>
<input type='hidden' name='mb_id'>
<input type='hidden' name='admin_password'>
</form>

<?
include_once("./admin.tail.php");
?>
