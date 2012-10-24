<?php

//**********************************************************
// 새로운 구인 정보를 DB에 추가하는 함수
// bool insert_employ(object db, array post_arr [, int user_num [, string company_type]] )
//**********************************************************
// db : MySQL 객체
// post_arr : 넘겨받은 폼 데이터
// user_num : 회원번호(생략시 현 사용자 값 들어감)
// company_type : 기업구분(생략시 현 사용자 값 들어감)
//**********************************************************
// 리턴값 : 등록 성공할 경우 true, 아니면 false
//**********************************************************
function insert_employ(&$db, &$post_arr, $user_num = '', $company_type = '')
{
	global $_PNJ, $login_user, $_SERVER;

	// addslashes 처리
	foreach($post_arr as $key => $value)
	{
		$post_arr[$key] = addslashes($value);
	}

	$user_num = $user_num == '' ? $login_user['num'] : $user_num;
	$company_type = $company_type == '' ? $login_user['company_type'] : $company_type;

	if( $company_type == 'H' )
	{
		// 헤드헌팅 회사일 경우
		$company_name = $login_user['ko_company_name'];
	}
	else
	{
		// 헤드헌팅 회사가 아닐경우
		$company_name = $login_user['nickname'] != '' ? $login_user['nickname'] : $login_user['ko_company_name'];
		$company_name_en = $login_user['en_company_name'];
	}

	$tag = get_arr_val($post_arr, 'tag');
  $tag = strip_tags($tag);

	$company_name = addslashes($company_name);
	$company_name_en = addslashes($company_name_en);

	$job_title = get_arr_val($post_arr, 'job_title');
	$job_type = get_arr_val($post_arr, 'job_type');
	$job_code = get_arr_val($post_arr, 'job_code');
	$job_desc = get_arr_val($post_arr, 'job_desc');
	$job_req = get_arr_val($post_arr, 'job_req');
	$work_location = get_arr_val($post_arr, 'work_location');
	$payment = get_arr_val($post_arr, 'payment');
	$fin_year = get_arr_val($post_arr, 'fin_year');
	$fin_month = get_arr_val($post_arr, 'fin_month');
	$fin_day = get_arr_val($post_arr, 'fin_day');
	$fin_date = $fin_month&&$fin_day&&$fin_year&&checkdate($fin_month, $fin_day, $fin_year) ? $fin_year."-".$fin_month."-".$fin_day : '';
	$career_level = get_arr_val($post_arr, 'career_level');
	$career_type1 = get_arr_val($post_arr, 'career_type1');
	$career_type2 = get_arr_val($post_arr, 'career_type2');
	$process = get_arr_val($post_arr, 'process');
	$document = get_arr_val($post_arr, 'document');
	$hr_name = get_arr_val($post_arr, 'hr_name');
	$hr_tel1 = get_arr_val($post_arr, 'hr_tel1');
	$hr_tel2 = get_arr_val($post_arr, 'hr_tel2');
	$hr_tel3 = get_arr_val($post_arr, 'hr_tel3');
	$hr_tel = $hr_tel1 != '' && $hr_tel2 != '' && $hr_tel3 != '' ? $hr_tel1."-".$hr_tel2."-".$hr_tel3 : '';
	$hr_email = get_arr_val($post_arr, 'hr_email');
	$etc = get_arr_val($post_arr, 'etc');

	$is_online_apply = get_arr_val($post_arr, 'is_online_apply');
	$is_receipt_email = get_arr_val($post_arr, 'is_receipt_email');
	$is_receipt_url = get_arr_val($post_arr, 'is_receipt_url');
	$receipt_email = get_arr_val($post_arr, 'receipt_email');
	$receipt_url = get_arr_val($post_arr, 'receipt_url');
	$receipt_url = ! preg_match("/http:\/\//", $receipt_url) ? "http://".$receipt_url : $receipt_url;

	$overseas = get_arr_val($post_arr, 'overseas');
	$tech = get_arr_val($post_arr, 'tech');

	$hr_file = get_arr_val($post_arr, 'hr_file');
	$hr_file_save = get_arr_val($post_arr, 'hr_file_save');

	// 직종 코드를 해당 텍스트로 찾기
	$job_type_arr = get_job_type_row($db, $job_type);
	$job_code_arr = get_job_code_row($db, $job_code);
	$job_type_text_kr = $job_type_arr['jobsort_kr']."/".$job_code_arr['job_kr'];
	$job_type_text_en = $job_type_arr['jobsort_en']."/".$job_code_arr['job_en'];

	$career_type1_text = isset($_PNJ['career_type1_arr'][$career_type1]) ? $_PNJ['career_type1_arr'][$career_type1] : '';
	$career_type2_text = isset($_PNJ['career_type2_arr'][$career_type2]) ? $_PNJ['career_type2_arr'][$career_type2] : '';

  // ip_address
  $ip_addr = "$_SERVER[REMOTE_ADDR]";

	// 데이터베이스에 insert 쿼리 전송
	$query  = "INSERT INTO `employ` ( `reg_date`, `user_num`, `company_type`, `company_name`, `company_name_en`, `job_title`, `job_type`, `job_code`, `job_type_text_kr`, `job_type_text_en`, `job_desc`, `job_req`, `work_location`, `payment`, `fin_date`, `career_level`, `career_type1`, `career_type2`, `process`, `document`, `hr_name`, `hr_tel`, `hr_email`, `etc`, `is_online_apply`, `is_receipt_email`, `is_receipt_url`, `receipt_email`, `receipt_url`, `ip_addr`, `tag`, `overseas`, `tech`, `hr_file`, `hr_file_save` ) ";
	$query .= "VALUES ( NOW(), '$user_num', '$company_type', '$company_name', '$company_name_en', '$job_title', '$job_type', '$job_code', '$job_type_text_kr', '$job_type_text_en', '$job_desc', '$job_req', '$work_location', '$payment', '$fin_date', '$career_level', '$career_type1_text', '$career_type2_text', '$process', '$document', '$hr_name', '$hr_tel', '$hr_email', '$etc', '$is_online_apply', '$is_receipt_email', '$is_receipt_url', '$receipt_email', '$receipt_url', '$ip_addr', '$tag', '$overseas', '$tech', '$hr_file', '$hr_file_save' )";

	$result = $db->query($query) or show_db_err($db);

	// posting_count값 1 감소
	$query = "UPDATE `user_company` SET posting_count = posting_count - 1 WHERE base_num='$user_num'";
	$db->query($query) or show_db_err($db);

	// 해당 사용자의 현재 채용공고수 1 증가
	$query = "UPDATE `user_company` SET cur_emp_cnt = cur_emp_cnt + 1 WHERE base_num='$user_num'";
	$db->query($query) or show_db_err($db);

	return $result;
}


