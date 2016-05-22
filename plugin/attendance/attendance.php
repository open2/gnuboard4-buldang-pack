<?php
include_once("./_common.php");

//���� ���������� ���� �÷����� css ��� ����
$plugin_dirs = Array();
$plugin_dirs[] = $g4[attendance_css_path];

$g4[title] = "�⼮üũ";
include_once("$g4[path]/_head.php");

if (empty($s_date)) $s_date = date('Y-m-d');

if ($go == 'prev') {//������

	// ��� ��¥�� 1�Ϸ� ������ �Ϸ縦 ����
    $s_date = date('Y-m-d', strtotime(substr($s_date, 0, 4) . '-' . substr($s_date, 5, 2) . '-01 -1 month'));
}
else if ($go == 'next') {//������

	// ��� ��¥�� 1�Ϸ� ������ �Ϸ縦 ���Ѵ�
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
$total_page  = ceil($total_count / $rows);  // ��ü ������ ���
if ($page == "") { $page = 1; } // �������� ������ ù ������ (1 ������)
$from_record = ($page - 1) * $rows; // ���� ���� ����

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
	// �޸�����
	@sql_free_result($result_current);
}

?>
<?php if($att['attendance_gnu']){ // �״����� ��ġ�϶��� ȣ��?>
<link type="text/css" rel="stylesheet" media="all" href="<?php echo $g4[attendance_css_path];?>/style.css" />
<?php }?>
<div class="row hidden-xs">
    <div class="col-md-4">

 	  <div class="attendance-info calendar-wrap  well well-sm">

	    <div class="attendance_title">
			<h5><strong><i class="fa fa-calendar"></i> �⼮üũ ����</strong></h5>
			</div>

			<form id='fattendancelist' name='fattendancelist' method="get" action="<?php echo $g4[attendance_path];?>/attendance.php">
				<input type="hidden" name="go" />
				<input type="hidden" name="s_date" value="<?php echo $s_date?>" />
				<input type="hidden" name="currentId" value="<?php echo $currentId?>" />
				<div class="huddakP-calendar-month-select">
					<a href='#' onclick='document.fattendancelist.go.value="prev"; document.fattendancelist.submit();' class="btn btn-default tooltip-top" title="������"><i class="fa fa-chevron-left"></i></a>
					<strong><?php echo $year?> - <?php echo $month?></strong>
					<a href='#' onclick='document.fattendancelist.go.value="next"; document.fattendancelist.submit();' class="btn btn-default tooltip-top" title="������"><i class="fa fa-chevron-right"></i></a>
				</div>
			</form>
				
			<table class="huddakP-attendance-calendar">
				<thead>
					<tr>
						<th scope="col" class="sun">��</th>
						<th scope="col">��</th>
						<th scope="col">ȭ</th>
						<th scope="col">��</th>
						<th scope="col">��</th>
						<th scope="col">��</th>
						<th scope="col" class="sat">��</th>
					</tr>
				</thead>
				<tbody>
					<? //�޷����
					
					// �̴޿� ù ������ ����
					$fist_week = date('w', strtotime($year . '-' . $month . '-01'));
					// �����޿��� �Ϸ縦 ���� ������ ���ڸ��� ���� ���Ѵ�
					$last_day = date('d', strtotime($year . '-' . $month . '-01 + 1 month') - (3600 * 24));
					// �̴��� ���� �ָ� ���Ѵ�
					$loof = ceil(($last_day + $fist_week) / 7);

					for($i = 0; $i < $loof * 7; $i++){

						// ���� ��
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

						// ������� ������
						$todayclass = '';
						if(date('Y-m-d') == "{$year}-{$month}-{$tmp_day}")
							$todayclass = ' class="today"';

						// ������ ��¥ Ȱ��ȭ
						if("{$year}-{$month}-{$tmp_day}" == "{$year}-{$month}-{$day}")
							$emclass = ' class="current"';

						if($current_data[$c_day])
							$c_day = "��";

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
				<i class="fa fa-calendar"></i> ������ �⼮ ȸ�� <?php echo $total_count;?>��<br />
				<i class="fa fa-thumbs-o-up"></i> �� <?php echo $total_win_count;?>�� / �� <?php echo $total_tie_count;?>�� / �� <?php echo $total_loss_count;?>��
			</div>
		</div>
	 </div>
     <div class="col-md-4">
	 <div class="attendance-info well well-sm">

	        <div class="attendance_title">
			<h5><strong><i class="fa fa-calendar-check-o"></i> �⼮üũ ���</strong></h5>
			</div>

			<ul class="notice">
				<li><i class="fa fa-check-square-o"></i> ȸ����� <span class="label label-info"><?php echo $att_levelname;?> �̻�</span> ����.</li>
				<li><i class="fa fa-check-square-o"></i> �� ���̵�� <span class="label label-warning">�� <?php echo $att['attendance_number'];?>ȸ</span> ����.</li>
				<li><i class="fa fa-check-square-o"></i> �� �����Ƿ� <span class="label label-danger">�� <?php echo $att['attendance_number'];?>ȸ</span> ����.</li>
				<li><i class="fa fa-check-square-o"></i> ���� �⼮ �ð� <span class="label label-default"><?php echo $att['attendance_start_time'];?> ~ <?php echo $att['attendance_end_time'];?></span></li>
				<li>
					<span class="tit"><i class="fa fa-check-square-o"></i> ȹ�� ����Ʈ</span>
					<ul>
						<li>�¸� : <?php echo $att['attendance_win_start_point'];?> ~ <?php echo $att['attendance_win_end_point'];?> ��</li>
						<li>���º� : <?php echo $att['attendance_tie_start_point'];?> ~ <?php echo $att['attendance_tie_end_point'];?> ��</li>
						<li>�й� : <?php echo $att['attendance_loss_start_point'];?> ~ <?php echo $att['attendance_loss_end_point'];?> ��</li>
					</ul>
				</li>
				<li>
					<span class="tit"><i class="fa fa-check-square-o"></i> �ΰ� ����Ʈ</span>
					<?php echo $att['attendance_successive'];?> ����,����,���� <i class="fa fa-info-circle tooltip-top" title="ȹ�� ����Ʈ * ����Ƚ��"></i>
				</li>
				<li><div class="alert alert-success" role="alert">
				<i class="fa fa-trophy"></i> ���� ������ �¹��а� �������� ������� ��������˴ϴ�.</div>
				</li>
			</ul>
		</div>
	 </div>
     <div class="col-md-4">
	 <div class="top-list well well-sm">

	        <div class="attendance_title">
			<h5><strong><i class="fa fa-trophy"></i> ���� ����</strong></h5>
			</div>

			<ol>
				<?php
				$honnor_cnt = 1;
				while($row_honor = sql_fetch_array($result_honor)){
					$mb = get_member($row_honor[mb_id]);
					// �Ҵ��� �޸�ȸ����???
					if ($mb[mb_nick] == "") {
					    $mb = sql_fetch(" select * from $g4[unlogin_table] where mb_id = '$mb[mb_id]' ");
					}
					// ������ ȸ����???
					if ($mb[mb_nick] == "") {
					    $mb['mb_nick'] = "undefined";
          }
					$nick = get_sideview($mb['mb_id'], $mb['mb_nick'], $mb['mb_email'], $mb['mb_homepage']);
				?>
				<li>
					<span class="user"><?php echo $nick;?></span>
					<span class="victory">
					(<?php echo $row_honor[as_successive]; // ���� ����?>��
					<img src="<?php echo $g4[attendance_path];?>/img/icon_top-victory<?php echo $row_honor[as_victory]; // ���� 3��,2��,1��?>.gif" alt="" />
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
	   
	   <!--��������/�⼮üũ��� ���â��ư { -->
	   <div class="pull-right clearfix visible-xs btn-group btn-group-lg" role="group" aria-label="��������/�⼮üũ��� ���â��ư" style="margin-bottom:15px;">
	      <a class="btn btn-default" data-toggle="modal" data-target="#att_calendar"><i class="fa fa-calendar"></i></a>
	      <a class="btn btn-default" data-toggle="modal" data-target="#att_check"><i class="fa fa-calendar-check-o"></i></a>
	      <a class="btn btn-default" data-toggle="modal" data-target="#att_trophy"><i class="fa fa-trophy"></i></a>
	   </div>
	   <!-- } -->
	   
	<div class="huddakP-attendance-form">
		<form id='fattendancewrite' name='fattendancewrite' method="post" action="<?php echo $g4[attendance_path];?>/attendance_update.php">
			<input type="hidden" name="s_date" value="<?php echo $s_date?>" />
			<input type="hidden" name="currentId" value="<?php echo $currentId?>" />
			<input type="hidden" name="at_type" title="����������" />
			<ul class="type-select">
				<li><a href="1" title="�� ����" class="n1 btn btn-info"><i class="fa fa-hand-rock-o fa-5x"></i></a></li>
				<li><a href="2" title="�� ����" class="n2 btn btn-success"><i class="fa fa-hand-peace-o fa-5x"></i></a></li>
				<li><a href="3" title="�� ����" class="n3 btn btn-danger"><i class="fa fa-hand-paper-o fa-5x"></i></a></li>
			</ul>
	</div>
	</div>
	<div class="col-md-8">
	<div class="input-group">

	<span class="">
	<?php if ($att['char_min'] || $att['char_max']) { ?><span id="char_count"></span>���� (�ּ� : <?php echo $att['char_min'];?> , �ִ� : <?php echo $att['char_max'];?>)<?php } ?>
	</span>

      <textarea id="at_memo" name="at_memo" class="form-control" rows=3 placeholder="�⼮üũ�� ���� �ھƳ��� �ܾ �����ּ���."><?php echo $att[attendance_memo][rand(0,count($att[attendance_memo])-1)];?></textarea>
      <span class="att_btn input-group-btn">
        <button type="input" alt="��ý!" class="tooltip-top btn btn-primary data-toggle="tooltip" data-placement="top" title="�⼮üũ"/>�⼮üũ</button>
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
			<h5><strong><i class="fa fa-list-alt"></i> �⼮üũ ��Ȳ��</h5>
	  </div>
	  </div>

      <!-- Table -->
      <table class="table" style="font-size:12px;">
        <thead>
          <tr>
				<th scope="col" colspan="4" class="text-center hidden-xs col-lg-2 col-md-2 col-sm-2 ">����</th>
				<th scope="col" class="text-center hidden-xs col-lg-1 col-md-1 col-sm-1">����</th>
				<th scope="col" class="text-center hidden-xs col-lg-1 col-md-1 col-sm-1">����Ʈ</th>
				<th scope="col" class="text-center hidden-xs col-lg-1 col-md-1 col-sm-1">�⼮��</th>
				<th scope="col" class="text-center hidden-xs">�ڸ�Ʈ</th>
        </thead>
        <tbody>
          <?php
			while($row = sql_fetch_array($result)){
				$mb = get_member($row[mb_id]);
				$nick = get_sideview($mb['mb_id'], $mb['mb_nick'], $mb['mb_email'], $mb['mb_homepage']);
			?>
			<tr>
				<td class="hidden-xs">
				    <? // ���� 3��,2��,1��
				    if ($row[at_victory] == 1)
				        echo '<a href="#" title="�¸�" class="n2 btn btn-success disabled">�¸�</a>';
				    else if ($row[at_victory] == 2)
				        echo '<a href="#" title="���º�" class="n1 btn btn-info disabled">���º�</a>';
				    else
				        echo '<a href="#" title="�й�" class="n3 btn btn-danger disabled">�й�</a>';
            ?>
				</td>
				<td class="hidden-xs">
				    <? // 1 ��, 2 ��, 3 ��
				    if ($row[at_default_type] == 1)
				        echo '<a href="#" title="�� ����" class="n1 btn btn-info"><i class="fa fa-hand-rock-o fa-2x btn-info"></i></a>';
				    else if ($row[at_default_type] == 2)
				        echo '<a href="#" title="�� ����" class="n2 btn btn-success"><i class="fa fa-hand-peace-o fa-2x"></i></a>';
				    else
				        echo '<a href="#" title="�� ����" class="n3 btn btn-danger"><i class="fa fa-hand-paper-o fa-2x"></i></a>';
            ?>
				</td>
				<td class="hidden-xs">
					<img src="<?php echo $g4[attendance_path];?>/img/txt_vs.png" alt="VS" />
				</td>
				<td class="hidden-xs">
				    <? // 1 ��, 2 ��, 3 ��
				    if ($row[at_type] == 1)
				        echo '<a href="#" title="�� ����" class="n1 btn btn-info"><i class="fa fa-hand-rock-o fa-2x"></i></a>';
				    else if ($row[at_type] == 2)
				        echo '<a href="#" title="�� ����" class="n2 btn btn-success"><i class="fa fa-hand-peace-o fa-2x"></i></a>';
				    else
				        echo '<a href="#" title="�� ����" class="n3 btn btn-danger"><i class="fa fa-hand-paper-o fa-2x"></i></a>';
            ?>
				</td>
				<td class="hidden-xs text-center"><?php echo $row[at_successive]; // ���� ����?></td>
				<td class="hidden-xs text-center"><?php echo $row[po_point]; // ��÷����Ʈ?></td>
				<td class="user hidden-xs text-center"><?php echo $nick;?></td>
				<td class="comment hidden-xs text-left">
					<?php echo $row[at_memo];?>
					<a href="<?php echo $g4[attendance_path];?>/attendance_delete.php?at_id=<?php echo $row[at_id];?>&amp;s_date=<?php echo $s_date;?>&amp;currentId=<?php echo $currentId;?>">
					<?php if($is_admin){?>&nbsp;<i class="fa fa-trash-o"></i></a>&nbsp;<?php }?>
				</td>

				<!--����� ��Ȳ�� { -->
				<td class="visible-xs">
					<span class="label label-default pull-left tooltip-top" title="�¸����">
					<img src="<?php echo $g4[attendance_path];?>/img/victory_<?php echo $row[at_victory]; // ���� 3��,2��,1��?>.png" alt="" />
					<img src="<?php echo $g4[attendance_path];?>/img/icon_<?php echo $row[at_default_type];// 1 ��, 2 ��, 3 ��?>.png" alt="" />
					<img src="<?php echo $g4[attendance_path];?>/img/txt_vs.png" alt="VS" />
					<img src="<?php echo $g4[attendance_path];?>/img/icon_<?php echo $row[at_type];// 1 ��, 2 ��, 3 ��?>.png" alt="" />
					</span>
					
					<span class="label label-danger pull-right tooltip-top" title="���ӽ¸�">
					<h5><?php echo $row[at_successive]; // ���� ����?></h5>
					</span>
					<span class="label label-default pull-right tooltip-top" title="��÷����Ʈ">
					<h5><?php echo $row[po_point]; // ��÷����Ʈ?></h5>
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

<!--�������� ��� { -->
<div class="top-list modal fade" id="att_trophy" tabindex="-1" role="dialog" aria-labelledby="att_trophyLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <span class="modal-title" id="myModalLabel">
			<h5><strong><i class="fa fa-trophy"></i> ���� ����</strong></h5>
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
					(<?php echo $row_honor[as_successive]; // ���� ����?>��
					<img src="<?php echo $g4[attendance_path];?>/img/icon_top-victory<?php echo $row_honor[as_victory]; // ���� 3��,2��,1��?>.gif" alt="" />
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
<!--�������� ��� } -->

<!-- �⼮üũ��� ��� { -->
<div class="top-list modal fade" id="att_check" tabindex="-1" role="dialog" aria-labelledby="att_checkLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <span class="modal-title" id="myModalLabel">
			<h5><strong><i class="fa fa-calendar-check-o"></i> �⼮üũ ���</strong></h5>
		</span>
      </div>
      <div class="modal-body">
	  <ul class="notice">
				<li><i class="fa fa-check-square-o"></i> ȸ����� <span class="label label-info"><?php echo $att_levelname;?> �̻�</span> ����.</li>
				<li><i class="fa fa-check-square-o"></i> �� ���̵�� <span class="label label-warning">�� <?php echo $att['attendance_number'];?>ȸ</span> ����.</li>
				<li><i class="fa fa-check-square-o"></i> �� �����Ƿ� <span class="label label-danger">�� <?php echo $att['attendance_number'];?>ȸ</span> ����.</li>
				<li><i class="fa fa-check-square-o"></i> ���� �⼮ �ð� <span class="label label-default"><?php echo $att['attendance_start_time'];?> ~ <?php echo $att['attendance_end_time'];?></span></li>
				<li>
					<span class="tit"><i class="fa fa-check-square-o"></i> ȹ�� ����Ʈ</span>
					<ul>
						<li>�¸� : <?php echo $att['attendance_win_start_point'];?> ~ <?php echo $att['attendance_win_end_point'];?> ��</li>
						<li>���º� : <?php echo $att['attendance_tie_start_point'];?> ~ <?php echo $att['attendance_tie_end_point'];?> ��</li>
						<li>�й� : <?php echo $att['attendance_loss_start_point'];?> ~ <?php echo $att['attendance_loss_end_point'];?> ��</li>
					</ul>
				</li>
				<li>
					<span class="tit"><i class="fa fa-check-square-o"></i> �ΰ� ����Ʈ</span>
					<?php echo $att['attendance_successive'];?> ����,����,���� <i class="fa fa-info-circle tooltip-top" title="ȹ�� ����Ʈ * ����Ƚ��"></i>
				</li>
				<li><div class="alert alert-success" role="alert">
				<i class="fa fa-trophy"></i> ���� ������ �¹��а� �������� ������� ��������˴ϴ�.</div>
				</li>
			</ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- �⼮üũ��� ��� } -->

<!--�޷� ��� { -->
<div class="top-list modal fade" id="att_calendar" tabindex="-1" role="dialog" aria-labelledby="att_calendarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <span class="modal-title" id="myModalLabel">
			<h5><strong><i class="fa fa-calendar"></i> �⼮üũ ����</strong></h5>
		</span>
      </div>
      <div class="modal-body">
				<div class="huddakP-calendar-month-select">
					<a href='#' onclick='document.fattendancelist.go.value="prev"; document.fattendancelist.submit();' class="btn btn-default tooltip-top" title="������"><i class="fa fa-chevron-left"></i></a>
					<strong><?php echo $year?> - <?php echo $month?></strong>
					<a href='#' onclick='document.fattendancelist.go.value="next"; document.fattendancelist.submit();' class="btn btn-default tooltip-top" title="������"><i class="fa fa-chevron-right"></i></a>
				</div>
 
			<table class="huddakP-attendance-calendar">
				<thead>
					<tr>
						<th scope="col" class="sun">��</th>
						<th scope="col">��</th>
						<th scope="col">ȭ</th>
						<th scope="col">��</th>
						<th scope="col">��</th>
						<th scope="col">��</th>
						<th scope="col" class="sat">��</th>
					</tr>
				</thead>
				<tbody>
					<? //�޷����
					
					// �̴޿� ù ������ ����
					$fist_week = date('w', strtotime($year . '-' . $month . '-01'));
					// �����޿��� �Ϸ縦 ���� ������ ���ڸ��� ���� ���Ѵ�
					$last_day = date('d', strtotime($year . '-' . $month . '-01 + 1 month') - (3600 * 24));
					// �̴��� ���� �ָ� ���Ѵ�
					$loof = ceil(($last_day + $fist_week) / 7);

					for($i = 0; $i < $loof * 7; $i++){

						// ���� ��
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

						// ������� ������
						$todayclass = '';
						if(date('Y-m-d') == "{$year}-{$month}-{$tmp_day}")
							$todayclass = ' class="today"';

						// ������ ��¥ Ȱ��ȭ
						if("{$year}-{$month}-{$tmp_day}" == "{$year}-{$month}-{$day}")
							$emclass = ' class="current"';

						if($current_data[$c_day])
							$c_day = "��";

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
				<i class="fa fa-heartbeat"></i> ������ �⼮ ȸ�� <?php echo $total_count;?>��<br />
				<i class="fa fa-thumbs-o-up"></i> �� <?php echo $total_win_count;?>�� / �� <?php echo $total_tie_count;?>�� / �� <?php echo $total_loss_count;?>��
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--�޷� ��� } -->

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
			alert('��/��/���� �������ּ���.');
			return false;
		}

		if(!wrestSubmit(this))
			return false;

        if($("#char_count") && (<?php echo $att[char_min];?> > 0 || <?php echo $att[char_max];?> > 0)) {
            var cnt = parseInt($("#char_count").html());
            if (<?php echo $att['char_min'];?> > 0 && <?php echo $att['char_min'];?> > cnt) {
                 alert("������ " + <?php echo $att['char_min'];?> + "���� �̻� ���ž� �մϴ�.");
                 return false;
            }
            else if (<?php echo $att['char_max'];?> > 0 && <?php echo $att['char_max'];?> < cnt) {
                alert("������ " + <?php echo $att['char_max'];?> + "���� ���Ϸ� ���ž� �մϴ�.");
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
