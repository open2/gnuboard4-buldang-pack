<?php

// 후딱빌더가 아닌 그누보드에 설치시 숫자 1을 넣어주시면 그누보드 또는 다른 빌더에서도 작동합니다.
$att['attendance_gnu'] = "1"; // 예제) $att['attendance_gnu'] = "1";

if($att['attendance_gnu']){
	$g4['plugin']      = 'plugin';
	$g4['plugin_path'] = $g4['path'] . '/' . $g4['plugin'];
}

//출석체크 기능의 환경 설정
$g4['attendance'] = 'attendance';
$g4['attendance_path'] = $g4['plugin_path'] . '/' . $g4['attendance']; // 플러그인 위치경로
$g4['attendance_img'] = 'img'; // 플러그인 이미지
$g4['attendance_css'] = 'css'; // 플러그인 css
$g4['attendance_lib'] = 'lib'; // 플러그인 라이브러리
$g4['attendance_img_path'] = $g4['attendance_path'] .'/'.$g4['attendance_img']; // 플러그인 이미지 경로
$g4['attendance_css_path'] = $g4['attendance_path'] .'/'.$g4['attendance_css']; // 플러그인 css 경로
$g4['attendance_lib_path'] = $g4['attendance_path'] .'/'.$g4['attendance_lib']; //  플러그인 라이브러리 경로


$g4['attendance_plugin_table'] = $g4['table_prefix'] . 'plugin_attendance'; // 출석체크 테이블
$g4['attendance_successive_plugin_table'] = $g4['table_prefix'] . 'plugin_attendance_successive'; // 명예 전당 테이블

//기타 환경 설정
$att['attendance_level'] = '2'; // 출석 체크 가능 레벨 2레벨 미만은 하실수 없습니다.
$att['attendance_rows'] = '20'; // 출석 인원 표현개수
$att['attendance_number'] = '1'; // 하루 출석 가능 횟수 ( 아이디기준 )
$att['attendance_ip'] = "2"; // 동일 아이피 출석막기 1 또는 허용횟수
$att['attendance_start_time'] = '00:00:00'; // 출석시작 시간 시:분:초
$att['attendance_end_time'] = '23:59:00'; // 출석종료 시간 시:분:초

$att['attendance_win_start_point'] = "1"; // 승리 시작 포인트
$att['attendance_win_end_point'] = "2"; // 승리 종료 포인트

$att['attendance_tie_start_point'] = "1"; // 무승부 시작 포인트
$att['attendance_tie_end_point'] = "2"; // 무승부 종료 포인트

$att['attendance_loss_start_point'] = "1"; // 패 시작 포인트
$att['attendance_loss_end_point'] = "2"; // 패 종료 포인트

// 연속 설정
$att['attendance_successive'] = "10,15,20"; // 연승,연속무승부,연패 설정 설정 예제 3,5,10  <== 3, 5, 10 연속을 뜻함

$att['attendance_page_rows'] = "15"; // 한페지 개수
$att['attendance_pages'] = "10"; // 페이징 출력수
$att['attendance_honor_rows'] = "10"; // 명예의 전당 출력수

$att['char_min'] = "10"; // 내용 최소 글자수
$att['char_max'] = "100"; // 내용 최대 글자수

$att['attendance_memo'] = array(); // 남길 말없을때 기본 디폴트설정 랜덤 표현
$att['attendance_memo']['0'] = "안녕하세요 즐거운 하루 보내세요!!";
$att['attendance_memo']['1'] = "오늘은 꼭이길테다!!";
$att['attendance_memo']['2'] = "2CPU 최고!!";
$att['attendance_memo']['3'] = "내가 멀낼까 궁금하지?";
$att['attendance_memo']['4'] = "사은품은 내꺼!";
$att['attendance_memo']['5'] = "초성체와 이모티콘을 안쓰겠습니다";
$att['attendance_memo']['9'] = "장터는 하루에 1번만 들리겠습니다";
$att['attendance_memo']['10'] = "남자는 주먹을 냅니다.";
$att['attendance_memo']['11'] = "주먹을 내고 져도 울지 않습니다.";
$att['attendance_memo']['12'] = "매일매일 출첵";
$att['attendance_memo']['14'] = "도전! 행복한 하루";
$att['attendance_memo']['15'] = "편안하게 잠자고 싶어요";


include_once($g4['attendance_path'] . '/lib/attendance.lib.php');
?>
<script>
/* 부트스트랩 툴팁 스크립트 */
$(document).ready(function(){
    $(".tooltip-top").tooltip({trigger: 'hover click','placement': 'top'});
});
$(document).ready(function(){
    $(".tooltip-left").tooltip({trigger: 'hover click','placement': 'left'});
});
$(document).ready(function(){
    $(".tooltip-right").tooltip({trigger: 'hover click','placement': 'right'});
});
$(document).ready(function(){
    $(".tooltip-bottom").tooltip({trigger: 'hover click','placement': 'bottom'});
});
</script>