//**********************************************************
// 구인정보를 업데이트 해주는 함수
// bool update_employ(object db, int employ_num, array post_arr [, string company_type] )
//**********************************************************
// db : MySQL 객체
// employ_num : 채용정보 번호
// post_arr : 넘겨받은 폼 데이터
// company_type : 기업구분(생략시 현 사용자 값 들어감)
//**********************************************************
// 리턴값 : 업데이트 성공할 경우 true, 아니면 false
//**********************************************************
function update_employ(&$db, $employ_num, &$post_arr, $company_type = '')
{
	global $_PNJ, $login_user;
	global $admin_user, $_SERVER;

	if( $company_type == 'H' )
	{
		// 헤드헌팅 회사일 경우
		$company_name = $login_user['ko_company_name'];
	}
	else
	{
		// 헤드헌팅 회사가 아닐경우
		$company_name = $login_user['nickname'] != '' ? $login_user['nickname'] : $login_user['ko_company_name'];
	}
	$company_name = addslashes($company_name);

	$company_name_en = $login_user['en_company_name'];
	$company_name_en = addslashes($company_name_en);

	$employ_num = intval($employ_num);

	$tag = get_arr_val($post_arr, 'tag');
  $tag = strip_tags($tag);

	$job_title = get_arr_val($post_arr, 'job_title');
	$job_type = get_arr_val($post_arr, 'job_type');
	$job_code = get_arr_val($post_arr, 'job_code');
	$job_desc = get_arr_val($post_arr, 'job_desc');
	$job_req = get_arr_val($post_arr, 'job_req');
	$work_location = get_arr_val($post_arr, 'work_location');
	$payment = get_arr_val($post_arr, 'payment');
	$fin_year = get_arr_val($post_arr, 'fin_year');
	$fin_month = get_arr_val($post_arr, 'fin_month');
	$fin_day = get_arr_val($post_arr, 'fin_day');
	$fin_date = $fin_month&&$fin_day&&$fin_year&&checkdate($fin_month, $fin_day, $fin_year) ? $fin_year."-".$fin_month."-".$fin_day : '';
	$career_level = get_arr_val($post_arr, 'career_level');
	$career_type1 = get_arr_val($post_arr, 'career_type1');
	$career_type2 = get_arr_val($post_arr, 'career_type2');
	$process = get_arr_val($post_arr, 'process');
	$document = get_arr_val($post_arr, 'document');
	$hr_name = get_arr_val($post_arr, 'hr_name');
	$hr_tel1 = get_arr_val($post_arr, 'hr_tel1');
	$hr_tel2 = get_arr_val($post_arr, 'hr_tel2');
	$hr_tel3 = get_arr_val($post_arr, 'hr_tel3');
	$hr_tel = $hr_tel1 != '' && $hr_tel2 != '' && $hr_tel3 != '' ? $hr_tel1."-".$hr_tel2."-".$hr_tel3 : '';
	$hr_email = get_arr_val($post_arr, 'hr_email');
	$etc = get_arr_val($post_arr, 'etc');

	$is_online_apply = get_arr_val($post_arr, 'is_online_apply');
	$is_receipt_email = get_arr_val($post_arr, 'is_receipt_email');
	$is_receipt_url = get_arr_val($post_arr, 'is_receipt_url');
	$receipt_email = get_arr_val($post_arr, 'receipt_email');
	$receipt_url = get_arr_val($post_arr, 'receipt_url');
	$receipt_url = ! preg_match("/http:\/\//", $receipt_url) ? "http://".$receipt_url : $receipt_url;

	$overseas = get_arr_val($post_arr, 'overseas');
	$tech = get_arr_val($post_arr, 'tech');

	$hr_file = get_arr_val($post_arr, 'hr_file');
	$hr_file_save = get_arr_val($post_arr, 'hr_file_save');

	// 직종 코드를 해당 텍스트로 찾기
	$job_type_arr = get_job_type_row($db, $job_type);
	$job_code_arr = get_job_code_row($db, $job_code);
	$job_type_text_kr = $job_type_arr['jobsort_kr']."/".$job_code_arr['job_kr'];
	$job_type_text_en = $job_type_arr['jobsort_en']."/".$job_code_arr['job_en'];

	$career_type1_text = isset($_PNJ['career_type1_arr'][$career_type1]) ? $_PNJ['career_type1_arr'][$career_type1] : '';
	$career_type2_text = isset($_PNJ['career_type2_arr'][$career_type2]) ? $_PNJ['career_type2_arr'][$career_type2] : '';

	// 데이터베이스에 insert 쿼리 전송
	$query  = "UPDATE `employ` SET modify_date=NOW(), ";
	$query .= $admin_user['id'] == '' ? "company_name='$company_name', " : '';
	$query .= $admin_user['id'] == '' ? "company_name_en='$company_name_en', " : '';	
	$query .= "job_title='$job_title', job_type='$job_type', job_code='$job_code', job_type_text_kr='$job_type_text_kr', job_type_text_en='$job_type_text_en', job_desc='$job_desc', job_req='$job_req', work_location='$work_location', payment='$payment', fin_date='$fin_date', career_level='$career_level', career_type1='$career_type1_text', career_type2='$career_type2_text', process='$process', document='$document', hr_name='$hr_name', hr_tel='$hr_tel', hr_email='$hr_email', etc='$etc', ";
	$query .= "is_online_apply='$is_online_apply', is_receipt_email='$is_receipt_email', is_receipt_url='$is_receipt_url', receipt_email='$receipt_email', receipt_url='$receipt_url', ";
  $query .= "tag='$tag', overseas='$overseas', tech='$tech', hr_file='$hr_file', hr_file_save='$hr_file_save' ";
	$query .= "WHERE num='$employ_num'";

	$result = $db->query($query) or show_db_err($db);

	return $result;
}

