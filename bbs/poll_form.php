<?
include_once("./_common.php");

$html_title = "투표";
if ($w == "") {
    $html_title .= " 생성";
    
    // 초기값 설정
    $po['po_level']     = 2;                  // 투표권한
    $po['po_point']     = 100;                // 투표할 회원에게 부여할 포인트
    $po['po_skin']      = "basic";            // 스킨
    $po['po_date']      = $g4['time_ymd'];    // 투표 시작일
    $po['po_end_date']  = "0000-00-00";       // 투표 마감일 (끝나는 날짜를 지정하지 않음)
    $po['po_etc']       = "기타 의견이 계시면 알려주세요";
    $po['po_end_date'] = date("Y-m-d", $g4['server_time'] + 3600*24*90);   // 투표 마감일 (90일 이후를 기본으로 설정)
}
else if ($w == "u")  {
    $html_title .= " 수정";
    $sql = " select * from $g4[poll_table] where po_id = '$po_id' ";
    $po = sql_fetch($sql);
} else 
    alert("w 값이 제대로 넘어오지 않았습니다."); 

$g4[title] = $html_title;
include_once("./_head.php");

include_once("$g4[admin_path]/admin.lib.php");
?>

<script type="text/javascript" src='<?=$g4['path']?>/js/sideview.js'></script>
<script type="text/javascript" src='<?=$g4['admin_path']?>/admin.js'></script>

<script language='javascript'>
if (!g4_is_ie) document.captureEvents(Event.MOUSEMOVE)
document.onmousemove = getMouseXY;
var tempX = 0;
var tempY = 0;
var prevdiv = null;
var timerID = null;

function getMouseXY(e) 
{
    if (g4_is_ie) { // grab the x-y pos.s if browser is IE
        tempX = event.clientX + document.body.scrollLeft;
        tempY = event.clientY + document.body.scrollTop;
    } else {  // grab the x-y pos.s if browser is NS
        tempX = e.pageX;
        tempY = e.pageY;
    }  

    if (tempX < 0) {tempX = 0;}
    if (tempY < 0) {tempY = 0;}  

    return true;
}

function help(id, left, top)
{
    menu(id);

    var el_id = document.getElementById(id);

    //submenu = eval(name+".style");
    submenu = el_id.style;
    submenu.left = tempX - 50 + left;
    submenu.top  = tempY + 15 + top;

    selectBoxVisible();

    if (el_id.style.display != 'none')
        selectBoxHidden(id);
}
</script>

<style>
.bg_menu1 { height:22px; 
            padding-left:15px; 
            padding-right:15px; } 
