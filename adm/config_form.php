<?
$sub_menu = "100100";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$token = get_token();

if ($is_admin != "super")
    alert("�ְ�����ڸ� ���� �����մϴ�.");

// �Ҵ��� - ��Ͽ� ���õ� ������ �߰��� �о� ���Դϴ�.
$config = get_config("reg");

$g4['title'] = "�⺻ȯ�漳��";
include_once ("./admin.head.php");
?>

<form name='fconfigform' method='post' onsubmit="return fconfigform_submit(this);" role="form" class="form-inline">
<input type=hidden name=token value='<?=$token?>'>

<table width=100% class="table table-hover" style="word-wrap:break-word;">
<tr class='success'>
    <td colspan=2>�⺻ ����</td>
</tr>
<tr>
    <td class="col-sm-2">Ȩ������ ����</td>
    <td>
        <input type=text  name='cf_title' size='30' required itemname='Ȩ������ ����' value='<?=$config[cf_title]?>'>
    </td>
</tr>
<tr>
    <td>�ְ������</td>
    <td><?=get_member_id_select("cf_admin", 10, $config[cf_admin], "required itemname='�ְ� ������'")?></td>
</tr>
<tr>
    <td>����Ʈ ���</td>
    <td><input type='checkbox' name='cf_use_point' value='1' <?=$config[cf_use_point]?'checked':'';?>> ���</td>
</tr>
<tr>
    <td>�α��ν� ����Ʈ</td>
    <td><input type=text  name='cf_login_point' size='5' required itemname='�α��ν� ����Ʈ' value='<?=$config[cf_login_point]?>'> ��
        <?=help("ȸ������ �Ϸ翡 �ѹ��� �ο�")?>
    </td>
</tr>
<tr>
    <td>���������� ���� ����Ʈ</td>
    <td><input type=text  name='cf_memo_send_point' size='5' required itemname='�������۽� ���� ����Ʈ' value='<?=$config[cf_memo_send_point]?>'> ��
        <?=help("����� �Է��Ͻʽÿ�.<br>0���� �Է��Ͻø� ���������� ����Ʈ�� �������� �ʽ��ϴ�.")?></td>
</tr>
<tr>
    <td>�̸�(����) ǥ��</td>
    <td><input type=text  name='cf_cut_name' value='<?=$config[cf_cut_name]?>' size=2> �ڸ��� ǥ��
        <?=help("������ 2���� = �ѱ� 1����")?></td>
</tr>
<tr>
    <td>���� ����</td>
    <td>������ �� <input type=text  name='cf_nick_modify' value='<?=$config[cf_nick_modify]?>' size=2> �� ���� �ٲ� �� ����</td>
</tr>
<tr>
    <td>�������� ����</td>
    <td>������ �� <input type=text  name='cf_open_modify' value='<?=$config[cf_open_modify]?>' size=2> �� ���� �ٲ� �� ����</td>
</tr>
<tr>
    <td>�ֱٰԽù� ����</td>
    <td><input type=text  name='cf_new_del' value='<?=$config[cf_new_del]?>' size=5> ��
        <?=help("�������� ���� �ֱٰԽù� �ڵ� ����")?></td>
</tr>
<tr> 
    <td>���� ����</td>
    <td><input type=text  name='cf_memo_del' value='<?=$config[cf_memo_del]?>' size=5> ��
        <?=help("�������� ���� ���� �ڵ� ����")?></td>
</tr> 
<tr>
    <td>�����ڷα� ����</td>
    <td><input type=text  name='cf_visit_del' value='<?=$config[cf_visit_del]?>' size=5> ��
        <?=help("�������� ���� ������ �α� �ڵ� ����")?></td>
</tr>
<tr>
    <td>�α�˻��� ����</td>
    <td><input type=text  name='cf_popular_del' value='<?=$config[cf_popular_del]?>' size=5> ��
        <?=help("�������� ���� �α�˻��� �ڵ� ����")?></td>
</tr>
<tr>
    <td>���� ������</td>
    <td><input type=text  name='cf_login_minutes' value='<?=$config[cf_login_minutes]?>' size=5> ��
        <?=help("������ �̳��� �����ڸ� ���� �����ڷ� ����")?></td>
</tr>
<tr>
    <td>���������� ���μ�</td>
    <td><input type=text  name='cf_page_rows' value='<?=$config[cf_page_rows]?>' size=5> ����
        <?=help("���(����Ʈ) ���������� ���μ�")?></td>