function delete_employ(&$db, $emp_num_arr, $user_num = '')
{
	if( sizeof($emp_num_arr) > 0 )
	{
		foreach( $emp_num_arr as $num )
		{
			if( intval($num) > 0 )
			{
        // 삭제할 db의 채용공고의 정보를 가져온다
				$query  = "SELECT * FROM `employ` WHERE num='$num' ";
      	$db->query($query) or show_db_err($db);
      	$row = $db->fetch();

				$query  = "DELETE FROM `employ` WHERE num='$num' ";
				$query .= $user_num != '' ? "AND user_num='$user_num' " : '';
				$db->query($query) or show_db_err($db);

				// 현재 채용공고수 1 감소
				$query = "UPDATE `user_company` SET cur_emp_cnt = cur_emp_cnt - 1 WHERE base_num='$user_num'";
				$db->query($query) or show_db_err($db);				

				// 지원내역 삭제
				$query  = "DELETE FROM `apply` WHERE employ_num='$num' ";
				$db->query($query) or show_db_err($db);

				// 스크랩 내역 삭제
				$query  = "DELETE FROM `scrap_employ` WHERE employ_num='$num' ";
				$db->query($query) or show_db_err($db);
				
				// 삭제 log 기록
				$query2 = "delete employ - user_num : $user_num, employ_num: $num";
				$query = "INSERT INTO `delete_log` SET log_datetime=NOW(), ip_addr='$_SERVER[REMOTE_ADDR]', action='$query2', user_id='$user_num', job_title='$row[job_title]' ";
				$db->query($query) or show_db_err($db);
			}
		}
	}

	return true;
}