.bg_line1 { height:1px; background-color:#EFCA95; } 

.bg_menu2 { height:22px; 
            padding-left:25px; } 
.bg_line2 { background-image:url('<?=$g4['admin_path']?>/img/dot.gif'); height:3px; } 
.dot {color:#D6D0C8;border-style:dotted;}

#csshelp1 { border:0px; background:#FFFFFF; padding:6px; }
#csshelp2 { border:2px solid #BDBEC6; padding:0px; }
#csshelp3 { background:#F9F9F9; padding:6px; width:200px; color:#222222; line-height:120%; text-align:left; }
</style>

<form name=fpollform method=post onsubmit="return fpoll_check(this);" enctype="multipart/form-data">
<input type=hidden name=po_id value='<?=$po_id?>'>
<input type=hidden name=w     value='<?=$w?>'>
<input type=hidden name=sfl   value='<?=$sfl?>'>
<input type=hidden name=stx   value='<?=$stx?>'>
<input type=hidden name=sst   value='<?=$sst?>'>
<input type=hidden name=sod   value='<?=$sod?>'>
<input type=hidden name=page  value='<?=$page?>'>
<input type=hidden name=token value='<?=$token?>'>
<table width=100% cellpadding=0 cellspacing=0>
<colgroup width=20% class='col1 pad1 bold right'>
<colgroup width=30% class='col2 pad2'>
<colgroup width=20% class='col1 pad1 bold right'>
<colgroup width=30% class='col2 pad2'>
<tr>
    <td colspan=4 class=title align=left><img src='<?=$g4[admin_path]?>/img/icon_title.gif'> <?=$html_title?></td>
</tr>
<tr><td colspan=4 class='line1'></td></tr>
<tr class='ht'>
    <td>투표 제목</td>
    <td colspan=3><input type='text' class=ed name='po_subject' style='width:99%;' required itemname='투표 제목' value='<?=$po[po_subject]?>' maxlength="125"></td>
</tr>

<tr class='ht'>
    <td>투표 개요</td>
    <td colspan=3>
    <textarea class=ed name="po_summary" rows=5 style='width:99%;'><?=$po['po_summary']?></textarea>
    </td>
</tr>

<? 
for ($i=1; $i<=9; $i++) {
    $required = "";
    $itemname = "";
    if ($i==1 || $i==2) {
        $required = "required";
        $itemname = "itemname='항목$i'";
    }

    $po_poll = get_text($po["po_poll".$i]);

    echo <<<HEREDOC
    <tr class='ht'>
        <td>항목{$i}</td>
        <td><input type="text" class=ed name="po_poll{$i}" {$required} {$itemname} value="{$po_poll}" style="width:99%;" maxlength="125"></td>
        <td>투표수</td>
        <td><input type="text" class=ed name="po_cnt{$i}" size=5 value="{$po["po_cnt".$i]}"></td>
        
    </tr>
HEREDOC;
} 
?>

<tr class='ht'>
    <td>기타의견</td>
    <td colspan=3>
    <input type='text' class=ed name='po_etc' style='width:95%;' value='<?=get_text($po[po_etc])?>' maxlength="125">
    <?=help("기타의견 입력을 가능하게 하려면, 이곳에 값을 입력해야 합니다.<br>(예: '기타' 선택 또는 다른 의견이 있다면 말씀해 주십시오.")?>
    </td>
</tr>

<tr class='ht'>
    <td>스킨 디렉토리</td>
    <td colspan=3><select name=po_skin required itemname="스킨 디렉토리">
        <?
        $arr = get_skin_dir("poll");
        for ($i=0; $i<count($arr); $i++) {
            echo "<option value='$arr[$i]'>$arr[$i]</option>\n";
        }
        ?></select>
        <script language="JavaScript">document.fpollform.po_skin.value="<?=$po[po_skin]?>";</script>
    </td>
</tr>

<tr class='ht'>
    <td>접근사용</td>
    <td colspan=3>
        <input type=checkbox name=po_use_access value='1' <?=$po[po_use_access]?'checked':'';?>>사용
        <?=help("사용에 체크하시면 이 투표는 관리자만 접근이 가능 합니다.")?>
    </td>
</tr>

<tr class='ht'>
    <td>투표권한</td>
    <td colspan=3><?=get_member_level_select("po_level", 1, 10, $po[po_level])?>이상 투표할 수 있음</td>
</tr>

<tr class='ht'>
    <td>기타의견 권한</td>
    <td colspan=3><?=get_member_level_select("po_etc_level", $po[po_level], 10, $po[po_etc_level])?>이상 투표할 수 있음</td>
</tr>

<tr class='ht'>
    <td>포인트</td>
    <td colspan=3><input type='text' class=ed name='po_point' size='10' value='<?=$po[po_point]?>'> 점 (투표한 회원에게 부여함)</td>
</tr>

<tr class='ht'>
    <td>투표시작일</td>
    <td colspan=3><input type="text" class=ed name="po_date" id="po_date" size=10 maxlength=10 value="<?=$po[po_date]?>" itemname="투표시작일" >
    <a href="#none" onClick="win_calendar('po_date', document.getElementById('po_date').value, '-');" >날짜 선택</a>
    </td>
</tr>

<tr class='ht'>
    <td>투표마감일</td>
    <td colspan=3><input type="text" class=ed name="po_end_date" id="po_end_date" size=10 maxlength=10 value="<?=$po[po_end_date]?>" itemname="투표마감일" >
    <a href="#none" onClick="win_calendar('po_end_date', document.getElementById('po_date').value, '-');" >날짜 선택</a> (마감일을 <a href="#none" onClick="document.getElementById('po_end_date').value = '0000-00-00';" >0000-00-00</a>으로 입력하면 투표종료가 되지 않습니다)
    </td>
</tr>

<? if ($w == "u" && $is_admin) { ?>

<tr class='ht'>
    <td>투표참가 IP</td>
    <td colspan=3><textarea class=ed name="po_ips" rows=10 style='width:99%;' readonly><?=preg_replace("/\n/", " / ", $po[po_ips])?></textarea></td>
</tr>

<tr class='ht'>
    <td>투표참가 회원</td>
    <td colspan=3><textarea class=ed name="mb_ids" rows=10 style='width:99%;' readonly><?=preg_replace("/\n/", " / ", $po[mb_ids])?></textarea></td>
</tr>

<? } ?>

<tr><td colspan=4 class='line2'></td></tr>
</table>

<p align=center>
    <input type=submit class=btn1 accesskey='s' value='  확  인  '>&nbsp;
    <input type=button class=btn1 value='  목  록  ' onclick="document.location.href='./poll_list.php?<?=$qstr?>';">
</form>

<script language='Javascript'>
function fpoll_check(f)
{
    f.action = './poll_form_update.php';
    return true;
}
</script>

<?
include_once("./_tail.php");
?>