</tr>
<tr>
    <td>�ֱٰԽù� ��Ų</td>
    <td><select id=cf_new_skin name=cf_new_skin required itemname="�ֱٰԽù� ��Ų">
        <?
        $arr = get_skin_dir("new");
        for ($i=0; $i<count($arr); $i++) {
            echo "<option value='$arr[$i]'>$arr[$i]</option>\n";
        }
        ?></select>
        <script language="JavaScript"> document.getElementById('cf_new_skin').value="<?=$config[cf_new_skin]?>";</script>
    </td>
</tr>
<tr>
    <td>�ֱٰԽù� ���μ�</td>
    <td><input type=text  name='cf_new_rows' value='<?=$config[cf_new_rows]?>' size=5> ����
        <?=help("��� ���������� ���μ�")?></td>
</tr>
<tr>
    <td>�˻� ��Ų</td>
    <td><select id=cf_search_skin name=cf_search_skin required itemname="�˻� ��Ų">
        <?
        $arr = get_skin_dir("search");
        for ($i=0; $i<count($arr); $i++) {
            echo "<option value='$arr[$i]'>$arr[$i]</option>\n";
        }
        ?></select>
        <script language="JavaScript"> document.getElementById('cf_search_skin').value="<?=$config[cf_search_skin]?>";</script>
    </td>
</tr>
<tr>
    <td>������ ��Ų</td>
    <td><select id=cf_connect_skin name=cf_connect_skin required itemname="������ ��Ų">
        <?
        $arr = get_skin_dir("connect");
        for ($i=0; $i<count($arr); $i++) {
            echo "<option value='$arr[$i]'>$arr[$i]</option>\n";
        }
        ?></select>
        <script language="JavaScript"> document.getElementById('cf_connect_skin').value="<?=$config[cf_connect_skin]?>";</script>
    </td>
</tr>
<tr>
    <td>����, �̵��� �α�</td>
    <td><input type='checkbox' name='cf_use_copy_log' value='1' <?=$config[cf_use_copy_log]?'checked':'';?>> ����
        <?=help("�Խù� �Ʒ��� ������ ���� ����, �̵��� ǥ��")?></td>
</tr>
<tr>
    <td>���ٰ��� IP</td>
    <td valign=top><textarea  name='cf_possible_ip' rows='5' style='width:99%;'><?=$config[cf_possible_ip]?> </textarea><br>�Էµ� IP�� ��ǻ�͸� ������ �� ����.<br>123.123.+ �� �Է� ����. (���ͷ� ����)</td>
</tr>
<tr>
    <td>�������� IP</td>
    <td valign=top><textarea  name='cf_intercept_ip' rows='5' style='width:99%;'><?=$config[cf_intercept_ip]?> </textarea><br>�Էµ� IP�� ��ǻ�ʹ� ������ �� ����.<br>123.123.+ �� �Է� ����. (���ͷ� ����)</td>
</tr>

<tr>
    <td colspan=2 align=left class="success">�⺻ ����(2)</td>
</tr>
<tr>
    <td>�ڵ���Ϲ��� ���</td>
    <td><input type='checkbox' name='cf_use_norobot' value='1' <?=$config[cf_use_norobot]?'checked':'';?>> ���
        <?=help("�ڵ� ȸ�����԰� �۾��⸦ ����")?></td>
</tr>
<tr>
    <td>�������� �����ֱ�</td>
    <td>
    <input type=text  name='cf_password_change_dates' value='<?=$config[cf_password_change_dates]?>' size=5> ��
        <?=help("�������� �����ֱ� ���Ŀ� �������� ������ �ϵ��� ���� �մϴ�. 0���� �����ϸ� �������� ���� ��û�� ���� �ʽ��ϴ�.")?>
    </td>
</tr>
<tr>
    <td>�ߺ��α���</td>
    <td>
    <input type=text  name='cf_double_login' value='<?=$config[cf_double_login]?>' size=5>���� �ߺ� �α����� ���
        <?=help("�ߺ��� �α����� ����� ����� ������ ���θ� �����մϴ�.")?>
    </td>
</tr>
<tr>
    <td>���̵� Retry Interval</td>
    <td>
    <input type=text  name='cf_retry_time_interval' value='<?=$config[cf_retry_time_interval]?>' size=5> ��
        <?=help("������ �ð����� ���̵�/����� ������ �߻��ϴ� Ƚ���� count �Ͽ�, �� Ƚ���� ������ ��� ��ŷ���� ���� �մϴ�.")?>
    </td>
</tr>
<tr>
    <td>���̵� Retry Ƚ��</td>
    <td>
    <input type=text  name='cf_retry_count' value='<?=$config[cf_retry_count]?>' size=5> ȸ
        <?=help("�α��� ���� ��踦 ���� ������ �ð��� Ƚ���� �����Ͻø� �˴ϴ�.")?>
    </td>
