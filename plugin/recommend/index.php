<?
include_once("./_common.php");

if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ�

$g4[title] = "ȸ�� ������õ�ϱ�";
include_once("$g4[path]/_head.php");

// ��ȸ�� ������ ��� EXIT
if (!$is_member) {
    echo "ȸ���� ��밡���� �޴� �Դϴ�";
    exit;
}

// �ҹ������� ������ ��ū����
$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

// ��ü ��õȽ��
$sql = " select count(*) as cnt from $g4[member_suggest_table] where mb_id = '$member[mb_id]' ";
$mb_tot_recommend = sql_fetch($sql);
        
// ���� ��õ�Ͽ� ������ ȸ����, ������ mismatch�� ���� �� �ִ�...���� ���α׷��� ���� ������...��.��...
$sql = " select count(*) as cnt from $g4[member_table] where mb_recommend = '$member[mb_id]' ";
$mb_recommend = sql_fetch($sql);

// �������� ��õ ȸ����
$wtime = date("Y-m-d H:i:s", $g4[server_time] - ($g4[member_suggest_days] * 86400)); 
$sql = " select count(*) as cnt from $g4[member_suggest_table] where mb_id = '$member[mb_id]' and suggest_datetime > '$wtime' ";
$mb_suggest = sql_fetch($sql);

// ��õ ������ ȸ����
$mb_suggest_cnt = $g4['member_suggest_count'] - $mb_suggest['cnt'];

// �Ű�Ƚ��
$singo_count = 0;
if ($g4[singo_table] && $g4['member_suggest_singo']) {
    $sql = " select count(*) as cnt 
               from $g4[singo_table] 
              where mb_id = '$member[mb_id]' and sg_reason <> '�Խ��Ǹ����� ������ �Խñ�' ";
    $row1 = sql_fetch($sql);
    $singo_count = $row1['cnt'];
    if ($singo_count)
        $mb_suggest_cnt = 0;
    }

// ��¼�� ��õ �Ǽ��� ���̳ʽ��� ���� 0���� setting
if ($mb_suggest_cnt < 0)
    $mb_suggest_cnt = 0;
?>
<link rel="stylesheet" href="<?=$g4['path']?>/plugin/recommend/style.css" type="text/css">
<div class="section1">
    <h2 class="hx">ȸ�� ��õ ���̵�</h2>
    <div class="tx">
    <? echo $g4['member_suggest_intro'] ?>
    <ul>
    <li>��ü ��õ�Ǽ� : <?=number_format($mb_tot_recommend[cnt])?></li>
    <li>��õ�� �ް� ������ ȸ���� : <?=number_format($mb_recommend[cnt])?></li>
    <li>��õ�� �ް� �̰��� ȸ���� : <?=number_format($mb_tot_recommend[cnt] - $mb_recommend[cnt])?></li>
    <li>��õ ���� ȸ���� : <?=number_format($mb_suggest_cnt)?></li>
    </ul>
    <?
    if ($singo_count) {
        echo "<b>�Ű�Ǽ��� $singo_count �� �־, ��õ�� �� �� �����ϴ�. ��Խ��ǿ� ���� �ٶ��ϴ�.</b>";
    } else if ($member['mb_email_certify'] == '0000-00-00 00:00:00' && $member['mb_hp_certify_datetime'] == '0000-00-00 00:00:00') {
        echo "<b>�̸������� �Ǵ� SMS ������ ���� ȸ���� ������õ�� �� �� �ֽ��ϴ�. <a href='$g4[bbs_path]/member_confirm.php?url=register_form.php'>SMS,�̸��� �����Ϸ� ����</a></b>";
    } else if ($mb_suggest_cnt == 0) {
        // ���� ��õ���� ���
        $sql = " select * from $g4[member_suggest_table] where mb_id = '$member[mb_id]' order by join_no desc limit 1 ";
        $result = sql_fetch($sql);
        $sql = " select DATE_ADD('$result[suggest_datetime]', INTERVAL ($g4[member_suggest_days]+1) DAY) as datetime ";
        $result = sql_fetch($sql);
        echo "<b>���� ��õ�������� " . substr($result[datetime], 0, 10) . "�� �Դϴ�.</b>";
    }
    ?>
    </div>
</div>

<? if ($mb_suggest_cnt > 0) { ?>
<br>
<form name=fregisterform method=post action="" enctype="multipart/form-data" autocomplete="off">
<input type=hidden name=token value="<?=$token?>">
<table class="tbl_type1" border="1" cellspacing="0" summary="ȸ�� ���� ��õ">
<caption>ȸ��������õ</caption>
<colgroup>
<col width="10%">
<col width="30%">
<col width="">
</colgroup>
<tbody>
<? if ($g4['member_suggest_phone']) { ?>
<tr>
  <td class="ranking" scope="row">SMS ��õ</td>
  <td><input class=m_text type=text name='mb_hp' size=35 maxlength=20 required itemname='�ڵ�����ȣ' value=''></td>
  <td align=left>
  &nbsp;&nbsp;
  <span class="btn_pack1 small icon">
  <input type=button value='�� õ  ' class='medium' onclick="hp_certify(this.form);">
  </span>
  &nbsp;&nbsp;
  ��õ�� ���� �ڵ��� ��ȣ�� �Է��ϰ� ��õ ��ư�� �����ּ���
  </td>
</tr>
<? } ?>
<? if ($g4['member_suggest_email']) { ?>
<tr>
  <td class="ranking" scope="row">�̸��� ��õ</td>
  <td><input class=m_text type=text name='mb_email' size=35 maxlength=35 required style="ime-mode:disabled" itemname='�̸����ּ�' value=''></td>
  <td align=left>
  &nbsp;&nbsp;
  <span class="btn_pack1 small icon">
  <input type=button value='�� õ  ' class='medium' onclick="email_certify(this.form);">
  </span>
  &nbsp;&nbsp;
  ��õ�� ���� �̸��� �ּҸ� �Է��ϰ� ��õ ��ư�� �����ּ���
  </td>
</tr>
<? } ?>
</tbody>
</table>
</form>
<? } ?>

