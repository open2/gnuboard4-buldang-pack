<?
include_once("./_common.php");

$g4[title] = "$config[cf_title] - MyOn";

if (isset($head))
    $head = (int) $head;
else
    $head = 1;
$rows = (int) $rows;

if ($member[mb_id]) 
    ;
else 
    alert("MyOn�� ȸ���� ���� ���� �Դϴ�.\\n\\nȸ���̽ö�� �α��� �� �̿��� ���ʽÿ�.", "./login.php?url=".urlencode("$g4[bbs_path]/myon.php?head=$head"));

if ($head)
    include_once("./_head.php");
else
    include_once("../head.sub.php");

// ��Ų�� $_GET���� ���� �Ѱ��ش�
$myon_skin = strip_tags($myon_skin);
if ($myon_skin)
    $skin = $myon_skin;
else
    $skin = "basic";

if ($rows > 0) {
    if ($rows > 50)
        $rows = 50;
}
else
    $rows = 20;

/* �Ҵ��� 1.2���� ��� �Լ��� �̵��߽��ϴ� */

$myon_skin_path = "$g4[path]/skin/myon/$skin";

include_once("$myon_skin_path/myon.skin.php");

if ($head)
    include_once("./_tail.php");
else
    include_once("../tail.sub.php");
?>