//**********************************************************
// DB에서 구인정보를 가져와 리턴하는 함수
// array get_employ_info(object db, int num)
//**********************************************************
// db : MySQL 객체
// num : 채용 정보 번호
//**********************************************************
// 리턴값 : 구인정보배열
//**********************************************************
function get_employ_info(&$db, $num, $is_join_company = true)
{
	$num = intval($num);

	$query  = "SELECT E.num, DATE_FORMAT(E.reg_date, '%Y-%m-%d %h:%m') reg_date, E.user_num, E.company_name, E.company_type, E.job_title, E.job_type, E.job_code, E.job_type_text_kr, E.job_desc, E.job_req, E.work_location, E.payment, E.fin_date, E.career_level, E.career_type1, E.career_type2, E.process, E.document, E.hr_name, E.hr_tel, E.hr_email, E.etc, E.req_urgent_posting, E.is_online_apply, E.is_receipt_email, E.is_receipt_url, E.receipt_email, E.receipt_url, E.bitly_url, E.twitter, E.hit_count, E.tag, E.overseas, E.tech, E.hr_file, E.hr_file_save ";
	$query .= $is_join_company ? ", UC.ko_company_name, UC.en_company_name, UC.company_nickname, UC.company_brief, UB.modify_date com_modify_date, UC.address1, UC.address2, UB.tel1, UB.tel2, UB.ko_name, BC1.biz_sort_ko biz1, BC2.biz_sort_ko biz2, BC3.biz_sort_ko biz3, UC.homepage_global, UC.homepage_korea, UC.main_product, UC.welfare, UB.certify " : "";
	$query .= "FROM `employ` E ";

	$query .= $is_join_company ? "LEFT JOIN `user_company` UC ON ( UC.base_num = E.user_num ) " : '';
	$query .= $is_join_company ? "LEFT JOIN `user_base` UB ON ( UB.num = UC.base_num ) " : '';

//	$query .= $is_join_company ? ", `user_company` UC, `user_base` UB " : "";
	$query .= $is_join_company ? "LEFT JOIN `bizcode` BC1 ON ( BC1.code = UC.bizcode1 ) " : "";
	$query .= $is_join_company ? "LEFT JOIN `bizcode` BC2 ON ( BC2.code = UC.bizcode2 ) " : "";
	$query .= $is_join_company ? "LEFT JOIN `bizcode` BC3 ON ( BC3.code = UC.bizcode3 ) " : "";
	$query .= "WHERE E.`num` = '$num' ";
//	$query .= "AND E.user_num = UC.base_num AND UB.num = UC.base_num ";

	$db->query($query) or show_db_err($db);

	$row = $db->fetch();

	$row['fin_date'] = intval($row['fin_date']) == 0 ? '채용시까지' : $row['fin_date'];

	if( isset($row['career_type2']) )
	{
		$row['career_type2'] = $row['career_type2'] == '' ? '신입/경력' : $row['career_type2'];
	}

	return $row;
}

