<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>
<script language="javascript" type="text/javascript">
    opener.location.reload();
</script>

<div class="panel panel-default">
<div class="panel-heading">
    다음과 같이 본인확인이 되었습니다
    <a class="btn btn-default pull-right" href="#" onclick="javascript:self.close();">닫기</a>
</div>
<div class="panel-body">
    <ul>
    <li>성명 : <?=$field[7]?> </li>
    <li>생년월일 : <?=$field[8]?> </li>
    <li>성별 : <?=$field[9]?> </li>
    <li>내외국인구분	: <?=$field[10]?> </li>
    <li>통신사코드	: <?=$field[11]?> </li>
    <li>휴대폰번호	: <?=$field[12]?> </li>
    </ul>
</div>
</div>

<? /*
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
    echo "리턴메시지		  :$field[11]	<br/>";
*/ ?>