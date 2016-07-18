<?
if ( ! defined('_GNUBOARD_')) {
    exit;
}

/**
 * Whats On 읽지 않은 갯수
 *   - 중복 실행해도 쿼리는 1회만 실행되도록 함.
 *
 * @param string $mb_id
 *
 * @return int
 */
function whatson_count($mb_id)
{
    global $g4, $member, $config;

    if ( ! isset($g4['whatson_count'])) {
        $sql    = " select count(*) as cnt from $g4[whatson_table] where mb_id='$mb_id' and wo_status = 0 ";
        $result = sql_fetch($sql);

        $g4['whatson_count'] = (int)$result['cnt'];
    }

    return $g4['whatson_count'];
}

// 그누보드 왔숑~ 추출
function whatson(
    $skin_dir = "",
    $rows = 10,
    $subject_len = 25,
    $page = 1,
    $options = "",
    $target = "",
    $check = "",
    $head = 1
) {
    global $g4, $member, $config;

    if ($skin_dir) {
        $whatson_skin_path = "$g4[path]/skin/whatson/$skin_dir";
    } else {
        $whatson_skin_path = "$g4[path]/skin/whatson/basic";
    }

    if ($target) {
        $target_link = "target=" . $target;
    }

    $list = array();

    // 비회원의 경우에는 왔숑~할 것이 없습니다.
    if ( ! $member[mb_id]) {
        return;
    }

    // 왔숑~의 전체 갯수를 구합니다.
    $sql    = " select count(*) as cnt from $g4[whatson_table] where mb_id='$member[mb_id]' ";
    $result = sql_fetch($sql);

    $total_count = $result[cnt];

    $total_page = ceil($total_count / $rows);  // 전체 페이지 계산

    $from_record = ($page - 1) * $rows; // 시작 열을 구함
    $limit_sql   = " limit $from_record, $rows ";

    if ( ! $head || $check == 1) {
        $write_pages    = get_paging($config[cf_write_pages], $page, $total_page,
            "$g4[bbs_path]/whatson.php?head=$head&rows=$rows&check=$check&page=");
        $write_pages_xs = get_paging($config[cf_write_pages_xs], $page, $total_page,
            "$g4[bbs_path]/whatson.php?head=$head&rows=$rows&check=$check&page=");
    }

    $sql    = " select * from $g4[whatson_table] where mb_id='$member[mb_id]' order by wo_datetime desc $limit_sql ";
    $result = sql_query($sql);

    // 결과값을 $list에 넣습니다. 스킨 코드가 심플하게 되게
    for ($i = 0; $row = sql_fetch_array($result); $i++) {
        $list[$i] = $row;

        if ($check == 1) {
            $list[$i][subject] = conv_latest(strip_tags(htmlspecialchars_decode($row[wr_subject])));
        } else {
            $list[$i][subject] = conv_latest(cut_str(strip_tags(htmlspecialchars_decode($row[wr_subject])),
                $subject_len));
        }

        $list[$i][url]      = whatson_click_url($row);
        $list[$i][datetime] = get_datetime($row[wo_datetime]);
    }

    ob_start();
    include "$whatson_skin_path/whatson.skin.php";
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}

/**
 * Whats On 클릭시 이동할 주소
 *
 * @param array $whatson
 *
 * @return null|string
 */
function whatson_click_url($whatson)
{
    global $g4;

    $url = null;

    if ($whatson[wo_type] === 'memo') {
        $url = "$g4[path]/bbs/memo.php?me_id=$whatson[wr_id]&kind=$whatson[bo_table]&class=view";
    } else {
        if ($whatson[bo_table] && $whatson[wr_id]) {
            $url = "$g4[path]/$whatson[bo_table]/$whatson[wr_id]";
        }
        if ($whatson[comment_id]) {
            $url .= "#c_" . $whatson[comment_id];
        }
    }

    return $url;
}

/**
 * Whats On 읽음 처리
 *   - 대상 글이나 쪽지가 존재하지 않을 경우 삭제
 *
 * @param string $mb_id 수신 회원 ID
 * @param int $wo_id
 */
