<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

// 사용자 화면 우측과 하단을 담당하는 페이지입니다.
// 우측, 하단 화면을 꾸미려면 이 파일을 수정합니다.
?>

</div>
</div><!-- 메인 content 끝 -->

</div><!-- class=row -->
</section><!-- 중간의 메인부 끝 -->

<!-- 페이지 하단부 footer -->
<footer class="footer-wrapper" role="contentinfo">
<div class="container">
    <div>
        <ul class="list-inline">
        <li><a href="<?=$g4[path]?>/">홈으로</a></li>
        <li><a href="<?=$g4[path]?>/company/privacy.php?mnb=info&snb=privacy"><b>개인정보취급방침</b></a></li>
        <li><a href="<?=$g4[path]?>/company/service.php?mnb=info&snb=service">이용약관</a></li>
        <li><a href="<?=$g4[path]?>/company/disclaimer.php?mnb=info&snb=disclaimer">책임한계와법적고지</a></li>
        <li><a href="<?=$g4[path]?>/company/rejection.php?mnb=info&snb=rejection">이메일주소무단수집거부</a></li>
        </ul>
    </div>
    <p>OpenCode는 지식중개자로서 지식의 주문, 배송 및 환불의 의무와 책임은 각 회원에 있습니다.</p>
    <p>사업자등록번호 :000-00-00000 <span>통신판매업신고번호</span> :제2009-서울서초-0000호<br>
       대표이사 :아빠불당 <span>주소</span> :서울시 서초구 서초동 먼산 <span>전화</span> :00-000-0000</p>
    <p>Copyright &copy; <a href="http://opencode.co.kr" target="_blank">Opencode.co.kr</a>. All rights reserved.</p>
</div>
</footer>

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