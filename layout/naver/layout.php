<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

/* mnb 정의
참조문서 : http://html.nhncorp.com/uio_factory/ui_pattern/lnb/1

NHN의 메뉴 java script는 사용하지 않습니다.
이것을 쓰면 애니메이션도 이쁘고 하지만, 선택된 메뉴의 class가 없어지고,
SUB 메뉴도 안쓰기 때문입니다.
<script type="text/javascript" src="<?=$g4[layout_skin_path]?>/menu.naver.js"></script>

submenu가 있는 경우, span에 class="i"가 들어가야 합니다.
<li><a class="m10" id="qna" href="http://www.bugsboard.co.kr" target=_new><span>벅스4<span class="i"></span></span></a></a></li>
*/

$mnb_arr[] = array('id'=>'home', 'name'=>'홈으로', 'href'=>"$g4[url]" );
if ($is_member) {
    $mnb_arr[] = array('id'=>'myon', 'name'=>'MyOn', 'href'=>"$g4[bbs_path]/whatson.php?rows=30&check=1&mnb=myon" );

    // 나의 게시판을 $snb로 정의해 줍니다.
    $my_menu = array();
    $sql = "select m.bo_table, b.bo_subject from $g4[my_menu_table] as m left join $g4[board_table] as b on m.bo_table = b.bo_table where mb_id = '$member[mb_id]'";
    $qry = sql_query($sql);
    while ($row = sql_fetch_array($qry)) {
        $snb_arr[myboard][] = array('id'=>"{$row[bo_table]}", 'name'=>"{$row[bo_subject]}");
    }

    // 내가 방문한 게시판을 $snb로 정의해 줍니다.
    $sql = " select b.bo_table, b.bo_subject, a.my_datetime from $g4[my_board_table] a left join $g4[board_table] b on a.bo_table = b.bo_table
              where a.mb_id = '$member[mb_id]' group by b.bo_table order by a.my_datetime desc limit 0, 10 ";
    $result = sql_query($sql);
    for ($i=0; $row = sql_fetch_array($result); $i++) {
        $bo_subject = cut_str($row[bo_subject], 20);
        $snb_arr[myvisit][] = array('id'=>"$row[bo_table]", 'name'=>"$bo_subject");
    }

    // 기타 
    $snb_arr[myon][] = array('id'=>'whatson', 'name'=>'Whats On', 'href'=>"$g4[bbs_path]/whatson.php?rows=30&check=1");
    if ($member[mb_level] >= 3 && $g4['member_suggest_join'])
        $snb_arr[myon][] = array('id'=>'suggest', 'name'=>'회원가입추천', 'href'=>"$g4[path]/plugin/recommend/");
    $snb_arr[myon][] = array('id'=>'bar', 'type'=>'bar', 'style'=>'height:1px; color:#e9e9e9; background:#e9e9e9; font-size:1em; border:0;');
    $snb_arr[myon][] = array('id'=>'mypost', 'name'=>'나의 최근 게시글', 'href'=>"$g4[bbs_path]/new.php?gr_id=&mb_id=$member[mb_id]");
    $snb_arr[myon][] = array('id'=>'mycomment', 'name'=>'내가올린코멘트', 'href'=>"$g4[bbs_path]/new.php?gr_id=&mb_id=$member[mb_id]&view_type=c");
    $snb_arr[myon][] = array('id'=>'my_reading', 'name'=>'내가 읽은 글', 'href'=> "$g4[path]/modules/my_read_list.php"  );
    $snb_arr[myon][] = array('id'=>'scrap', 'name'=>'스크랩', 'href'=>"$g4[bbs_path]/scrap.php?head_on=1");
    $snb_arr[myon][] = array('id'=>'bar', 'type'=>'bar', 'style'=>'height:1px; color:#e9e9e9; background:#e9e9e9; font-size:1em; border:0;');
    $snb_arr[myon][] = array('id'=>'gooded', 'name'=>'추천된 글', 'href'=>"$g4[bbs_path]/my_good_ed.php?head_on=1");
    $snb_arr[myon][] = array('id'=>'nogooded', 'name'=>'비추천된 글', 'href'=>"$g4[bbs_path]/my_good_ed.php?w=nogood&head_on=1");
    $snb_arr[myon][] = array('id'=>'good', 'name'=>'내가 추천한 글', 'href'=>"$g4[bbs_path]/my_good.php?head_on=1");
    $snb_arr[myon][] = array('id'=>'nogood', 'name'=>'내가 비추천한 글', 'href'=>"$g4[bbs_path]/my_good.php?w=nogood&head_on=1");
    $snb_arr[myon][] = array('id'=>'bar', 'type'=>'bar', 'style'=>'height:1px; color:#e9e9e9; background:#e9e9e9; font-size:1em; border:0;');
    if ($config[cf_use_recycle])
        $snb_arr[myon][] = array('id'=>'trash', 'name'=>'휴지통', 'href'=>"$g4[bbs_path]/recycle_list.php");
    $snb_arr[myon][] = array('id'=>'intercept', 'name'=>'신고된 게시글', 'href'=> "$g4[bbs_path]/singo_search.php"  );
    $snb_arr[myon][] = array('id'=>'1to1_bkup', 'name'=>'옮겨진 내글들' );
    //$snb_arr[myon][] = array('id'=>'hidden_comment_search', 'name'=>'딴지된 게시글', 'href'=> "$g4[bbs_path]/hidden_comment_search.php"  );

}
$mnb_arr[] = array('id'=>'talk', 'name'=>'토크' );
$mnb_arr[] = array('id'=>'tips', 'name'=>'개발팁' );
$mnb_arr[] = array('id'=>'gnu4', 'name'=>'그누보드4' );
$mnb_arr[] = array('id'=>'gnu4_b4', 'name'=>'불당팩' );
$mnb_arr[] = array('id'=>'mart', 'name'=>'쇼핑.소셜' );
$mnb_arr[] = array('id'=>'gblog', 'name'=>'지블로그' );
$mnb_arr[] = array('id'=>'club2', 'name'=>'클럽2' );
$mnb_arr[] = array('id'=>'android', 'name'=>'모바일' );
$mnb_arr[] = array('id'=>'yc4', 'name'=>'영카트4' );
$mnb_arr[] = array('id'=>'bugs4', 'name'=>'벅스4', 'new'=>'1', 'href'=>'http://www.bugsboard.co.kr' );
$mnb_arr[] = array('id'=>'info', 'name'=>'오픈코드', 'hidden'=>'1' );