//**********************************************************
// 선택한 구인 정보를 다시 DB에 insert하는 함수
// bool repost_employ(object db, array employ_numbers [, int user_num] )
//**********************************************************
// db : MySQL 객체
// employ_numbers : 구인 정보 번호 배열
// user_num : 회원번호(생략가능)
//**********************************************************
// 리턴값 : 성공할 경우 true, 아니면 false
//**********************************************************
function repost_employ(&$db, $employ_numbers, $user_num = '')
{
  global $_SERVER;

	// 번호 배열 정렬
	sort($employ_numbers, SORT_NUMERIC);

	// 현재 시간 구하기
	$cur_date_time = date("Y-m-d H:i:s");

	// 마감일 구하기
	$fin_date = date("Y-m-d", mktime(0,0,0,date("n"),date("j")+7,date("Y")));	// 오늘로부터 7일 후의 날짜 구하기

	foreach( $employ_numbers as $num )
	{
		if( $num > 0 )
		{
			// 쿼리 생성
			$query =  "INSERT INTO `employ` ( `reg_date`, `user_num`, `company_type`, `company_name`,`job_title`, `job_type`, `job_code`, `job_type_text_kr`, `job_type_text_en`, `job_desc`, `job_req`, `work_location`, `payment`, `fin_date`, `career_level`, `career_type1`, `career_type2`, `process`, `document`, `hr_name`, `hr_tel`, `hr_email`, `partner_level`, `partner_type`, `emp_status`, `company_keyword`, `job_keyword`, `ip_addr` ) ";
			$query .= "SELECT '$cur_date_time', `user_num`, `company_type`, `company_name`, `job_title`, `job_type`, `job_code`, `job_type_text_kr`, `job_type_text_en`, `job_desc`, `job_req`, `work_location`, `payment`, '$fin_date', `career_level`, `career_type1`, `career_type2`, `process`, `document`, `hr_name`, `hr_tel`, `hr_email`, `partner_level`, `partner_type`, `emp_status`, `company_keyword`, `job_keyword`, '$_SERVER[REMOTE_ADDR]' FROM `employ` WHERE num = '$num' ";
			$query .= $user_num != '' ? "AND user_num = '$user_num' " : '';
			$query .= " ORDER BY num";
			$db->query($query) or show_db_err($db);

			// 해당 사용자의 채용공고가능횟수 1감소
			if( $user_num > 0 )
			{
				$query = "UPDATE `user_company` SET posting_count=posting_count-1 WHERE base_num='$user_num'";
				$db->query($query) or show_db_err($db);
				
				// 해당 사용자의 현재 채용공고수 1 증가
				$query = "UPDATE `user_company` SET cur_emp_cnt = cur_emp_cnt + 1 WHERE base_num='$user_num'";
				$db->query($query) or show_db_err($db);
			}
		}
	}

	return true;
}

//**********************************************************
// 선택한 구인 정보를 마감처리하는 함수
// bool finish_employ(object db, array employ_numbers [, int user_num] )
//**********************************************************
// db : MySQL 객체
// employ_numbers : 구인 정보 번호 배열
// user_num : 회원번호(생략가능)
//**********************************************************
// 리턴값 : 성공할 경우 true, 아니면 false
//**********************************************************
function finish_employ(&$db, $employ_numbers, $user_num = '')
{
	// 마감일 계산
	$fin_date = date("Y-m-d", (time()-86400));

	// 입력된 번호 수만큼 반복
	foreach( $employ_numbers as $num )
	{
		if( $num > 0 )	//번호가 0보다 클 경우...
		{
			// 마감 여부 확인
			$query = "SELECT COUNT(*) FROM `employ` WHERE num='$num' AND fin_date < '".date("Y-m-d")."' AND fin_date != 0 ";
			$query .= $user_num != '' ? "AND user_num='$user_num' " : '';						
			$db->query($query) or show_db_err($db);
			if( $db->fetchOne() > 0 )
			{
				show_error_page('채용공고 마감 오류', '이미 마감된 채용공고입니다.', false, true);				
			}

			// 쿼리 생성
			$query  = "UPDATE `employ` SET fin_date = '$fin_date' WHERE num='$num' ";
			$query .= $user_num != '' ? "AND user_num='$user_num' " : '';

			// 쿼리 전송
			$db->query($query) or show_db_err($db);
		}
	}

	return true;
}

