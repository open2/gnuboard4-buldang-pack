<?
$sub_menu = "300100";
include_once("./_common.php");

auth_check($auth[$sub_menu], "w");

$token = get_token();

$g4[title] = "게시판 복사";
include_once("$g4[path]/head.sub.php");
?>

<form name="fboardcopy" method='post' onsubmit="return fboardcopy_check(this);" autocomplete="off">
<input type="hidden" name="bo_table" value="<?=$bo_table?>">
<input type="hidden" name="token"    value="<?=$token?>">
<table width=100%  class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<colgroup width=30%>
<colgroup width=70%>
<tr>
    <td colspan=2 class="success"><?=$g4[title]?></td>
</tr>
<tr>
	<td>원본 테이블</td>
	<td><?=$bo_table?></td>
</tr>
<tr>
	<td>복사할 TABLE</td>
	<td><input type=text class="form-control" name="target_table" size="20" maxlength="20" required alphanumericunderline itemname="TABLE"> 영문자, 숫자, _ 만 가능 (공백없이)</td>
</tr>
<tr>
	<td>게시판 제목</td>
	<td><input type=text class="form-control" name='target_subject' size=60 maxlength=120 required itemname='게시판 제목' value='[복사본] <?=$board[bo_subject]?>'></td>
</tr>
<tr>
	<td>복사 유형</td>
	<td>
        <input type="radio" name="copy_case" value="schema_only" checked>구조만
        <input type="radio" name="copy_case" value="schema_data_both">구조와 데이터
    </td>
</tr>
</table>

<div style="text-align:center">
    <input type="submit" value="  복  사  " class="btn btn-default">&nbsp;
    <input type="button" value="창닫기" onclick="window.close();" class="btn btn-default">
</div>

</form>

<script type='text/javascript'>
function fboardcopy_check(f)
{
    f.action = "./board_copy_update.php";
    return true;
}
</script>

<?
include_once("$g4[path]/tail.sub.php");
?>
