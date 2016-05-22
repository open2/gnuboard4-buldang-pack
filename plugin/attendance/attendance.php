<?php
include_once("./_common.php");

//현재 페이지에서 사용될 플러그인 css 경로 적음
$plugin_dirs = Array();
$plugin_dirs[] = $g4[attendance_css_path];

$g4[title] = "출석체크";
include_once("$g4[path]/_head.php");

if (empty($s_date)) $s_date = date('Y-m-d');

if ($go == 'prev') {//이전달

	// 어느 날짜던 1일로 만든후 하루를 뺀다
    $s_date = date('Y-m-d', strtotime(substr($s_date, 0, 4) . '-' . substr($s_date, 5, 2) . '-01 -1 month'));
}
else if ($go == 'next') {//다음달

	// 어느 날짜던 1일로 만든후 하루를 더한다
    $s_date = date('Y-m-d', strtotime(substr($s_date, 0, 4) . '-' . substr($s_date, 5, 2) . '-01 +1 month'));
}

$year = substr($s_date, 0, 4);
$month = substr($s_date, 5, 2);
$day = substr($s_date, 8, 2);

$qstr_html .= "&amp;s_date=" . urlencode($s_date);

if($att['attendance_gnu'])
	$att_levelname = $member[mb_level];
else
	$att_levelname = sql_value("select ln_name{$att[attendance_level]} from $g4[levelname_table] where ln_level{$att[attendance_level]} = '$att[attendance_level]'");

$sql_common = " from $g4[attendance_plugin_table] ";
$sql_search = " where at_date = '" . $year . '-' . $month . '-' . $day . "' ";
$sql_order = " order by at_datetime desc ";

$sql = " select count(*) as cnt
         $sql_common
         $sql_search
         $sql_order ";
$row = sql_fetch($sql);
$total_count = $row[cnt];

$sql = " select count(*) as cnt
         $sql_common
         $sql_search and at_victory = 3
         $sql_order ";
$row = sql_fetch($sql);
$total_win_count = $row[cnt];

$sql = " select count(*) as cnt
         $sql_common
         $sql_search and at_victory = 2
         $sql_order ";
$row = sql_fetch($sql);
$total_tie_count = $row[cnt];

$sql = " select count(*) as cnt
         $sql_common
         $sql_search and at_victory = 1
         $sql_order ";
$row = sql_fetch($sql);
$total_loss_count = $row[cnt];

$sql = " select mb_id,as_victory,as_successive
         from $g4[attendance_successive_plugin_table]
         order by as_successive desc
         limit 0, $att[attendance_honor_rows] ";
$result_honor = sql_query($sql);

$rows = $att['attendance_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$pages = get_paging($att['attendance_pages'], $page, $total_page, "?s_date=$s_date&amp;currentId=$currentId&amp;page=");

$sql = " select *
          $sql_common
          $sql_search
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);

if($is_member){
	$sql_current = " select mb_id,at_date
			  $sql_common
			  where at_date like '{$year}-{$month}%' and mb_id = '$member[mb_id]' ";
	$result_current = sql_query($sql_current);
	$current_data = Array();
	while($temp_current = sql_fetch_array($result_current)){
		$temp_day = (int)substr($temp_current['at_date'], 8, 2);
		$current_data[$temp_day] = 1;

	}
	// 메모리해제
	@sql_free_result($result_current);
}

