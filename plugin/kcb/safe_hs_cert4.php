<?php
include_once("./_common.php");

$g4[title] = "KCB(코리아크레딧뷰로) - okname 본인인증";
include_once("./_head.php");
include_once("./nc.config.php");


//	생년월일 본인 확인서비스 결과 화면
/* 공통 리턴 항목 */
$idcfMbrComCd		  = $_POST["idcf_mbr_com_cd"];		  // 고객사코드
$hsCertSvcTxSeqno	= $_POST["hs_cert_svc_tx_seqno"];	// 거래번호
$hsCertRqstCausCd	= $_POST["hs_cert_rqst_caus_cd"];	// 인증요청사유코드 2byte  (00:회원가입, 01:성인인증, 02:회원정보수정, 03:비밀번호찾기, 04:상품구매, 99:기타);// 

$resultCd			    = $_POST["result_cd"];				    // 결과코드
$resultMsg			  = $_POST["result_msg"];				    // 결과메세지
$certDtTm			    = $_POST["cert_dt_tm"];				    // 인증일시
$di					      = $_POST["di"];						        // DI
$ci					      = $_POST["ci"];						        // CI
$name				      = $_POST["name"];					        // 성명
$birthday			    = $_POST["birthday"];				      // 생년월일
$gender				    = $_POST["gender"];					      //성별
$nation				    = $_POST["nation"];					      //내외국인구분
$telComCd			    = $_POST["tel_com_cd"];			      //통신사코드
$telNo				    = $_POST["tel_no"];					      //휴대폰번호
$returnMsg			  = $_POST["return_msg"];			      //리턴메시지
?>

<? if ($resultCd == "B000") { ?>
<h3>다음과 같이 본인확인이 되었습니다</h3>
<ul>
  <li>성명			: <?=$name?> </li>
  <li>생년월일		: <?=$birthday?> </li>
  <li>성별			: <?=$gender?> </li>
  <li>내외국인구분	: <?=$nation?> </li>
  <li>통신사코드	: <?=$telComCd?> </li>
  <li>휴대폰번호	: <?=$telNo?> </li>
</ul>

원활한 서비스 이용을 위해 바른 사용을 부탁드립니다.<br>
관련 법률에 따라 다른 사람의 개인정보를 도용하여 인터넷 서비스에 가입하는 경우는 명백한 범죄행위로<br>
<b>3년 이하의 징역또는 1천만원 이하의 벌금</b>에 처해질 수 있습니다.

<? } else {?>
<h3>본인확인 오류입니다</h3>
<ul>
  <li>결과코드		: <?=$resultCd?></li>
  <li>결과메세지	: <?=$resultMsg?></li>
  <li>거래번호		: <?=$hsCertSvcTxSeqno?> </li>
  <li>성명			: <?=$name?> </li>
  <li>생년월일		: <?=$birthday?> </li>
  <li>성별			: <?=$gender?> </li>
  <li>내외국인구분	: <?=$nation?> </li>
  <li>통신사코드	: <?=$telComCd?> </li>
  <li>휴대폰번호	: <?=$telNo?> </li>
</ul>

다시 인증받으시려면, <a href="./index.php">이곳</a>을 클릭해주세요.
<? } ?>

<!--  // 개발과정에서만 Open해서 보세요.
<h3>인증결과</h3>
<ul>
  <li>고객사코드	: <?=$idcfMbrComCd?> </li>
  <li>인증사유코드	: <?=$hsCertRqstCausCd?></li>
  <li>결과코드		: <?=$resultCd?></li>
  <li>결과메세지	: <?=$resultMsg?></li>
  <li>거래번호		: <?=$hsCertSvcTxSeqno?> </li>
  <li>인증일시		: <?=$certDtTm?> </li>
  <li>DI			: <?=$di?> </li>
  <li>CI			: <?=$ci?> </li>
  <li>성명			: <?=$name?> </li>
  <li>생년월일		: <?=$birthday?> </li>
  <li>성별			: <?=$gender?> </li>
  <li>내외국인구분	: <?=$nation?> </li>
  <li>통신사코드	: <?=$telComCd?> </li>
  <li>휴대폰번호	: <?=$telNo?> </li>
</ul>
-->

<?
include_once("./_tail.php"); 
?>