<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// 이모티콘이 수정할 때 사라지지 않게...
if ($w == "u")
    $wr_subject = $write['wr_subject'];
else
    $wr_subject = "no-image";
?>
<script type="text/javascript">
// 글자수 제한
var char_min = parseInt(<?=$write_min?>); // 최소
var char_max = parseInt(<?=$write_max?>); // 최대
</script>

<form role="form" class="form-horizontal" name="fwrite" method="post" onsubmit="return fwrite_submit(this);" enctype="multipart/form-data">
<input type=hidden name=null> 
<input type=hidden name=w        value="<?=$w?>">
<input type=hidden name=bo_table value="<?=$bo_table?>">
<input type=hidden name=wr_id    value="<?=$wr_id?>">
<input type=hidden name=sca      value="<?=$sca?>">
<input type=hidden name=sfl      value="<?=$sfl?>">
<input type=hidden name=stx      value="<?=$stx?>">
<input type=hidden name=spt      value="<?=$spt?>">
<input type=hidden name=sst      value="<?=$sst?>">
<input type=hidden name=sod      value="<?=$sod?>">
<input type=hidden name=page     value="<?=$page?>">
<input type=hidden name=mnb      value="<?=$mnb?>">
<input type=hidden name=snb      value="<?=$snb?>">

<input type=hidden name=wr_subject id=wr_subject value="<?=$wr_subject?>">

<?
// reply일 때는 $content를 비워버립니다
if ($w == "r")
    $content = "";

// list.skin.php에서 읽어들일 때 write.php의 흉내를 내기 위해서 bbs/write.php에서 참조했습니다
if ($w == "") {
    $is_notice = false;

    if ($is_admin)
        $is_notice = true;
    $is_secret = $board[bo_use_secret];

    $is_mail = false;
    if ($config[cf_email_use] && $board[bo_use_email])
        $is_mail = true;
}

$option = "";
$option_hidden = "";
if ($is_notice || $is_secret || $is_mail) { 
    $option = "";
    if ($is_notice) {
        $option .= '<label class="checkbox-inline">';
        $option .= "<input type=checkbox name=notice value='1' $notice_checked>공지";
        $option .= '</label>';
    }

    if ($is_secret) {
        if ($is_admin || $is_secret==1) {
            $option .= '<label class="checkbox-inline">';
            $option .= "<input type=checkbox value='secret' name='secret' $secret_checked>비밀글";
            $option .= '</label>';
        } else {
            $option_hidden .= "<input type=hidden value='secret' name='secret'>";
        }
    }
    
    if ($is_mail) {
        $option .= '<label class="checkbox-inline">';
        $option .= "<input type=checkbox value='mail' name='mail' $recv_email_checked>답변메일받기";
        $option .= '</label>';
    }

      // hidden으로 넘겨야 하는 옵션을 출력 합니다.
			echo $option_hidden;
}
?>
<? if ($member[mb_level] > 1) { ?>
    <div>
        <textarea class="form-control" id="wr_content" name="wr_content" style='word-break:break-all;' rows=4 itemname="내용" required 
        <? if ($write_min || $write_max) { ?>onkeyup="check_byte('wr_content', 'char_count');"<?}?>><?=$content?></textarea>
        <? if ($write_min || $write_max) { ?><script type="text/javascript"> check_byte('wr_content', 'char_count'); </script><?}?>
    
        <a class="btn btn-default" style="cursor: pointer;" data-toggle="collapse" href="#collapseOne">이미지</a>
        <img id="smiley">
        <div class="btn-group" style="margin-top:5px;margin-bottom:5px;">
            &nbsp;<span class="btn btn-default" style="cursor: pointer;" onclick="textarea_decrease('wr_content', 5);"> <i class="fa fa-minus-square"></i> </span>
            <!-- 이게 있으면 폰에서 줄바꿈이 일어나기도... -..-...
            &nbsp;<span class="btn btn-default" style="cursor: pointer;" onclick="textarea_original('wr_content', 5);"> <i class="fa fa-circle-o"></i> </span>
            -->
            &nbsp;<span class="btn btn-default" style="cursor: pointer;" onclick="textarea_increase('wr_content', 5);"> <i class="fa fa-plus-square"></i> </span>
        </div>
        
        <div id="collapseOne" class="collapse well">
            <ul class="list-unstyled list-inline" style="margin-bottom:5px;">
                <?
                for ($i=101; $i<144;$i++) {
                    echo "<li><img class='emoticon' id='$i' src='$board_skin_path/emoticons/$i.png' style='cursor:pointer'></li>";
                }
                ?>
                <li><a href='#' onclick="$('#collapseOne').collapse('hide');$('#smiley').attr('src','');$('#wr_subject').val('no-image')" class="btn btn-default btn-sm" style="margin-top:5px;">이모티콘없애기</a></li>
            </ul>
        </div>
        
        <script type="text/javascript">
            $('.emoticon').click(function() {
                var emo_no = $(this).attr("id");
                $('#collapseOne').collapse('hide');$('#smiley').attr('src','<?=$board_skin_path?>/emoticons/'+emo_no+'.png');
                $('#wr_subject').val(emo_no);
            });
        
            <? if ($w == "u") { ?>
                $('#smiley').attr('src','<?=$board_skin_path?>/emoticons/'+<?=$wr_subject?>+'.png');
            <? } ?>
        </script>
    
        <? if ($write_min || $write_max) { ?><span id=char_count></span>글자<?}?>
    
        <span class="pull-right" style="margin-top:5px;margin-bottom:5px;">
        <? if ($member[mb_level] > 1) { ?>
        <? if ($option) { ?>
            <?=$option?>&nbsp;&nbsp;&nbsp;&nbsp;
        <?}?>
        <?}?>
            <a class="btn btn-default" href="<?=$list_href?>">List</a>
            &nbsp;
            <button type="submit" class="btn btn-success" id="btn_submit">Write</button>
        </span>
    </div>
<? } else { ?>
<div class="well">
    <?
    $login_url = "./login.php?url=".urlencode("board.php?bo_table=$bo_table");
    ?>
    <a href="<?=$login_url?>">로그인 하시면 댓글을 남길 수 있습니다</a>
</div>
<? } ?>
</form>

<script type="text/javascript">
function fwrite_submit(f) 
{
    if (document.getElementById('char_count')) {
        if (char_min > 0 || char_max > 0) {
            var cnt = parseInt(document.getElementById('char_count').innerHTML);
            if (char_min > 0 && char_min > cnt) {
                alert("내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
                return false;
            } 
            else if (char_max > 0 && char_max < cnt) {
                alert("내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
                return false;
            }
        }
    }

    var subject = "";
    var content = "";
    $.ajax({
        url: "<?=$g4[bbs_path]?>/ajax.filter.php",
        type: "POST",
        data: {
            "subject": f.wr_subject.value,
            "content": f.wr_content.value
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function(data, textStatus) {
            subject = data.subject;
            content = data.content;
        }
    });

    if (subject) {
        alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
        f.wr_subject.focus();
        return false;
    }

    if (content) {
        alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
        if (typeof(ed_wr_content) != "undefined") 
            ed_wr_content.returnFalse();
        else 
            f.wr_content.focus();
        return false;
    }

    <?
    if ($g4[https_url])
        echo "f.action = '$g4[https_url]/$g4[bbs]/write_update.php';";
    else
        echo "f.action = './write_update.php';";
    ?>
    
    return true;
}
</script>