?>
<?php if($att['attendance_gnu']){ // 그누보드 설치일때만 호출?>
<link type="text/css" rel="stylesheet" media="all" href="<?php echo $g4[attendance_css_path];?>/style.css" />
<?php }?>
<div class="row hidden-xs">
    <div class="col-md-4">

 	  <div class="attendance-info calendar-wrap  well well-sm">

	    <div class="attendance_title">
			<h5><strong><i class="fa fa-calendar"></i> 출석체크 일자</strong></h5>
			</div>

			<form id='fattendancelist' name='fattendancelist' method="get" action="<?php echo $g4[attendance_path];?>/attendance.php">
				<input type="hidden" name="go" />
				<input type="hidden" name="s_date" value="<?php echo $s_date?>" />
				<input type="hidden" name="currentId" value="<?php echo $currentId?>" />
				<div class="huddakP-calendar-month-select">
					<a href='#' onclick='document.fattendancelist.go.value="prev"; document.fattendancelist.submit();' class="btn btn-default tooltip-top" title="이전달"><i class="fa fa-chevron-left"></i></a>
					<strong><?php echo $year?> - <?php echo $month?></strong>
					<a href='#' onclick='document.fattendancelist.go.value="next"; document.fattendancelist.submit();' class="btn btn-default tooltip-top" title="다음달"><i class="fa fa-chevron-right"></i></a>
				</div>
			</form>
				
			<table class="huddakP-attendance-calendar">
				<thead>
					<tr>
						<th scope="col" class="sun">일</th>
						<th scope="col">월</th>
						<th scope="col">화</th>
						<th scope="col">수</th>
						<th scope="col">목</th>
						<th scope="col">금</th>
						<th scope="col" class="sat">토</th>
					</tr>
				</thead>
				<tbody>
					<? //달력출력
					
					// 이달에 첫 요일을 구함
					$fist_week = date('w', strtotime($year . '-' . $month . '-01'));
					// 다음달에서 하루를 빼서 마지막 두자리수 일을 구한다
					$last_day = date('d', strtotime($year . '-' . $month . '-01 + 1 month') - (3600 * 24));
					// 이달의 시작 주를 구한다
					$loof = ceil(($last_day + $fist_week) / 7);

					for($i = 0; $i < $loof * 7; $i++){

						// 현재 일
						$c_day = ($i - $fist_week) + 1;
						if ($c_day < 1 || $c_day > $last_day) $c_day = '';

						if ($i % 7 == 0) {

							$emclass = ' class="sun"';

							echo "<tr>";
						}
						else if ($i % 7 == 6){

							$emclass = ' class="sat"';
						}
						else {

							$emclass = '';
						}

						$tmp_day = "";
						if(strlen($c_day) == 1)
							$tmp_day = "0".$c_day;
						else
							$tmp_day = $c_day;

						// 년월일이 같으면
						$todayclass = '';
						if(date('Y-m-d') == "{$year}-{$month}-{$tmp_day}")
							$todayclass = ' class="today"';

						// 선택한 날짜 활성화
						if("{$year}-{$month}-{$tmp_day}" == "{$year}-{$month}-{$day}")
							$emclass = ' class="current"';

						if($current_data[$c_day])
							$c_day = "√";

						?>
							<td<?=$todayclass?>><a href="?s_date=<? echo "{$year}-{$month}-{$tmp_day}";?>"<?=$emclass;?>><?=$c_day?></a></td>
						<?

						if ($i % 7 == 6) {

							echo "</tr>";
						}
					}

					?>
				</tbody>
			</table>
			<div class="alert alert-info" role="alert">
				<i class="fa fa-calendar"></i> 오늘의 출석 회원 <?php echo $total_count;?>명<br />
				<i class="fa fa-thumbs-o-up"></i> 승 <?php echo $total_win_count;?>명 / 무 <?php echo $total_tie_count;?>명 / 패 <?php echo $total_loss_count;?>명
			</div>
		</div>
	 </div>
     <div class="col-md-4">
	 <div class="attendance-info well well-sm">

	        <div class="attendance_title">
			<h5><strong><i class="fa fa-calendar-check-o"></i> 출석체크 방법</strong></h5>
			</div>

			<ul class="notice">
				<li><i class="fa fa-check-square-o"></i> 회원등급 <span class="label label-info"><?php echo $att_levelname;?> 이상</span> 가능.</li>
				<li><i class="fa fa-check-square-o"></i> 한 아이디로 <span class="label label-warning">일 <?php echo $att['attendance_number'];?>회</span> 가능.</li>
				<li><i class="fa fa-check-square-o"></i> 한 아이피로 <span class="label label-danger">일 <?php echo $att['attendance_number'];?>회</span> 가능.</li>
				<li><i class="fa fa-check-square-o"></i> 매일 출석 시간 <span class="label label-default"><?php echo $att['attendance_start_time'];?> ~ <?php echo $att['attendance_end_time'];?></span></li>
				<li>
					<span class="tit"><i class="fa fa-check-square-o"></i> 획득 포인트</span>
					<ul>
						<li>승리 : <?php echo $att['attendance_win_start_point'];?> ~ <?php echo $att['attendance_win_end_point'];?> 점</li>
						<li>무승부 : <?php echo $att['attendance_tie_start_point'];?> ~ <?php echo $att['attendance_tie_end_point'];?> 점</li>
						<li>패배 : <?php echo $att['attendance_loss_start_point'];?> ~ <?php echo $att['attendance_loss_end_point'];?> 점</li>
					</ul>
				</li>
				<li>
					<span class="tit"><i class="fa fa-check-square-o"></i> 부가 포인트</span>
					<?php echo $att['attendance_successive'];?> 연승,연패,연무 <i class="fa fa-info-circle tooltip-top" title="획득 포인트 * 연속횟수"></i>
				</li>
				<li><div class="alert alert-success" role="alert">
				<i class="fa fa-trophy"></i> 명예의 전당은 승무패가 연속으로 많을경우 상위노출됩니다.</div>
				</li>
			</ul>
		</div>
	 </div>
     <div class="col-md-4">
	 <div class="top-list well well-sm">

	        <div class="attendance_title">
			<h5><strong><i class="fa fa-trophy"></i> 명예의 전당</strong></h5>
			</div>

			<ol>
				<?php
				$honnor_cnt = 1;
				while($row_honor = sql_fetch_array($result_honor)){
					$mb = get_member($row_honor[mb_id]);
					// 불당팩 휴면회원은???
					if ($mb[mb_nick] == "") {
					    $mb = sql_fetch(" select * from $g4[unlogin_table] where mb_id = '$mb[mb_id]' ");
					}
					// 삭제된 회원은???
					if ($mb[mb_nick] == "") {
					    $mb['mb_nick'] = "undefined";
          }
					$nick = get_sideview($mb['mb_id'], $mb['mb_nick'], $mb['mb_email'], $mb['mb_homepage']);
				?>
				<li>
					<span class="user"><?php echo $nick;?></span>
					<span class="victory">
					(<?php echo $row_honor[as_successive]; // 연속 개수?>연
					<img src="<?php echo $g4[attendance_path];?>/img/icon_top-victory<?php echo $row_honor[as_victory]; // 승패 3승,2무,1패?>.gif" alt="" />
					)
					</span>
				</li>
				<?php
					$honnor_cnt++;
				}
				?>
			</ol>
		</div>
	 </div>