// snb 들여 쓰기 정의, $snb_arr에 공통으로 정의되는 다른 스탈이 있으면 그것도 더 추가하면 됩니다.
$snb_indent = "text-align:left;margin-left:20px;";

/*
snb - 좌측 side 메뉴의 정의

$snb_arr[id]의 id에는 위의 $mnb_arr의 id 값을 넣어주는데, $mnb가 연동 안될 경우에는 맘대로 넣으면 됩니다.

$snb_arr의 id는 그냥 임의로 쓰면 됩니다. 
중복 되면 문제 될 수도 있으니 중복 안되게 "$mnb_내맘대로" 와 같이 하는게 편하지만,
구분하기 좋게 이름 지으면 더 좋습니다.
*/
$snb_arr[talk][] = array('id'=>'talk_check', 'name'=>'출석체크', 'href'=>"$g4[plugin_path]/attendance/attendance.php" );
$snb_arr[talk][] = array('id'=>'guestbook', 'name'=>'방명록', 'href'=>"$g4[plugin_path]/guestbook/guestbook.php" );
$snb_arr[talk][] = array('id'=>'notice', 'style'=>'font-weight: bold');
$snb_arr[talk][] = array('id'=>'talk_best', 'name'=>'베스트글', 'href'=>"$g4[bbs_path]/good_list.php" );
$snb_arr[talk][] = array('id'=>'talk_new', 'name'=>'최근게시글', 'href'=>"$g4[bbs_path]/new.php" );
$snb_arr[talk][] = array('id'=>'bar', 'type'=>'bar', 'style'=>'height:1px; color:#e9e9e9; background:#e9e9e9; font-size:1em; border:0;');
$snb_arr[talk][] = array('id'=>'qna', 'name'=>'잡담나누기');
$snb_arr[talk][] = array('id'=>'g4_100', 'name'=>'그누보드100일완성');
$snb_arr[talk][] = array('id'=>'g4_books', 'name'=>'그누보드참고서');
$snb_arr[talk][] = array('id'=>'gnu4_pack_req', 'name'=>'불당팩버그및개선');
$snb_arr[talk][] = array('id'=>'gnu4_pack_qna', 'name'=>'그누보드묻고답하기');
$snb_arr[talk][] = array('id'=>'bar', 'type'=>'bar', 'style'=>'height:1px; color:#e9e9e9; background:#e9e9e9; font-size:1em; border:0;');
$snb_arr[talk][] = array('id'=>'sitetips', 'name'=>'사이트개발운영');
$snb_arr[talk][] = array('id'=>'biz', 'name'=>'비즈니스참고자료');
$snb_arr[talk][] = array('id'=>'budongsan', 'name'=>'머니테크');
$snb_arr[talk][] = array('id'=>'gabia', 'name'=>'가비아', 'img'=>"$g4[path]/img/banner/gabia.gif", 'href'=>"http://www.gabia.com", 'new'=>'1' );

