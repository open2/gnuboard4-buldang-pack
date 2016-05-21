<?
// �� ������ ���ο� ���� ������ �ݵ�� ���ԵǾ�� ��
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

// session lock�� ���� ���ؼ� �� ������ �׻� �ݾ��ش�
session_write_close();

if (is_mobile()) {
    require($g4['path'] . '/m/tail.sub.php');

    return;
}
?>

<script type="text/javascript" src="<?=$g4['path']?>/js/wrest.js"></script>

<!-- bootstrap tooltip script -->
<script type="text/javascript">
$(document).ready(function(){
    $(".tooltip-help").tooltip({trigger: 'hover click','placement': 'top'});
});
</script>

<!-- sideview script -->
<script type="text/javascript">
$('.sideview').bind('click',function(e){

    var el=$(this);
    var popover_title = el.attr('alt');
    var _data = "mb_nick="+encodeURI(popover_title)+"&mb_nick2="+encodeURI('<?=$member[mb_nick]?>')+"&bo_table="+encodeURI('<?=$bo_table?>')+"&sfl="+encodeURI('<?=urlencode($sfl)?>');

    e.isDefaultPrevented();

    $.ajax({url: "<?=$g4[bbs_path]?>/ajax_sideview.php", type: "POST", data: _data, 
            success: function(response) {
                el.popover({html: true, content: response, title: '<a onclick="" style="cursor:pointer">'+popover_title+'&nbsp;<i class="fa fa-plus-square"></i></a>'});
                el.popover('show'); 
            }
    });

    el.unbind('click');

});
</script>

<!-- ��â ��� ����ϴ� iframe -->
<iframe width=0 height=0 name='hiddenframe' style='display:none;' title='hidden frame'></iframe>

<? if ($is_admin == "super") { ?><div class="well" style="text-align:center;">RUN TIME : <?=get_microtime()-$begin_time;?><br></div><? } ?>

</body>
</html>
<?
if ($g4['session_type'] == "redis") {
    // redis�϶��� redis login ������ ����.
    $redis_login = new Redis();
    $redis_login->connect($g4["rhost"], $g4["rport"]);
    $redis_login->select($g4["rdb1"]);

    // redis key�� ���� (where�� �ִ� ���� ��� key�� ����� �ݴϴ�)
    $rkey = $g4["rdomain"] . "_login_" . "$remote_addr";
    $rvalue = $remote_addr . "|". $member[mb_id] . "|" . $g4[time_ymdhis] . "|" . $lo_location . "|" . $lo_url . "|" . $lo_referer . "|" . $user_agent;

    // key�� ������ ���� �����;���?
    if ($redis_login->exists($rkey) && $redis_login->get($rkey)) {

        // key�� �ִ� ��� ttl (key�� expire �Ǿ������� üũ)
        if ($redis_login->ttl($rkey) > 0) {

            // �α��������� ������Ʈ
            $redis_login->setex($rkey, 60*$config['cf_login_minutes'], "$rvalue"); // sets key �� value
        } else {
            // expire�� key�� ����
            $redis_login->delete($rkey);
        }
    } else {
        // key�� ���ų� ���� ������ key�� �־��ݴϴ�.
        $redis_login->setex($rkey, 60*$config['cf_login_minutes'], "$rvalue"); // sets key
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
    	sql_query(" delete from $g4[login_table] where lo_datetime < '".date("Y-m-d H:i:s", $g4[server_time] - (60 * $config[cf_login_minutes]))."' ");
    }
}
?>