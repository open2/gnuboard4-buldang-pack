<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 
?>
<script type="text/javascript">
var member_skin_path = "<?=$member_skin_path?>";
</script>
<script type="text/javascript" src="<?=$member_skin_path?>/jquery.ajax_register_form.js"></script>
<script type="text/javascript" src="<?=$g4[path]?>/js/md5.js"></script>

<form class="form-horizontal" role="form" name=fregisterform id=fregisterform method=post onsubmit="return fregisterform_submit(this);" enctype="multipart/form-data" autocomplete="off">
<input type=hidden name=w                id=w                   value="<?=$w?>">
<input type=hidden name=url              id=url                 value="<?=$urlencode?>">
<input type=hidden name=mb_id_enabled    id="mb_id_enabled"     value="" >
<input type=hidden name=mb_nick_enabled  id="mb_nick_enabled"   value="" >
<input type=hidden name=mb_email_enabled id="mb_email_enabled"  value="" >
<input type=hidden name=mb_name_enabled  id="mb_name_enabled"   value="" >
<input type=hidden name=ug_id            id="ug_id"             value="<?=$ug_id?>" >
<input type=hidden name=join_code        id="join_code"         value="<?=$join_code?>" >
<input type=hidden name=token            id="token"             value="<?=$token?>" >