<script type='text/javascript'>
function hp_certify(f) { 
    var pattern = /^(0(?:10|11|16|17|18|19|70))[-]{0,1}[0-9]{3,4}[-]{0,1}[0-9]{4}$/; 
    if(!pattern.test(f.mb_hp.value)){  
        alert("�ڵ��� ��ȣ�� �Էµ��� �ʾҰų� ��ȣ�� Ʋ���ϴ�.\n\n�ڵ��� ��ȣ�� 010-123-4567 �Ǵ� 01012345678 �� ���� �Է��� �ֽʽÿ�."); 
        f.mb_hp.select(); 
        f.mb_hp.focus(); 
        return; 
    }

    f.target = "hiddenframe";
		f.action = "./hp_certify_suggest.php?hp="+f.mb_hp.value+"&token=<?=$token?>";
		f.submit();            
}

function email_certify(f) { 
    var pattern = /([0-9a-zA-Z_\.-]+)@([0-9a-zA-Z_-]+)\.([0-9a-zA-Z_-]+)/;
    if(!pattern.test(f.mb_email.value)){  
        alert("�̸��� �ּҰ� �Էµ��� �ʾҰų� ��ȣ�� Ʋ���ϴ�.\n\n�̸��� �ּҸ� ���Ŀ� �°� �Է��� �ֽʽÿ�."); 
        f.mb_email.select(); 
        f.mb_email.focus(); 
        return; 
    }
    
    //f.target = "hiddenframe";
		f.action = "./email_certify_suggest.php?email="+f.mb_email.value+"&token=<?=$token?>";
		f.submit();            
} 
</script> 

<?
$sql = " select * from $g4[member_suggest_table] where mb_id = '$member[mb_id]' order by join_no desc ";
$result = sql_query($sql);
$list = array();
for ($i=0; $row=sql_fetch_array($result); $i++)
{
    $list[$i][no] = $i+1;
    $list[$i][suggest_datetime] = cut_str($row[suggest_datetime], 10, '');
    $list[$i][join_hp] = $row[join_hp];
    $list[$i][join_email] = $row[join_email];
    if ($row[join_mb_id]) 
        $list[$i][join_code] = '-';
    else
        $list[$i][join_code] = $row[join_code];
    if ($row[join_datetime] == '0000-00-00 00:00:00') 
        $list[$i][join_datetime] = '�̰���';
    else
        $list[$i][join_datetime] = cut_str($row[join_datetime], 10, '');
    // �̰����� ���� ��� �Ǵ� ��Ȯ���� ��ȸ�� �ش�
    if ($row[join_mb_id] || $row[join_datetime] !== '0000-00-00 00:00:00')
        $list[$i][join_mb_id] = $row[join_mb_id];
    else {
        // �̰����̸� ����� ��ư�� �־��ش�.
        $list[$i][join_cancel] = "<a href='./join_suggest_re.php?w=d&join_no=$row[join_no]'>��õ���</a>&nbsp;&nbsp;
                                  <a href='./join_suggest_re.php?w=r&join_no=$row[join_no]'>��õ�ϰ���</a>";
    }
}
?>

<br>
<table class="tbl_type1" border="1" cellspacing="0" summary="ȸ�� ���� ��õ ����">
<caption>ȸ��������õ ����</caption>
<colgroup>
<col width="10%">
<col width="15%">
<col>
<col width="15%">
<col width="15%">
<col width="20%">
</colgroup>
<thead>
<tr>
<th abbr="No." scope="col">No.</th>
<th scope="col">��õ����</th>
<th scope="col">��õ��ȭ��ȣ/�̸���</th>
<th scope="col">����������ȣ</th>
<th scope="col">��������</th>
<th scope="col">����ȸ��</th>
</tr>
</thead>
<tbody>
<?
foreach ($list as $row) {
?>
<tr>
  <td class="ranking" scope="row"><?=$row[no]?></td>
  <td><?=$row[suggest_datetime]?></td>
  <td><?=$row[join_hp]?></td>
  <td><?=$row[join_code]?></td>
  <td><?=$row[join_datetime]?></td>
  <td>
  <?
  if ($row[join_mb_id]) {
      $mb = get_member($row[join_mb_id], "mb_id, mb_nick, mb_email, mb_homepage");
      $mb_nick = get_sideview($mb[mb_id], get_text($mb[mb_nick]), $mb[mb_email], $mb[mb_homepage]);
      echo $mb_nick;
  } else {
      echo $row[join_cancel];
  }
  ?>
  </td>
</tr>
<? } ?>
</tbody>
</table>
<br><br>
<?
include_once("$g4[path]/_tail.php"); 
?>
