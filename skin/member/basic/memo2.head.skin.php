<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

// 쪽지 - 기본적인 사항 정의
$table_width           = 680;                   // 100%로 설정을 하면 윈도가 떠오를 때 크기 지정을 깜박하시는 분이 있어요.
$left_menu_width       = 164;                   // 왼쪽 메뉴의 폭
$content_width         = 495;                   // 쪽지 내용창의 폭
$content_inner_width   = 494;                   // 쪽지 내용창 안쪽의 최대 폭
$max_img_width          = $content_width - 50;  // 이미지의 폭

// resize를 위한 넓이를 지정
$board['resize_img_width'] = $max_img_width;
?>

<link rel="stylesheet" href="<?=$memo_skin_path?>/memo2.css" type="text/css">

<!-- sideview를 위해서 -->
<script type='text/javascript' src='<?=$g4[path]?>/js/sideview.js'></script>

<!-- 상단부 여백 설정하기 -->
<table border="0" cellspacing="0" cellpadding="0"><tr><td height="10"></td></tr></table>
</table>



<!-- 메뉴영역 -->
<table width=<?=$table_width?> border="0" cellspacing="0" cellpadding="0"> 

<tr valign=top>
    <td width=10></td> <!-- 좌측의 여백 설정하기 -->
    <td width=<?=$left_menu_width?>> <!-- 좌측 메뉴 -->
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="7" height="7" background="<?=$memo_skin_path?>/img/memo_box_tl.gif"></td>
            <td height="7" background="<?=$memo_skin_path?>/img/memo_line_top.gif"></td>
            <td width="7" height="7" background="<?=$memo_skin_path?>/img/memo_box_tr.gif"></td>
          </tr>
          <tr>
            <td width="7" background="<?=$memo_skin_path?>/img/memo_line_left.gif">&nbsp;</td>
            <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="25" align="center"><img src="<?=$memo_skin_path?>/img/memo_icon01.gif" width="13" height="12" /></td>
                <td width="125" height="25"><strong><a href='<?=$memo_url?>?kind=recv'>받은쪽지함</a></strong> <? if ($total_count_recv_unread > 0) {?><a href='<?=$memo_url?>?kind=recv&unread=only'>(<font color=red><b><?=$total_count_recv_unread?></b></font>)</a><? } ?></td>
              </tr>
              <tr><td height="1" colspan="2" bgcolor="e1e1e1"></td></tr>
              <tr>
                <td align="center"><img src="<?=$memo_skin_path?>/img/memo_icon03.gif" width="16" height="14" /></td>
                <td height="25"><strong><a href='<?=$memo_url?>?kind=send'>보낸쪽지함</a></strong></td>
              </tr>
              <tr><td height="1" colspan="2" bgcolor="e1e1e1"></td></tr>
              <tr>
                <td align="center"><img src="<?=$memo_skin_path?>/img/memo_icon02.gif" width="13" height="13" /></td>
                <td height="25"><strong><a href='<?=$memo_url?>?kind=write'>쪽지보내기</a></strong></td>
              </tr>
              <? if ($is_admin) { ?>
              <tr><td height="1" colspan="2" bgcolor="e1e1e1"></td></tr>
              <tr>
                <td align="center"><img src="<?=$memo_skin_path?>/img/memo_icon02.gif" width="13" height="13" /></td>
                <td height="25"><strong><a href='<?=$memo_url?>?kind=notice'><a href='<?=$memo_url?>?kind=write&option=notice'>공지쪽지 보내기</a></strong>
                </tr>
              <? } ?>
              <tr><td height="1" colspan="2" bgcolor="e1e1e1"></td></tr>
              <tr>
                <td align="center"><img src="<?=$memo_skin_path?>/img/memo_icon04.gif" width="3" height="3" /></td>
                <td height="25"><strong><a href='<?=$memo_url?>?kind=save'>보관한쪽지함</a></strong></td>
              </tr>
              <tr><td height="1" colspan="2" bgcolor="e1e1e1"></td></tr>
              <tr>
                <td align="center"><img src="<?=$memo_skin_path?>/img/memo_icon04.gif" width="3" height="3" /></td>
                <td height="25"><strong><a href='<?=$memo_url?>?kind=notice'>공지쪽지함</a>
                </tr>
<!--
              <tr><td height="1" colspan="2" bgcolor="e1e1e1"></td></tr>
              <tr>
                <td align="center"><img src="<?=$memo_skin_path?>/img/memo_icon02.gif" width="3" height="3" /></td>
                <td height="25"><strong><a href='<?=$memo_url?>?kind=temp'>작성중인쪽지함</a></strong></td>
              </tr>
-->
              <tr>
                <td height="1" colspan="2" bgcolor="e1e1e1"></td>
              </tr>
              <tr>
                <td align="center"><img src="<?=$memo_skin_path?>/img/co_btn_delete.gif" width="3" height="3" /></td>
                <td height="25"><strong><a href='<?=$memo_url?>?kind=trash'>삭제한쪽지함</a></strong></td>
              </tr>
