<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

// 쪽지 - 기본적인 사항 정의
$table_width      = 680;              // 100%로 설정을 하면 윈도가 떠오를 때 크기 지정을 깜박하시는 분이 있어요.
$left_menu_width  = 164;              // 왼쪽 메뉴의 폭
$content_width    = 458;              // 쪽지 내용창의 폭
$max_img_width = $content_width - 40; // 이미지의 폭
                   
// 쪽지2 프로그램의 location을 정의, $_SERVER[PHP_SELF]를 안쓰기 위해서
$memo_url = $g4[bbs_path] . "/memo.php";
?>

<script type="text/javascript"> 
<!-- // 회원ID 찾기  
function popup_id(frm_name, ss_id, top, left) 
{ 
    url = './write_id.php?frm_name='+frm_name+'&ss_id='+ss_id; 
    opt = 'scrollbars=yes,width=320,height=470,top='+top+',left='+left; 
    window.open(url, "write_id", opt); 
} 
//--> 
</script>
<link rel="stylesheet" href="<?=$member_skin_path?>/memo4_style.css" type="text/css">

<div class="memo">
<!-- Left Menu Start -->
    <div class="lbox">
        <div class="lbox1">
            <div class="inner">
                <ul>
                    <li class="icon01">
                        <a href='<?=$memo_url?>?kind=recv'>받은쪽지함</a>
                      <? if ($total_count_recv_unread > 0) {?>
                        <a href='<?=$memo_url?>?kind=recv&unread=only'>(<span class="red"><?=$total_count_recv_unread?></span>)</a><? } ?>
                    </li>
                    <li class="icon03">
                        <a href='<?=$memo_url?>?kind=send'>보낸쪽지함</a>
                      <? if ($total_count_recv_unread > 0) {?>
                        <a href='<?=$memo_url?>?kind=recv&unread=only'>(<span class="red"><?=$total_count_recv_unread?></span>)</a><? } ?>
                    </li>
                    <li class="icon02"><a href='<?=$memo_url?>?kind=write'>쪽지보내기</a></li>
                    <? if ($is_admin) { ?><li class="icon02"><a href='<?=$memo_url?>?kind=write&option=notice'>공지쪽지 보내기</a></li><? } ?>
                    <li class="icon04"><a href='<?=$memo_url?>?kind=save'>보관한쪽지함</a></li>
                    <li class="icon04"><a href='<?=$memo_url?>?kind=notice'>공지쪽지함</a></li>
                    <!-- <li class="icon02"><a href='<?=$memo_url?>?kind=temp'>작성중인쪽지함</a></li> -->
                    <li class="co_btn_delete"><a href='<?=$memo_url?>?kind=trash'>삭제한쪽지함</a></li>
                    <!-- <li class="icon04"><a href='<?=$memo_url?>?kind=cafe'>카페쪽지함</a></li> -->
                    <li class="icon04"><a href='<?=$memo_url?>?kind=spam'>스팸쪽지함</a></li>
                </ul>
            </div>
        </div>
        <div class="lbox2">
            <div class="inner">
                <ul>
                    <? if ($config[cf_friend_management]) { ?><li class="icon05"><a href='<?=$memo_url?>?kind=online'>친구관리</a></li><? } ?>
                    <li class="friend_online"><a href='<?=$memo_url?>?kind=online&fr_type=online'>현재접속자</a></li>
                    <li class="icon06"><a href='<?=$memo_url?>?kind=memo_group_admin'>그룹관리</a></li>
                    <li class="icon06"><a href='<?=$memo_url?>?kind=memo_address_book'>주소록</a></li>
                    <li class="btn_c_ok"><a href='<?=$memo_url?>?kind=memo_config'>쪽지 설정</a></li>
                </ul>
            </div>
        </div>
        <? if ($config['cf_memo_notice_memo']) { ?><div class="notice"><?=$config['cf_memo_notice_memo']?></div><? } ?>
    </div>
<!-- Left Menu End -->
    <div class="rbox">
    <!-- Right Box Start -->
      <? 
      if ($class == "view") { // 쪽지 보기
          include_once("$member_skin_path/memo2_view.skin.php");
          
      } else { // 쪽지 보기가 아닌경우
        
      switch ($kind) {
        case 'write' : 
              include_once("$member_skin_path/memo2_write.skin.php"); 
              break;
        case 'online' :
              include_once("$member_skin_path/memo2_online.skin.php"); 
              break;        
        case 'memo_group' :
              include_once("$member_skin_path/memo2_group_member.skin.php"); 
              break;
        case 'memo_group_admin' :
              include_once("$member_skin_path/memo2_group_admin.skin.php"); 
              break;
        case 'memo_address_book' :
              include_once("$member_skin_path/memo2_memo_address_book.skin.php"); 
              break;
        case 'memo_config' :
              include_once("$member_skin_path/memo2_config.skin.php"); 
              break;
        default :
              include_once("$member_skin_path/memo2_list.skin.php"); 
      }

      } ?>
    <!-- Right Box End -->
    </div>
</div>

<!-- 하단부 공지사항 영역 -->
<? include_once("$member_skin_path/memo2_bottom.skin.php"); ?>

<script language="JavaScript">
function all_checked(sw) {
    var f = document.fboardlist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_me_id[]")
            f.elements[i].checked = sw;
    }
}

function check_confirm(str) {
    var f = document.fboardlist;
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_me_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(str + "할 쪽지를 하나 이상 선택하세요.");
        return false;
    }
    return true;
}

// 선택한 게시물 삭제
function select_delete() {
    var f = document.fboardlist;

    str = "삭제";
    if (!check_confirm(str))
        return;

    if (!confirm("선택한 쪽지를 정말 "+str+" 하시겠습니까?\n\n한번 "+str+"한 자료는 복구할 수 없습니다"))
        return;

    f.action = "./memo2_form_delete.php";
    f.submit();
}

// 모든 게시물 삭제
function all_delete_trash() {
    var f = document.fboardlist;

    str = "삭제";

    if (!confirm("모든 쪽지를 정말 "+str+" 하시겠습니까?\n\n한번 "+str+"한 자료는 복구할 수 없습니다"))
        return;

    f.action = "./memo2_form_delete_all_trash.php";
    f.submit();
}
</script>