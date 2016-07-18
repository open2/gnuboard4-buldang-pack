<?
include_once("./_common.php");

// 변수의 태그를 없애줍니다
$id = strip_tags($id);

// 등록에 관련된 설정을 추가로 읽어 들입니다.
$config = get_config("reg");

switch ($id) {
    case 'disclaimer' :
        $g4['title'] = "책임한계와 법적고지";
        $wr_content = nl2br(implode("", file("./disclaimer.php")));
        break;
    case 'rejection' :
        $g4['title'] = "이메일주소무단수집거부";
        $wr_content = nl2br(implode("", file("./rejection.php")));
        break;
    case 'service' :
    default :
        $g4['title'] = "이용약관";
        $wr_content = nl2br(stripslashes($config[cf_stipulation]));
        break;
    case 'privacy' :
    default :
        $g4['title'] = "개인정보취급방침";
        $wr_content = nl2br(stripslashes($config[cf_privacy]));
        break;
}
include_once("./_head.php");
?>
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title"><?=$g4['title']?></h3>
  </div>
  <div class="panel-body">
    <?=$wr_content?>
  </div>
</div>
<?
include_once("./_tail.php");
?>
