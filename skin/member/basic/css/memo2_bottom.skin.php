    <div class="bottom">
    <? if ($kind == 'write' && $config[cf_memo_send_point]) { // 쪽지보내기 + 포인트 차감이 있을 때만 메시지를 출력 합니다.
       echo "* 쪽지 보낼때 회원당 ".number_format($config[cf_memo_send_point])."점의 포인트를 차감합니다.<br />"; } ?>
    <? if ($kind == "write") { // 쓰기 일때만 메시지를 출력 합니다.
       echo "* 여러명에게 쪽지 발송시 컴마(,)로 구분 합니다.<br />"; ?>
       <? if ($config[cf_memo_use_file]) { echo "* 첨부가능한 파일의 최대 용량은 ".ini_get('upload_max_filesize')." 입니다.<br />";  } ?><? } ?>
    <? if ($kind == "send") { // 보낸쪽지함 일때만 메시지를 출력 합니다.
       echo "* <span class='red'>읽지 않은 쪽지를 삭제하면, 발신이 취소(수신자 쪽지함에서 삭제) 됩니다.</span><br />"; }

       if ($kind == "send" || $kind == "recv") { // 보낸쪽지함 일때만 메시지를 출력 합니다.
       echo "* 보관하지 않은 쪽지는 {$config[cf_memo_del]}일 후 삭제되므로 중요한 쪽지는 보관하시기 바랍니다."; } ?>
    </div>