$snb_arr[test][] = array('id'=>'talk_test', 'name'=>'테스트', 'href'=>"$g4[bbs_path]/board.php?bo_table=test2" );
$snb_arr[test][] = array('id'=>'talk_test2', 'name'=>'테스트2', 'href'=>"$g4[bbs_path]/board.php?bo_table=test" );
$snb_arr[test][] = array('id'=>'test_banner', 'name'=>'배너 테스트');

$snb_arr[tips][] = array('id'=>'tips_linux_tips', 'name'=>'Linux', 'href'=>"$g4[bbs_path]/board.php?bo_table=linux_tips" );
$snb_arr[tips][] = array('id'=>'tips_apache_tips', 'name'=>'Apache', 'href'=>"$g4[bbs_path]/board.php?bo_table=apache_tips" );
$snb_arr[tips][] = array('id'=>'tips_mysql_tips', 'name'=>'MySQL', 'href'=>"$g4[bbs_path]/board.php?bo_table=mysql_tips" );
$snb_arr[tips][] = array('id'=>'tips_php_tips', 'name'=>'PHP', 'href'=>"$g4[bbs_path]/board.php?bo_table=php_tips" );
$snb_arr[tips][] = array('id'=>'tips_html_tips', 'name'=>'HTML', 'href'=>"$g4[bbs_path]/board.php?bo_table=html_tips" );
$snb_arr[tips][] = array('id'=>'tips_html5_tips', 'name'=>'HTML5', 'href'=>"$g4[bbs_path]/board.php?bo_table=html5_tips" );
$snb_arr[tips][] = array('id'=>'tips_css', 'name'=>'CSS', 'href'=>"$g4[bbs_path]/board.php?bo_table=css" );
$snb_arr[tips][] = array('id'=>'tips_javascript_tips', 'name'=>'Java Script', 'href'=>"$g4[bbs_path]/board.php?bo_table=javascript_tips" );
$snb_arr[tips][] = array('id'=>'tips_jquery_tips', 'name'=>'jQuery', 'href'=>"$g4[bbs_path]/board.php?bo_table=jquery_tips" );
$snb_arr[tips][] = array('id'=>'tips_ajax', 'name'=>'AJAX', 'href'=>"$g4[bbs_path]/board.php?bo_table=ajax" );
$snb_arr[tips][] = array('id'=>'tips_other_tips', 'name'=>'기타 팁들', 'href'=>"$g4[bbs_path]/board.php?bo_table=other_tips" );
$snb_arr[tips][] = array('id'=>'tips_cheditor', 'name'=>'cheditor(상용)', 'href'=>"$g4[bbs_path]/board.php?bo_table=cheditor" );

$snb_arr[gnu4_b4][] = array('id'=>'b4_gnu4_pack', 'name'=>'그누보드불당팩', 'href'=>"$g4[bbs_path]/board.php?bo_table=gnu4_pack" );
$snb_arr[gnu4_b4][] = array('id'=>'b4_gnu4_pack_book', 'name'=>'불당팩 매뉴얼', 'href'=>"$g4[bbs_path]/board.php?bo_table=gnu4_pack_book" );
$snb_arr[gnu4_b4][] = array('id'=>'b4_gnu4_pack_skin', 'name'=>'불당팩 스킨', 'href'=>"$g4[bbs_path]/board.php?bo_table=gnu4_pack_skin" );
$snb_arr[gnu4_b4][] = array('id'=>'b4_gnu4_pack_req', 'name'=>'불당팩 버그 및 개선', 'href'=>"$g4[bbs_path]/board.php?bo_table=gnu4_pack_req" );
$snb_arr[gnu4_b4][] = array('id'=>'b4_gnu4_pack_qna', 'name'=>'불당팩 묻고답하기', 'href'=>"$g4[bbs_path]/board.php?bo_table=gnu4_pack_qna" );

