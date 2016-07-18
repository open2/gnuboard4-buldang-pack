<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>
<div class="well">
    <p><strong>회원가입을 진심으로 축하합니다.</strong></p>
    <p>
    회원님의 패스워드는 아무도 알 수 없는 암호화 코드로 저장되며,<br>
    아이디, 패스워드 분실시에는 회원가입시 입력하신 이메일을 이용하여 찾을 수 있습니다.
    <? if ($config[cf_use_email_certify]) { ?>
    </p>
    <p>
    E-mail(<?=$mb[mb_email]?>)로 발송된 내용을 확인한 후 인증하셔야 회원가입이 완료됩니다.
    <? } ?>
    <br><br>
    </p>
  <p>
      <a class="btn btn-primary btn-md" role="button" href="<?=$g4[url]?>">홈으로 가기</a>
      <a class="btn btn-primary btn-md" role="button" href="<?=$g4[path]?>/plugin/kcb/">본인인증하러 가기</a></p>
  </p>
</div>
