<?
// 사용법
// 1. g4_member 테이블에, mb_realcheck 필드를 datetime 형식으로 생성합니다.
// 2. php_okname.so php extension 화일을 업로드 하고 php.ini에 extension을 추가해야 합니다.
// 3. 로그 디렉토리(date/kcb)의 권한을 rwx---rwx로 줘야 합니다.
// 4. nc.config.php에서 회원사코드, 로그파일의 위치정보를 수정해야 합니다.
// 5. 서버의 문자셋과 사이트의 문자셋이 다른 경우에는 $g4['okname_charset']에 값을 적정하게 넣어줘야 합니다.

// 테스트중일때는. 1로 설정하고 이후에는 0으로 하면 됩니다.
$kcb_test = 0;

// Live  회원사코드
$memid = "kcb에서 알려주는 값...";

// KCB 로그파일의 위치
$kcblog = "/home/opencode/public_html/data/kcb";

// 본인확인 로그 디렉토리 (절대경로로 줍니다. 실명확인이 없어져서 로그디렉토리 구분 없앱니다)
$logPath = $kcblog;	

// 서버의 문자셋 - 문자셋이 사이트와 다른 경우에는 수정해주세요 (euc-kr/utf-8)
$g4['okname_charset'] = $g4['charset'];

// *** 회원사 도메인, $_SERVER["HTTP_HOST"] 사용가능.
$qryDomain = $_SERVER["HTTP_HOST"];

// *** 회원사 IP,   $_SERVER["SERVER_ADDR"] 사용가능.
$qryIP = "x";

// 본인확인 리턴 URL 설정- 본인인증 완료후 리턴될 URL (도메인 포함 full path)
$returnUrl = "http://$qryDomain/plugin/kcb/safe_hs_cert3.php";

// KCB의 인증 URL.
if ($kcb_test) {
    $EndPointURL = "http://tsafe.ok-name.co.kr:29080/KcbWebService/OkNameService"; 
} else {
    $EndPointURL = "http://safe.ok-name.co.kr/KcbWebService/OkNameService"; 
}

/**************************************************************************
 * okname 본인 확인서비스 파라미터
 **************************************************************************/
$inTpBit = "0";										// 입력구분코드(고정값 '0' : KCB팝업에서 개인정보 입력)
$name = "x";										  // 성명 (고정값 'x')
$birthday = "x";									// 생년월일 (고정값 'x')
$gender = "x";										// 성별 (고정값 'x')
$ntvFrnrTpCd="x";									// 내외국인구분 (고정값 'x')
$mblTelCmmCd="x";									// 이동통신사코드 (고정값 'x')
$mbphnNo="x";										  // 휴대폰번호 (고정값 'x')
	
$svcTxSeqno = date("YmdHis");		  // 거래번호. 동일문자열을 두번 사용할 수 없음. ( 20자리의 문자열. 0-9,A-Z,a-z 사용.)
	
$rsv1 = "0";										  // 예약 항목
$rsv2 = "0";										  // 예약 항목
$rsv3 = "0";										  // 예약 항목
	
$hsCertMsrCd = "10";							// 인증수단코드 2byte  (10:핸드폰)
$returnMsg = "x";									// 리턴메시지 (고정값 'x') 

$hsCertRqstCausCd = "02";					// 인증요청사유코드 2byte  (00:회원가입, 01:성인인증, 02:회원정보수정, 03:비밀번호찾기, 04:상품구매, 99:기타)
$option2 = "QL";                  // D: debug mode(Console에서 사용시에), L: log 기록.
$option3 = "SL";                  // D: debug mode(Console에서 사용시에), L: log 기록. safe_hd_cert3.php의 옵션은 S 입니다.
?>
<script type="text/javascript">
function popup_real()
{
    window.open("<?=$g4[url]?>/plugin/kcb/safe_hs_cert2.php", "auth_popup", "width=432, height=560, scrollbars=0");
}
</script>