</tr>

<tr>
    <td colspan=2 align=left class="success">Ȩ������ ��Ÿ���� ����</td>
</tr>
<tr>
    <td>�ۼ���(Author)</td>
    <td>
    <input type=text  name='cf_meta_author' value='<?=$config[cf_meta_author]?>' size=30>
        <?=help("Ȩ������ �ۼ���")?></td>
</tr>
<tr>
    <td>Ű���� (Keywords)</td>
    <td>
    <input type=text  name='cf_meta_keywords' value='<?=$config[cf_meta_keywords]?>' size=100>
        <?=help("Ȩ������ Ű����, �޸��� ������ �ݴϴ�.")?>
    </td>
</tr>
<tr>
    <td>���� (Description)</td>
    <td>
    <input type=text  name='cf_meta_description' value='<?=$config[cf_meta_description]?>' size=100>
        <?=help("Ȩ�������� ���� ����")?>
    </td>
</tr>

<tr>
    <td colspan=2 align=left class="success">������ ����</td>
</tr>
<tr>
    <td>������ ���</td>
    <td><input type='checkbox' name='cf_use_recycle' value='1' <?=$config[cf_use_recycle]?'checked':'';?>> ���
        <?=help("������ ��� ���θ� �����մϴ�.")?></td>
</tr>
<tr>
    <td>������ ���̺�</td>
    <td>
    <input type=text  name='cf_recycle_table' value='<?=$config[cf_recycle_table]?>' size=20>
        <?=help("������ ���̺��̸� (\$bo_table) ���� �����ݴϴ�.")?>
    </td>
</tr>
<tr>
    <td>������ ���� �ϼ�</td>
    <td>
    <input type=text  name='cf_recycle_days' value='<?=$config[cf_recycle_days]?>' size=20>
        <?=help("n���Ŀ� �������� ����ݴϴ�.")?>
    </td>
</tr>

<tr>
    <td colspan=2 align=left class="success">�Ű���</td>
</tr>
<tr>
    <td>�Խñ� �Ű�ó��</td>
    <td>
    <input type=text  name='cf_singo_intercept_count' value='<?=$config[cf_singo_intercept_count]?>' size=5> ȸ
        <?=help("�Խñ� �Ű�Ǽ��� ������ Ƚ���� �ʰ��ϸ� ó��")?>
    </td>
</tr>
<tr>
    <td>�Ϸ� �Ű��� Ƚ��</td>
    <td>
    <input type=text  name='cf_singo_today_count' value='<?=$config[cf_singo_today_count]?>' size=5> ȸ
        <?=help("�Ϸ����� �Խñ� �Ű�Ǽ��� ������ Ƚ���� �ʰ��ϸ� �Ű��� �� �����ϴ�")?>
    </td>
</tr>
<tr>
    <td>�Ű��� ����</td>
    <td>
    <? echo get_member_level_select('cf_singo_level', 2, 9, $config[cf_singo_level]) ?>
        <?=help("�Խñ� �Ű� �� �� �ִ� ȸ���� �ּ� ����. ������ �̴��ϸ� �Ű��� �� �����ϴ�")?>
    </td>
</tr>
<tr>
    <td>�Ű��� ����Ʈ</td>
    <td>
    <input type=text  name='cf_singo_point' value='<?=$config[cf_singo_point]?>' size=5> ��
        <?=help("�Խñ� �Ű� �� �� �ִ� ȸ���� �ּ� ����Ʈ. ����Ʈ�� �̴��ϸ� �Ű��� �� �����ϴ�")?>
    </td>
</tr>
<tr>
    <td>�Ű��� ��� ��������Ʈ</td>
    <td>
    <input type=text  name='cf_singo_point_send' value='<?=$config[cf_singo_point_send]?>' size=5> ��
        <?=help("�Ű��� ������Լ� ������ ����Ʈ�� ���� �մϴ�. 0 �̻����� �����Ǹ� �Ű�ǿ� ���ؼ� ����Ʈ�� �����մϴ�.")?>
    </td>
</tr>
<tr>
    <td>�Ű�� ��� ��������Ʈ</td>
    <td>
    <input type=text  name='cf_singo_point_recv' value='<?=$config[cf_singo_point_recv]?>' size=5> ��
        <?=help("�Ű�� ������Լ� ������ ����Ʈ�� ���� �մϴ�. 0 �̻����� �����Ǹ� �Ű�ǿ� ���ؼ� ����Ʈ�� ���� �մϴ�.")?>
    </td>
</tr>

<tr>
    <td colspan=2 align=left class="success">�Խ��� ����</td>
