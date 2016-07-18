<?
include_once("./_common.php");

$g4['title'] = "쪽지5";

include_once("$g4[path]/head.sub.php");
include_once("$g4[path]/memo.config.php");

if (!$member['mb_id'])
    alert("회원만 이용하실 수 있습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.", "./login.php?$qstr&url=".urlencode("$_SERVER[PHP_SELF]?kind=$kind"));

// 자동응답으로 설정되어 있는 경우에는 쪽지를 사용할 수 없습니다.
if (!$is_admin && $member['mb_memo_no_reply'] && $kind != "memo_config")
    alert("부재중 설정을 해제해야 쪽지를 사용할 수 있습니다", "./memo.php?kind=memo_config");

// 받은 쪽지함의 읽지 않은 쪽지의 카운터를 구하기는 시스템에 엄청난 부담이 가므로 제한적으로 합니다.
// 신규로 수신한 쪽지가 있을 때, 안읽은 쪽지 갯수를 구합니다.
// 이외에는 쪽지를 삭제할 때, 안읽은 쪽지 카운터를 누를 때, 안읽은 쪽지 갯수를 구하구요.
if ($g4['mb_memo_call_datetime'] == "0000-00-00 00:00:00" || $member['mb_memo_unread'] < 0) {
    $sql = " select count(*) as cnt from $g4[memo_recv_table] 
              where me_recv_mb_id = '$member[mb_id]' and me_read_datetime = '0000-00-00 00:00:00' ";
    $row = sql_fetch($sql);
    $total_count_recv_unread = $row['cnt'];
    
    // 그리고 안읽은 쪽지 갯수를 g4_member db에 업데이트
    $sql = " update $g4[member_table] 
                set mb_memo_unread = '$total_count_recv_unread', mb_memo_call_datetime='0000-00-00 00:00:00' 
              where mb_id = '$member[mb_id]' ";
    sql_query($sql);
} else {
    $total_count_recv_unread = $member['mb_memo_unread'];
}

// 쪽지 title 설정하기
switch ($kind) 
{ 
    case 'friend' : // 친구관리
                  $memo_title = "친구관리";
                  break;
    case 'online' : // 현재접속자
                  $memo_title = "현재접속자";
                  break;
    case 'memo_group' : // 메모그룹
                  $memo_title = "메모그룹";
                  break;
    case 'memo_group_admin' : // 메모그룹 관리
                  $memo_title = "메모그룹관리";
                  break;        
    case 'memo_config' : // 설정 관리
                  $memo_title = "설정관리";
                  break;
    case 'memo_address_book' : // 주소록 관리
                  $memo_title = "주소록관리";
                  break;
    case 'write' : // 쪽지보내기
                  $memo_title = "쪽지보내기";
                  break;                  
    case 'send' : // 발신함
                  $memo_title = "보낸쪽지함";
                  break;
    case 'save' : // 저장함 (수신/발신 모두 쪽지 저장이 가능)
                  $memo_title = "보관한쪽지함";
                  break;
    case 'trash': // 휴지통 
                  $memo_title = "삭제한쪽지함";
                  break;
    case 'notice' : // 공지쪽지함
                  $memo_title = "공지쪽지함";
                  break; 
    case 'spam' : // 스팸함 (수신한 것만 스팸함으로)
                  $memo_title = "스팸쪽지함";
                  break;
    case 'recv' : // 수신함
                  $memo_title = "받은쪽지함";
                  break;
    default     : // 아무런 지정이 없을 때는 수신함으로 설정
                  $memo_title = "받은쪽지함";
                  $kind=recv;
                  break;
}

// 글쓰기 할때
if ($kind == "write") {

    // 친구관리 기능을 사용하려면
    if ($config['cf_friend_management'] == true) {

        // join을 하지 않고, loop를 돌린다. 그게 가장 빠르다.
        $sql = " select fr_id from $g4[friend_table] where mb_id = '$member[mb_id]' ";
        $qry = sql_query($sql);

        $my_friend = array();
        $i = 0;

        while ($row = sql_fetch_array($qry))
        {
            $mb = get_member($row['fr_id'], "mb_nick");
            $my_friend[$i]['mb_nick'] = $mb['mb_nick'];
            $my_friend[$i]['fr_id'] = $row['fr_id'];
            $i++;
       }
    }
}

