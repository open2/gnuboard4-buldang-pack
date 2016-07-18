<?
$sub_menu = "300540";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$token = get_token();

$sql_common = " from $g4[singo_table] ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "wr_subject" :
        case "wr_content" :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
        case "wr_content2" :
            $sql_search .= " (wr_subject like '%$stx%' and wr_content like '%$stx%') ";
            break;
        case "wr_id" :
            $wr = explode(",", $stx);
            if ($wr[1] && is_integer($wr[1]))
                ;
            else
                alert("게시글의 wr_id를 입력해주세요");
            $sql_search .= " (bo_table = '$wr[0]' and wr_id = $wr[1]) ";
            break;
        case "sg_reason" :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
        default :
            $sql_search .= " ($sfl like '$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst = "sg_id";
    $sod = "desc";
}

$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt
         $sql_common
         $sql_search
         $sql_order ";
$row = sql_fetch($sql);
$total_count = $row[cnt];

$rows = $config[cf_page_rows];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if (!$page) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$listall = "<a href='$_SERVER[PHP_SELF]' class=tt>처음</a>";

$g4[title] = "게시물신고관리";
include_once("./admin.head.php");

$sql = " select * 
          $sql_common
          $sql_search
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);
?>

<script type="text/javascript">
var list_update_php = "singo_list_update.php";
var list_delete_php = "singo_list_delete.php";
</script>

<form name=fsearch method=get role="form" class="form-inline">
<div class="btn-group">
    <?=$listall?> (신고된 게시물 : <?=number_format($total_count)?>)
</div>
<div class="pull-right">
    <select name=sfl class="form-control">
        <option value='mb_id'>신고된 회원아이디</option>
        <option value='sg_mb_id'>신고한 회원아이디</option>
        <option value='sg_ip'>신고한 IP</option>
        <option value='sg_reason'>신고한 이유</option>
        <option value='wr_subject'>게시글제목</option>
        <option value='wr_content'>게시글내용</option>
        <option value='wr_content2'>게시글제목,내용</option>
        <option value='bo_table'>bo_table</option>
        <option value='wr_id'>bo_table,wr_id</option>
    </select>
    <input class="form-control" type=text name=stx required itemname='검색어' value='<?=$stx?>'>
    <div class="form-group">
        <button class="btn btn-primary">검색</button>
    </div>
</div>
</form>

<form name=fsingolist method=post role="form" class="form-inline">
<input type=hidden name=sst  value='<?=$sst?>'>
<input type=hidden name=sod  value='<?=$sod?>'>
<input type=hidden name=sfl  value='<?=$sfl?>'>
<input type=hidden name=stx  value='<?=$stx?>'>
<input type=hidden name=page value='<?=$page?>'>

<table width=100% class="table table-condensed table-hover table-responsive" style="word-wrap:break-word;">
<tr class="success">
    <td width=30 rowspan=2><input type=checkbox name=chkall value='1' onclick='check_all(this.form)'></td>
    <td width=110 align='left'><?=subject_sort_link('mb_id')?>신고된 회원</a></td>
    <td>게시판 - 게시물 - 신고해제</td>
    <td width=110>게시물 등록일시</td>
    <td width=100>게시물 IP</td>
	  <td width=60 rowspan=2>(회원접근<br>IP차단)</td>
</tr>
<tr class="success">
    <td><?=subject_sort_link('sg_mb_id')?>신고한 회원</a></td>
    <td>신고한 이유</td>
    <td>신고한 일시</td>
    <td>신고한 IP</td>
</tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++) {
    $mb = array();
    $sg_mb = array();

    if ($row[mb_id]) {
        $mb = get_member($row[mb_id], "mb_id, mb_nick, mb_email, mb_homepage, mb_intercept_date");
        $mb_nick = $mb[mb_nick];
        $mb_id = $mb[mb_id];
    } else {
        $mb_nick = "비회원";
        $mb_id = "비회원";
    }

    if ($row[sg_mb_id]) {
        $sg_mb = get_member($row[sg_mb_id], "mb_id, mb_nick, mb_email, mb_homepage, mb_intercept_date");
        $sg_mb_nick = $sg_mb[mb_nick];
        $sg_mb_id = $sg_mb[mb_id];
    } else {
        $sg_mb_nick = "비회원";
        $sg_mb_id = "비회원";
    }

    $hidden_comment = "";
    $wr_subject = "";
    $wr_ip = "";
    $wr_datetime = "";
    $singo_href = "";

    $title = "";

    if ($row['sg_notes']) {
        $wr_subject = $row['sg_notes'];
    } else if ($row['bo_table'] == "@memo") {
        // 쪽지 신고
        $wr_subject = "쪽지 스팸";
        $singo_href = "<a href='$g4[bbs_path]/memo.php?me_id=$row[wr_id]&kind=spam&class=view' target='_blank'>";

        // 게시판 제목
        $bo_subject = "<a href='./singo_list.php?sfl=bo_table&stx=@memo'>" . cut_str($bo[bo_subject],30) . "</a>";
    } else if ($row['bo_table'] == "@user") {
        // 사용자 신고
        $wr_subject = "회원 신고";
        $row['sg_reason'] = "";
        $singo_href = "<a href='$g4[bbs_path]/singo_member_view.php?sg_id=$row[sg_id]' target='_blank'>";

        // 게시판 제목
        $bo_subject = "<a href='./singo_list.php?sfl=bo_table&stx=@user'>" . cut_str($bo[bo_subject],30) . "</a>";
    } else if ($row['bo_table'] == "@hidden_comment") {
        // 딴지걸기 신고
        $hidden_comment = sql_fetch(" select bo_table, wr_id, co_content, co_id, wr_ip, co_datetime from $g4[hidden_comment_table] where co_id = '$row[wr_id]' ");
        $wr_subject = "딴지걸기 신고 - $hidden_comment[co_content]";
        $wr_ip = $hidden_comment['wr_ip'];
        $wr_datetime = $hidden_comment['co_datetime'];
        $bo = get_board($hidden_comment[bo_table], "bo_subject");

        // 게시판 제목
        $bo_subject = "<a href='./singo_list.php?sfl=bo_table&stx=$row[bo_table]'>" . cut_str($bo[bo_subject],30) . "</a>";

        // 게시글 바로가기 링크도 한방

        $singo_href = "<a href='$g4[bbs_path]/board.php?bo_table=$hidden_comment[bo_table]&wr_id=$hidden_comment[wr_id]&h_id=$hidden_comment[co_id]' target='_blank'>";
    } else {
        // 게시글 신고
        $write_table = $g4['write_prefix'].$row[bo_table];
        $bo = get_board($row[bo_table], "bo_subject");
        $sql = " select wr_subject, wr_content, wr_ip, wr_is_comment, wr_parent, wr_datetime, wr_singo from $write_table where wr_id = '$row[wr_id]' ";
        $write_row = sql_fetch($sql);

        // 옛날 신고는 제목이 없으니... 현재의 제목이라도 넣어준다. 제목을 바꿨으면... ㄷㄷㄷ
        if ($row[wr_subject] == "")
            $row[wr_subject] = $write_row[wr_subject];

        if ($write_row[wr_is_comment]) {
            $wr_subject = $row[wr_subject];
            $wr_ip = $row[wr_ip];
            $wr_datetime = $row[wr_datetime];
            
            $title = $row[wr_content];
        } else {
            // wr_singo == 0, 신고해제가 되어 무효가 된 신고라는거. 신고해제는 원글에만 해당.
            if ($write_row[wr_singo] == 0)
                $wr_subject = "<del>" . $row[wr_subject] . "</del>";
            else
                $wr_subject = $row[wr_subject];

            $wr_subject = "<a href='./singo_list.php?sfl=wr_id&stx=$row[bo_table],$row[wr_id]'>" . $wr_subject . "</a>";

            $wr_ip = $row[wr_ip];
            $wr_datetime = $row[wr_datetime];

            // 신고해제 건수를 계산
            $sql3 = " select count(*) as cnt from $g4[unsingo_table] where bo_table='$row[bo_table]' and wr_id = '$row[wr_id]' ";
            $result3 = sql_fetch($sql3);
            if ($result3[cnt] > 0) {
                // 신고 해제건수에 링크를 걸어줘야죠
                $unsingo = " - <b><a href=./unsingo_list.php?sfl=wr_id&stx=$row[bo_table],$row[wr_id] target=new>$result3[cnt]<a></b>";
            }
            else
                $unsingo = " - 0";
        }
        $singo_href = "<a href='$g4[bbs_path]/board.php?bo_table=$row[bo_table]&wr_id=$row[wr_id]' target='_blank'>";

        // 게시판 제목
        $bo_subject = "<a href='./singo_list.php?sfl=bo_table&stx=$row[bo_table]' title='$title'>" . cut_str($bo[bo_subject],30) . "</a>";
    } 

    // 닉을 누르면, 해당 닉의 모든게 검색되게 수정해 주시고
    $mb_nick = "<a href=./singo_list.php?sfl=mb_id&stx=$mb_id>$mb_nick</a>";
    $sg_mb_nick = "<a href=./singo_list.php?sfl=sg_mb_id&stx=$sg_mb_id>$sg_mb_nick</a>";

    if ($mb[mb_intercept_date]) $mb_nick = $mb_nick." <span style='color:#ff0000' title='$mb[mb_intercept_date]'>*</span>";
    if ($sg_mb[mb_intercept_date]) $sg_mb_nick = $sg_mb_nick." <span style='color:#ff0000' title='$sg_mb[mb_intercept_date]'>*</font>";

    $ip_intercept = preg_match("/[\n]?$wr_ip/", $config['cf_intercept_ip']);
    $wr_ip_intercept = "";
    if ($ip_intercept)
        $wr_ip_intercept = " <span style='color:#ff0000'>*</span>";

    $ip_intercept = preg_match("/[\n]?$row[sg_ip]/", $config['cf_intercept_ip']);
    $sg_ip_intercept = "";
    if ($ip_intercept)
        $sg_ip_intercept = " <span style='color:#ff0000'>*</span>";

    $sg_ip = "<a href=./singo_list.php?sfl=sg_ip&stx=$row[sg_ip]>" . $row[sg_ip] . "</a>";

    echo "
    <input type=hidden name=sg_id[$i] value='$row[sg_id]'>
    <tr>
        <td rowspan=2><input type=checkbox name=chk[] value='$i'></td>
        <td title='$row[mb_id]' align='left'>$mb_nick</td>
        <td>
                $bo_subject -
                $wr_subject
                $unsingo
                {$singo_href}<i class='fa fa-external-link'></i></a>
        </td>
        <td>".get_datetime($wr_datetime)."</td>
        <td>$wr_ip $wr_ip_intercept</td>
        <td>
        <a href=\"javascript:singo_intercept('$row[mb_id]', '$wr_ip');\">차단</a>
        </td>
    </tr>
    <tr>
        <td title='$row[sg_mb_id]'>: $sg_mb_nick</td>
        <td>".get_text($row[sg_reason])."</td>
        <td>".get_datetime($row[sg_datetime])."</td>
        <td>$sg_ip $sg_ip_intercept</td>
        <td><a href=\"javascript:singo_intercept('$row[sg_mb_id]', '$row[sg_ip]');\">차단</a></td>
    </tr>
    ";
}

