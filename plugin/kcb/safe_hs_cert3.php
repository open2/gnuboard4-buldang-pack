<?php
/**************************************************************************
	파일명 : safe_hs_cert3.php
	
	생년월일 본인 확인서비스 결과 화면(return url)
**************************************************************************/

include_once("./_common.php");

// 비회원 접속불가
if ($member['mb_id'] == "")
    die;

$g4[title] = "KCB(코리아크레딧뷰로) - okname 본인확인";

include_once("$g4[path]/head.sub.php");
include_once("./nc.config.php");

/* 공통 리턴 항목 */
$idcfMbrComCd			=	$_POST["idcf_mbr_com_cd"];		  // 고객사코드
$hsCertSvcTxSeqno	=	$_POST["hs_cert_svc_tx_seqno"];	// 거래번호
$rqstSiteNm				=	$_POST["rqst_site_nm"];			    // 접속도메인	
$hsCertRqstCausCd	=	$_POST["hs_cert_rqst_caus_cd"];	// 인증요청사유코드 2byte  (00:회원가입, 01:성인인증, 02:회원정보수정, 03:비밀번호찾기, 04:상품구매, 99:기타)

$resultCd				=	$_POST["result_cd"];			// 결과코드
$resultMsg			=	$_POST["result_msg"];			// 결과메세지
$certDtTm				=	$_POST["cert_dt_tm"];			// 인증일시

/**************************************************************************
 * 모듈 호출	; 생년월일 본인 확인서비스 결과 데이터를 복호화한다.
 **************************************************************************/
$encInfo = $_POST["encInfo"];

//KCB서버 공개키
$WEBPUBKEY = trim($_POST["WEBPUBKEY"]);
//KCB서버 서명값
$WEBSIGNATURE = trim($_POST["WEBSIGNATURE"]);

// 본인확인 - 암호화키 파일 설정 (절대경로) - 파일은 주어진 파일명으로 자동 생성 됨
if ($kcb_test)
    $keypath = "$kcblog/tsafecert_$idcfMbrComCd.key";
else
    $keypath = "$kcblog/safecert_$idcfMbrComCd.key";

$cpubkey = $WEBPUBKEY;    //server publickey
$csig = $WEBSIGNATURE;    //server signature

// 명령어
$cmd = array($keypath, $idcfMbrComCd, $EndPointURL, $WEBPUBKEY, $WEBSIGNATURE, $encInfo, $logPath, $option3);

if ($kcb_test) {
    echo "$cmd<br>";
}

// 실행
$output = NULL;
$ret = okname($cmd, $output);
if ($kcb_test) {
    echo "ret=$ret<br/>";
}

if($ret == 0) {
		// 복호화가 잘 되는지 보고 싶을 때 풀어준다.
		// echo "복호화 요청 호출 성공.<br/>";

		// 결과라인에서 값을 추출
		$output = iconv($g4['okname_charset'] , $g4['charset'], $output);
		$field = explode("\n", $output);
} else {
		echo "복호화 요청 호출 에러. 리턴값 : ".$ret."<br/>";		 
		if($ret <=200)
			$resultCd=sprintf("B%03d", $ret);
		else
			$resultCd=sprintf("S%03d", $ret);
}

// *** 이 두 값을 $_POST 의 값 대신 사용.
$resultCd = $field[0];
$resultMsg = $field[1];
$hsCertSvcTxSeqno = $field[2];

// *** 테스트할때 풀어주세요.
//$kcb_test = 1;
if ($kcb_test) {
    echo "처리결과코드		:$resultCd	<br/>";
    echo "처리결과메시지	:$field[1]	<br/>";
    echo "거래일련번호		:$field[2]	<br/>";
    echo "인증일시			  :$field[3]	<br/>";
    echo "DI				      :$field[4]	<br/>";
    echo "CI				      :$field[5]	<br/>";
    echo "성명				    :$field[7]	<br/>";
    echo "생년월일			  :$field[8]	<br/>";
    echo "성별				    :$field[9]	<br/>";
    echo "내외국인구분		:$field[10]	<br/>";
    echo "통신사코드		  :$field[11]	<br/>";
    echo "휴대폰번호		  :$field[12]	<br/>";
    echo "리턴메시지		  :$field[16]	<br/>";
}
// *** 테스트할 때 값을 확인하고 싶은 경우 풀어주세요.
//print_r($field);die;

// 무조건 로그를 남긴다
$sql = " insert into $g4[realcheck_table] set mb_id = '$member[mb_id]', cb_authtype = '$hsCertRqstCausCd', cb_ip = '$_SERVER[REMOTE_ADDR]', cb_datetime = '$g4[time_ymdhis]', cb_errorcode = '$resultCd' ";
sql_query($sql);

// 결과처리 ===
switch ($resultCd) {
case "B000" : // 정상처리
    $sql = " update $g4[member_table] set mb_name = '$name', mb_realcheck = '$g4[time_ymdhis]', mb_hp = '$field[12]' where mb_id = '$member[mb_id]' ";
    sql_query($sql);
    
    include("./realcheck.skin.php");
    break;
default :     // 정상이 아닌 경우
    include("./realcheck.error.skin.php");
    break;
}

include_once("$g4[path]/tail.sub.php");
?>
