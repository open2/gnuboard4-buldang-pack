<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ�

// ��Ų���� ����ϴ� lib �о���̱�
include_once("$g4[path]/lib/write.skin.lib.php");
?>

<script type="text/javascript">
// ���ڼ� ����
var char_min = parseInt(<?=$write_min?>); // �ּ�
var char_max = parseInt(<?=$write_max?>); // �ִ�
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

<? 
$option = "";
$option_hidden = "";
if ($is_notice || $is_html || $is_secret || $is_mail) { 
    $option = "";
    if ($is_notice) {
        $option .= '<label class="checkbox-inline">';
        $option .= "<input type=checkbox name=notice value='1' $notice_checked>����";
        $option .= '</label>';
    }

    // �Ҵ��� - ��ü ����
    if ($is_g_notice) {
        $option .= '<label class="checkbox-inline">';
        $option .= "<input type=checkbox name=g_notice value='1' $g_notice_checked>��ü����";
        $option .= '</label>';
    }

    if ($is_html) {
        if ($is_dhtml_editor) {
            $option_hidden .= "<input type=hidden value='html1' name='html'>";
        } else {
            $option .= '<label class="checkbox-inline">';
            $option .= "<input onclick='html_auto_br(this);' type=checkbox value='$html_value' name='html' $html_checked>html";
            $option .= '</label>';
        }
    }

    if ($is_secret) {
        if ($is_admin || $is_secret==1) {
            $option .= '<label class="checkbox-inline">';
            $option .= "<input type=checkbox value='secret' name='secret' $secret_checked>��б�";
            $option .= '</label>';
        } else {
            $option_hidden .= "<input type=hidden value='secret' name='secret'>";
        }
    }
    
    if ($is_mail) {
        $option .= '<label class="checkbox-inline">';
        $option .= "<input type=checkbox value='mail' name='mail' $recv_email_checked>�亯���Ϲޱ�";
        $option .= '</label>';
    }

      // hidden���� �Ѱܾ� �ϴ� �ɼ��� ��� �մϴ�.
			echo $option_hidden;
}
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <div>
            <strong><?=$title_msg?></strong>
        </div>
    </div>
    <div class="panel-body">
        <? if ($option) { ?>
        <div class="form-group">
            <label class="col-sm-1 hidden-xs">Option</label>
            <div class="col-xs-12 col-sm-11"><?=$option?></div>
        </div>
        <?}?>

        <? if ($is_name) { ?>
        <div class="form-group">
            <label for="wr_name" class="col-sm-1 hidden-xs">Name</label>
            <div class="col-xs-12 col-sm-11">
            <input class="form-control" maxlength=20 size=15 name=wr_name id=wr_name itemname="�̸�" placeholder="�̸�" required value="<?=$name?>">
            </div>
        </div>
        <? } ?>

        <? if ($is_password) { ?>
        <div class="form-group">
            <label for="" class="col-sm-1 hidden-xs">Password</label>
            <div class="col-xs-12 col-sm-11">
            <input class="form-control" type=password maxlength=20 size=15 name=wr_password id=wr_password itemname="�н�����" placeholder="�н�����" <?=$password_required?>>
            </div>
        </div>
        <? } ?>

        <? if ($is_email) { ?>
        <div class="form-group">
            <label for="wr_email" class="col-sm-1 hidden-xs">E-mail</label>
            <div class="col-xs-12 col-sm-11">
            <input class="form-control" type="email" maxlength=100 size=50 name=wr_email id=wr_email email itemname="�̸���" placeholder="�̸���" value="<?=$email?>">
            </div>
        </div>
        <? } ?>            

        <? if ($is_homepage) { ?>
        <div class="form-group">
            <label for="wr_homepage" class="col-sm-1 hidden-xs">Homepage URL</label>
            <div class="col-xs-12 col-sm-11">
            <input class="form-control" type="url" size=50 name=wr_homepage id=wr_homepage itemname="Ȩ������" placeholder="Ȩ������" value="<?=$homepage?>">
            </div>
        </div>
        <? } ?>            

        <div class="form-group">
            <label for="wr_subject" class="col-sm-1 hidden-xs">Subject</label>
            <div class="col-xs-12 col-sm-11">
            <input class="form-control" name=wr_subject id="wr_subject" itemname="����" required value="<?=$subject?>" placeholder="Subject">
            </div>
        </div>

        <? if ($is_category) { ?>
        <div class="form-group">
            <label for="ca_name" class="col-sm-1 hidden-xs">Category</label>
            <div class="col-xs-12 col-sm-11">
            <select class="form-control" name=ca_name id="ca_name" required itemname="�з�"><option value="">�����ϼ���<?=$category_option?></select>
            </div>
        </div>
        <? } ?>            

        <div class="form-group">
            <label class="col-sm-1 hidden-xs"></label>
            <div class="col-xs-12 col-sm-11">
                <? if ($is_dhtml_editor) { ?>

  <!-- Include Editor style. -->
  <link href="<?=$g4[path]?>/froala/css/froala_editor.min.css" rel="stylesheet" type="text/css" />
  <link href="<?=$g4[path]?>/froala/css/froala_style.min.css" rel="stylesheet" type="text/css" />

  <!-- Include Editor Plugins style. -->
  <link rel="stylesheet" href="<?=$g4[path]?>/froala/css/plugins/char_counter.css">
  <link rel="stylesheet" href="<?=$g4[path]?>/froala/css/plugins/code_view.css">
  <link rel="stylesheet" href="<?=$g4[path]?>/froala/css/plugins/colors.css">
  <link rel="stylesheet" href="<?=$g4[path]?>/froala/css/plugins/emoticons.css">
  <link rel="stylesheet" href="<?=$g4[path]?>/froala/css/plugins/file.css">
  <link rel="stylesheet" href="<?=$g4[path]?>/froala/css/plugins/fullscreen.css">
  <link rel="stylesheet" href="<?=$g4[path]?>/froala/css/plugins/image.css">
  <link rel="stylesheet" href="<?=$g4[path]?>/froala/css/plugins/image_manager.css">
  <link rel="stylesheet" href="<?=$g4[path]?>/froala/css/plugins/line_breaker.css">
  <link rel="stylesheet" href="<?=$g4[path]?>/froala/css/plugins/table.css">
  <link rel="stylesheet" href="<?=$g4[path]?>/froala/css/plugins/video.css">

                    <textarea id="froala-editor" name="froala-editor" style='display:none;'></textarea>

  <!-- Include JS files. -->
  <script src="<?=$g4[path]?>/froala/js/froala_editor.min.js"></script>

  <script type="text/javascript">
      $.FroalaEditor.DEFAULTS.key = 'ljvbfjeoiA3md1C1zf1==';
  </script>

  <!-- Include Plugins. -->
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/align.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/char_counter.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/code_view.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/colors.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/emoticons.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/entities.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/file.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/font_family.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/font_size.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/fullscreen.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/image.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/image_manager.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/inline_style.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/line_breaker.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/link.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/lists.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/paragraph_format.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/paragraph_style.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/quote.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/table.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/save.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/url.min.js"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/video.min.js"></script>

  <link href="<?=$g4[path]?>/froala/css/themes/gray.min.css" rel="stylesheet" type="text/css" />

  <!-- Include Language file if we'll use it. -->
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/languages/ko.js"></script>

  <!-- Initialize the editor. -->
  <script type="text/javascript">
  $(function() {
    $('#froala-editor').froalaEditor({
      theme: 'gray',
      language: 'ko',     // https://www.froala.com/wysiwyg-editor/docs/examples/language
      fontFamily: {       // https://www.froala.com/wysiwyg-editor/docs/examples/font-family
        "���� ���": '���� ���',
        "����": '����',
        "Malgun Gothic": 'Malgun Gothic',
        "gulim": 'gulim'
      },
      heightMin: 200,     // https://www.froala.com/wysiwyg-editor/docs/examples/adjustable-height
      heightMax: 300,

        // Set the image upload parameter.
        imageUploadParam: 'file',

        // Set the image upload URL.
        imageUploadURL: '/froala/image_upload.php',

        // Additional upload params (bo_table ���� image_upload.php�� �ѱ��)
        imageUploadParams: {bo_table: '<?=$bo_table?>'},

        // Set request type.
        imageUploadMethod: 'POST',

        // Set max image size to 20MB.
        imageMaxSize: 20 * 1024 * 1024,

        // Allow to upload PNG and JPG. GIF
        imageAllowedTypes: ['jpeg', 'jpg', 'png', 'gif'],

        // image alignment
        imageDefaultAlign: 'left',

        // image width
        imageDefaultWidth: 0

    })

      // Catch image removal from the editor.
      .on('froalaEditor.image.removed', function (e, editor, $img) {
          $.ajax({
  
          // Request method.
          method: "POST",

          // Request URL.
          url: "/froala/image_delete.php",

          // Request params.
          data: {
            src: $img.attr('src')
          }
        })
        .done (function (data) {
          console.log ('image was deleted');
        })
        .fail (function () {
          console.log ('image delete problem');
        })
        
        });
  });
  </script>

                <? } else { ?>
                    <span style="cursor: pointer;" onclick="textarea_decrease('wr_content', 5);"> <i class="fa fa-minus-square"></i> </span>
                    <span style="cursor: pointer;" onclick="textarea_original('wr_content', 15);"> <i class="fa fa-circle-o"></i> </span>
                    <span style="cursor: pointer;" onclick="textarea_increase('wr_content', 5);"> <i class="fa fa-plus-square"></i> </span>
                    <? if ($write_min || $write_max) { ?><div class="pull-right"><span id=char_count></span>����</div><?}?>
                    <textarea class="form-control" id="wr_content" name="wr_content" style='width:100%; word-break:break-all;' rows=15 itemname="����" required 
                    <? if ($write_min || $write_max) { ?>onkeyup="check_byte('wr_content', 'char_count');"<?}?>><?=$content?></textarea>
                    <? if ($write_min || $write_max) { ?><script language="javascript"> check_byte('wr_content', 'char_count'); </script><?}?>
                <? } ?>
            </div>
        </div>

        <? if ($board[bo_related]) { ?>
        <div class="form-group">
            <label class="col-sm-1 hidden-xs">Keyword</label>
            <div class="col-xs-12 col-sm-11">
            <input class="form-control" type="text" size=50 name="wr_related" itemname="���ñ� Ű����" placeholder="���ñ� Ű����, �޸��� ���� �մϴ�. ��) Ű����1, Ű����2" value="<?=$write[wr_related]?>">
            </div>
        </div>
        <? } ?>

        <? if ($is_link) { ?>
        <? for ($i=1; $i<=$g4[link_count]; $i++) { ?>
        <div class="form-group">
            <label class="col-sm-1 hidden-xs">Link #<?=$i?></label>
            <div class="col-xs-12 col-sm-11">
            <input class="form-control" type='text' class='field_pub_01' size=50 name='wr_link<?=$i?>' itemname='��ũ #<?=$i?>' placeholder="��ũ #<?=$i?>" value='<?=$write["wr_link{$i}"]?>'>
            </div>
        </div>
        <? } ?>
        <? } ?>

        <? if ($is_file) { ?>
        <div class="form-group">
            <label class="col-xs-1 hidden-xs">File
            </label>
            <div class="col-xs-12 col-sm-11">
                <a class="btn btn-default btn-xs" onclick="add_file();" style="cursor:pointer;" title="add file/÷������ �Է�â 1�� �߰�"><i class="fa fa-plus"></i></a>&nbsp;&nbsp;
                <a class="btn btn-default btn-xs" onclick="del_file();" style="cursor:pointer;" title="delete file/÷������ �Է�â 1�� ����"><i class="fa fa-minus"></i></a>
                <br>
                <table id="variableFiles" class="table table-condensed" style="word-break:break-all;overflow:hidden;table-layout:fixed"></table>
                <script type="text/javascript">
                var flen = 0;
                function add_file(delete_code) {
                    var upload_count = <?=(int)$board[bo_upload_count]?>;
                    if (upload_count && flen >= upload_count) {
                        alert("�� �Խ����� "+upload_count+"�� ������ ���� ���ε尡 �����մϴ�.");
                        return;
                    }
    
                    var objTbl;
                    var objRow;
                    var objCell;
                    if (document.getElementById)
                        objTbl = document.getElementById("variableFiles");
                    else
                        objTbl = document.all["variableFiles"];
    
                    objRow = objTbl.insertRow(objTbl.rows.length);
                    objCell = objRow.insertCell(0);
    
                    objCell.innerHTML = "<input type='file' name='bf_file[]' style='margin-top:5px;margin-bottom:5px;' title='���� �뷮 <?=$upload_max_filesize?> ���ϸ� ���ε� ����'>";
                    if (delete_code)
                        objCell.innerHTML += delete_code;
                    else {
                        <? if ($is_file_content) { ?>
                        objCell.innerHTML += "<input type='text' class='form-control' name='bf_content[]' placeholder='���ε� �̹��� ���Ͽ� �ش� �Ǵ� ������ �Է��ϼ���.'>";
                        <? } ?>
                        ;
                    }
    
                    flen++;
                }
    
                <?=$file_script; //�����ÿ� �ʿ��� ��ũ��Ʈ?>

                function del_file() {
                    // file_length ���Ϸδ� �ʵ尡 �������� �ʾƾ� �մϴ�.
                    var file_length = <?=(int)$file_length?>;
                    var objTbl = document.getElementById("variableFiles");
                    if (objTbl.rows.length - 1 > file_length) {
                        objTbl.deleteRow(objTbl.rows.length - 1);
                        flen--;
                    }
                }
                </script>

            </div>
        </div>
        <? } ?>

        <? if ($is_guest) { ?>
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <div id="grecaptcha" class="g-recaptcha" data-sitekey="<?=$g4['recaptcha_sitekey']?>" style="float:right"></div>
        <? } ?>

    </div>
