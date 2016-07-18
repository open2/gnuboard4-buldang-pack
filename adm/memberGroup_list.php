<?
$sub_menu = "200130";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$token = get_token();

$g4[title] = "회원권한명관리";
include_once("./admin.head.php");

$sql = " select * from $g4[member_group_table] order by gl_id asc";
$result = sql_query($sql);
?>
<script type="text/javascript">
var list_update_php = "memberGroup_list_update.php";
</script>

<div>
    회원 레벨(권한)명을 설정합니다.
</div>

<form name=fmemberG_list method=post role="form" class="form-inline">
<input type=hidden name=token value='<?=$token?>'>
<table width=100% class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<colgroup width=30>
<colgroup width=80>
<colgroup width="">
<tr class='success'>
    <td><input type=checkbox name=chkall value='1' onclick='check_all(this.form)'></td>
    <td>회원레벨</td>
    <td>레벨명</td>
</tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++) {
    $list = $i%2;
?>    
    <input type="hidden" name="gl_id[<?=$row[gl_id]?>]" value="<?=$row[gl_id]?>">
    <tr>
        <td><input type="checkbox" name="chk[]" value='<?=$i?>'></td>
        <td title='<?=$row[gl_id]?>'><?=$row[gl_id]?></td>
        <td>
            <input class="form-control" type='text' name='groupName_[<?=$i?>]' value='<?=$row[gl_name]?>'>
            <a class="btn btn-default" href="#" onclick="member_group_update('<?=$row[gl_name]?>' , '<?=$row[gl_id]?>' ,'<?=$i?>');">수정</a>
        </td>
    </tr>
<?
}
?>
</table>

<div>
    <input type=button class='btn btn-default' value='선택수정' onclick="btn_check(this.form, 'update')">
</div>

</form>

<?
include_once ("./admin.tail.php");
?>