</tr>
<tr>
    <td>���б� ����Ʈ</td>
    <td><input type=text  name='cf_read_point' size='10' required itemname='���б� ����Ʈ' value='<?=$config[cf_read_point]?>'> ��</td>
</tr>
<tr>
    <td>�۾��� ����Ʈ</td>
    <td><input type=text  name='cf_write_point' size='10' required itemname='�۾��� ����Ʈ' value='<?=$config[cf_write_point]?>'> ��</td>
</tr>
<tr>
    <td>�ڸ�Ʈ���� ����Ʈ</td>
    <td><input type=text  name='cf_comment_point' size='10' required itemname='�亯, �ڸ�Ʈ���� ����Ʈ' value='<?=$config[cf_comment_point]?>'> ��</td>
</tr>
<tr>
    <td>�ٿ�ε� ����Ʈ</td>
    <td><input type=text  name='cf_download_point' size='10' required itemname='�ٿ�ε�ޱ� ����Ʈ' value='<?=$config[cf_download_point]?>'> ��</td>
</tr>
<tr>
    <td>�ڸ�Ʈ ��������Ʈ ����</td>
    <td><input type=text  name='cf_no_comment_point_days' size='10' required itemname='�ڸ�Ʈ ���⿡ ����Ʈ �ο����� �ʴ� �Ⱓ' value='<?=$config[cf_no_comment_point_days]?>'> �� <?=help("����Ʈ�� �ø��� ���ؼ� ������ �ۿ� �ڸ�Ʈ �ٴ� ������� �ִ� ��츦 ���ؼ� ������ �ۿ� �ڸ�Ʈ �ٴ� �Ϳ� ����Ʈ �ο����� �ʴ� �Ⱓ�� ���ϴ� �� �Դϴ�.\n\n 0���� �νø� ������ �ۿ� �ڸ�Ʈ�� �޾Ƶ� ����Ʈ�� �ο��˴ϴ�.\n\n ���ڰ� �����Ǹ� �ۼ����� n���� �ۿ��� �ڸ�Ʈ�� �ᵵ ����Ʈ�� �ο����� �ʽ��ϴ�.")?></td>
</tr>
<tr>
    <td>LINK TARGET</td>
    <td><input type=text  name='cf_link_target' size='10' value='<?=$config[cf_link_target]?>'> 
        <?=help("�Խ��� ������ �ڵ����� ��ũ�Ǵ� â�� Ÿ���� �����մϴ�.\n\n_self, _top, _blank, _new �� �ַ� �����մϴ�.")?></td>
</tr>
<tr>
    <td>�˻� ����</td>
    <td><input type=text  name='cf_search_part' size='10' itemname='�˻� ����' value='<?=$config[cf_search_part]?>'> �� ������ �˻�</td>
</tr>
<tr>
    <td>�˻� ��� ����</td>
    <td><input type=text  name='cf_search_bgcolor' size='10' required itemname='�˻� ��� ����' value='<?=$config[cf_search_bgcolor]?>'></td>
</tr>
<tr>
    <td>�˻� ���� ����</td>
    <td><input type=text  name='cf_search_color' size='10' required itemname='�˻� ���� ����' value='<?=$config[cf_search_color]?>'></td>
</tr>
<tr>
    <td>���������� ���� ��</td>
    <td><input type=text  name='cf_write_pages' size='10' required itemname='������ ǥ�� ��' value='<?=$config[cf_write_pages]?>'> �������� ǥ��</td>
</tr>
<tr>
    <td>���������� ���� ��(xs)</td>
    <td><input type=text  name='cf_write_pages_xs' size='10' required itemname='������ ǥ�� ��' value='<?=$config[cf_write_pages_xs]?>'> �������� ǥ��
        <?=help("����� xs/ ȭ�鿡�� �������� ǥ�ü�")?></td>
    </td>
</tr>
<tr>
    <td>�̹��� ���ε� Ȯ����</td>
    <td><input type=text  name='cf_image_extension' size='80' itemname='�̹��� ���ε� Ȯ����' value='<?=$config[cf_image_extension]?>'>
        <?=help("�Խ��� ���ۼ��� �̹��� ���� ���ε� ���� Ȯ����. | �� ����")?></td>
</tr>
<tr>
    <td>�÷��� ���ε� Ȯ����</td>
    <td><input type=text  name='cf_flash_extension' size='80' itemname='�÷��� ���ε� Ȯ����' value='<?=$config[cf_flash_extension]?>'>
        <?=help("�Խ��� ���ۼ��� �÷��� ���� ���ε� ���� Ȯ����. | �� ����")?></td>