function whatson_read($mb_id, $wo_id)
{
    global $g4;

    // 이시점에서 왔숑~의 글이 그대로 있는지 확인할 필요가 있습니다.
    // 코멘트의 경우, 경우의 수가 너무 많아서 왔숑의 처리를 안하고, 기타 사유로 글이 없어지기도 하거든요.
    $sql = " select * from $g4[whatson_table] where wo_id = '$wo_id' and mb_id = '$mb_id' ";
    $wo  = sql_fetch($sql);
    if ($wo[wo_type] === 'memo') {
        // 메모 존재하는지 확인
        $memo_table = $g4['table_prefix'] . 'memo_' . $wo['bo_table'];
        $memo_count = pdo(
            "select count(*) from $memo_table where me_id=?",
            array(
                $wo[wr_id]
            )
        )->fetchColumn(0);
        if ($memo_count == 0) {
            sql_query(" delete from $g4[whatson_table] where wo_id = '$wo_id' and mb_id = '$mb_id' ");
        }
    } else {
        $tmp_write_table = $g4['write_prefix'] . $wo[bo_table];  // 게시판 테이블 전체이름

        // 선택한 왔숑~의 원글이 존재하는지를 확인
        $sql       = " select * from $tmp_write_table where wr_id='$wo[wr_id]' ";
        $wr_result = sql_fetch($sql);

        if ( ! $wr_result[wr_id]) {
            sql_query(" delete from $g4[whatson_table] where wo_id = '$wo_id' and mb_id = '$mb_id' ");
        }

        // 코멘트의 경우, 본인의 코멘트가 있는지 확인
        if ($wo['comment_id']) {
            $sql       = " select * from $tmp_write_table where wr_id='$wo[comment_id]' ";
            $co_result = sql_fetch($sql);

            if ( ! $co_result[wr_id]) {
                sql_query(" delete from $g4[whatson_table] where wo_id = '$wo_id' and mb_id = '$mb_id' ");
            }
        }
    }

    if ($wo[status] != 1) {
        $sql = " update $g4[whatson_table] set wo_status=1 where wo_id = '$wo_id' and mb_id = '$mb_id' ";
        sql_query($sql);
    }
}

/**
 * Whats On 발송 - 댓글
 *   - 이미 등록된 댓글은 wo_count 만 증가
 *
 * @param string $mb_id 수신 회원 ID
 * @param string $wr_subject 제목
 * @param string $bo_table 게시판 테이블
 * @param int $wr_id 게시판 ID
 * @param int $comment_id 코멘트 ID
 * @param string $sender_nick 발송 회원 닉네임
 * @param bool $is_reply false: 댓글, true: 댓글 답변
 */
function whatson_send_comment($mb_id, $wr_subject, $bo_table, $wr_id, $comment_id, $sender_nick, $is_reply = false)
{
    global $g4;

    $wr_subject = get_text(strip_tags($wr_subject));

    $q      = "select wo_id from {$g4['whatson_table']} " .
              "where bo_table=:bo_table and wr_id=:wr_id and mb_id=:mb_id and wo_type='write_comment'";
    $params = array(
        ':bo_table' => $bo_table,
        ':wr_id'    => $wr_id,
        ':mb_id'    => $mb_id,
    );

    // 댓글 답변시에는 comment_id 기준 추가
    if ($is_reply) {
        $q .= " and comment_id=:comment_id";
        $params[':comment_id'] = $comment_id;
    }
    $whatson = pdo(
        $q,
        $params
    )->fetch();

    if ($whatson) {
        pdo(
            "update {$g4['whatson_table']} set " .
            "wr_subject=:wr_subject, " .
            "wo_count=wo_count+1, " .
            "wo_datetime='$g4[time_ymdhis]' " .
            "where wo_id=:wo_id",
            array(
                ':wr_subject' => $wr_subject,
                ':wo_id'      => $whatson['wo_id'],
            )
        );
        $wo_id = $whatson['wo_id'];
    } else {
        pdo_create($g4['whatson_table'], array(
            'mb_id'       => $mb_id,
            'wr_subject'  => $wr_subject,
            'wo_type'     => 'write_comment',
            'wo_count'    => '1',
            'wo_datetime' => $g4[time_ymdhis],
            'bo_table'    => $bo_table,
            'wr_id'       => $wr_id,
            'comment_id'  => $comment_id,
        ));
        $wo_id = pdo_last_insert_id();
    }

    require_once(G4_PHP79_PATH . "/lib/pushes.php");
    if ($is_reply) {
        $title = $sender_nick . '님의 새로운 댓글 답변입니다.';
    } else {
        $title = $sender_nick . '님의 새로운 댓글입니다.';
    }
    push_queue($mb_id, $wo_id, $title);
}

