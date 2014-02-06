<?
$sub_menu = "100410";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$token = get_token();

$g4[title] = "db상태정보";
include_once("./admin.head.php");

$colspan = 13;
?>

<div>
		<strong>DB size</strong> : <span name=size id=size></span>
</div>

<table width=100% class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<tr class="success">
		<td>No</td>
		<td>테이블 이름</td>
		<td>형식</td>
		<td>줄(Rows)</td>
		<td>데이타 용량</td>
		<td>인덱스 용량</td>
		<td><b>전체 용량</b></td>
		<td>생성시간</td>
</tr>
<?
$result = sql_query("show table status from $mysql_db like '$g4[table_prefix]%' ");
$size = 0;
$num = 1;
while($dbData=sql_fetch_array($result)) {
		$size += $dbData[Data_length]+$dbData[Index_length];
?>
    <tr>
    		<td><?=$num?></td>
		    <td><?=$dbData[Name]?></td>
    		<td><?=$dbData[Type]?></td>
		    <td><?=number_format($dbData[Rows])?></td>
    		<td><?=getFileSize($dbData[Data_length])?></td>
		    <td><?=getFileSize($dbData[Index_length])?></td>
    		<td><?=getFileSize($dbData[Data_length]+$dbData[Index_length])?></td>
		    <td><?=$dbData[Create_time]?></td>
  	</tr>
<?
		$num++;
}
?>
</table>

<script type="text/javascript">
$("#size").text("<?=getFileSize($size)?> (<?=$num-1?>개)");
</script>

<?
include_once("./admin.tail.php");
?>

<?
	function getfilesize($size) {
		if(!$size) return "0 Byte";
		if($size<1024) { 
			return ($size." Byte");
		} elseif($size >1024 && $size< 1024 *1024)  {
			return sprintf("%0.1f KB",$size / 1024);
		}
		else return sprintf("%0.2f MB",$size / (1024*1024));
	}
?>