</tr>
<tr>
    <td>������ ���ε� Ȯ����</td>
    <td><input type=text  name='cf_movie_extension' size='80' itemname='������ ���ε� Ȯ����' value='<?=$config[cf_movie_extension]?>'>
        <?=help("�Խ��� ���ۼ��� ������ ���� ���ε� ���� Ȯ����. | �� ����")?></td>
</tr>
<tr>
    <td>�ܾ� ���͸�
        <?=help("�Էµ� �ܾ ���Ե� ������ �Խ��� �� �����ϴ�.\n\n�ܾ�� �ܾ� ���̴� ,�� �����մϴ�.")?></td>
    <td><textarea  name='cf_filter' rows='7' style='width:99%;word-break:break-all;'><?=$config[cf_filter]?> </textarea></td>
</tr>

<tr>
    <td colspan=2 align=left class="success">�۾������� ����</td>
</tr>
<tr>
    <td>���ο� �۾���</td>
    <td><input type=text  name='cf_delay_sec' size='10' required itemname='���ο� �۾���' value='<?=$config[cf_delay_sec]?>'> �� ������ ����</td>
</tr>
<tr>
    <td>�۾������� ���� ����</td>
    <td><? echo get_member_level_select('cf_delay_level', 2, 9, $config[cf_delay_level]) ?> �������� ���� ����</td>
</tr>
<tr>
    <td>�۾������� ���� ����Ʈ</td>
    <td><input type=text  name='cf_delay_point' size='10' required itemname='���ο� �۾��� ���� ����Ʈ' value='<?=$config[cf_delay_point]?>'> ����Ʈ���� ���� ����</td></td>
</tr>

<tr>
    <td colspan=2 align=left class="success">ȸ������ ����</td>
</tr>
<tr>
    <td>ȸ�� ��Ų</td>
    <td><select id=cf_member_skin name=cf_member_skin required itemname="ȸ������ ��Ų">
        <?
        $arr = get_skin_dir("member");
        for ($i=0; $i<count($arr); $i++) {
            echo "<option value='$arr[$i]'>$arr[$i]</option>\n";
        }
        ?></select>
        <script language="JavaScript"> document.getElementById('cf_member_skin').value="<?=$config[cf_member_skin]?>";</script>
    </td>
</tr>
<tr>
    <td>Ȩ������ �Է�</td>
    <td>
        <input type='checkbox' name='cf_use_homepage' value='1' <?=$config[cf_use_homepage]?'checked':'';?>> ���̱�
        <input type='checkbox' name='cf_req_homepage' value='1' <?=$config[cf_req_homepage]?'checked':'';?>> �ʼ��Է�
    </td>
</tr>
<tr>
    <td>�ּ� �Է�</td>
    <td>
        <input type='checkbox' name='cf_use_addr' value='1' <?=$config[cf_use_addr]?'checked':'';?>> ���̱�
        <input type='checkbox' name='cf_req_addr' value='1' <?=$config[cf_req_addr]?'checked':'';?>> �ʼ��Է�
    </td>
</tr>
<tr>
    <td>��ȭ��ȣ �Է�</td>
    <td>
        <input type='checkbox' name='cf_use_tel' value='1' <?=$config[cf_use_tel]?'checked':'';?>> ���̱�
        <input type='checkbox' name='cf_req_tel' value='1' <?=$config[cf_req_tel]?'checked':'';?>> �ʼ��Է�
    </td>
</tr>
<tr>
    <td>�ڵ��� �Է�</td>
    <td>
        <input type='checkbox' name='cf_use_hp' value='1' <?=$config[cf_use_hp]?'checked':'';?>> ���̱�
        <input type='checkbox' name='cf_req_hp' value='1' <?=$config[cf_req_hp]?'checked':'';?>> �ʼ��Է�
    </td>
</tr>
<tr>
    <td>���� �Է�</td>
    <td>
        <input type='checkbox' name='cf_use_signature' value='1' <?=$config[cf_use_signature]?'checked':'';?>> ���̱�
        <input type='checkbox' name='cf_req_signature' value='1' <?=$config[cf_req_signature]?'checked':'';?>> �ʼ��Է�
    </td>
</tr>
<tr>
    <td>�ڱ�Ұ� �Է�</td>
    <td>
        <input type='checkbox' name='cf_use_profile' value='1' <?=$config[cf_use_profile]?'checked':'';?>> ���̱�
        <input type='checkbox' name='cf_req_profile' value='1' <?=$config[cf_req_profile]?'checked':'';?>> �ʼ��Է�
    </td>
</tr>
<tr>
    <td>����(����/����) �Է�</td>
    <td>
        <input type='checkbox' name='cf_use_sex' value='1' <?=$config[cf_use_sex]?'checked':'';?>> ���̱�
    </td>
