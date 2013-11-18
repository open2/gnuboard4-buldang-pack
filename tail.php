<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

// 사용자 화면 우측과 하단을 담당하는 페이지입니다.
// 우측, 하단 화면을 꾸미려면 이 파일을 수정합니다.
?>

</div><!-- 메인 content 끝 -->

</section><!-- 중간의 메인부 끝 -->

<!-- 페이지 하단부 footer -->
<footer class="footer-wrapper col-sm-offset-2 visible-sm visible-md visible-lg" role="contentinfo">
<div class="container">
    <div>
        <ul class="list-inline">
        <li><a href="<?=$g4[path]?>/">홈으로</a></li>
        <li><a href="<?=$g4[path]?>/company/company.php?id=privacy"><strong>개인정보취급방침</strong></a></li>
        <li><a href="<?=$g4[path]?>/company/company.php?id=service">이용약관</a></li>
        <li><a href="<?=$g4[path]?>/company/company.php?id=disclaimer">책임한계와법적고지</a></li>
        <li><a href="<?=$g4[path]?>/company/company.php?id=rejection">이메일주소무단수집거부</a></li>
        </ul>
    <p>사업자등록번호 :000-00-00000 <span>통신판매업신고번호</span> :제2009-서울서초-0000호<br>
       대표이사 :아빠불당 주소 :서울시 서초구 서초동 먼산 전화</span> :00-000-0000</p>
    <p><?=$config[cf_title]?>는 지식중개자로서 지식의 주문, 배송 및 환불의 의무와 책임은 각 회원에 있습니다.<br>
    <?=$config[cf_title]?>의 사전 서면 동의 없이 <?=$config[cf_title]?>의 일체의 정보, 콘텐츠 및 UI등을 상업적 목적으로 전재, 전송, 스크래핑 등 무단 사용할 수 없습니다.<br>
    Copyright &copy; <a href="http://opencode.co.kr" target="_blank">Opencode.co.kr</a>. All rights reserved.</p>
    </div>
</div>
</footer>

<?
include_once("$g4[path]/tail.sub.php");
?>