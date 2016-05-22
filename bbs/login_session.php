<?php
// tail.sub.php���� �и��� ������ ó��

if ($g4['session_type'] == "redis") {
    // redis�϶��� redis login ������ ����.
    $redis_login = new Redis();
    $redis_login->connect($g4["rhost"], $g4["rport"]);
    $redis_login->select($g4["rdb1"]);

    // redis key�� ���� (where�� �ִ� ���� ��� key�� ����� �ݴϴ�)
    $rkey   = $g4["rdomain"] . "_login_" . "$remote_addr";
    $rvalue = $remote_addr . "|" . $member[mb_id] . "|" . $g4[time_ymdhis] . "|" . $lo_location . "|" . $lo_url . "|" . $lo_referer . "|" . $user_agent;

    // key�� ������ ���� �����;���?
    if ($redis_login->exists($rkey) && $redis_login->get($rkey)) {

        // key�� �ִ� ��� ttl (key�� expire �Ǿ������� üũ)
        if ($redis_login->ttl($rkey) > 0) {

            // �α��������� ������Ʈ
            $redis_login->setex($rkey, 60 * $config['cf_login_minutes'], "$rvalue"); // sets key �� value
        } else {
            // expire�� key�� ����
            $redis_login->delete($rkey);
        }
    } else {
        // key�� ���ų� ���� ������ key�� �־��ݴϴ�.
        $redis_login->setex($rkey, 60 * $config['cf_login_minutes'], "$rvalue"); // sets key
    }

    // redis instance connection�� �ݾ��ݴϴ�.
    $redis_login->close();

} else {
    // ����Ʈ�� �湮�ϰ� �Ǹ� ó�� �ѹ� insert�� �ǰ�, ���Ŀ��� ��� update�� �̷�� ���ϴ�. ���� ������ update�� �����ϰ� �ϴ°� �ӵ��� �� ������ �մϴ�.
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

    // update�� �ȵǴ� ��쿡�� insert�� �մϴ�.
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

        // �ð��� ���� ������ �����Ѵ�
        sql_query(" delete from $g4[login_table] where lo_datetime < '" . date("Y-m-d H:i:s",
                $g4[server_time] - (60 * $config[cf_login_minutes])) . "' ");
    }
}
?>