</tr>
<tr>
    <td>������� �Է�</td>
    <td>
        <input type='checkbox' name='cf_use_birthdate' value='1' <?=$config[cf_use_birthdate]?'checked':'';?>> ���̱�
    </td>
</tr>
<tr>
    <td>ȸ�����Խ� ����</td>
    <td><? echo get_member_level_select('cf_register_level', 1, 9, $config[cf_register_level]) ?></td>
</tr>
<tr>
    <td>ȸ�����Խ� ����Ʈ</td>
    <td><input type=text  name='cf_register_point' size='5' value='<?=$config[cf_register_point]?>'> ��</td>
</tr>
<tr>
    <td>ȸ��Ż���� ������</td>
    <td><input type=text  name='cf_leave_day' size='5' value='<?=$config[cf_leave_day]?>'> �� �� �ڵ� ����</td>
</tr>
<tr>
    <td>ȸ�������� ���</td>
    <td>
        <select name='cf_use_member_icon'> 
        <option value='0'>�̻��
        <option value='1'>�����ܸ� ǥ��
        <option value='2'>������+�̸� ǥ��
        </select>
        <?=help("�Խù��� �Խ��� ���� ��� ������ ���")?>
    <script language='javascript'> document.fconfigform.cf_use_member_icon.value = '<?=$config[cf_use_member_icon]?>'; </script>
    </td>
</tr>
<tr>
    <td>������ ���ε� ����</td>
    <td><? echo get_member_level_select('cf_icon_level', 1, 9, $config[cf_icon_level]) ?> �̻�</td>
</tr>
<tr>
    <td>ȸ�������� �뷮</td>
    <td><input type=text  name='cf_member_icon_size' size='5' value='<?=$config[cf_member_icon_size]?>'> ����Ʈ ����</td>
</tr>
<tr>
    <td>ȸ�������� ������</td>
    <td>�� <input type=text  name='cf_member_icon_width' size='5' value='<?=$config[cf_member_icon_width]?>'> �ȼ� , ���� <input type=text  name='cf_member_icon_height' size='5' value='<?=$config[cf_member_icon_height]?>'> �ȼ� ����</td>
</tr>
<tr>
    <td>��õ������ ���</td>
    <td>
    <input type='checkbox' name='cf_use_recommend' value='1' <?=$config[cf_use_recommend]?'checked':'';?>> ���̱�
    <input type='checkbox' name='cf_req_recommend' value='1' <?=$config[cf_req_recommend]?'checked':'';?>> �ʼ��Է�
    </td>
</tr>
<tr>
    <td>��õ�� ����Ʈ</td>
    <td><input type=text  name='cf_recommend_point' size='5' value='<?=$config[cf_recommend_point]?>'> ��</td>
</tr>
<tr>
    <td>ȸ������ �������� �߼�</td>
    <td><input type='checkbox' name='cf_memo_mb_member' value='1' <?=$config[cf_memo_mb_member]?'checked':'';?>> ���</td>
</tr>
<tr>
    <td>���̵�,���� �����ܾ�
        <?=help("�Էµ� �ܾ ���Ե� ������ ȸ�����̵�, �������� ����� �� �����ϴ�.\n\n�ܾ�� �ܾ� ���̴� , �� �����մϴ�.")?></td>
    <td valign=top><textarea class="ed" name='cf_prohibit_id' rows='5' style='width:99%;word-break:break-all;'><?=$config[cf_prohibit_id]?> </textarea></td>
</tr>
<tr>
    <td>�Է� ���� ����
        <?=help("hanmail.net�� ���� ���� �ּҴ� �Է��� ���մϴ�.\n\n���ͷ� �����մϴ�.")?></td>
    <td valign=top><textarea  name='cf_prohibit_email' rows='5' style='width:99%;'><?=$config[cf_prohibit_email]?> </textarea><br></td>
</tr>
<tr>
    <td>ȸ�����Ծ��</td>
    <td valign=top><textarea  name='cf_stipulation' rows='10' style='width:99%;'><?=$config[cf_stipulation]?> </textarea></td>
</tr>
<tr>
    <td>����������޹�ħ</td>
    <td valign=top><textarea  name='cf_privacy' rows='10' style='width:99%;'><?=$config[cf_privacy]?> </textarea></td>
</tr>
<tr>
    <td>�����ϴ� ���������� <br>�׸�</td>
    <td valign=top><textarea  name='cf_privacy_1' rows='5' style='width:99%;'><?=$config[cf_privacy_1]?> </textarea></td>