</div>

<div class="clearfix"></div>
<hr />

<div class="row">
    <div class="col-md-4">
	   
	   <!--명예의전당/출석체크방법 모달창버튼 { -->
	   <div class="pull-right clearfix visible-xs btn-group btn-group-lg" role="group" aria-label="명예의전당/출석체크방법 모달창버튼" style="margin-bottom:15px;">
	      <a class="btn btn-default" data-toggle="modal" data-target="#att_calendar"><i class="fa fa-calendar"></i></a>
	      <a class="btn btn-default" data-toggle="modal" data-target="#att_check"><i class="fa fa-calendar-check-o"></i></a>
	      <a class="btn btn-default" data-toggle="modal" data-target="#att_trophy"><i class="fa fa-trophy"></i></a>
	   </div>
	   <!-- } -->
	   
	<div class="huddakP-attendance-form">
		<form id='fattendancewrite' name='fattendancewrite' method="post" action="<?php echo $g4[attendance_path];?>/attendance_update.php">
			<input type="hidden" name="s_date" value="<?php echo $s_date?>" />
			<input type="hidden" name="currentId" value="<?php echo $currentId?>" />
			<input type="hidden" name="at_type" title="가위바위보" />
			<ul class="type-select">
				<li><a href="1" title="묵 선택" class="n1 btn btn-info"><i class="fa fa-hand-rock-o fa-5x"></i></a></li>
				<li><a href="2" title="찌 선택" class="n2 btn btn-success"><i class="fa fa-hand-peace-o fa-5x"></i></a></li>
				<li><a href="3" title="빠 선택" class="n3 btn btn-danger"><i class="fa fa-hand-paper-o fa-5x"></i></a></li>
			</ul>
	</div>
	</div>
	<div class="col-md-8">
	<div class="input-group">

	<span class="">
	<?php if ($att['char_min'] || $att['char_max']) { ?><span id="char_count"></span>글자 (최소 : <?php echo $att['char_min'];?> , 최대 : <?php echo $att['char_max'];?>)<?php } ?>
	</span>

      <textarea id="at_memo" name="at_memo" class="form-control" rows=3 placeholder="출석체크시 힘이 솟아나는 단어를 적어주세요."><?php echo $att[attendance_memo][rand(0,count($att[attendance_memo])-1)];?></textarea>
      <span class="att_btn input-group-btn">
        <button type="input" alt="출첵!" class="tooltip-top btn btn-primary data-toggle="tooltip" data-placement="top" title="출석체크"/>출석체크</button>
      </span>
    </div><!-- /input-group -->
	<br />
		</form>
	</div>
	</div>

