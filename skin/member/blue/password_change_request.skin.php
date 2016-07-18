<div class="panel panel-default">
    <div class="panel-heading"><strong>비밀번호 변경을 해주세요.</strong></div>
    <div class="panel-body">
        <ul class="list-unstyled">
            <li>주기적인 개인정보(비밀번호) 수정은 개인정보를 지키는 기본 사항 입니다.</li>
            <li>비밀번호는 연속되는 숫자, 문자 및 개인신상정보 (아이디, 생년월일 등)로 하는 것을 피해야 합니다.</li>
            <li>사용하는 사이트마다 비밀번호를 다르게 하는 것이 바람직 합니다.</li>
            <li>PC방등의 공공장소에서는 반드시 로그아웃 해야 합니다.</li>
            <li>비밀번호는 절대 타인과 공유를 하면 안됩니다.</li>
        </ul>
        <div class="btn-group">
            <a class="btn btn-primary" href="<?=$g4[bbs_path]?>/member_confirm.php?url=register_form.php" onfocus="this.blur()">
            비밀번호 수정하러 가기
            </a>
            <a class="btn btn-default" href="<?=$g4[bbs_path]?>/password_change_reset.php?url=<?=$url?>" onfocus="this.blur()">
            다음 주기에 변경하기 (<?=$config['cf_password_change_dates']?>일 후에 꼭 바꿔주세요)
            </a>
        </div>
    </div>
</div>