</tr>
<tr>
    <td>���������� <br>���� �� �̿� ����</td>
    <td valign=top><textarea  name='cf_privacy_2' rows='5' style='width:99%;'><?=$config[cf_privacy_2]?> </textarea></td>
</tr>
<tr>
    <td>���������� <br>���� �� �̿�Ⱓ</td>
    <td valign=top><textarea  name='cf_privacy_3' rows='5' style='width:99%;'><?=$config[cf_privacy_3]?> </textarea></td>
</tr>
<tr>
    <td>���������� ��3������<br>�س����� ������ ���ǹ�ư�� ��µ˴ϴ�</td>
    <td valign=top><textarea  name='cf_privacy_4' rows='5' style='width:99%;'><?=$config[cf_privacy_4]?> </textarea></td>
</tr>
<tr>
    <td>���������� �����Ź<br>���ʼ� �����̿��� �����Ź�� �ϴ� ���� ���ǹ�ư�� ������ ������ �մϴ�.</td>
    <td valign=top><textarea  name='cf_privacy_5' rows='5' style='width:99%;'><?=$config[cf_privacy_5]?> </textarea></td>
</tr>

<tr>
    <td colspan=2 class="success">�ڵ������� ����</td>
</tr>
<tr>
    <td>�ڵ������� ���</td>
    <td><input type=checkbox name=cf_hp_certify value='1' <?=$config[cf_hp_certify]?'checked':'';?>> ��� (üũ���� ������ �ڵ��������� ������� �ʽ��ϴ�.)</td>
</tr>
<tr>
    <td>�ڵ��� ���� �޽���</td>
    <td><textarea  name='cf_hp_certify_message' rows='5' style='width:99%;'><?=$config[cf_hp_certify_message]?> </textarea>
    <BR>
        <?=help("�ڵ��� ������ ���� ���ڿ��� �Է��մϴ�. \n\n������ȣ�� $certify_number�̸�ܾ�� �ٹٲ��� \\n �Դϴ�.")?>
    (��: �Ҵ��� Opencode\n\n$certify_number\n������ȣ �Դϴ�. )
    </td>
</tr>
<tr>
    <td>�߽��� ��ȭ��ȣ</td>
    <td><input type=text  name='cf_hp_certify_return' size='16' itemname='�߽��� ��ȭ��ȣ' value='<?=$config[cf_hp_certify_return]?>'> (��: 01012341234)</td>
</tr>

<tr>
    <td colspan=2 align=left class="success">���� ����</td>
</tr>
<tr>
    <td>������ ���� �ּ�</td>
    <td>
        <input type="text" name="cf_admin_email" value="<?php echo $config['cf_admin_email'] ?>" id="cf_admin_email" required class="required email frm_input" size="40">
        <?=help('�����ڰ� ������ �޴� �뵵�� ����ϴ� ���� �ּҸ� �Է��մϴ�. (ȸ������, ��������, �׽�Ʈ, ȸ�����Ϲ߼� ��� ���)') ?>
    </td>
</tr>
<tr>
    <td>���Ϲ߼� ���</td>
    <td><input type=checkbox name=cf_email_use value='1' <?=$config[cf_email_use]?'checked':'';?>> ��� (üũ���� ������ ���Ϲ߼��� �ƿ� ������� �ʽ��ϴ�. ���� �׽�Ʈ�� �Ұ��մϴ�.)</td>
</tr>
<tr>
    <td>�������� ���</td>
    <td><input type='checkbox' name='cf_use_email_certify' value='1' <?=$config[cf_use_email_certify]?'checked':'';?>> ���
        <?=help("���Ͽ� ��޵� ���� �ּҸ� Ŭ���Ͽ��� ȸ������ �����մϴ�.");?></td>
</tr>
<tr>
    <td>������ ��� ����</td>
    <td><input type='checkbox' name='cf_formmail_is_member' value='1' <?=$config[cf_formmail_is_member]?'checked':'';?>> ȸ���� ���
        <?=help("üũ���� ������ ��ȸ���� ��� �� �� �ֽ��ϴ�.")?></td>
</tr>
<tr>
    <td class="success">�Խ��� �� �ۼ���</td>
    <td></td>
</tr>
<tr>
    <td>�ְ������ ���Ϲ߼�</td>
    <td><input type=checkbox name=cf_email_wr_super_admin value='1' <?=$config[cf_email_wr_super_admin]?'checked':'';?>> ��� (�ְ�����ڿ��� ������ �߼��մϴ�.)</td>
</tr>
<tr>
    <td>�׷������ ���Ϲ߼�</td>
    <td><input type=checkbox name=cf_email_wr_group_admin value='1' <?=$config[cf_email_wr_group_admin]?'checked':'';?>> ��� (�׷�����ڿ��� ������ �߼��մϴ�.)</td>