<div class="row">
    <div class="col-md-12">
	  <div class="panel panel-default">
      <!-- Default panel contents -->
      <div class="attendance-info panel-heading">
	  <div class="attendance_title">
			<h5><strong><i class="fa fa-list-alt"></i> 출석체크 현황판</h5>
	  </div>
	  </div>

      <!-- Table -->
      <table class="table" style="font-size:12px;">
        <thead>
          <tr>
				<th scope="col" colspan="4" class="text-center hidden-xs col-lg-2 col-md-2 col-sm-2 ">승패</th>
				<th scope="col" class="text-center hidden-xs col-lg-1 col-md-1 col-sm-1">연속</th>
				<th scope="col" class="text-center hidden-xs col-lg-1 col-md-1 col-sm-1">포인트</th>
				<th scope="col" class="text-center hidden-xs col-lg-1 col-md-1 col-sm-1">출석자</th>
				<th scope="col" class="text-center hidden-xs">코멘트</th>
        </thead>
        <tbody>
          <?php
			while($row = sql_fetch_array($result)){
				$mb = get_member($row[mb_id]);
				$nick = get_sideview($mb['mb_id'], $mb['mb_nick'], $mb['mb_email'], $mb['mb_homepage']);
			?>
			<tr>
				<td class="hidden-xs">
				    <? // 승패 3승,2무,1패
				    if ($row[at_victory] == 1)
				        echo '<a href="#" title="승리" class="n2 btn btn-success disabled">승리</a>';
				    else if ($row[at_victory] == 2)
				        echo '<a href="#" title="무승부" class="n1 btn btn-info disabled">무승부</a>';
				    else
				        echo '<a href="#" title="패배" class="n3 btn btn-danger disabled">패배</a>';
            ?>
				</td>
				<td class="hidden-xs">
				    <? // 1 묵, 2 찌, 3 빠
				    if ($row[at_default_type] == 1)
				        echo '<a href="#" title="묵 선택" class="n1 btn btn-info"><i class="fa fa-hand-rock-o fa-2x btn-info"></i></a>';
				    else if ($row[at_default_type] == 2)
				        echo '<a href="#" title="찌 선택" class="n2 btn btn-success"><i class="fa fa-hand-peace-o fa-2x"></i></a>';
				    else
				        echo '<a href="#" title="빠 선택" class="n3 btn btn-danger"><i class="fa fa-hand-paper-o fa-2x"></i></a>';
            ?>
				</td>
				<td class="hidden-xs">
					<img src="<?php echo $g4[attendance_path];?>/img/txt_vs.png" alt="VS" />
				</td>
				<td class="hidden-xs">
				    <? // 1 묵, 2 찌, 3 빠
				    if ($row[at_type] == 1)
				        echo '<a href="#" title="묵 선택" class="n1 btn btn-info"><i class="fa fa-hand-rock-o fa-2x"></i></a>';
				    else if ($row[at_type] == 2)
				        echo '<a href="#" title="찌 선택" class="n2 btn btn-success"><i class="fa fa-hand-peace-o fa-2x"></i></a>';
				    else
				        echo '<a href="#" title="빠 선택" class="n3 btn btn-danger"><i class="fa fa-hand-paper-o fa-2x"></i></a>';
            ?>
				</td>
				<td class="hidden-xs text-center"><?php echo $row[at_successive]; // 연속 개수?></td>
				<td class="hidden-xs text-center"><?php echo $row[po_point]; // 당첨포인트?></td>
				<td class="user hidden-xs text-center"><?php echo $nick;?></td>
				<td class="comment hidden-xs text-left">
					<?php echo $row[at_memo];?>
					<a href="<?php echo $g4[attendance_path];?>/attendance_delete.php?at_id=<?php echo $row[at_id];?>&amp;s_date=<?php echo $s_date;?>&amp;currentId=<?php echo $currentId;?>">
					<?php if($is_admin){?>&nbsp;<i class="fa fa-trash-o"></i></a>&nbsp;<?php }?>
				</td>

				<!--모바일 현황판 { -->
				<td class="visible-xs">
					<span class="label label-default pull-left tooltip-top" title="승리결과">
					<img src="<?php echo $g4[attendance_path];?>/img/victory_<?php echo $row[at_victory]; // 승패 3승,2무,1패?>.png" alt="" />
					<img src="<?php echo $g4[attendance_path];?>/img/icon_<?php echo $row[at_default_type];// 1 묵, 2 찌, 3 빠?>.png" alt="" />
					<img src="<?php echo $g4[attendance_path];?>/img/txt_vs.png" alt="VS" />
					<img src="<?php echo $g4[attendance_path];?>/img/icon_<?php echo $row[at_type];// 1 묵, 2 찌, 3 빠?>.png" alt="" />
					</span>
					
					<span class="label label-danger pull-right tooltip-top" title="연속승리">
					<h5><?php echo $row[at_successive]; // 연속 개수?></h5>
					</span>
					<span class="label label-default pull-right tooltip-top" title="당첨포인트">
					<h5><?php echo $row[po_point]; // 당첨포인트?></h5>
					</span>

					&nbsp;<?php echo $nick;?>
			    &nbsp;&nbsp;&nbsp;<?php echo $row[at_memo];?>
					<a href="<?php echo $g4[attendance_path];?>/attendance_delete.php?at_id=<?php echo $row[at_id];?>&amp;s_date=<?php echo $s_date;?>&amp;currentId=<?php echo $currentId;?>">
					<?php if($is_admin){?>&nbsp;<i class="fa fa-scissors fa-2x"></i>&nbsp;<?php }?>


				</td>
				<!-- } -->
			</tr>
			<?php
			}
			?>
        </tbody>
      </table>
	      <div style="text-align:center;">
    	      <ul class="pagination"><?php echo $pages;?>
    	      </ul>
	      </div>
    </div>
	</div>
