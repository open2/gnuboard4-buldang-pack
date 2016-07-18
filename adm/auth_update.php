<?
$sub_menu = "100110";
include_once("./_common.php");

check_token();

$mb_id = trim($_POST[mb_id]);
$au_menu = $_POST[au_menu];
$r = $_POST[r];
$w = $_POST[w];
$d = $_POST[d];

if ($is_admin != "super")
    alert("최고관리자만 접근 가능합니다.");

$mb = get_member($mb_id);
if (!$mb[mb_id])
    alert("존재하는 회원아이디가 아닙니다."); 

if ($mb[mb_id] == $config[cf_admin])
    alert("최고관리자의 권한은 제한할 수 없습니다."); 

$sql = " insert into $g4[auth_table] 
            set mb_id   = :mb_id,
                au_menu = :au_menu,
                au_auth = '$r,$w,$d' ";
//$result = sql_query($sql, FALSE);
$stmt = $pdo_db->prepare($sql);
$stmt->bindParam(":mb_id", $mb_id);
$stmt->bindParam(":au_menu", $au_menu);
$result = pdo_query($stmt, FALSE);

if (!$result) {
    $sql = " update $g4[auth_table] 
                set au_auth = '$r,$w,$d'
              where mb_id   = :mb_id
                and au_menu = :au_menu ";
    //sql_query($sql);
    $stmt = $pdo_db->prepare($sql);
    $stmt->bindParam(":mb_id", $mb_id);
    $stmt->bindParam(":au_menu", $au_menu);
    $result = pdo_query($stmt);
}

// 불당팩 - 관리자 권한변경 작업내역을 db log에 남깁니다
$sql_2 = " insert into $g4[admin_log_table] 
            set log_datetime = '$g4[time_ymdhis]', log = :log ";
//sql_query($sql);
$stmt = $pdo_db->prepare($sql_2);
$log = "adm/auth_update.php - " . $sql;
$stmt->bindParam(":log", $log);
$result = pdo_query($stmt);

//불당 mb_auth_count를 업데이트
$sql = " select count(*) as cnt from $g4[auth_table] where mb_id = :mb_id ";
//$result = sql_fetch($sql);
$stmt = $pdo_db->prepare($sql);
$stmt->bindParam(":mb_id", $mb_id);
$result = pdo_fetch($stmt, $params);

$sql = " update $g4[member_table] set mb_auth_count = '$result[cnt]' where mb_id = :mb_id ";
//sql_query($sql);
$stmt = $pdo_db->prepare($sql);
$stmt->bindParam(":mb_id", $mb_id);
$result = pdo_query($stmt);

goto_url("./auth_list.php?$qstr");
?>
