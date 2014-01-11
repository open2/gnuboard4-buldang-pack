<!-- 메인화면 최신글 시작 -->

<?
include_once("$g4[path]/lib/popular.lib.php");      // 인기글
include_once("$g4[path]/lib/latest.lib.php");       // 최신글
include_once("$g4[path]/lib/latest.group.lib.php"); // 그룹 최신글
include_once("$g4[path]/lib/latest.my.lib.php"); // 그룹 최신글
include_once("$g4[path]/lib/latest.club.lib.php");  // 클럽 최신글
?>
<div class="row-fluid row">
<div class="col-sm-6">
<?echo latest_scrap("scrap", "", "echo4me", 9, 40);?>
</div>
<div class="col-sm-6">
<table width=100%><tr><td><!-- 요기만 table로 감싸주는 것은 그렇게 하지 않으면 div, span 태그가 이상하게 동작하기 때문이다 -->
<?echo db_cache('main_notice2', 1, "latest_one('one', 'gnu4_pack_skin, 118, 0, 430)");?>
</td></tr></table>
</div>
</div>

<div class="row-fluid row">
<div class="col-sm-6">
<?echo db_cache('all_latest', 1, "latest_group(naver, , 12, 40, , 전체최근글, '$g4[bbs_path]/new.php')");?>
</div>
<div class="col-sm-6">
<? 
$db_key = $member[mb_id] . "_all_my_latest";
echo db_cache("$db_key", 1, "latest_group(naver, , 12, 40, , 전체내글의반응, '$g4[bbs_path]/new.php','my_datetime')");
?>
</div>
</div>

<div class="row-fluid row">
<div class="col-sm-6">
<?
// 블로그 최신글을 출력
include_once("$g4[path]/lib/latest.gblog.lib.php");
echo latest_gblog('naver','',12,40);
?>
</div>
<div class="col-sm-6">
<?
// 클럽 최신글을 출력
?>
</div>
</div>

<div class="row-fluid row">
<div class="col-sm-6">
<?
// 최근글 - 내가 그냥 출력하고 싶은거 지정할 때,
echo db_cache('gr_trash', 1, "latest(naver, gnu4_pack)");
?>
</div>
<div class="col-sm-6">
<?echo db_cache('gr_turning', 1, "latest(naver, gnu4_turning)");?>
</div>
</div>

<!-- 메인화면 최신글 끝 -->