</div>

<!--명예의전당 모달 { -->
<div class="top-list modal fade" id="att_trophy" tabindex="-1" role="dialog" aria-labelledby="att_trophyLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <span class="modal-title" id="myModalLabel">
			<h5><strong><i class="fa fa-trophy"></i> 명예의 전당</strong></h5>
		</span>
      </div>
      <div class="modal-body">
	  <ol>
				<?php
				$sql = " select mb_id,as_victory,as_successive
				from $g4[attendance_successive_plugin_table]
				order by as_successive desc
				limit 0, $att[attendance_honor_rows] ";
				$result_honor = sql_query($sql);

				$honnor_cnt = 1;
				while($row_honor = sql_fetch_array($result_honor)){
					$mb = get_member($row_honor[mb_id]);
					$nick = get_sideview($mb['mb_id'], $mb['mb_nick'], $mb['mb_email'], $mb['mb_homepage']);
				?>
				<li>
					<span class="user"><?php echo $nick;?></span>
					<span class="victory">
					(<?php echo $row_honor[as_successive]; // 연속 개수?>연
					<img src="<?php echo $g4[attendance_path];?>/img/icon_top-victory<?php echo $row_honor[as_victory]; // 승패 3승,2무,1패?>.gif" alt="" />
					)
					</span>
				</li>
				<?php
					$honnor_cnt++;
				}
				?>
	</ol>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--명예의전당 모달 } -->