</div>

<span class="pull-right">
    <button type="submit" class="btn btn-success" id="btn_submit">Write</button>
    &nbsp;&nbsp;&nbsp;
    <a class="btn btn-default" href="<?=$list_href?>">List</a>
</span>

</form>

<script type="text/javascript">
<?
// �����ڶ�� �з� ���ÿ� '����' �ɼ��� �߰���
if ($is_admin) 
{
    echo "
    if (typeof(document.fwrite.ca_name) != 'undefined')
    {
        document.fwrite.ca_name.options.length += 1;
        document.fwrite.ca_name.options[document.fwrite.ca_name.options.length-1].value = '����';
        document.fwrite.ca_name.options[document.fwrite.ca_name.options.length-1].text = '����';
    }";
} 
?>

with (document.fwrite) {
    if (typeof(wr_name) != "undefined")
        wr_name.focus();
    else if (typeof(wr_subject) != "undefined")
        wr_subject.focus();
    else if (typeof(wr_content) != "undefined")
        wr_content.focus();

    if (typeof(ca_name) != "undefined") {
        if (w.value == "u")
            ca_name.value = "<?=$write[ca_name]?>";
        if (w.value == "r")
            ca_name.value = "<?=$write[ca_name]?>"; 
    }
}

function html_auto_br(obj) 
{
    if (obj.checked) {
        result = confirm("�ڵ� �ٹٲ��� �Ͻðڽ��ϱ�?\n\n�ڵ� �ٹٲ��� �Խù� ������ �ٹٲ� ����<br>�±׷� ��ȯ�ϴ� ����Դϴ�.");
        if (result)
            obj.value = "html2";
        else
            obj.value = "html1";
    }
    else
        obj.value = "";
}