<!--
              <tr><td height="1" colspan="2" bgcolor="e1e1e1"></td></tr>
              <tr>
                <td align="center"><img src="<?=$memo_skin_path?>/img/memo_icon04.gif" width="3" height="3" /></td>
                <td height="25"><a href='<?=$memo_url?>?kind=cafe'>카페쪽지함</a></td>
              </tr>
              <tr>
                <td height="1" colspan="2" bgcolor="e1e1e1"></td>
              </tr>
-->
              <tr><td height="1" colspan="2" bgcolor="e1e1e1"></td></tr>
              <tr>
                <td align="center"><img src="<?=$memo_skin_path?>/img/memo_icon04.gif" width="3" height="3" /></td>
                <td height="25"><strong><a href='<?=$memo_url?>?kind=spam'>스팸쪽지함</a></strong></td>
              </tr>
              <tr><td height="1" colspan="2" bgcolor="ffffff"></td></tr>
            </table></td>
            <td width="7" background="<?=$memo_skin_path?>/img/memo_line_right.gif">&nbsp;</td>
          </tr>
          <tr>
            <td width="7" height="7" background="<?=$memo_skin_path?>/img/memo_box_dl.gif"></td>
            <td height="7" background="<?=$memo_skin_path?>/img/memo_line_down.gif"></td>
            <td width="7" height="7" background="<?=$memo_skin_path?>/img/memo_box_dr.gif"></td>
          </tr>
        </table>

        <? if ($config['cf_memo_notice_memo']) { ?>
        <br>
        <table width="100%" border="0" cellpadding="10" cellspacing="0" style='border-width:1; border-color:#DDDDDD; border-style:solid;'>
        <tr><td width=100%>
        <?=nl2br($config['cf_memo_notice_memo'])?>
        </td></tr></table>
        <? } ?>

        <!-- 좌측 메뉴 사이의 여백 -->
        <table width="100%"><tr><td height="5" colspan="3"></td></tr></table>
        
        <!-- 좌측 두번째 메뉴 -->
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="7" height="7" background="<?=$memo_skin_path?>/img/memo_box3_tl.gif"></td>
            <td height="7" background="<?=$memo_skin_path?>/img/memo_line3_top.gif"></td>
            <td width="7" height="7" background="<?=$memo_skin_path?>/img/memo_box3_tr.gif"></td>
          </tr>

          <tr>
            <td width="7" background="<?=$memo_skin_path?>/img/memo_line3_left.gif">&nbsp;</td>
            <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
                <? if ($config[cf_friend_management]) { ?>
                <tr>
                  <td width="25" align="center"><img src="<?=$memo_skin_path?>/img/memo_icon05.gif" width="19" height="19" /></td>
                  <td width="125" height="25"><strong><a href='<?=$memo_url?>?kind=online'>친구관리</a></strong></td>
                </tr>
                <tr><td height="1" colspan="2" bgcolor="e1e1e1"></td></tr>
                <? } ?>
                <tr>
                  <td align="center"><img src="<?=$memo_skin_path?>/img/friend_online.jpg" width="12" height="11" /></td>
                  <td height="25"><strong>
                  <a href='<?=$memo_url?>?kind=online&fr_type=online'>현재접속자</a>
                  </strong>
                  </td>
                </tr>
                <tr><td height="1" colspan="2" bgcolor="e1e1e1"></td></tr>
                <tr>
                  <td align="center"><img src="<?=$memo_skin_path?>/img/memo_icon06.gif" width="12" height="11" /></td>
                  <td height="25"><strong>
                  <a href='<?=$memo_url?>?kind=memo_group_admin'>그룹관리</a>
                  </strong>
                  </td>
                </tr>
                <tr><td height="1" colspan="2" bgcolor="e1e1e1"></td></tr>
                <tr>
                  <td align="center"><img src="<?=$memo_skin_path?>/img/memo_icon06.gif" width="12" height="11" /></td>
                  <td height="25"><strong>
                  <a href='<?=$memo_url?>?kind=memo_address_book'>주소록</a>
                  </strong>
                  </td>
                </tr>
                <tr><td height="1" colspan="2" bgcolor="e1e1e1"></td></tr>
                <? if ($config[cf_memo_user_config] || $is_admin == "super") { ?>
                <tr>
                  <td align="center"><img src="<?=$memo_skin_path?>/img/btn_c_ok.gif" width="12" height="11" /></td>
                  <td height="25"><strong>
                  <a href='<?=$memo_url?>?kind=memo_config'>쪽지 설정</a>
                  </strong>
                  </td>
                </tr>
                <tr><td height="1" colspan="2" bgcolor="e1e1e1"></td></tr>
                <? } ?>
            </table></td>
            <td width="7" background="<?=$memo_skin_path?>/img/memo_line3_right.gif">&nbsp;</td>
          </tr>

          <tr>
            <td width="7" height="7" background="<?=$memo_skin_path?>/img/memo_box3_dl.gif"></td>
            <td height="7" background="<?=$memo_skin_path?>/img/memo_line3_down.gif"></td>
            <td width="7" height="7" background="<?=$memo_skin_path?>/img/memo_box3_dr.gif"></td>
          </tr>
        </table>

    </td>
    
    <td width=15></td> <!-- 쪽지목록과 내용사이의 여백설정하기 -->

    <td> <!-- 우측 내용부분 -->