<!-- 출석체크방법 모달 { -->
<div class="top-list modal fade" id="att_check" tabindex="-1" role="dialog" aria-labelledby="att_checkLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <span class="modal-title" id="myModalLabel">
			<h5><strong><i class="fa fa-calendar-check-o"></i> 출석체크 방법</strong></h5>
		</span>
      </div>
      <div class="modal-body">
	  <ul class="notice">
				<li><i class="fa fa-check-square-o"></i> 회원등급 <span class="label label-info"><?php echo $att_levelname;?> 이상</span> 가능.</li>
				<li><i class="fa fa-check-square-o"></i> 한 아이디로 <span class="label label-warning">일 <?php echo $att['attendance_number'];?>회</span> 가능.</li>
				<li><i class="fa fa-check-square-o"></i> 한 아이피로 <span class="label label-danger">일 <?php echo $att['attendance_number'];?>회</span> 가능.</li>
				<li><i class="fa fa-check-square-o"></i> 매일 출석 시간 <span class="label label-default"><?php echo $att['attendance_start_time'];?> ~ <?php echo $att['attendance_end_time'];?></span></li>
				<li>
					<span class="tit"><i class="fa fa-check-square-o"></i> 획득 포인트</span>
					<ul>
						<li>승리 : <?php echo $att['attendance_win_start_point'];?> ~ <?php echo $att['attendance_win_end_point'];?> 점</li>
						<li>무승부 : <?php echo $att['attendance_tie_start_point'];?> ~ <?php echo $att['attendance_tie_end_point'];?> 점</li>
						<li>패배 : <?php echo $att['attendance_loss_start_point'];?> ~ <?php echo $att['attendance_loss_end_point'];?> 점</li>
					</ul>
				</li>
				<li>
					<span class="tit"><i class="fa fa-check-square-o"></i> 부가 포인트</span>
					<?php echo $att['attendance_successive'];?> 연승,연패,연무 <i class="fa fa-info-circle tooltip-top" title="획득 포인트 * 연속횟수"></i>
				</li>
				<li><div class="alert alert-success" role="alert">
				<i class="fa fa-trophy"></i> 명예의 전당은 승무패가 연속으로 많을경우 상위노출됩니다.</div>
				</li>
			</ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- 출석체크방법 모달 } -->