function fwrite_submit(f) 
{
    if (document.getElementById('char_count')) {
        if (char_min > 0 || char_max > 0) {
            var cnt = parseInt(document.getElementById('char_count').innerHTML);
            if (char_min > 0 && char_min > cnt) {
                alert("������ "+char_min+"���� �̻� ���ž� �մϴ�.");
                return false;
            } 
            else if (char_max > 0 && char_max < cnt) {
                alert("������ "+char_max+"���� ���Ϸ� ���ž� �մϴ�.");
                return false;
            }
        }
    }

    <?
    if ($is_dhtml_editor) echo cheditor3('wr_content'); 
    if ($is_dhtml_editor) echo cheditor4('wr_content'); 
    ?>

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
        alert("���� �����ܾ�('"+subject+"')�� ���ԵǾ��ֽ��ϴ�");
        f.wr_subject.focus();
        return false;
    }

    if (content) {
        alert("���뿡 �����ܾ�('"+content+"')�� ���ԵǾ��ֽ��ϴ�");
        if (typeof(ed_wr_content) != "undefined") 
            ed_wr_content.returnFalse();
        else 
            f.wr_content.focus();
        return false;
    }

    if (typeof(grecaptcha) != 'undefined') {
        var v = grecaptcha.getResponse();
        if(v.length == 0) {
            alert("���Թ����ڵ�(Captcha Code)�� Ʋ�Ƚ��ϴ�. �ٽ� �Է��� �ּ���.");
            return false;
        }
    }

    <?
    if ($g4[https_url])
        echo "f.action = '$g4[https_url]/$g4[bbs]/write_update.php';";
    else
        echo "f.action = './write_update.php';";
    ?>
    
    //return true;
}
</script>

<script type="text/javascript">
// ���ε��� �̹��� ������ ���� �޴� �����Դϴ�.
function showImageInfo() {
    var data = ed_wr_content.getImages();
    if (data == null) {
        return 0;
    }

    var img_sum = 0;
    for (var i=0; i<data.length; i++) {
        img_sum += parseInt(data[i].fileSize);
    }
}
</script>