$snb_arr[gblog][] = array('id'=>'gblog_gblog4', 'name'=>'gblog 불당버젼', 'href'=>"$g4[bbs_path]/board.php?bo_table=gblog" );
$snb_arr[gblog][] = array('id'=>'gblog_index', 'name'=>'gblog 테스트', 'href'=>"$g4[path]/blog/", 'new'=>'1' );

$snb_arr[club2][] = array('id'=>'club_club2', 'name'=>'클럽2', 'href'=>"$g4[bbs_path]/board.php?bo_table=club2" );
$snb_arr[club2][] = array('id'=>'club_test_club2', 'name'=>'클럽2 테스트', 'href'=>"$g4[path]/club/", 'new'=>'1' );

$snb_arr[android][] = array('id'=>'and_talk', 'name'=>'안드로이드 게시판', 'href'=>"$g4[bbs_path]/board.php?bo_table=and_talk" );
$snb_arr[android][] = array('id'=>'and_tip', 'name'=>'안드로이드 팁', 'href'=>"$g4[bbs_path]/board.php?bo_table=and_tip" );
$snb_arr[android][] = array('id'=>'and_pds', 'name'=>'안드로이드 자료실', 'href'=>"$g4[bbs_path]/board.php?bo_table=and_pds" );
$snb_arr[android][] = array('id'=>'webapp', 'name'=>'웹앱', 'href'=>"$g4[bbs_path]/board.php?bo_table=webapp" );

$snb_arr[gnu4][] = array('id'=>'gnu4_gnu4_turning', 'name'=>'그누보드4 튜닝', 'href'=>"$g4[bbs_path]/board.php?bo_table=gnu4_turning" );
$snb_arr[gnu4][] = array('id'=>'gnu4_gnu4_turning2', 'name'=>'그누보드4 튜닝(비공개)', 'href'=>"$g4[bbs_path]/board.php?bo_table=gnu4_turning2" );
$snb_arr[gnu4][] = array('id'=>'gnu4_memo4', 'name'=>'쪽지5', 'href'=>"$g4[bbs_path]/board.php?bo_table=memo4" );
$snb_arr[gnu4][] = array('id'=>'gnu4_thumb', 'name'=>'불당썸/Resize', 'href'=>"$g4[bbs_path]/board.php?bo_table=thumb" );
$snb_arr[gnu4][] = array('id'=>'gnu4_builder', 'name'=>'불당빌더(100%수동빌더)', 'href'=>"$g4[bbs_path]/board.php?bo_table=layout" );
$snb_arr[gnu4][] = array('id'=>'gnu4_recycle', 'name'=>'휴지통/Recycle', 'href'=>"$g4[bbs_path]/board.php?bo_table=g4_recycle" );
$snb_arr[gnu4][] = array('id'=>'gnu4_unicro', 'name'=>'유니크로장터/게시판', 'href'=>"$g4[bbs_path]/board.php?bo_table=gnu4_unicro" );
$snb_arr[gnu4][] = array('id'=>'gnu4_skin', 'name'=>'그누보드스킨', 'href'=>"$g4[bbs_path]/board.php?bo_table=gnu4_skin" );
$snb_arr[gnu4][] = array('id'=>'gnu4_tips', 'name'=>'그누보드팁', 'href'=>"$g4[bbs_path]/board.php?bo_table=gnu4_tips" );
$snb_arr[gnu4][] = array('id'=>'gnu4_qna', 'name'=>'그누보드 묻고 답하기', 'href'=>"$g4[bbs_path]/board.php?bo_table=gnu4_qna" );

$snb_arr[tobe][] = array('id'=>'gnu4_contentsmall', 'name'=>'컨텐츠몰4', 'href'=>"$g4[bbs_path]/board.php?bo_table=g4_contents" );
$snb_arr[tobe][] = array('id'=>'gnu4_gcash4', 'name'=>'gcash4', 'href'=>"$g4[bbs_path]/board.php?bo_table=gnu4_gcash" );
$snb_arr[tobe][] = array('id'=>'gnu4_popup2', 'name'=>'팝업관리자2', 'href'=>"$g4[bbs_path]/board.php?bo_table=popup2" );