/**
 * Whats On 발송 - 답변
 *   - 이미 등록된 답변은 wo_count 만 증가
 *
 * @param string $mb_id 수신 회원 ID
 * @param string $wr_subject 제목
 * @param string $bo_table 게시판 테이블
 * @param int $wr_id 게시판 ID
 * @param string $sender_nick 발송 회원 닉네임
 */
function whatson_send_reply($mb_id, $wr_subject, $bo_table, $wr_id, $sender_nick)
{
    global $g4;

    $wr_subject = get_text(strip_tags($wr_subject));

    $q       = "select wo_id from {$g4['whatson_table']} " .
               "where bo_table=:bo_table and wr_id=:wr_id and mb_id=:mb_id and wo_type='write_reply'";
    $params  = array(
        ':bo_table' => $bo_table,
        ':wr_id'    => $wr_id,
        ':mb_id'    => $mb_id,
    );
    $whatson = pdo(
        $q,
        $params
    )->fetch();

    if ($whatson) {
        pdo(
            "update {$g4['whatson_table']} set " .
            "wr_subject=:wr_subject, " .
            "wo_count=wo_count+1, " .
            "wo_datetime='$g4[time_ymdhis]' " .
            "where wo_id=:wo_id",
            array(
                ':wr_subject' => $wr_subject,
                ':wo_id'      => $whatson['wo_id'],
            )
        );
        $wo_id = $whatson['wo_id'];
    } else {
        pdo_create($g4['whatson_table'], array(
            'mb_id'       => $mb_id,
            'wr_subject'  => $wr_subject,
            'wo_type'     => 'write_reply',
            'wo_count'    => '1',
            'wo_datetime' => $g4[time_ymdhis],
            'bo_table'    => $bo_table,
            'wr_id'       => $wr_id,
        ));
        $wo_id = pdo_last_insert_id();
    }

    require_once(G4_PHP79_PATH . "/lib/pushes.php");
    $title = $sender_nick . '님의 새로운 답변입니다.';
    push_queue($mb_id, $wo_id, $title);
}

/**
 * Whats On 발송 - 쪽지
 *
 * @param string $mb_id 수신 회원 ID
 * @param string $wr_subject 제목
 * @param string $bo_table 쪽지 kind
 * @param int $wr_id 쪽지 ID
 * @param string $sender_nick 발송 회원 닉네임
 */
function whatson_send_memo($mb_id, $wr_subject, $bo_table, $wr_id, $sender_nick)
{
    global $g4;

    $wr_subject = get_text(strip_tags($wr_subject));

    pdo_create($g4['whatson_table'], array(
        'mb_id'       => $mb_id,
        'wr_subject'  => $wr_subject,
        'wo_type'     => 'memo',
        'wo_count'    => '1',
        'wo_datetime' => $g4[time_ymdhis],
        'bo_table'    => $bo_table,
        'wr_id'       => $wr_id,
    ));
    $wo_id = pdo_last_insert_id();

    require_once(G4_PHP79_PATH . "/lib/pushes.php");
    $title = $sender_nick . '님의 새로운 쪽지입니다.';
    push_queue($mb_id, $wo_id, $title);
}

?>
