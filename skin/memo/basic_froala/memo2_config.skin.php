<div class="container">
    <strong><a href="<?=$memo_url?>?kind=<?=$kind?>"><?=$memo_title?></a></strong>

    <form  role="form" class="form-inline" name='fmemoform' method='post' enctype='multipart/form-data' onsubmit="return fmemoconfig_submit(this);">
    <input type=hidden name=mb_memo_no_reply_org value="<?=$member[mb_memo_no_reply]?>">

    <div class="panel panel-default">
    <div class="panel-heading">���μ���</div>
    <div class="panel-body">
        <? if ($config[cf_memo_user_config]) { ?>
  			<table width="100%" class="table table-hover table-condensed table-borderless" style="border:none;">
        <?
        $time_diff = ($g4[server_time] - (86400 * $config['cf_memo_no_reply'])) - strtotime($member[mb_memo_no_reply_datetime]);
        ?>
        <tr>
            <td width="150px;">�����߼���</td>
            <td>
                <? if ($config['cf_memo_no_reply'] && $time_diff > 0) { ?>
                    <input type=checkbox name=mb_memo_no_reply value='1' <?=($member[mb_memo_no_reply])?'checked':'';?>>���ŵ� ������ �ڵ����� ������ ������ �����ϴ�.<BR>
                <? } else { ?>
                    <?=$member[mb_memo_no_reply_datetime]?>�� ������ ������ �����ϼ̽��ϴ�.<br>
                <? } ?>
                <strong>�����߼��� ������ �����ϸ� <font color="red"><?=$config['cf_memo_no_reply']?> ��</font> �Ŀ� ������ �����մϴ�.</strong>
            </td>
        </tr>
        <tr>
            <?
            if (!$member['mb_memo_no_reply_text'])
                $member['mb_memo_no_reply_text'] = "������ �������� �������� ������ �Դϴ�. �����ֽ� ������ ������ ������ ������ Ȯ���� ���� �帮�ڽ��ϴ�";
            ?>
            <td>�����߾˸��޸�</td>
            <td><textarea class=ed name='mb_memo_no_reply_text' rows=5 style='width:98%;'><?=stripslashes($member[mb_memo_no_reply_text])?></textarea>
        </tr>
        </table>
        <? } else { ?>
            ������ �׸��� �����ϴ�.
        <? } ?>
    </div>
    </div>


    <? if ($is_admin == 'super') { ?>
    <? include_once("$g4[admin_path]/admin.lib.php")?>
    <div class="panel panel-default">
    <div class="panel-heading">�ý��ۼ���</div>
    <div class="panel-body">
  			<table width="100%" class="table table-hover table-condensed table-borderless">
        <tr>
            <td width="150px;">�������� ��ϼ�</td>
            <td><input type=text required name='cf_memo_page_rows' size='5' required itemname='�������� ���' value='<?=$config[cf_memo_page_rows]?>'>
            </td>
        </tr>
        <tr>
            <td>���������� ���� ����Ʈ</td>
            <td><input type=text required name='cf_memo_send_point' size='5' required itemname='�������۽� ���� ����Ʈ' value='<?=$config[cf_memo_send_point]?>'> ��
            <!-- <BR>(����� �Է��Ͻʽÿ�. 0���� �Է��Ͻø� ���������� ����Ʈ�� �������� �ʽ��ϴ�.) -->
            </td>
        </tr>
        <tr>
            <td>���� ����</td>
            <td><input type=text class=ed name='cf_memo_del' value='<?=$config[cf_memo_del]?>' size=5 required > �� (�������� ���� ���� �ڵ� ����)
            </td>
        </tr>
        <tr>
            <td>������ ���� ����</td>
            <td><input type=text class=ed name='cf_memo_del_unread' value='<?=$config[cf_memo_del_unread]?>' size=5 required > �� (���� �����Ϻ��� ũ�ų� ����)
            </td>
        </tr>
        <tr>
            <td>������ �����ϼ�</td>
            <td><input type=text class=ed name='cf_memo_del_trash' value='<?=$config[cf_memo_del_trash]?>' size=5 required > �� (�������� ���� ������ �����뿡�� ����)
            </td>
        </tr>
        <tr>
            <td>dhtml ������</td>
            <td><input type=checkbox name=cf_memo_user_dhtml value='1' <?=($config[cf_memo_user_dhtml])?'checked':'';?>>dhtml ������ ���
            </td>
        </tr>
        <tr>
            <td width="150">��������Ʈ</td>
            <td><input type=checkbox name=cf_memo_print value='1' <?=($config[cf_memo_print])?'checked':'';?>> ���
            </td>
        </tr>
        <tr>
            <td>����/���� ��������</td>
            <td><input type=checkbox name=cf_memo_before_after value='1' <?=($config[cf_memo_before_after])?'checked':'';?>> ���
            </td>
        </tr>
        <tr>
            <td>÷������</td>
            <td><input type=checkbox name=cf_memo_use_file value='1' <?=($config[cf_memo_use_file])?'checked':'';?>>÷������ ��ɻ��
            </td>
        </tr>
        <tr>
            <td>�ִ�÷�����Ͽ뷮</td>
            <td><input type=text class=ed name='cf_memo_file_size' value='<?=$config[cf_memo_file_size]?>' size=5 required > M(�ް�) (�����ִ�뷮 : <?=ini_get("upload_max_filesize")?>, �Է¿�: 2, 4, 8)
            </td>
        </tr>
        <tr>
            <td>���κ�÷������ �ִ��ѵ�</td>
            <td><input type=text class=ed name='cf_max_memo_file_size' value='<?=$config[cf_max_memo_file_size]?>' size=5 required > M(�ް�) (�Է¿�, 0:���Ѿ���, 50, 100 )
            </td>
        </tr>
        <!--
        <tr>
            <td>���������� ÷�����ϻ���</td>
            <td><input type=checkbox name=cf_memo_del_file value='1' <?=($config[cf_memo_del_file])?'checked':'';?>>÷�������� �������� �����ϴ� ��ɻ��
            </td>
        </tr>
  			<tr>
            <td>�ǽð�����</td>
            <td>
            <input type=checkbox name=cf_memo_realtime value='1' <?=($config[cf_memo_realtime])?'checked':'';?>> �ǽð����� ����� ���
            </td>
        </tr>
        -->
        <tr>
            <td>ģ������</td>
            <td><input type=checkbox name=cf_friend_management value='1' <?=($config[cf_friend_management])?'checked':'';?>>ģ������ ��ɻ��
            </td>
        </tr>
        <tr>
            <td>�����߼��� ��������</td>
            <td><?=get_member_level_select(cf_memo_no_reply, 0, 7, $config[cf_memo_no_reply])?> �� (0 ���� �����ϸ� �����߼����� ������� ����)
            </td>
        </tr>
        <tr>
            <td>�����޸�</td>
            <td><textarea class=ed name='cf_memo_notice_memo' rows=3 style='width:98%;'><?=$config[cf_memo_notice_memo]?></textarea>
            </td>
        </tr>
  			<tr>
            <td>�Ҵ�resize/�Ҵ��</td>
            <td>
            <input type=checkbox name=cf_memo_b4_resize value='1' <?=($config[cf_memo_b4_resize])?'checked':'';?>> �Ҵ�resize/�Ҵ�� ����� ���
            </td>
        </tr>
   			<tr>
            <td>�̸��� �⺻���� ���</td>
            <td>
            <input type=checkbox name=cf_memo_mb_name value='1' <?=($config[cf_memo_mb_name])?'checked':'';?>> ��ϵ�� �̸��� �⺻���� ���
            </td>
        </tr>
  			<tr>
            <td>DB�� ���� ÷������ ����</td>
            <td>
	          <a href="#" onclick="tmpFileChk();">�����ϱ�</a>
            </td>
        </tr>
        </table>
    </div>
    </div>
    <? } ?>

    <div class="pull-right">
        <button type="submit" class="btn btn-success" id="btn_submit">Send</button>
    </div>

    </form>

</div>

<script type="text/javascript">
function fmemoconfig_submit(f) {
    f.action = "./memo2_config_update.php";
    return true;
}

function tmpFileChk(){
	if(confirm('���� DB�� ���� ÷�������� �����մϱ�?')){
		location.href="./memo2_chkunlinkfile.php";
	}
}
</script>