<?
if ( ! defined("_GNUBOARD_")) {
    exit;
} // 개별 페이지 접근 불가

$nick = cut_str($member['mb_nick'], $config['cf_cut_name'])

?>
<div id="o-wrapper" class="o-wrapper">
    <nav id="c-menu--slide-left" class="c-menu c-menu--slide-left sidebar">
        <div class="sidebar-header">
            <div>
                <div class="pull-left"><a href="/" style="display: inline-block;line-height: 34px;"><i
                            class="material-icons">&#xE88A;</i> 홈으로</a></div>
                <div class="pull-right"><a href="#" class="c-menu__close"
                                           style="display: inline-block;line-height: 34px;"><i
                            class="material-icons">&#xE5CD;</i></a>
                </div>
                <div class="clearfix"></div>
            </div>

            <?php if ($member['mb_id']) { ?>
                <div class="desc">
                    <div class="pull-left">
                        <strong><a
                                href="<?= $g4['bbs_path'] ?>/profile.php?mb_id=<?= $member['mb_id'] ?>"><?= $nick ?></a></strong>
                    </div>
                    <div class="pull-left" style="margin-top: -5px; margin-left: 10px;">
                        <a href="<?= $g4['bbs_path'] ?>/point.php"><span
                                class="badge badge-outline">Point: <?= number_format($member['mb_point']) ?></span></a>
                        <span class="badge">Lv. <?= $member['mb_level'] ?></span>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="guest">
                    <button class="btn btn-outline"
                            onclick="redirect('<?= $g4['bbs_path'] ?>/logout.php')">로그아웃
                    </button>
                    <button class="btn btn-outline"
                            onclick="redirect('<?= $g4['bbs_path'] ?>/member_confirm.php?url=register_form.php')">회원정보
                    </button>
                </div>
            <?php } else { ?>
                <div class="desc">
                    <strong>2CPU</strong>
                </div>

                <div class="guest">
                    <button class="btn btn-outline"
                            onclick="redirect('/bbs/login.php?url=<?= urlencode($_SERVER['REQUEST_URI']) ?>')">로그인
                    </button>
                    <button class="btn btn-outline" onclick="redirect('/bbs/register.php')">회원가입</button>
                </div>
            <?php } ?>
        </div>

        <?php if ($member['mb_id']) { ?>
            <ul class="sidebar-menu" style="border-bottom: 1px solid #E5E5E5;">

                <li class="treeview active">
                    <a href="#"><i class="material-icons">&#xE88F;</i> <span>바로가기</span>
                        <i class="material-icons pull-right">&#xE316;</i></a>
                    <ul class="treeview-menu">
                        <?php
                        $sql = "select m.bo_table, b.bo_subject from $g4[my_menu_table] as m left join $g4[board_table] as b on m.bo_table = b.bo_table where mb_id = '$member[mb_id]'";
                        $qry = sql_query($sql);
                        while ($row = sql_fetch_array($qry)) {
                            ?>
                            <li><a href="<?= $g4['path'] ?>/<?= $row['bo_table'] ?>"><i class="material-icons md-18">
                                    &#xE157;</i> <?= $row['bo_subject'] ?>
                            </a></li><?php
                        }
                        ?>
                        <li><a href="<?= $g4['bbs_path'] ?>/my_menu_edit.php"><i
                                    class="material-icons md-18">&#xE254;</i>
                                바로가기 편집</a></li>
                    </ul>
                </li>
            </ul>
        <?php } ?>

        <div class="sidebar-quick">
            <div class="pull-left">
                <a href="<?= $g4['path'] ?>/freeboard_2011"><i class="material-icons">&#xE896;</i> 자유게시판</a>
            </div>
            <div class="pull-right">
                <a href="<?= $g4['path'] ?>/oneline"><i class="material-icons">&#xE896;</i> 한줄</a>
            </div>
            <div class="pull-left">
                <a href="<?= $g4['path'] ?>/QnA"><i class="material-icons">&#xE896;</i> QnA</a>
            </div>
            <div class="pull-right">
                <a href="<?= $g4['path'] ?>/column"><i class="material-icons">&#xE896;</i> 생활</a>
            </div>
            <div class="pull-left">
                <a href="<?= $g4['path'] ?>/drone"><i class="material-icons">&#xE896;</i> 드론</a>
            </div>
            <div class="pull-right">
                <a href="<?= $g4['path'] ?>/hojak"><i class="material-icons">&#xE896;</i> DIY</a>
            </div>
            <div class="pull-left">
                <a href="<?= $g4['path'] ?>/unbox"><i class="material-icons">&#xE896;</i> 언박싱</a>
            </div>
            <div class="pull-right">
                <a href="<?= $g4['path'] ?>/nas"><i class="material-icons">&#xE896;</i> NAS</a>
            </div>
            <div class="pull-left">
                <a href="<?= $g4['path'] ?>/cloud"><i class="material-icons">&#xE896;</i> 클라우드</a>
            </div>
            <div class="pull-right">
                <a href="<?= $g4['path'] ?>/vm"><i class="material-icons">&#xE896;</i> 가상화</a>
            </div>
            <div class="pull-left">
                <a href="<?= $g4['path'] ?>/hardware_2014"><i class="material-icons">&#xE896;</i> 하드웨어</a>
            </div>
            <div class="pull-right">
                <a href="<?= $g4['path'] ?>/network"><i class="material-icons">&#xE896;</i> 네트웍</a>
            </div>
            <div class="pull-left">
                <a href="<?= $g4['path'] ?>/4raid"><i class="material-icons">&#xE896;</i> RAID</a>
            </div>
            <div class="pull-right">
                <a href="<?= $g4['path'] ?>/lec"><i class="material-icons">&#xE896;</i> 강좌</a>
            </div>
            <div class="pull-left">
                <a href="<?= $g4['path'] ?>/PDS"><i class="material-icons">&#xE896;</i> 자료</a>
            </div>
            <div class="pull-right">
                <a href="<?= $g4['path'] ?>/bmt"><i class="material-icons">&#xE896;</i> BMT</a>
            </div>
            <div class="pull-left">
                <a href="<?= $g4['path'] ?>/ha"><i class="material-icons">&#xE896;</i> 훈훈</a>
            </div>
            <div class="pull-right">
                <a href="<?= $g4['path'] ?>/sell"><i class="material-icons">&#xE896;</i> 판매</a>
            </div>
            <div class="pull-left">
                <a href="<?= $g4['path'] ?>/buy"><i class="material-icons">&#xE896;</i> 구매</a>
            </div>
            <div class="pull-right">
                <a href="<?= $g4['path'] ?>/quot"><i class="material-icons">&#xE896;</i> 견적</a>
            </div>
            <div class="pull-left">
                <a href="<?= $g4['path'] ?>/hongik"><i class="material-icons">&#xE896;</i> 해외</a>
            </div>
            <div class="pull-right">
                <a href="<?= $g4['path'] ?>/plugin/attendance/attendance.php"><i class="material-icons">&#xE896;</i> 출석</a>
            </div>
            <div class="clearfix"></div>
        </div>

        <div style="margin: 10px;">
            <button class="btn btn-default btn-outline btn-sm"
                    onclick="redirect('<?= $g4['bbs_path'] ?>/scrap.php')">스크랩
            </button>
            <button class="btn btn-default btn-outline btn-sm"
                    onclick="redirect('<?= $g4['path'] ?>/plugin/kcb/')">본인인증
            </button>
        </div>
        <div style="margin: 10px;">
            <button class="btn btn-default btn-outline btn-sm"
                    onclick="redirect('<?= $g4['bbs_path'] ?>/point.php')">포인트
            </button>
            <button class="btn btn-default btn-outline btn-sm"
                    onclick="redirect('<?= $g4['bbs_path'] ?>/write.php?bo_table=pg')">포인트 충전
            </button>
        </div>

    </nav>
</div>
<div id="c-mask" class="c-mask"></div>