<div class="panel panel-default">
    <div class="panel-heading"><h4><strong>ȸ������</strong></h4>
    </div>
    <div class="panel-body">

        <div class="form-group">
            <label for="mb_id" class="col-sm-2 control-label">���̵�</label>
            <div class="col-sm-6">
                <input class="form-control" placeholder="User id" maxlength=20 size=20 id='mb_id' name="mb_id" required style="ime-mode:disabled" value="<?=$member[mb_id]?>" 
                <? if ($w=='u') { echo "readonly"; } ?>
                <? if ($w=='') { echo "onblur='reg_mb_id_check()'"; } ?>>
                <? if ($w=='') { ?>
                    <p class="help-block">������, ����, _ �� �Է� ����. 3���̻� �Է��ϼ���.</p>
      	    				<p class="help-block"><span id="msg_mb_id"></span></p>
                <? } ?>
            </div>
        </div>
    
        <div class="form-group">
            <label for="mb_password" class="col-sm-2 control-label">�н�����</label>
            <div class="col-sm-6">
                <INPUT class="form-control" type="password" name="mb_password" id="mb_password" style="ime-mode:disabled" size=20 maxlength=20 <?=($w=="")?"required":"";?> itemname="�н�����" placeholder="Password">
            </div>
        </div>

        <div class="form-group">
            <label for="mb_password_re" class="col-sm-2 control-label">�н�����Ȯ��</label>
            <div class="col-sm-6">
                <INPUT class="form-control" type="password" name="mb_password_re" style="ime-mode:disabled" size=20 maxlength=20 <?=($w=="")?"required":"";?> itemname="�н����� Ȯ��" placeholder="Password�� �ѹ� �� �Է�">
                <p class="help-block">��й�ȣ�� 8�� �̻����� ���� ������ �� ���� ���ڿ� �����ڸ� ��� ����� �����մϴ�.</p>
            </div>
        </div>

        <? if ($g4['nick_reg_only'] !== 1) { ?>
        <div class="form-group">
            <label for="mb_password_re" class="col-sm-2 control-label">�̸�</label>
            <div class="col-sm-6">
                <INPUT class="form-control" type="text" name=mb_name id=mb_name size=20 maxlength=20 required itemname="�̸�" value="<?=$member[mb_name]?>" placeholder="Name"
                <? if ($w=='u') { echo "readonly"; } ?>
                <? if ($w=='') { echo "onblur='reg_mb_name_check()'"; } ?>>
                <? if ($w=='') { ?>
                    <p class="help-block">������� �ѱ� �Ǵ� ������ �Է� �����մϴ�.</p>
      	    				<p class="help-block"><span id="msg_mb_name"></span></p>
                <? } ?>
            </div>
        </div>
        <? } ?>

        <? if ($member[mb_nick_date] <= date("Y-m-d", $g4[server_time] - ($config[cf_nick_modify] * 86400))) { // ����������� �����ٸ� �������� ?>
        <input type=hidden name=mb_nick_default value='<?=$member[mb_nick]?>'>
        <div class="form-group">
            <label for="mb_nick" class="col-sm-2 control-label">�г���</label>
            <div class="col-sm-6">
                <input class="form-control" type="text" id='mb_nick' name='mb_nick' required hangulalphanumeric maxlength=20 value='<?=$member[mb_nick]?>' placeholder="Nick name" onblur="reg_mb_nick_check();">
                    <p class="help-block">������� �ѱ�,����,���ڸ� �Է� ���� (�ѱ�2��, ����4�� �̻�)<br>
                    ������ �ٲٽø� ������ <?=(int)$config[cf_nick_modify]?>�� �̳����� ���� �� �� �����ϴ�.</p>
      	    				<p class="help-block"><span id="msg_mb_nick"></span></p>
            </div>
        </div>
        <? } else { ?>
        <input type=hidden name="mb_nick_default" value='<?=$member[mb_nick]?>'>
        <div class="form-group">
            <label for="mb_nick" class="col-sm-2 control-label">�г���</label>
            <div class="col-sm-6">
                <?
                $d_times = (int)(($config[cf_nick_modify] * 86400 - ( $g4[server_time] - strtotime($member[mb_nick_date]))) / 86400) + 1;
                ?>
                <input class="form-control" readonly type="text" id='mb_nick' name='mb_nick' value='<?=$member[mb_nick]?>'>
                    <p class="help-block">�� <?=$d_times?>�� �� ������ ���� �մϴ�.</p>
            </div>
        </div>
        <? } ?>

        <input type=hidden name='old_email' value='<?=$member[mb_email]?>'>
        <div class="form-group">
            <label for="mb_email" class="col-sm-2 control-label">E-mail</label>
            <div class="col-sm-6">
                <input class="form-control" type="text" id='mb_email' name='mb_email' required style="ime-mode:disabled" size=38 maxlength=100 value='<?=$member[mb_email]?>' placeholder="E-mail"
                    onblur="reg_mb_email_check()">
                <? if ($w=='') { ?>
                    <p class="help-block">���̵�, ��й�ȣ �н� �� ����Ȯ�ο����� ���ǹǷ�<br>��ȿ�� �̸��� �������� �Է��Ͻñ� �ٶ��ϴ�.</p>
      	    				<p class="help-block"><span id="msg_mb_email"></span></p>
      	    		<?}?>
                <? if ($member[mb_email_certify]) {?>
                    <p class="help-block"><?=cut_str($member[mb_email_certify],10,"")?> �� �����Ǿ����ϴ�.</p>
                <? } ?>
                <? if ($config[cf_use_email_certify]) { ?>
                    <? if ($w=='') { ?><p class="help-block">e-mail �� �߼۵� ������ Ȯ���� �� �����ϼž� ȸ�������� �Ϸ�˴ϴ�.</p><?}?>
                    <? if ($w=='u'){ ?><p class="help-block">e-mail �ּҸ� �����Ͻø� �ٽ� �����ϼž� �մϴ�.</p><?}?>
                <? } ?>
                <? if ($w=='u') {
                    if ($g4['email_certify_point'] || $config['cf_use_email_certify']) ?>
                        <p class="help-block"><a href='<?=$g4[bbs_path]?>/email_re_certify.php' target=new>�̸��������Ϸ� ����</a>�� �����ø� ����â�� �����ϴ�.</p>
                <?}?>
            </div>
        </div>

        <? if ($w=="") { ?>
            <? if ($config[cf_use_birthdate]) { ?>
            <div class="form-group">
                <label for="mb_birth" class="col-sm-2 control-label">�������</label>
                <div class="col-sm-6">
                    <div class="input-group">
                    <span class="input-group-btn">
                    <a class="btn btn-default" href="javascript:win_calendar('mb_birth', document.getElementById('mb_birth').value, '');"><i class="fa fa-calendar"></i></a>
                    </span>
                    <INPUT class="form-control" type="text" id="mb_birth" name='mb_birth' size=8 maxlength=8 minlength=8 required numeric itemname='�������' value='<?=$member[mb_birth]?>' readonly title='���� �޷� �������� Ŭ���Ͽ� ��¥�� �Է��ϼ���.'>
                    </div>
                </div>
            </div>
            <? } ?>
        <? } ?>

        <? if ($member[mb_sex]) { ?>
        <input type=hidden name=mb_sex value='<?=$member[mb_sex]?>'>
        <div class="form-group">
            <label for="mb_sex" class="col-sm-2 control-label">����</label>
            <div class="col-sm-6">
                <p class="help-block">
                    <? 
                    switch ($member[mb_sex]) {
                      case 'F' : echo "����"; break;
                      case 'M' : echo "����"; break;
                    }
                    ?>
                </p>
            </div>
        </div>
        <? } else { ?>
            <? if ($config[cf_use_sex]) { ?>
            <div class="form-group">
                <label for="mb_sex" class="col-sm-2 control-label">����</label>
                <div class="col-sm-6">
                    <div class="radio-inline">
                        <label>
                        <input type="radio" name="mb_sex" id="mb_sex1" value="F" checked>����
                        </label>
                    </div>
                    <div class="radio-inline">
                        <label>
                        <input type="radio" name="mb_sex" id="mb_sex2" value="M">����
                        </label>
                    </div>
                </div>
            </div>
            <? } ?>
        <? } ?>

        <? if ($config[cf_use_hp]) { ?>
        <div class="form-group">
            <label for="mb_homepage" class="col-sm-2 control-label">�ڵ�����ȣ</label>
            <div class="col-sm-6">
                <?
                if ($member[mb_hp_certify_datetime] && $member[mb_hp_certify_datetime] !== '0000-00-00 00:00:00') {
                    echo "$member[mb_hp_certify_datetime] �� �����Ͽ����ϴ�.<br>"; 
                    echo "<input type='hidden' name='mb_hp_old' value='$member[mb_hp]'>"; 
                } 
                ?>
                <?if ($w=='u') { ?>
                    <input type=text name='mb_hp' size=21 maxlength=20 <?=$config[cf_req_hp]?'required':'';?> itemname='�ڵ�����ȣ' value='<?=$member[mb_hp]?>'>
                    <? if ($config[cf_hp_certify]) { ?>
                        <input type=button value='������ȣ ����' onclick="hp_certify(this.form);">  
                        ������ȣ : <input class=m_text type=text name='mb_hp_certify' size=6 maxlength=6> 6�ڸ� ����<br> 
                        <span> 
                            �ڵ������� ���۵� ������ȣ�� �Է� �� ȸ�������� ����(Ȯ�� ��ư)�Ͻñ� �ٶ��ϴ�.<br> 
                        </span> 
                        <script> 
                        function hp_certify(f) { 
                            var pattern = /^01[0-9][-]{0,1}[0-9]{3,4}[-]{0,1}[0-9]{4}$/; 
                            if(!pattern.test(f.mb_hp.value)){  
                                alert("�ڵ��� ��ȣ�� �Էµ��� �ʾҰų� ��ȣ�� Ʋ���ϴ�.\n\n�ڵ��� ��ȣ�� 010-123-4567 �Ǵ� 01012345678 �� ���� �Է��� �ֽʽÿ�."); 
                                f.mb_hp.select(); 
                                f.mb_hp.focus(); 
                                return; 
                            } 
                            win_open("<?=$g4[sms_path]?>/hp_certify.php?hp="+f.mb_hp.value+"&token=<?=$token?>", "hiddenframe"); 
                        } 
                        </script>
                    <? } ?>
                <? } else { ?>
                    <input class="form-control" type=text name='mb_hp' size=21 maxlength=20 <?=$config[cf_req_hp]?'required':'';?> itemname='�ڵ�����ȣ' value='<?=$member[mb_hp]?>'>
                <? } ?>
            </div>
        </div>
        <? } ?>

        <? if ($config[cf_use_tel]) { ?>
        <div class="form-group">
            <label for="mb_tel" class="col-sm-2 control-label">��ȭ��ȣ</label>
            <div class="col-sm-6">
                <INPUT class="form-control" type="text" name="mb_tel" size=21 maxlength=20 <?=$config[cf_req_tel]?'required':'';?> itemname='��ȭ��ȣ' value='<?=$member[mb_tel]?>' placeholder="Telephone no.">
            </div>
        </div>
        <? } ?>

        <? if ($config[cf_use_homepage]) { ?>
        <div class="form-group">
            <label for="mb_homepage" class="col-sm-2 control-label">Ȩ������</label>
            <div class="col-sm-6">
                <INPUT class="form-control" type="text" name="mb_homepage" id="mb_homepage" size=38 maxlength=255 <?=$config[cf_req_homepage]?'required':'';?> itemname='Ȩ������' value='<?=$member[mb_homepage]?>' placeholder="Homepage URL">
            </div>
        </div>
        <? } ?>

        <? if ($config[cf_use_addr]) { ?>
        <div class="form-group">
            <label class="col-sm-2 control-label">�ּ�</label>
            <div class="col-sm-6">
                <input class=m_text type=text name='mb_zip1' size=4 maxlength=3 readonly <?=$config[cf_req_addr]?'required':'';?> itemname='�����ȣ ���ڸ�' value='<?=$member[mb_zip1]?>'>
                - 
                <input class=m_text type=text name='mb_zip2' size=4 maxlength=3 readonly <?=$config[cf_req_addr]?'required':'';?> itemname='�����ȣ ���ڸ�' value='<?=$member[mb_zip2]?>'>
                &nbsp;<a href="javascript:;" onclick="win_zip('fregisterform', 'mb_zip1', 'mb_zip2', 'mb_addr1', 'mb_addr2');"><img width="91" height="20" src="<?=$member_skin_path?>/img/post_search_btn.gif" border=0 align=absmiddle></a>
                <input class="form-control" type=text name='mb_addr1' size=60 readonly <?=$config[cf_req_addr]?'required':'';?> itemname='�ּ�' value='<?=$member[mb_addr1]?>'>
                <input class="form-control" type=text name='mb_addr2' size=60 <?=$config[cf_req_addr]?'required':'';?> itemname='���ּ�' value='<?=$member[mb_addr2]?>'>
            </div>
        </div>
        <? } ?>

        <div class="form-group">
            <label for="mb_mailling" class="col-sm-2 control-label">���ϸ�����</label>
            <div class="col-sm-6">
            <div class="checkbox">
						<label>
                <input type="checkbox" name="mb_mailling" id="mb_mailling" value='1' <?=($w=='' || $member[mb_mailling])?'checked':'';?>>
                ���� ������ �ްڽ��ϴ�.
						</label>
						</div>
            </div>
        </div>

        <? if ($config[cf_use_hp]) { ?>
        <div class="form-group">
            <label for="mb_sms" class="col-sm-2 control-label">SMS ���ſ���</label>
            <div class="col-sm-6">
            <div class="checkbox">
						<label>
                <input type="checkbox" name="mb_sms" value='1' <?=($w=='' || $member[mb_sms])?'checked':'';?>>
                �ڵ��� ���ڸ޼����� �ްڽ��ϴ�.
						</label>
						</div>
            </div>
        </div>
        <? } ?>

        <? if ($member[mb_open_date] <= date("Y-m-d", $g4[server_time] - ($config[cf_open_modify] * 86400)) || !$member['mb_open']) { // �������� �������� �����ٸ� �������� ?>
        <input type=hidden name=mb_open_default value='<?=$member[mb_open]?>'>
        <div class="form-group">
            <label for="mb_open" class="col-sm-2 control-label">��������</label>
            <div class="col-sm-6">
            <div class="checkbox">
						<label>
                <input type="checkbox" name="mb_open" id="mb_open" value='1' <?=($w=='' || $member[mb_open])?'checked':'';?>>
                �ٸ� ȸ������ ���� ������ �� �� �ֵ��� �մϴ�.
						</label>
						</div>
                <? if ($config[cf_open_modify]) { ?>
                <p class="help-block">���������� �ٲٽø� ������ <?=(int)$config[cf_open_modify]?>�� �̳����� ������ �ȵ˴ϴ�.</p>
                <? } ?>
            </div>
        </div>        
        <? } else { ?>
        <input type=hidden name="mb_open" value="<?=$member[mb_open]?>">
        <div class="form-group">
            <?
            $d_times = (int)(($config[cf_open_modify] * 86400 - ( $g4[server_time] - strtotime($member[mb_open_date]))) / 86400) + 1;
            if ($member[mb_open]) $msg="��������"; else $msg = "���������";
            ?>
            <label for="mb_open" class="col-sm-2 control-label">��������</label>
            <div class="col-sm-6">
                <p class="help-block">
                ���� <?=$msg?> �����̸�, <?=$member[mb_open_date]?>�� ������ �����߽��ϴ�.<br>
                ���������� ������ <?=(int)$config[cf_open_modify]?>�� �̳�, <?=date("Y�� m�� j��", strtotime("$member[mb_open_date] 00:00:00") + ($config[cf_open_modify] * 86400))?> ������ ������ �ȵ˴ϴ�.<br> 
                </p>
            </div>
        </div>         
        <? } ?>

        <? if ($config[cf_use_signature]) { ?>
        <div class="form-group">
            <label for="mb_open" class="col-sm-2 control-label">����</label>
            <div class="col-sm-6">
                <textarea name=mb_signature class="form-control" rows=3 style='width:100%;' <?=$config[cf_req_signature]?'required':'';?> itemname='����'><?=$member[mb_signature]?></textarea>
            </div>
        </div>
        <? } ?>

        <? if ($config[cf_use_profile]) { ?>
        <div class="form-group">
            <label for="mb_open" class="col-sm-2 control-label">�ڱ�Ұ�</label>
            <div class="col-sm-6">
                <textarea name=mb_profile class="form-control" rows=3 style='width:100%;' <?=$config[cf_req_profile]?'required':'';?> itemname='�ڱ� �Ұ�'><?=$member[mb_profile]?></textarea>            </div>
            </div>
        </div>
        <? } ?>

        <? if ($member[mb_level] >= $config[cf_icon_level]) { ?>
        <div class="form-group">
            <label for="mb_open" class="col-sm-2 control-label">ȸ��������</label>
            <div class="col-sm-6">
                <INPUT type=file name='mb_icon' size=30>
    						<div>
                * �̹��� ũ��� ����(<?=$config[cf_member_icon_width]?>�ȼ�)x����(<?=$config[cf_member_icon_height]?>�ȼ�) ���Ϸ� ���ּ���.<br>&nbsp;&nbsp;(gif/jpg/bmp/png�� ���� / �뷮:<?=number_format($config[cf_member_icon_size]/1000)?>k ����Ʈ ���ϸ� ��ϵ˴ϴ�.)
                <? if ($w == "u" && file_exists($mb_icon)) { ?>
                    <br><img src='<?=$mb_icon?>' align=absmiddle> <input type=checkbox name='del_mb_icon' value='1'>����
                <? } ?>
                </div>
            </div>
        </div>
        <? } ?>

        <? if ($w == "" && $config[cf_use_recommend]) { ?>
        <div class="form-group">
            <label for="mb_recommend" class="col-sm-2 control-label">��õ�ξ��̵�</label>
            <div class="col-sm-6">
                <? if ($mb_recommend) { ?>
                    <input type=hidden name=mb_recommend         id=mb_recommend            value="<?=$mb_recommend?>">
                    <?=$mb_recommend?>
                <? } else { ?>
                    <input  class="form-control" type=text name=mb_recommend maxlength=20 size=20 <?=$config[cf_req_recommend]?'required':'';?> itemname='��õ�ξ��̵�'>
                <? } ?>
                <? if ($config[cf_recommend_point]) { ?>
                    *��õ ȸ������ <?=$config[cf_recommend_point]?> ����Ʈ�� �����մϴ�.
                <? } ?>
            </div>
        </div>
        <? } else if ($config[cf_use_recommend] && $member[mb_recommend]) {?>
        <div class="form-group">
            <label for="mb_recommend" class="col-sm-2 control-label">��õ�ξ��̵�</label>
            <div class="col-sm-6">
                <? $mb=get_member($member['mb_recommend'], "mb_id, mb_nick")?>
                <?=get_sideview($mb['mb_id'], $mb['mb_nick'])?>
            </div>
        </div>
        <? } ?>

        <? if ($config[cf_use_norobot]) { ?>
        <div class="form-group">
            <label for="wr_key" class="col-sm-2 control-label">
                <script type="text/javascript" src="<?="$g4[path]/zmSpamFree/zmspamfree.js"?>"></script>
                <img id="zsfImg">
            </label>
            <div class="col-sm-6">
                <input class="form-control" type="input" size=10 name=wr_key id=wr_key itemname="�ڵ���Ϲ���" required placeholder="Captcha">
                <p class="help-block">������ ���ڸ� �Է��ϼ���.</p>
            </div>
        </div>        
        <? } ?>

        <? if ($w == "u") { ?>
        <div class="form-group">
            <label class="col-sm-2 control-label">������</label>
            <div class="col-sm-6">
                <p class="help-block"><?=$member[mb_datetime]?></p>
            </div>
        </div>
        <? } ?>

    </div>
    <div class="panel-footer">
    <div style="text-align:center">
    <? if ($w == "") { ?>
        <button class="btn btn-success">�� ��</button>
    <? } else { ?>
        <button class="btn btn-success">�� ��</button>
    <? } ?>
    </style>

    <? if ($is_member) { ?> 
    <a href="javascript:member_leave();" class="btn btn-default pull-right">ȸ��Ż��</a>
    <? } ?> 
    </div>

    </div>
