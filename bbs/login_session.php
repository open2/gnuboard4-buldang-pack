<?php
// tail.sub.php에서 분리한 접속자 처리

if ($g4['session_type'] == "redis") {
    // redis일때만 redis login 관리를 쓴다.
    $redis_login = new Redis();
    $redis_login->connect($g4["rhost"], $g4["rport"]);
    $redis_login->select($g4["rdb1"]);

    // redis key를 정의 (where에 있는 것을 묶어서 key를 만들어 줍니다)
    $rkey   = $g4["rdomain"] . "_login_" . "$remote_addr";
    $rvalue = $remote_addr . "|" . $member[mb_id] . "|" . $g4[time_ymdhis] . "|" . $lo_location . "|" . $lo_url . "|" . $lo_referer . "|" . $user_agent;

    // key가 있으면 값을 가져와야죠?
    if ($redis_login->exists($rkey) && $redis_login->get($rkey)) {

        // key가 있는 경우 ttl (key가 expire 되었는지를 체크)
        if ($redis_login->ttl($rkey) > 0) {

            // 로그인정보를 업데이트
            $redis_login->setex($rkey, 60 * $config['cf_login_minutes'], "$rvalue"); // sets key → value
        } else {
            // expire된 key는 삭제
            $redis_login->delete($rkey);
        }
    } else {
        // key가 없거나 값이 없으면 key를 넣어줍니다.
        $redis_login->setex($rkey, 60 * $config['cf_login_minutes'], "$rvalue"); // sets key
    }

    // redis instance connection을 닫아줍니다.
    $redis_login->close();

} else {
    // 사이트를 방문하게 되면 처음 한번 insert가 되고, 이후에는 계속 update가 이루어 집니다. 따라서 무조건 update를 먼저하게 하는게 속도를 더 빠르게 합니다.
    //$tmp_sql = " update $g4[login_table] set mb_id = '$member[mb_id]', lo_datetime = '$g4[time_ymdhis]', lo_location = '$lo_location', lo_url = '$lo_url', lo_referer='$referer', lo_agent='$user_agent' where lo_ip = '$remote_addr' ";
    //sql_query($tmp_sql, FALSE);

    $stmt = $pdo_db->prepare("update $g4[login_table] set mb_id = :mb_id, lo_datetime = '$g4[time_ymdhis]', lo_location = :lo_location, lo_url = :lo_url, lo_referer=:lo_referer, lo_agent=:lo_agent where lo_ip = :lo_ip ");
    $stmt->bindParam(":mb_id", $member[mb_id]);
    $stmt->bindParam(":lo_location", $lo_location);
    $stmt->bindParam(":lo_url", $lo_url);
    $stmt->bindParam(":lo_referer", $referer);
    $stmt->bindParam(":lo_agent", $user_agent);
    $stmt->bindParam(":lo_ip", $remote_addr);
    $result = pdo_query($stmt);

    // update가 안되는 경우에는 insert를 합니다.
    if ($stmt->rowCount() == 0) {
        /*
      	$tmp_sql = " insert into $g4[login_table]
      	                set
    	                      lo_ip = '$remote_addr',
    	                      mb_id = '$member[mb_id]',
    	                      lo_datetime = '$g4[time_ymdhis]',
    	                      lo_location = '$lo_location',
    	                      lo_url = '$lo_url',
    	                      lo_referer = '$referer',
    	                      lo_agent = '$user_agent'
                            ";
      	sql_query($tmp_sql, FALSE);
        */

        $stmt = $pdo_db->prepare(" insert into $g4[login_table] set lo_ip = :lo_ip, mb_id = :mb_id, lo_datetime = '$g4[time_ymdhis]', lo_location = :lo_location, lo_url = :lo_url, lo_referer = :lo_referer, lo_agent = :lo_agent ");
        $stmt->bindParam(":lo_ip", $remote_addr);
        $stmt->bindParam(":mb_id", $member[mb_id]);
        $stmt->bindParam(":lo_location", $lo_location);
        $stmt->bindParam(":lo_url", $lo_url);
        $stmt->bindParam(":lo_referer", $referer);
        $stmt->bindParam(":lo_agent", $user_agent);
        $result = pdo_query($stmt, false);

        // 시간이 지난 접속은 삭제한다
        sql_query(" delete from $g4[login_table] where lo_datetime < '" . date("Y-m-d H:i:s",
                $g4[server_time] - (60 * $config[cf_login_minutes])) . "' ");
    }
}
?>