//**********************************************************
// 선택한 구인 정보를 프리미엄 공고 의뢰 처리하는 함수
// bool req_employ_premium(object db, array employ_numbers [, int user_num] )
//**********************************************************
// db : MySQL 객체
// employ_numbers : 구인 정보 번호 배열
// user_num : 회원번호(생략가능)
//**********************************************************
// 리턴값 : 성공할 경우 true, 아니면 false
//**********************************************************
function req_employ_premium(&$db, $employ_numbers, $user_num = '', $value = 'R')
{
	// 입력된 번호 수만큼 반복
	foreach( $employ_numbers as $num )
	{
		if( $num > 0 )	//번호가 0보다 클 경우...
		{
			// 쿼리 생성
			$query  = "UPDATE `employ` SET req_premium_posting = '$value' WHERE num='$num' ";
			$query .= $user_num != '' ? "AND user_num='$user_num' " : '';

			// 쿼리 전송
			$db->query($query) or show_db_err($db);
		}
	}

	return true;
}

//**********************************************************
// 선택한 구인 정보를 Urgent 공고 의뢰 처리하는 함수
// bool req_employ_urgent(object db, array employ_numbers [, int user_num] )
//**********************************************************
// db : MySQL 객체
// employ_numbers : 구인 정보 번호 배열
// user_num : 회원번호(생략가능)
//**********************************************************
// 리턴값 : 성공할 경우 true, 아니면 false
//**********************************************************
function req_employ_urgent(&$db, $employ_numbers, $user_num = '', $value = 'U')
{
	global $submenu_file, $selected_menu_num;
	global $admin_user;

	// 입력된 번호 수만큼 반복
	foreach( $employ_numbers as $num )
	{
		if( ! $user_num )	// 사용자 번호가 입력되지 않았을 경우
		{
			//DB에서 사용자 번호를 가져옴 
			$query = "SELECT user_num FROM `employ` WHERE num='$num'";
			$db->query($query) or show_db_err($db);
			$user_num = $db->fetchOne();
		}
		
		if( $num > 0 )	//번호가 0보다 클 경우...
		{
			// 선택한 공고가 이미 urgent 상태인지 확인
			$query = "SELECT COUNT(*) FROM `employ` WHERE num='$num' AND req_urgent_posting != ''";
			$db->query($query) or show_db_err($db);
			if( $db->fetchOne() > 0 )
			{
				show_error_page("URGENT 공고 불가", "선택하신 공고는 이미 Urgent 공고중입니다.", false, true);
			}

			// 회원의 urgent 공고 가능 횟수 확인
			if( $admin_user['id'] == '' )
			{
				$query  = "SELECT urgent_count FROM `user_company` WHERE base_num='$user_num'";
				$db->query($query) or show_db_err($db);
				if( $db->fetchOne() <= 0 )
				{
					show_error_page("URGENT 공고 불가", "URGENT 공고 횟수가 초과되어 더이상 공고하실 수 없습니다", false, true);
				}
			}
			
			// 쿼리 생성
			$query  = "UPDATE `employ` SET req_urgent_posting = '$value' WHERE num='$num' ";
			$query .= $user_num != '' ? "AND user_num='$user_num' " : '';
			$db->query($query) or show_db_err($db);

			if( $admin_user['id'] == '' )
			{
				// urgent count 1 감소
				$query = "UPDATE `user_company` SET urgent_count=urgent_count-1 WHERE base_num='$user_num'";
				$db->query($query) or show_db_err($db);
			}
		}
	}

	return true;
}

