<?
if (!defined('_GNUBOARD_')) exit;

// 현재 접속자수 출력
function connect($skin_dir="")
{
    global $config, $g4;

    if ($g4['session_type'] == "redis") {

        // redis일때만 redis login 관리를 쓴다.
        $redis_login = new Redis();
        $redis_login->connect($g4["rhost"], $g4["rport"]);
        $redis_login->select($g4["rdb1"]);

        // 모든 key를 가져와서 갯수를 세어 줍니다.
        $total_cnt = 0;
        $mb_cnt = 0;
        $admin_cnt = 0;
        $allKeys = $redis_login->keys($g4["rdomain"] . "_login_*");   // all keys will match this.
        foreach ($allKeys as $rkey) {

            $rdat = explode ( "|", $redis_login->get($rkey) );
            $mb_id = $rdat["1"];
            
            if ($redis_login->ttl($rkey) > 0) {
                if ($mb_id !== $config[cf_admin]) {
                    $total_cnt++;
                    if ($mb_id)
                        $mb_cnt++;
                }
            }
        }

        // redis instance connection을 닫아줍니다.
        $redis_login->close();

    } else {

        // 회원, 방문객 카운트
        //$sql = " select sum(IF(mb_id<>'',1,0)) as mb_cnt, count(*) as total_cnt from $g4[login_table]  where mb_id <> '$config[cf_admin]' ";
        //$row = sql_fetch($sql);

        $sql = " select count(*) as total_cnt from $g4[login_table]";
        $row1 = sql_fetch($sql);
        $total_cnt = $row1[total_cnt];

        $sql = " select count(*) as mb_cnt from $g4[login_table] where mb_id <> '' ";
        $row1 = sql_fetch($sql);
        $mb_cnt = $row1[mb_cnt];

        $sql = " select count(*) as admin_cnt from $g4[login_table] where mb_id = '$config[cf_admin]' ";
        $row1 = sql_fetch($sql);
        $admin_cnt = $row1[admin_cnt];
    }

    if ($admin_cnt) {
        $mb_cnt--;
        $total_cnt--;
    }

    $row['mb_cnt'] = $mb_cnt;
    $row['total_cnt'] = $total_cnt;

    if ($skin_dir)
        $connect_skin_path = "$g4[path]/skin/connect/$skin_dir";
    else
        $connect_skin_path = "$g4[path]/skin/connect/$config[cf_connect_skin]";

    ob_start();
    include_once ("$connect_skin_path/connect.skin.php");
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}
?>
