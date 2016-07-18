<? 
include_once("./_common.php");

if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$g4[title] = "KCB(코리아크레딧뷰로) - okname 본인인증";
include_once("./_head.php");
include_once("./nc.config.php");
?>
<div class="panel panel-default">
<div class="panel-heading">
    <strong>KCB 본인확인</strong>
    <span class="pull-right"><a href="http://okname.allcredit.co.kr/" target=_new>kcb okname</a></span>
</div>
<div class="panel-body">
    <? if ($member['mb_id'] == "") { ?>
				<p>회원대상 서비스 입니다. <a href='<?=$g4[bbs_path]?>/login.php?<?=$qstr?>&url=<?=urlencode("$_SERVER[PHP_SELF]")?>'>로그인</a> 하신후 이용해주세요.</p>
    <? } else { ?>
        <? if ($member[mb_realcheck] !== "0000-00-00 00:00:00") { ?>
            <p><?=$member[mb_realcheck]?> 에 본인인증을 받으셨습니다.</p>
       	<? } else { ?>
 		    	  <p>본인인증을 받으시려면 <a href="javascript:popup_real();"><strong>이곳</strong></a>을 클릭해주세요.</p>
   			<? } ?>
    <? } ?>
</div>
<div class="panel-footer">
	<ul>
		<li>다른 사람의 개인정보를 도용하는 것은 명백한 범죄행위로 관련 법률에 따라 <strong>3년 이하의 징역또는 1천만원 이하의 벌금</strong>에 처해질 수 있습니다.</li>
		<li>본인확인은 <a href="http://okname.allcredit.co.kr/" target=_new>KCB(코리아크레딧뷰로, OK Name)</a>의 본인확인 서비스로 이루어 집니다.</li>
		<li>본인확인을 위해 주민번호를 요구하지 않습니다.</li>
		<li>본인인증에 대한 문의는 사이트의 운영자 또는 KCB의 실명확인 콜센터(T.02-708-1000)로 하시면 됩니다.</li>
	</ul>
</div>
</div>

<?
include_once("./_tail.php"); 
?>