if ($i == 0)
    echo "<tr><td colspan='6' align=center height=100>내역이 없습니다.</td></tr>";

echo "</table>";
?>

<!-- 페이지 -->
<div class="hidden-xs" style="text-align:center;">
    <ul class="pagination">
    <?=get_paging($config[cf_write_pages], $page, $total_page, "$_SERVER[PHP_SELF]?$qstr&page=");?>
    </ul>
</div>

<div class="btn-group">
    <? if ($is_admin == "super") { ?>
        <input type=button class='btn btn-default' value='선택삭제' onclick="btn_check(this.form, 'delete')">
    <? } ?>
</div>

<?
if ($stx)
    echo "<script language='javascript'>document.fsearch.sfl.value = '$sfl';</script>\n";
?>
</form>

<p>
* 삭제시 신고된 내역만을 삭제하며 게시물의 삭제는 하지 않습니다.<br>
* 신고회원과 신고대상회원으로 나누어져 있으며 각각 차단 할 수 있습니다. (정상적인 글을 신고하는 회원 차단 기능)<br>
* 차단하는 경우 기본환경설정의 접근차단IP와 회원정보의 접근차단에 모두 등록됩니다.<br>
* 회원별명 옆의 <font color='#ff0000'>*</font> 표시는 차단된 회원임을 나타냅니다. 마우스 오버시 차단일자가 표시됩니다.
</p>

<form name="fsingo" method="post" action="" style="margin:0px;">
<input type="hidden" name="mb_id">
<input type="hidden" name="ip">
<input type="hidden" name="page" value="<?=$page?>">
</form>

<script type="text/javascript">
function singo_intercept(mb_id, ip) 
{
    var f = document.fsingo;
    if (confirm(ip+" : IP를 정말 차단 하시겠습니까?")) {
        f.mb_id.value = mb_id;
        f.ip.value = ip;
        f.action = "singo_intercept.php";
        f.submit();
    }
}
</script>

<?
include_once ("./admin.tail.php");
?>