//**********************************************************
// 선택한 구인 정보를 Urgent 공고 해제하는 함수
// bool req_employ_unurgent(object db, array employ_numbers)
//**********************************************************
// db : MySQL 객체
// employ_numbers : 구인 정보 번호 배열
//**********************************************************
// 리턴값 : 성공할 경우 true, 아니면 false
//**********************************************************
function req_employ_unurgent(&$db, $employ_numbers, $is_recover_count = false)
{
	// 입력된 번호 수만큼 반복
	foreach( $employ_numbers as $num )
	{
		if( $num > 0 )	//번호가 0보다 클 경우...
		{
			// 쿼리 생성
			$query  = "UPDATE `employ` SET req_urgent_posting = '' WHERE num='$num' ";
			$db->query($query) or show_db_err($db);

			// urgent count 1 감소
			if( $is_recover_count )
			{
				$query = "UPDATE `user_company` SET urgent_count=urgent_count-1 WHERE base_num='$user_num'";
				$db->query($query) or show_db_err($db);
			}
		}
	}

	return true;
}

//**********************************************************
// 채용정보를 추천하는 함수
// bool recommand_employ(object db, int user_num, int employ_num)
//**********************************************************
// db : MySQL 객체
// user_num : 추천인 사용자 번호
// employ_num : 채용공고번호
//**********************************************************
// 리턴값 : 성공할 경우 true, 아니면 false
//********************************************************** 
function recommand_employ(&$db, $user_num, $employ_num)
{
	// 해당 채용공고를 이미 추천했는지 확인
	$query = "SELECT COUNT(*) FROM `employ_recommand` WHERE user_num='$user_num' AND employ_num = '$employ_num'";
	$db->query($query) or show_db_err($db);
	if( $db->fetchOne() > 0 )
	{
		show_error_page("추천 불가", "해당 채용공고는 이미 추천하셨습니다.", false, true);
		return false;
	}

	// 해당 채용공고의 회사 고유번호 select
	$query = "SELECT user_num FROM `employ` WHERE num='$employ_num'";
	$db->query($query) or show_db_err($db);
	$company_num = $db->fetchOne();
	
	// 추천 테이블에 기록
	$query = "INSERT INTO `employ_recommand` SET reg_date=NOW(), user_num='$user_num', employ_num='$employ_num'";
	$db->query($query) or show_db_err($db);

	// 채용공고 추천 count 1 증가
	$query = "UPDATE `employ` SET recommand_count=recommand_count+1 WHERE num='$employ_num'";
	$db->query($query) or show_db_err($db);

	// 회사 추천 count 1 증가
	$query = "UPDATE `user_company` SET recommand_count=recommand_count+1 WHERE base_num = '$company_num'";
	$db->query($query) or show_db_err($db);
	
	return true;
}

//**********************************************************
// 채용정보를 신고하는 함수
// bool bad_employ(object db, int user_num, int employ_num, string reason)
//**********************************************************
// db : MySQL 객체
// user_num : 신고인 사용자 번호
// employ_num : 채용공고번호
// reason : 사유
//**********************************************************
// 리턴값 : 성공할 경우 true, 아니면 false
//********************************************************** 
function bad_employ(&$db, $user_num, $employ_num, $reason)
{
	$reason = addslashes(stripslashes($reason));	

	// 신고한 채용공고가 이미 신고되어있는지 확인
	$query = "SELECT COUNT(*) FROM `employ_bad` WHERE user_num='$user_num' AND employ_num='$employ_num'";
	$db->query($query) or show_db_err($db);
	if( $db->fetchOne() > 0 )
	{
		echo "<html><script language=\"javascript\"> ";
		echo "alert('해당 채용공고는 이미 신고하신 채용공고입니다.'); ";
		echo "window.close(); ";
		echo "</script></html>";
		exit;
	}

	// 해당 채용공고의 회사 고유번호 select
	$query = "SELECT user_num FROM `employ` WHERE num='$employ_num'";
	$db->query($query) or show_db_err($db);
	$company_num = $db->fetchOne();	
	
	// 신고 테이블에 기록
	$query = "INSERT INTO `employ_bad` SET reg_date=NOW(), user_num='$user_num', employ_num='$employ_num', reason='$reason'";
	$db->query($query) or show_db_err($db);

	// 채용공고 신고 count 1 증가
	$query = "UPDATE `employ` SET bad_count=bad_count+1 WHERE num='$employ_num'";
	$db->query($query) or show_db_err($db);

	// 회사 신고 count 1 증가
	$query = "UPDATE `user_company` SET bad_count=bad_count+1 WHERE base_num = '$company_num'";
	$db->query($query) or show_db_err($db);
	
	return true;
}