$snb_arr[mart][] = array('id'=>'checkout', 'name'=>'네이버 체크아웃');
$snb_arr[mart][] = array('id'=>'social_pack', 'name'=>'그누보드 소셜팩', 'href'=>"$g4[bbs_path]/board.php?bo_table=social_pack" );
$snb_arr[mart][] = array('id'=>'social_qna', 'name'=>'소셜팩 묻고답하기', 'href'=>"$g4[bbs_path]/board.php?bo_table=social_qna" );
$snb_arr[mart][] = array('id'=>'mart_download', 'name'=>'불당장터', 'href'=>"$g4[bbs_path]/board.php?bo_table=mart_download" );
$snb_arr[mart][] = array('id'=>'mart_info', 'name'=>'불당장터 정보', 'href'=>"$g4[bbs_path]/board.php?bo_table=mart_info" );
$snb_arr[mart][] = array('id'=>'mart_qna', 'name'=>'불당장터 묻고답하기', 'href'=>"$g4[bbs_path]/board.php?bo_table=mart_qna" );
$snb_arr[mart][] = array('id'=>'test_mart', 'name'=>'불당장터 테스트', 'href'=>"$g4[path]/mart/", 'new'=>'1' );

$snb_arr[yc4][] = array('id'=>'yc4_pack', 'name'=>'영4 불당팩 공지', 'href'=>"$g4[bbs_path]/board.php?bo_table=yc4_pack" );
$snb_arr[yc4][] = array('id'=>'yc4_pack_qna', 'name'=>'영4 불당팩 묻고답하기', 'href'=>"$g4[bbs_path]/board.php?bo_table=yc4_pack_qna" );
$snb_arr[yc4][] = array('id'=>'yc4_pack_tips_open', 'name'=>'영4 불당팩 팁', 'href'=>"$g4[bbs_path]/board.php?bo_table=yc4_pack_tips_open" );
$snb_arr[yc4][] = array('id'=>'yc4_turning', 'name'=>'불영4 불당팩 튜닝', 'href'=>"$g4[bbs_path]/board.php?bo_table=yc4_turning" );
$snb_arr[yc4][] = array('id'=>'yc4_pack_download', 'name'=>'불영4 불당팩 다운(회원)', 'href'=>"$g4[bbs_path]/board.php?bo_table=yc4_pack_download" );
$snb_arr[yc4][] = array('id'=>'yc4_pack_book', 'name'=>'영4 불당팩 매뉴얼(회원)', 'href'=>"$g4[bbs_path]/board.php?bo_table=yc4_pack_book" );
$snb_arr[yc4][] = array('id'=>'test_yc4', 'name'=>'영카트4 테스트', 'href'=>"http://www.diorshop.co.kr", 'new'=>'1' );

$snb_arr[yc4_old][] = array('id'=>'yc4_tips', 'name'=>'영카트4 팁', 'href'=>"$g4[bbs_path]/board.php?bo_table=yc4_tips" );
$snb_arr[yc4_old][] = array('id'=>'yc4_tips_hidden', 'name'=>'영카트4 팁(회원 only)', 'href'=>"$g4[bbs_path]/board.php?bo_table=yc4_tips_hidden" );
$snb_arr[yc4_old][] = array('id'=>'yc4_tips_op', 'name'=>'영영카트4 묻고답하기', 'href'=>"$g4[bbs_path]/board.php?bo_table=yc4_tips_op" );
$snb_arr[yc4_old][] = array('id'=>'yc4_tips_tobe', 'name'=>'영카트4 HELP', 'href'=>"$g4[bbs_path]/board.php?bo_table=yc4_tips_tobe" );
$snb_arr[yc4_old][] = array('id'=>'yc4_tips_fixed', 'name'=>'영카트4 Fixed', 'href'=>"$g4[bbs_path]/board.php?bo_table=yc4_tips_fixed" );

// 상단부 메뉴에는 없고, 필요시에 sub에만 나오는 회사정보
$snb_arr[info][] = array('id'=>'talk_notice', 'name'=>'공지사항', 'href'=>"$g4[bbs_path]/board.php?bo_table=notice" );
$snb_arr[info][] = array('id'=>'privacy', 'name'=>'개인정보보호방침', 'href'=>"$g4[path]/company/privacy.php" );
$snb_arr[info][] = array('id'=>'service', 'name'=>'이용약관', 'href'=>"$g4[path]/company/service.php" );
$snb_arr[info][] = array('id'=>'disclaimer', 'name'=>'책임한계와법적고지', 'href'=>"$g4[path]/company/disclaimer.php" );
$snb_arr[info][] = array('id'=>'rejection', 'name'=>'이메일주소무단수집거부', 'href'=>"$g4[path]/company/rejection.php" );

?>