// kind에 따라서 action~!!!
switch ($kind) {

    case 'memo_config'      : // 설정 관리

        // 쪽지 삭제일이 1보다 작으면 재조정
        if ($config[cf_memo_del] < 1)
           $config[cf_memo_del] = 180;

        // 안읽은 쪽지 삭제일이 1보다 작거나 쪽지삭제일보다 작으면 재조정
        if ($config[cf_memo_del_unread] < 1 or $config[cf_memo_del_unread] < $config[cf_memo_del])
            $config[cf_memo_del_unread] = $config[cf_memo_del];

        if (!$config[cf_memo_page_rows])    $config[cf_memo_page_rows] = 12;
        if (!$config[cf_memo_del_unread])   $config[cf_memo_del_unread] = $config[cf_memo_del];
        if (!$config[cf_memo_del_trash])    $config[cf_memo_del_trash] = $config[cf_memo_del];
        if (!$config[cf_memo_no_reply])     $config[cf_memo_no_reply] = 0;

        // 최대 업로드 파일 사이즈 (m로 지정되었을 때)
        if (!$config[cf_memo_file_size]) {
            $max_upload_size = intval(substr(ini_get("upload_max_filesize"), 0, -1));
            if ($max_upload_size > 4)
                $config[cf_memo_file_size] = "4";
            else
                $config[cf_memo_file_size] = intval(substr(ini_get("upload_max_filesize"), 0, -1));
        }

        if (!$config[cf_max_memo_file_size]) {
            $config[cf_max_memo_file_size] = 0;
        }
        break;

    case 'friend'           : // 친구관리
    case 'online'           : // 현재접속자
    case 'memo_group'       : // 메모그룹
    case 'memo_group_admin' : // 메모그룹 관리
        break;
        
    case 'memo_address_book' : // 주소록 관리

        // mysql 4.0.x에서 union all을 지원하지 않아서 무식하게 query를 4번 수행 합니다. 아흐~
        $addr = array();
        $sql = " select 'recv' as type, a.me_send_mb_id as mb_id, count(*) as cnt, b.mb_name, b.mb_nick, b.mb_email, b.mb_homepage from $g4[memo_recv_table] a left join $g4[member_table] b on a.me_send_mb_id = b.mb_id where a.me_recv_mb_id = '$member[mb_id]' group by a.me_send_mb_id ";
        $res1 = sql_query($sql);
        if ($res1) {
            for ($i=0; $row=sql_fetch_array($res1); $i++) {
                $addr[] = $row;
            }
        }
        
        $sql = " select 'send' as type, a.me_recv_mb_id as mb_id, count(*) as cnt, b.mb_name, b.mb_nick, b.mb_email, b.mb_homepage from $g4[memo_send_table] a left join $g4[member_table] b on a.me_recv_mb_id = b.mb_id where a.me_send_mb_id = '$member[mb_id]' group by a.me_recv_mb_id ";
        $res1 = sql_query($sql);
        if ($res1) {
            for ($i=0; $row=sql_fetch_array($res1); $i++) {
                $addr[] = $row;
            }
        }

        $sql = " select 'save_send' as type, a.me_recv_mb_id as mb_id, count(*) as cnt, b.mb_name, b.mb_nick, b.mb_email, b.mb_homepage from $g4[memo_save_table] a left join $g4[member_table] b on a.me_recv_mb_id = b.mb_id where a.memo_owner = '$member[mb_id]' and memo_type='send' group by a.me_recv_mb_id ";
        $res1 = sql_query($sql);
        if ($res1) {
            for ($i=0; $row=sql_fetch_array($res1); $i++) {
                $addr[] = $row;
            }
        }

        $sql = " select 'save_recv' as type, a.me_send_mb_id as mb_id, count(*) as cnt, b.mb_name, b.mb_nick, b.mb_email, b.mb_homepage from $g4[memo_save_table] a left join $g4[member_table] b on a.me_send_mb_id = b.mb_id where a.memo_owner = '$member[mb_id]' and memo_type='recv' group by a.me_send_mb_id ";
        $res1 = sql_query($sql);
        if ($res1) {
            for ($i=0; $row=sql_fetch_array($res1); $i++) {
                $addr[] = $row;
            }
        }
        
        foreach ($addr as $row) {
            $list[$row['mb_id']][$row['type']] = $row['cnt'];
            $list[$row['mb_id']]['mb_id'] = $row['mb_id'];
            if ($config['cf_memo_mb_name']) $row['mb_nick'] = $row['mb_name'];
            $list[$row['mb_id']]['mb_nick'] = $row['mb_nick'];
        
            if ($row['mb_nick'])
                if ($config['cf_memo_mb_name'])
                    $mb_nick = $row['mb_name'];
                else
                    $mb_nick = $row['mb_nick'];
            else
                $mb_nick = "<font color=silver>정보없음</font>";
            $name = get_sideview($row['mb_id'], $row['mb_nick'], $row['mb_email'], $row['mb_homepage']);

            $list[$row['mb_id']]['name'] = $name;
        }

        // 주소록 전체 갯수
        $tot_cnt = count($list);

        break;
        
    case 'write'  : // 쪽지보내기

        // 그룹발송인 경우 ...
        if ($gr_id) { 
            $sql = " select * from $g4[memo_group_table] where gr_id = '$gr_id' ";
            $result = sql_fetch($sql);
        
            $sql2 = " select count(*) as cnt from $g4[memo_group_member_table] where gr_id = '$gr_id' ";
            $result2 = sql_fetch($sql2);
            $gr_member_count = $result2[cnt];
            
            if ($gr_member_count > 0) {} else alert("그룹 구성원이 아무도 없습니다.");
            
            $sql3 = " select * from $g4[memo_group_member_table] where gr_id = '$gr_id' ";
            $result3 = sql_query($sql3);
            
            $me_recv_mb_id = "";
            for ($i=0; $row = sql_fetch_array($result3); $i++)
            {
                if ($i+1 < $gr_member_count)
                  $me_recv_mb_id .= $row[gr_mb_id] . ",";
                else 
                  $me_recv_mb_id .= $row[gr_mb_id];
            }
        
            $write_header_msg = "( 그룹쪽지 :: " . cut_str($result[gr_name], 30) . " :: $gr_member_count 명)";
            
        }

        // 첨부파일 보내기의 경우
        if ($config[cf_memo_use_file]) {
            // 사용자별 할당 가능한 disk 용량 제한이 있을 경우, 이것은 시스템에서 exec 명령을 지원해야 가능
            if ($config[cf_max_memo_file_size]) {
                $memo_dir_size = get_dir_size($memo_file_path);
                // exec 오류가 있는 경우에는, 최대 메모 첨부 파일 용량의 20배로 설정
                if ($memo_dir_size == "error")
                    $memo_dir_msg = "시스템에서 디렉토리 사용량 정보를 허용하지 않습니다.<BR>관리자의 쪽지설정에서 개인별첨부파일 최대한도를 0으로 설정하기 바랍니다.";
                else {
                     $memo_dir = (int) $memo_dir_size / 1000;
                     if ($memo_dir > $config[cf_max_memo_file_size])
                        $memo_dir_msg = "전체첨부파일 용량 <?=$config[cf_max_memo_file_size]?> M(메가)를 초과해서 파일을 첨부할 수 없습니다.<BR>첨부파일이 있는 발신쪽지를 삭제하시기 바랍니다.";
                }
            }
        }

        // 공지쪽지 보내기의 경우
        if ($option == 'notice') {
            if ($is_admin)
                $write_header_msg = "( <font color='red'><b>공지쪽지는 취소할 수 없습니다. 신중하게 작성해 주세요</b></font> )";
            else {
                $me_recv_mb_id = 'notice';
                alert("공지쪽지는 관리자만 발송할 수 있습니다");
            }
        }

        // 답하는 쪽지의 경우 원본의 글을 참조로 ... 
          if ($me_id) {
          switch ($me_box) {
              case 'recv' : $from_table = $g4[memo_recv_table]; break;
              case 'save' : $from_table = $g4[memo_save_table]; break;
              default     : alert("me_box 오류 입니다");
          }
          $sql = " select me_memo, me_subject, me_send_mb_id, me_option from $from_table where me_id = '$me_id' ";
          $view = sql_fetch($sql);
          
          $subject = "Re) " . $view[me_subject];
          $view[me_memo] = stripslashes($view[me_memo]);
          
          if ($is_dhtml_editor) {

          $html = 1;
          $view[memo] = conv_content($view[me_memo], $html);

          $view[memo] = $view[me_memo];
          $content = "<br><br>"
                   . "<br>>  "
                   . "<br>>  " . preg_replace("/<BR>/", "<BR>>  ", $view[memo]) 
                   . "<br>>  "
                   . "<br>>  ";

          } else {

          $tags = array("<BR>");
          $view[memo] = strip_tags($view[me_memo], $tags);
          $content = "\n\n\n>"
                   . "\n>"
                   . "\n> " . preg_replace("/\n/", "\n> ", $view[memo]) 
                   . "\n>"
                   . "\n";
          
          }
        }

break;

    case 'recv'   : // 수신함
    case 'send'   : // 발신함
    case 'save'   : // 저장함 (수신/발신 모두 쪽지 저장이 가능)
    case 'trash'  : // 휴지통
    case 'notice' : // 공지쪽지함
    case 'spam'   : // 스팸함 (수신한 것만 스팸함으로)

    if ($class == 'view') { // 쪽지 읽기

        // $me_id가 없는 경우
        if (!$me_id)
            alert("읽을 쪽지가 없습니다.");

        // 튜닝을 위해서 select할 필드를 지정
        $memo_select = " me_id, me_recv_mb_id, me_send_mb_id, me_send_datetime, me_read_datetime, me_memo, me_file_local, me_file_server, me_subject, memo_type, memo_owner, me_option";

        switch ($kind) { // 쪽지읽기
        case 'send' : 
            $sql = " select $memo_select from $g4[memo_send_table] where me_send_mb_id = '$member[mb_id]' and me_id = '$me_id' "; 
            if ($config[cf_memo_before_after]) {
                $sql_before = " select max(me_id) as before_id from $g4[memo_send_table] where me_send_mb_id = '$member[mb_id]' and me_id < '$me_id' "; 
                $sql_after  = " select min(me_id) as after_id from $g4[memo_send_table] where me_send_mb_id = '$member[mb_id]' and me_id > '$me_id' "; 
            }
            break;
        case 'save' : 
            $sql = " select $memo_select from $g4[memo_save_table] where memo_owner = '$member[mb_id]' and me_id = '$me_id' "; 
            if ($config[cf_memo_before_after]) {
                $sql_before = " select max(me_id) as before_id from $g4[memo_save_table] where memo_owner = '$member[mb_id]' and me_id < '$me_id' "; 
                $sql_after = " select min(me_id) as after_id from $g4[memo_save_table] where memo_owner = '$member[mb_id]' and me_id > '$me_id' "; 
            }
            break;
        case 'trash' : 
            $sql = " select $memo_select, me_from_kind from $g4[memo_trash_table] where me_id = '$me_id' "; 
            if ($config[cf_memo_before_after]) {
                $sql_before = " select max(me_id) as before_id from $g4[memo_notice_table] where me_id < '$me_id' "; 
                $sql_after = " select min(me_id) as after_id from $g4[memo_notice_table] where me_id > '$me_id' "; 
            }
            break;
        case 'notice' : 
            $sql = " select $memo_select from $g4[memo_notice_table] where me_id = '$me_id' "; 
            if ($config[cf_memo_before_after]) {
                $sql_before = " select max(me_id) as before_id from $g4[memo_notice_table] where me_id < '$me_id' "; 
                $sql_after = " select min(me_id) as after_id from $g4[memo_notice_table] where me_id > '$me_id' "; 
            }
            break;
        case 'spam' : 
            if ($is_admin) 
               $sql = " select $memo_select from $g4[memo_spam_table] where me_id = '$me_id' ";
            else
               $sql = " select $memo_select from $g4[memo_spam_table] where me_recv_mb_id = '$member[mb_id]' and me_id = '$me_id' "; 
            if ($config[cf_memo_before_after]) {
                if ($is_admin) 
                {
                   $sql_before = " select max(me_id) as before_id from $g4[memo_spam_table] where me_id < '$me_id' "; 
                   $sql_after = " select min(me_id) as after_id from $g4[memo_spam_table] where me_id > '$me_id' "; 
                }
                else
                {
                   $sql_before = " select max(me_id) as before_id  from $g4[memo_spam_table] where me_recv_mb_id = '$member[mb_id]' and me_id < '$me_id' "; 
                   $sql_after = " select min(me_id) as after_id  from $g4[memo_spam_table] where me_recv_mb_id = '$member[mb_id]' and me_id > '$me_id' "; 
                }
            }
            break;
        case 'recv' : 
            $sql = " select $memo_select from $g4[memo_recv_table] where me_recv_mb_id = '$member[mb_id]' and me_id = '$me_id' "; 
            if ($config[cf_memo_before_after]) {
                $sql_before = " select max(me_id) as before_id from $g4[memo_recv_table] where me_recv_mb_id = '$member[mb_id]' and me_id < '$me_id' "; 
                $sql_after = " select min(me_id) as after_id from $g4[memo_recv_table] where me_recv_mb_id = '$member[mb_id]' and me_id > '$me_id' "; 
            }
            break;
        }
        $view = sql_fetch($sql);

        // sql 검색결과가 0 일때 - 쪽지가 없거나 권한이 없는 쪽지의 경우
        if (count($view) == 1) 
            alert("바르지 못한 접근이거나 읽을 쪽지가 없습니다.");

        $before_id = "";
        if ($sql_before) {
            $result_before = sql_fetch($sql_before);
            $before_id = $result_before['before_id'];
        }

        $after_id = "";
        if ($sql_after) {
            $result_after = sql_fetch($sql_after);
            $after_id = $result_after['after_id'];
        }

        // html 옵션
        $html = 2; // 기본으로 \n을 <br>로 바꿔줍니다. 쪽지5에서는 $html = 0은 사용하지 않습니다. $html에 값이 없으면 무조건 $html=2로 인식 합니다.
        if (strstr($view[me_option], "html1"))
            $html = 1;
        $view[me_memo] = stripslashes($view[me_memo]);
        $view[memo] = conv_content($view[me_memo], $html);

        // 이미지 resize를 위해서 (bbs/view.php에서 코드 발췌)
        $view[memo] = preg_replace("/(\<img )([^\>]*)(\>)/i", "\\1 name='target_resize_image[]' onclick='image_window(this)' style='cursor:pointer;' \\2 \\3", $view[memo]);

        // 발신자 sideview
        $mb_send = get_member($view[me_send_mb_id], "mb_id, mb_name, mb_nick, mb_email, mb_homepage, mb_signature");
        if ($config[cf_memo_mb_name]) $mb_send[mb_nick] = $mb_send[mb_name];
        $view[me_send_mb_id_nick] = get_sideview($mb_send[mb_id], $mb_send[mb_nick], $mb_send[mb_email], $mb_send[mb_homepage]);

        // 수신자 sideview
        $mb_recv = get_member($view[me_recv_mb_id], "mb_id, mb_name, mb_nick, mb_email, mb_homepage, mb_today_login");
        if ($config[cf_memo_mb_name]) $mb_recv[mb_nick] = $mb_recv[mb_name];
        $view[me_recv_mb_id_nick] = get_sideview($mb_recv[mb_id], $mb_recv[mb_nick], $mb_recv[mb_email], $mb_recv[mb_homepage]);

         // 쪽지를 읽은 날짜를 update
        if (substr($view[me_read_datetime],0,1) == '0') { // 쪽지를 읽지 않았을 때

            if ($kind == "recv") { // 수신함의 읽지 않은 쪽지는 읽은 날짜를 업데이트
              $sql = " update $g4[memo_recv_table] set me_read_datetime = '$g4[time_ymdhis]' where me_id = '$me_id' "; 
              sql_query($sql);
              $sql = " update $g4[memo_send_table] set me_read_datetime = '$g4[time_ymdhis]' where me_id = '$me_id' "; 
              sql_query($sql);
              $sql = " update $g4[memo_save_table] set me_read_datetime = '$g4[time_ymdhis]' where me_id = '$me_id' "; 
              sql_query($sql);
              $view[me_read_datetime] = $g4[time_ymdhis];
              
              // 안읽은 쪽지 갯수를 업데이트 (처음 쪽지5를 열 때, 안읽은 갯수를 업데이트 했으니, 차감만 하면 됩니다.
              sql_query(" update $g4[member_table] set mb_memo_unread = mb_memo_unread - 1 where mb_id = '$member[mb_id]' ");
              
              // 쪽지 프로그램에서 보여지는 읽지 않은 쪽지 count도 업데이트
              $total_count_recv_unread = $member['mb_memo_unread'] - 1;

            } else {
              $time_diff = strtotime($mb_recv['mb_today_login']) - strtotime($view['me_send_datetime']);
              if ($kind=='send' && $time_diff <= 0)
                  $view[me_read_datetime] = '수신 않음';       
              else
                  $view[me_read_datetime] = '읽지 않음';
            }

        }

        // 첨부파일의 image 여부를 확인
        $view[imagesize] = @getimagesize("{$g4[data_path]}/memo2/{$view[me_file_server]}");
        $img_type = $view[imagesize][2];
        if ($img_type >= 1 && $img_type <= 18)
            $view[valid_image] = 1;
        else
            $view[valid_image] = 0;

        // href 
        $view[save_href] = "./memo2_form_save.php?me_id=$me_id&kind=$kind";
        
        // 내꺼, 관리자꺼는 신고할 수 없게 하기
        if ($view[me_send_mb_id] == $member[mb_id] || is_admin($view[me_send_mb_id]))
        {
            $view[spam_href] = "";
        } else {
            $view[spam_href] = "./memo2_form_spam.php?me_id=$me_id&kind=$kind";
        }
        $view[del_href] = "./memo2_form_delete.php?me_id=$me_id&kind=$kind";
        $view[cancel_href] = "./memo2_form_cancel.php?me_id=$me_id&kind=$kind";
        
        if ($kind == "trash" && $view[me_from_kind] !== "")
            $view[recover_href] = "./memo2_form_recover.php?me_id=$me_id&me_from_kind=$view[me_from_kind]";
            
        if ($before_id) 
          $view[before_href] = "./memo.php?me_id=$before_id&kind=$kind&class=view";
        else
          $view[before_href] = "";
      
        if ($after_id)
          $view[after_href] = "./memo.php?me_id=$after_id&kind=$kind&class=view";
        else
          $view[after_href] = "";
    
        break;

    } else { // view가 아닌 경우? 그러면 목록보기징. ㅎㅎ

        $sql_search = "";
    
        // 안 읽은 쪽지만 골라보는거 때문에
        if ($kind == "recv" && $unread == "only")
            $sql_search_unread = " and me_read_datetime = '0000-00-00 00:00:00' ";
        else
            $sql_search_unread = "";
    
        if ($stx && $sfl) { // 검색을 하는 경우
          switch ($sfl) {
            case 'me_send_mb_nick'  : 
                if ($config['cf_memo_mb_name'])
                    $resm = sql_fetch(" select mb_id from $g4[member_table] where mb_name = '$stx' ");
                else
                    $resm = sql_fetch(" select mb_id from $g4[member_table] where mb_nick = '$stx' ");
                if (!$resm)
                    alert("입력하신 mb_nick/mb_name이 없습니다.");
                else
                    $sql_search = " and me_send_mb_id = '$resm[mb_id]' ";
                break;
            case 'me_send_mb_id'    : 
                $sql_search = " and me_send_mb_id = '$stx' ";
                break;
            case 'me_recv_mb_nick'  : 
                if ($config['cf_memo_mb_name'])
                    $resm = sql_fetch(" select mb_id from $g4[member_table] where mb_name = '$stx' ");
                else
                    $resm = sql_fetch(" select mb_id from $g4[member_table] where mb_nick = '$stx' ");
                if (!$resm)
                    alert("입력하신 mb_nick/mb_name이 없습니다.");
                else
                    $sql_search = " and me_recv_mb_id = '$resm[mb_id]' ";
                break;
            case 'me_recv_mb_id'    : 
                $sql_search = " and me_recv_mb_id = '$stx' ";
                break;
            case 'me_subject'       : 
                $sql_search = " and me_subject like '%$stx%' ";
                break;
            case 'me_memo'          : 
                $sql_search = " and me_memo like '%$stx%' ";
                break;
            case 'me_subject_memo':
                $sql_search = " and ( me_subject like '%$stx%' or me_memo like '%$stx%' )";
                break;
            case 'me_file'          : 
                $sql_search = " and me_file_local != '' ";
                break;
            default :
          }
        }
    
        switch ($kind) {
        case 'send' : // 발신함
                      $sql = " select count(*) as cnt 
                                  from  $g4[memo_send_table]
                                  where me_send_mb_id = '$member[mb_id]' $sql_search ";
    
                      break;
        case 'save' : // 저장함 (수신/발신 모두 쪽지 저장이 가능)
                      $sql = " select count(*) as cnt 
                                  from $g4[memo_save_table]
                                  where memo_owner = '$member[mb_id]' $sql_search  ";
                      break;
        case 'trash': // 휴지통
                      $sql = " select count(*) as cnt 
                                  from $g4[memo_trash_table]
                                  where memo_owner = '$member[mb_id]' $sql_search ";
                      break;
        case 'notice' : // 전체쪽지함
                      $sql = " select count(*) as cnt 
                                  from $g4[memo_notice_table] 
                            where memo_owner = '$member[mb_id]' ";
                      break;
        case 'spam' : // 스팸함 (수신한 것만 스팸함으로)
                      if ($is_admin)
                          $sql = " select count(*) as cnt 
                                      from $g4[memo_spam_table]
                                   where 1 $sql_search ";
                      else 
                          $sql = " select count(*) as cnt 
                                      from $g4[memo_spam_table]
                                      where me_recv_mb_id = '$member[mb_id]' $sql_search ";
                      break;
        case 'recv' : // 수신함
                      $sql = " select count(*) as cnt 
                                  from $g4[memo_recv_table]
                                  where me_recv_mb_id = '$member[mb_id]' $sql_search $sql_search_unread ";
                      break;
        }
    
        // 선택한 메일박스의 전체 메일 갯수
        $row = sql_fetch($sql);
        $total_count = $row['cnt'];
    
        // 안읽은 메일일 경우에는 전체 메일 갯수를 업데이트 - 굳이 안해도 될거 같지만, 있는 정보니까 써보는거야.
        if ($kind == "recv" && $unread == "only") {
            sql_query(" update $g4[member_table] set mb_memo_unread = '$row[cnt]' where mb_id = '$member[mb_id]' ");
        }
    
        // 페이징
        if (!$config['cf_memo_page_rows'] || $config['cf_memo_page_rows'] < 0)
            $one_rows = 20;
        else
            $one_rows = $config['cf_memo_page_rows'];
        $total_page  = ceil($total_count / $one_rows);  // 전체 페이지 계산 
        if ($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지) 
        $from_record = ($page - 1) * $one_rows; // 시작 열을 구함 
    
        // 메일목록 가져오기
        $order_by = " order by me_id desc ";
        $select_sql = " me_recv_mb_id, me_send_mb_id, me_read_datetime, me_send_datetime, me_subject, me_id, me_file_local ";
    
        // 휴지통의 경우에는 출처정보도 같이 읽어야지 함
        if ($kind == "trash")
            $select_sql = $select_sql . ", me_from_kind";
    
        // 이제 목록정보 가져와야죠?
        switch ($kind) {
        case 'send' : // 발신함
                      $sql = " select $select_sql 
                                 from $g4[memo_send_table]
                                 where me_send_mb_id = '$member[mb_id]' $sql_search 
                                 $order_by 
                                 limit $from_record, $one_rows";
                      break;
        case 'save' : // 저장함 (수신/발신 모두 쪽지 저장이 가능)
                      $sql = " select $select_sql, memo_type
                                 from $g4[memo_save_table]
                                 where memo_owner = '$member[mb_id]' $sql_search
                                 $order_by 
                                 limit $from_record, $one_rows";
                      break;
        case 'trash': // 휴지통
                      $sql = " select $select_sql, memo_type
                                 from $g4[memo_trash_table]
                                 where memo_owner = '$member[mb_id]' $sql_search 
                                 $order_by 
                                 limit $from_record, $one_rows";
                      break;
        case 'notice' : // 전체쪽지함
                      $sql = " select $select_sql
                                 from $g4[memo_notice_table] a left join $g4[member_table] b on ( a.me_send_mb_id = b.mb_id )
                                where memo_owner = '$member[mb_id]'
                                 $sql_search 
                                 order by me_id desc 
                                 limit $from_record, $one_rows";
                      break;
        case 'spam' : // 스팸함 (수신한 것만 스팸함으로
                      if ($is_admin)
                      $sql = " select $select_sql
                                 from $g4[memo_spam_table]
                                 where 1 $sql_search
                                 $order_by 
                                 limit $from_record, $one_rows";
                      else
                      $sql = " select $select_sql
                                 from $g4[memo_spam_table]
                                 where me_recv_mb_id = '$member[mb_id]' $sql_search 
                                 $order_by 
                                 limit $from_record, $one_rows";                  
                      break;
        case 'recv' : // 수신함
                      $sql = " select $select_sql 
                                 from $g4[memo_recv_table]
                                 where me_recv_mb_id = '$member[mb_id]' $sql_search $sql_search_unread 
                                 order by me_id desc 
                                 limit $from_record, $one_rows";
                      break;
        }
        $result = sql_query($sql);

        // 목록정보를 $list에 저장하기
        $list = array();
    
        for ($i=0; $row=sql_fetch_array($result); $i++)
        {
            $list[$i] = $row;
        
            switch ($kind) { // 쪽지 목록에 보이는 아이디를 지정
                case 'send' : // 발신함
                              $kind_mb_id = get_member($row['me_recv_mb_id'], "mb_id, mb_name, mb_nick, mb_email, mb_homepage");
                              $list_title = "받는사람";
                              break;
                default : 
                              $kind_mb_id = get_member($row['me_send_mb_id'], "mb_id, mb_name, mb_nick, mb_email, mb_homepage");
                              $list_title = "보낸사람";
            }
        
             if ($config['cf_memo_mb_name']) $kind_mb_id['mb_nick'] = $kind_mb_id['mb_name'];
             $row['mb_nick'] = $kind_mb_id['mb_nick'];
             $row['mb_id'] = $kind_mb_id['mb_id'];
             $row['mb_email'] = $kind_mb_id['mb_email'];
             $row['mb_homepage'] = $kind_mb_id['mb_homepage'];
            
            $name = get_sideview($row['mb_id'], $row['mb_nick'], $row['mb_email'], $row['mb_homepage']);
        
            // 당일인 경우 시간으로 표시함 (읽은시간)
            if (substr($row['me_read_datetime'],0,1) == '0') {
                // 발신함의 안읽은 글의 경우 수신않음(수신후 로그인 기록 있는지 여부)를 위해 회원 정보 더 가져와야함
                if ($kind == "send") {
                    $mb = get_member($row[me_recv_mb_id], "mb_today_login");
                    $time_diff = strtotime($mb['mb_today_login']) - strtotime($row['me_send_datetime']);
                    if ($time_diff <= 0)
                        $read_datetime = '수신 않음';       
                    else
                        $read_datetime = '읽지 않음';
                } else {
                    $read_datetime = '읽지 않음';
                }
                
                $mb = get_member($row[mb_recv_mb_id], "mb_today_login");
    
            } else {
                if (substr($row[me_read_datetime],0,10) == $g4['time_ymd'])
                    $read_datetime = substr($row[me_read_datetime],11,5);
                else
                    $read_datetime = substr($row[me_read_datetime],5,5);
            }
        
            // 당일인 경우 시간으로 표시함 (보낸시간)
            if (substr($row[me_send_datetime],0,10) == $g4['time_ymd'])
                $send_datetime = substr($row[me_send_datetime],11,5);
            else
                $send_datetime = substr($row[me_send_datetime],5,5);
        
            $list[$i][name] = $name;
            if (strlen($row[me_subject]) ==0) // 투명글의 경우에 제목없음으로 표시
                $list[$i][subject] = "제목이 없습니다";
            else
                $list[$i][subject] = strip_tags($row[me_subject]);
            
            // 휴지통의 경우에는 게시글의 출처를 표시
            if ($kind == "trash") {
                if ($row[me_from_kind])
                    $list[$i][subject] = "[" . $row[me_from_kind] . "] " . $list[$i][subject];
                else
                    $list[$i][subject] = $list[$i][subject];
            }

            $list[$i][read_datetime] = $read_datetime;
            $list[$i][send_datetime] = $send_datetime;

            $list[$i][view_href] = "./memo.php?me_id=$row[me_id]&kind=$kind&class=view";
            // 휴지통의 경우에는 게시글의 출처를 표시
            if ($kind == "trash")
                $list[$i][view_href] = $list[$i][view_href] . "&me_from_kind=$row[me_from_kind]";

            $list[$i][me_file] = $row[me_file_local];
        } // end of for loop
    
    } // end of if ($class)
} // end of switch($kind)

// 쪽지5 스킨을 읽어들입니다.
include_once("$g4[memo_skin_path]/memo2.skin.php");

include_once("$g4[path]/tail.sub.php");
?>