<!--달력 모달 { -->
<div class="top-list modal fade" id="att_calendar" tabindex="-1" role="dialog" aria-labelledby="att_calendarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <span class="modal-title" id="myModalLabel">
			<h5><strong><i class="fa fa-calendar"></i> 출석체크 일자</strong></h5>
		</span>
      </div>
      <div class="modal-body">
				<div class="huddakP-calendar-month-select">
					<a href='#' onclick='document.fattendancelist.go.value="prev"; document.fattendancelist.submit();' class="btn btn-default tooltip-top" title="이전달"><i class="fa fa-chevron-left"></i></a>
					<strong><?php echo $year?> - <?php echo $month?></strong>
					<a href='#' onclick='document.fattendancelist.go.value="next"; document.fattendancelist.submit();' class="btn btn-default tooltip-top" title="다음달"><i class="fa fa-chevron-right"></i></a>
				</div>
 
			<table class="huddakP-attendance-calendar">
				<thead>
					<tr>
						<th scope="col" class="sun">일</th>
						<th scope="col">월</th>
						<th scope="col">화</th>
						<th scope="col">수</th>
						<th scope="col">목</th>
						<th scope="col">금</th>
						<th scope="col" class="sat">토</th>
					</tr>
				</thead>
				<tbody>
					<? //달력출력
					
					// 이달에 첫 요일을 구함
					$fist_week = date('w', strtotime($year . '-' . $month . '-01'));
					// 다음달에서 하루를 빼서 마지막 두자리수 일을 구한다
					$last_day = date('d', strtotime($year . '-' . $month . '-01 + 1 month') - (3600 * 24));
					// 이달의 시작 주를 구한다
					$loof = ceil(($last_day + $fist_week) / 7);

					for($i = 0; $i < $loof * 7; $i++){

						// 현재 일
						$c_day = ($i - $fist_week) + 1;
						if ($c_day < 1 || $c_day > $last_day) $c_day = '';

						if ($i % 7 == 0) {

							$emclass = ' class="sun"';

							echo "<tr>";
						}
						else if ($i % 7 == 6){

							$emclass = ' class="sat"';
						}
						else {

							$emclass = '';
						}

						$tmp_day = "";
						if(strlen($c_day) == 1)
							$tmp_day = "0".$c_day;
						else
							$tmp_day = $c_day;

						// 년월일이 같으면
						$todayclass = '';
						if(date('Y-m-d') == "{$year}-{$month}-{$tmp_day}")
							$todayclass = ' class="today"';

						// 선택한 날짜 활성화
						if("{$year}-{$month}-{$tmp_day}" == "{$year}-{$month}-{$day}")
							$emclass = ' class="current"';

						if($current_data[$c_day])
							$c_day = "√";

						?>
							<td<?=$todayclass?>><a href="?s_date=<? echo "{$year}-{$month}-{$tmp_day}";?>"<?=$emclass;?>><?=$c_day?></a></td>
						<?

						if ($i % 7 == 6) {

							echo "</tr>";
						}
					}

					?>
				</tbody>
			</table>
			<div class="alert alert-info" role="alert">
				<i class="fa fa-heartbeat"></i> 오늘의 출석 회원 <?php echo $total_count;?>명<br />
				<i class="fa fa-thumbs-o-up"></i> 승 <?php echo $total_win_count;?>명 / 무 <?php echo $total_tie_count;?>명 / 패 <?php echo $total_loss_count;?>명
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--달력 모달 } -->

<script type="text/javascript">
//<![CDATA[
$(function() {

	var aw = $('.huddakP-attendance-form').width();
	var bw = $('.type-select').width();
	var tw = aw - bw - 120;
	$('.huddakP-attendance-form .iTextarea').css('width',tw);
	$('.huddakP-attendance-form .iLabel').css('width',tw - 10);

	var randType = <?php echo rand(1,3);?>;

	$('input[name=at_type]').val(randType);
	$('.type-select .n'+randType).parent().addClass('selected');

	$('.type-select a').click(function(){
		var typeVal = $(this).attr('href');
		$('.type-select li').removeClass('selected');
		$(this).parent().addClass('selected');
		$('input[name=at_type]').val(typeVal);
		return false;
	});

    $("#at_memo")
    .keyup(function() {
        check_byte('at_memo', 'char_count');
    });

    $("#fattendancewrite")
    .attr("autocomplete", "off")
    .submit(function() {

		if (!$('input[name=at_type]').val())
		{
			alert('묵/찌/빠를 선택해주세요.');
			return false;
		}

		if(!wrestSubmit(this))
			return false;

        if($("#char_count") && (<?php echo $att[char_min];?> > 0 || <?php echo $att[char_max];?> > 0)) {
            var cnt = parseInt($("#char_count").html());
            if (<?php echo $att['char_min'];?> > 0 && <?php echo $att['char_min'];?> > cnt) {
                 alert("내용은 " + <?php echo $att['char_min'];?> + "글자 이상 쓰셔야 합니다.");
                 return false;
            }
            else if (<?php echo $att['char_max'];?> > 0 && <?php echo $att['char_max'];?> < cnt) {
                alert("내용은 " + <?php echo $att['char_max'];?> + "글자 이하로 쓰셔야 합니다.");
                return false;
            }
        }

    })
    .find(":input[type=text]:visible:enabled:first").focus();
});
//]]>
</script>

<?
include_once("$g4[path]/_tail.php");
?>