// 오늘 등록가능한 채용공고 갯수를 알려주는 함수
//
function today_posting(&$db)
{
	global $_PNJ, $login_user, $userinfo_arr;
  global $g4;
  global $one_day_posting, $today_remain, $today_posting, $today_posting1, $after_time;

  $date_diff = date("Y-m-d H:i:s", $g4['server_time'] - 86400*3);
  $query = "SELECT count(*) FROM `employ` WHERE user_num='".$login_user['num']."' AND reg_date > '".$date_diff."';";
  $db->query($query) or show_db_err($db);
	$today_posting = $db->fetchOne();

  $today_remain = 1;
  if ($userinfo_arr[payment_expire_ut] >= $g4[server_time]) {
      // 유료서비스
      // 프리미엄 - 120건, 딜럭스 - 60건, 클래식 - 30건
      if (preg_match("/PREMIUM/i", $userinfo_arr[payment_type])) {
          $one_day_posting = 120/3;
          if ($today_posting >= 120)
          {
              $posting_limit = 120;
              $today_remain = 0;
          } 
          else
              $today_remain = 120 - $today_posting;
      }
      else if (preg_match("/DELUXE/i", $userinfo_arr[payment_type])) {
          $one_day_posting = 60/3;
          if ($today_posting >= 60)
          {
              $posting_limit = 60;
              $today_remain = 0;
          }
          else
              $today_remain = 60 - $today_posting;
      }
      else if (preg_match("/CLASSIC/i", $userinfo_arr[payment_type])) {
          $one_day_posting = 30/3;
          if ($today_posting >= 30)
          {
              $posting_limit = 30;
              $today_remain = 0;
          }
          else
              $today_remain = 30 - $today_posting;
      }
      else { 
          // 무료체험 서비스 중일 때 - 15건까지 가능. 밑의 코드를 그대로 복사해서 붙인다.
          $one_day_posting = 15/3;
          if ($today_posting >= 15)
              $today_remain = 0;
          else
              $today_remain = 15 - $today_posting;
          }
  } else {
      // 무료서비스 - 15건까지 가능
      $one_day_posting = 15/3;
      if ($today_posting >= 15)
          $today_remain = 0;
      else
          $today_remain = 15 - $today_posting;
  }

  // 유료회원에게만 언제부터 등록가능한지를 check
  if ($today_remain == 0 && $posting_limit > 0) {
    $sql = " SELECT reg_date from  `employ` WHERE user_num='".$login_user['num']."' order by num desc LIMIT ".($posting_limit - 1).", 1;";
    $db->query($sql) or show_db_err($db);
	  $reg_date = $db->fetchOne();
    // 제한건수가 걸리는 공고의 시간을 timestamp로
	  $after_time = 86400*3 - ($g4['server_time'] - strtotime($reg_date));

    // 시간단위일때
    $after_time_h = floor($after_time / 3600);
	      
    // 남은 분
    $after_time_m = floor( ($after_time - $after_time_h * 3600) / 60);
	      
    // 남은 시
    $after_time_s = ($after_time - $after_time_h * 3600 - $after_time_m * 60 );

    $after_time = $after_time_h ."시 " . $after_time_m ."분 " . $after_time_s . "초";
  }

  $date_diff1 = date("Y-m-d H:i:s", $g4['server_time'] - 86400*1);
	$query1 = "SELECT count(*) FROM `employ` WHERE user_num='".$login_user['num']."' AND reg_date > '".$date_diff1."';";
  $db->query($query1) or show_db_err($db);
	$today_posting1 = $db->fetchOne();  
}
?>