</div>
</form> 

<script type="text/javascript">
// ȸ�����Խ� -> mb_id�� focus, ������ -> mb_password�� focus
$(document).ready(function(){
    if ($('#w').val() == '')
        $('#mb_id').focus();
    else {
        $('#mb_password').focus();
    }
});

// submit ���� ��üũ
function fregisterform_submit(f) 
{
    // ȸ�����̵� �˻�
    if (f.w.value == "") {

        reg_mb_id_check();

        if (f.mb_id_enabled.value != '000') {
            alert('ȸ�����̵� �Է����� �ʾҰų� �Է¿� ������ �ֽ��ϴ�.');
            f.mb_id.focus();
            return false;
        }
    }

    if (f.w.value == '') {
        if ($.trim(f.mb_password.value).length < 8) {
            alert('�н����带 8���� �̻� �Է��Ͻʽÿ�.');
            f.mb_password.focus();
            return false;
        }
    }

    if ($.trim(f.mb_password.value).length > 0) {
        if ($.trim(f.mb_password_re.value).length < 8) {
            alert('�н����带 8���� �̻� �Է��Ͻʽÿ�.');
            f.mb_password_re.focus();
            return false;
        }
    }

    if (f.mb_password.value != f.mb_password_re.value) {
        alert('�н����尡 ���� �ʽ��ϴ�.');
        f.mb_password_re.focus();
        return false;
    }

    <? if ($g4['nick_reg_only'] !== 1) { ?>
    // �̸� �˻�
    if (f.w.value == "") {

        reg_mb_name_check();

        if (f.mb_name_enabled.value != '000') {
            alert('�̸��� �Է����� �ʾҰų� �Է¿� ������ �ֽ��ϴ�.');
            f.mb_name.focus();
            return false;
        }
    }
    <? } ?>

    // ���� �˻�
    if ((f.w.value == "") ||
        (f.w.value == "u" && f.mb_nick.defaultValue != f.mb_nick.value)) {

        reg_mb_nick_check();

        if (f.mb_nick_enabled.value != '000') {
            alert('������ �Է����� �ʾҰų� �Է¿� ������ �ֽ��ϴ�.');
            f.mb_nick.focus();
            return false;
        }
    }

    // E-mail �˻�
    if ((f.w.value == "") ||
        (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {

        reg_mb_email_check();

        if (f.mb_email_enabled.value != '000') {
            alert('E-mail�� �Է����� �ʾҰų� �Է¿� ������ �ֽ��ϴ�.');
            f.mb_email.focus();
            return false;
        }
    }

    if (typeof(f.mb_birth) != 'undefined') {
        if ($.trim(f.mb_birth.value).length < 1) {
            alert('�޷� ��ư�� Ŭ���Ͽ� ������ �Է��Ͽ� �ֽʽÿ�.');
            //f.mb_birth.focus();
            return false;
        }

        var todays = <?=date("Ymd", $g4['server_time']);?>;
        // ���ó�¥���� ������ ���� �ű⼭ 140000 �� ����.
        // ����� 0 �̻��� ����̸� �� 14���� ��������
        var n = todays - parseInt(f.mb_birth.value) - 140000;
        if (n < 0) {
            alert("�� 14���� ������ ���� ��̴� ������Ÿ� �̿����� �� ������ȣ � ���� ����\n\n�� 31�� 1���� ������ ���Ͽ� �����븮���� ���Ǹ� ���� �ϹǷ�\n\n�����븮���� �̸��� ����ó�� '�ڱ�Ұ�'���� ������ �Է��Ͻñ� �ٶ��ϴ�.");
            return false;
        }
    }

    if (typeof(f.mb_sex) != 'undefined') {
        if (f.mb_sex.value == '') {
            alert('������ �����Ͽ� �ֽʽÿ�.');
            f.mb_sex.focus();
            return false;
        }
    }

    if (typeof f.mb_icon != 'undefined') {
        if (f.mb_icon.value) {
            if (!f.mb_icon.value.toLowerCase().match(/.(gif|bmp|jpg|png)$/i)) {
                alert('ȸ���������� gif|jpg|bmp|png ������ �ƴմϴ�.');
                f.mb_icon.focus();
                return false;
            }
        }
    }

    if (typeof(f.mb_recommend) != 'undefined') {
        if (f.mb_id.value == f.mb_recommend.value) {
            alert('������ ��õ�� �� �����ϴ�.');
            f.mb_recommend.focus();
            return false;
        }
    }

    if (typeof(f.wr_key) != 'undefined') {
        if (!checkFrm()) {
            alert ("���Թ����ڵ�(Captcha Code)�� Ʋ�Ƚ��ϴ�. �ٽ� �Է��� �ּ���.");
            return false;
        }
    }

    <?
    if ($g4[https_url])
        echo "f.action = '$g4[https_url]/$g4[bbs]/register_form_update.php';";
    else
        echo "f.action = './register_form_update.php';";
    ?>

    // ������������ �ڵ�� �ݵ�� ���ԵǾ�� �մϴ�.
    set_cookie("<?=md5($token)?>", "<?=base64_encode($token)?>", 1, "<?=$g4['cookie_domain']?>");

    return true;
}

// ȸ�� Ż�� 
function member_leave() 
{ 
    if (confirm("���� ȸ������ Ż�� �Ͻðڽ��ϱ�?")) 
            location.href = "<?=$g4[bbs_path]?>/mb_leave.php"; 
}
</script>
