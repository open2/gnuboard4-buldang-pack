<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

// 사용자 화면 우측과 하단을 담당하는 페이지입니다.
// 우측, 하단 화면을 꾸미려면 이 파일을 수정합니다.
?>

</div><!-- 가운데 영역 div - content 끝 -->

<!-- 오른쪽 컬럼 div - side 시작 -->
<div class="aside">
    <div>
    <?
        // 와이즈넛 광고를 봐주셔야 합니다. ㅠ..ㅠ...
        include_once("$g4[path]/adsense_aside.php");
    if ($is_member) {
        $loading_msg = "Loading...";
        include_once("$g4[path]/lib/whatson.lib.php");
        echo "<div id='my_whatson'>$loading_msg</div>";
        //echo whatson("basic");
    
        include_once "$g4[path]/lib/latest.my.lib.php";
        echo "<div id='my_post' style='height:260px'>$loading_msg</div>";
        echo "<div id='my_comment' style='height:260px'>$loading_msg</div>";
    };
    
    // 항상 광고노출
    include_once("$g4[path]/adsense_aside2.php");
    ?>
    </div>
</div><!-- 오른쪽 컬럼 div - side 끝 -->

</div><!-- 중간부 div - container 끝 -->

<!-- 페이지 하단부 footer -->
<div id="footer">
		<ul  class="footer-nav">
  			<li><a href="<?=$g4[path]?>/">홈으로</a></li>
	  		<li><a href="<?=$g4[path]?>/company/privacy.php?mnb=info&snb=privacy"><b>개인정보취급방침</b></a></li>
  			<li><a href="<?=$g4[path]?>/company/service.php?mnb=info&snb=service">이용약관</a></li>
        <li><a href="<?=$g4[path]?>/company/disclaimer.php?mnb=info&snb=disclaimer">책임한계와법적고지</a></li>
        <li><a href="<?=$g4[path]?>/company/rejection.php?mnb=info&snb=rejection">이메일주소무단수집거부</a></li>
		</ul>
    <p class="info">
        OpenCode는 지식중개자로서 지식의 주문, 배송 및 환불의 의무와 책임은 각 회원에 있습니다.
    </p>
    <p class="info2">
        사업자등록번호 :000-00-00000 <span>통신판매업신고번호</span> :제2009-경기성남-0000호<br>
        대표이사 :아빠불당 <span>주소</span> :서울시 서초구 서초동<br>
        대표전화 :00-000-0000 <span>팩스</span> :02-0000-0000
    </p>
    <p class="copyright">
        Copyright &copy; <a href="http://opencode.co.kr" target="_blank">Opencode.co.kr</a>. All rights reserved.
    </p>
</div>

</div><!-- 전체 div : wrap 끝 -->

<script type="text/javascript">
// jquery를 이용한 비동기 페이지 로딩 입니다

<? if ($is_member) { ?>

$("#my_whatson").html( " <? echo remove_nr( trim(  whatson('basic')   ))?> " );

<? if ($g4[path] == ".") { ?>

$("#my_post").html( " <? echo remove_nr( trim(   db_cache("$member[mb_id]_latest_my_post", 1, "latest_my('naver','내가올린글',80,10,25)")   ))?> " );
<?} else { ?>
$("#my_post").html( " <? echo remove_nr( trim(   db_cache("$member[mb_id]_latest_my_post_sub", 1, "latest_my('naver','내가올린글',80,10,25)")   ))?> " );
<? } ?>

<? if ($g4[path] == ".") { ?>
$("#my_comment").html( " <? echo remove_nr( trim(   db_cache("$member[mb_id]_latest_my_comment", 1, "latest_my('naver','내가올린 코멘트',80,10,25,'comment')")   ))?> " );
<?} else { ?>
$("#my_comment").html( " <? echo remove_nr( trim(   db_cache("$member[mb_id]_latest_my_comment_sub", 1, "latest_my('naver','내가올린 코멘트',80,10,25,'comment')")   ))?> " );
<? } ?>

<? if ($g4[path] == ".") { ?>
$("#my_response").html( " <? echo remove_nr( trim(   db_cache("$member[mb_id]_latest_my_update", 1, "latest_my_update('naver','내글의반응',80,10,25)")   ))?> " );
<?} else { ?>
$("#my_response").html( " <? echo remove_nr( trim(   db_cache("$member[mb_id]_latest_my_update_sub", 1, "latest_my_update('naver','내글의반응',80,10,25)")   ))?> " );
<? } ?>

<? } ?>

</script> 

<?
include_once("$g4[path]/tail.sub.php");
?>