</tr>
<tr>
    <td>�Խ��ǰ����� ���Ϲ߼�</td>
    <td><input type=checkbox name=cf_email_wr_board_admin value='1' <?=$config[cf_email_wr_board_admin]?'checked':'';?>> ��� (�Խ��ǰ����ڿ��� ������ �߼��մϴ�.)</td>
</tr>
<tr>
    <td>���� ���Ϲ߼�</td>
    <td><input type=checkbox name=cf_email_wr_write value='1' <?=$config[cf_email_wr_write]?'checked':'';?>> ��� (�Խ��ڴԲ� ������ �߼��մϴ�.)</td>
</tr>
<tr>
    <td>�ڸ�Ʈ ���Ϲ߼�</td>
    <td><input type=checkbox name=cf_email_wr_comment_all value='1' <?=$config[cf_email_wr_comment_all]?'checked':'';?>> ��� (���ۿ� �ڸ�Ʈ�� �ö���� ��� �ڸ�Ʈ �� ��� �е鲲 ������ �߼��մϴ�.)</td>
</tr>
<tr>
    <td class="success">ȸ�� ���Խ�</td>
    <td></td>
</tr>
<tr>
    <td>�ְ������ ���Ϲ߼�</td>
    <td><input type=checkbox name=cf_email_mb_super_admin value='1' <?=$config[cf_email_mb_super_admin]?'checked':'';?>> ��� (�ְ�����ڿ��� ������ �߼��մϴ�.)</td>
</tr>
<tr>
    <td>ȸ���Բ� ���Ϲ߼�</td>
    <td><input type=checkbox name=cf_email_mb_member value='1' <?=$config[cf_email_mb_member]?'checked':'';?>> ��� (ȸ�������� ȸ���Բ� ������ �߼��մϴ�.)</td>
</tr>
<tr>
    <td class="success">��ǥ ��Ÿ�ǰ� �ۼ���</td>
    <td></td>
</tr>
<tr>
    <td>�ְ������ ���Ϲ߼�</td>
    <td><input type=checkbox name=cf_email_po_super_admin value='1' <?=$config[cf_email_po_super_admin]?'checked':'';?>> ��� (�ְ�����ڿ��� ������ �߼��մϴ�.)</td>
</tr>

<tr>
    <td colspan=2 class="success">���� �ʵ�</td>
</tr>
<? for ($i=1; $i<=10; $i=$i+2) { $k=$i+1; ?>
<tr>
    <td><input type=text name='cf_<?=$i?>_subj' value='<?=get_text($config["cf_{$i}_subj"])?>' title='�����ʵ� <?=$i?> ����' style='text-align:right;font-weight:bold;' size=15></td>
    <td><input type='text' style='width:99%;' name=cf_<?=$i?> value='<?=$config["cf_$i"]?>' title='�����ʵ� <?=$i?> ������'>
    <input type=text name='cf_<?=$k?>_subj' value='<?=get_text($config["cf_{$k}_subj"])?>' title='�����ʵ� <?=$k?> ����' style='text-align:right;font-weight:bold;' size=15>
    <input type='text' style='width:99%;' name=cf_<?=$k?> value='<?=$config["cf_$k"]?>' title='�����ʵ� <?=$k?> ������'>
    </td>
</tr>
<? } ?>

<tr>
    <td colspan=2 class="success">
        XSS / CSRF ����
    </td>
</tr>
<tr>
    <td>
        ������ �н�����
    </td>
    <td>
        <input class='btn btn-default' type='password' name='admin_password' itemname="������ �н�����" required>
        <?=help("������ ������ ���ѱ� �Ϳ� ����Ͽ� �α����� �������� �н����带 �ѹ� �� ���°� �Դϴ�.");?>

        <script src='https://www.google.com/recaptcha/api.js'></script> 
        <div id="grecaptcha" class="g-recaptcha" data-sitekey="<?=$g4['recaptcha_sitekey']?>"></div> 
    </td>
</tr>
</table>

<div style="text-align:center;">
    <input type=submit class="btn btn-default" accesskey='s' value='  Ȯ  ��  '>
</div>

</form>

<script type="text/javascript">
function fconfigform_submit(f)
{
    if (typeof(grecaptcha) != 'undefined') { 
        if(grecaptcha.getResponse() == "") { 
            alert("���Թ����ڵ�(Captcha Code)�� Ʋ�Ƚ��ϴ�. �ٽ� �Է��� �ּ���."); 
            return false; 
        } 
    }

    f.action = "./config_form_update.php";
    return true;
}
</script>

<?
include_once ("./admin.tail.php");
?>
