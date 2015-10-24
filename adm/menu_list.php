<?php
$sub_menu = "300910";
include_once('./_common.php');

if ($is_admin != 'super')
    alert('�ְ�����ڸ� ���� �����մϴ�.');

$token = get_token();

$sql = " select * from {$g4['menu_table']} order by me_id ";
$result = sql_query($sql);

$g4['title'] = "�޴�����";
include_once('./admin.head.php');

$colspan = 5;
?>
<div class="alert alert-warning">
        <strong>����!</strong> �޴����� �۾� �� �ݵ�� <strong>Ȯ��</strong>�� �����ž� ����˴ϴ�.
      </div>

<form name="fmenulist" id="fmenulist" method="post" action="./menu_list_update.php" onsubmit="return fmenulist_submit(this);">
<input type="hidden" name="token" value="<?php echo $token ?>">

<button type="button" class="btn btn-default pull-right" onclick="return add_menu();">�޴��߰�<span class="sr-only"> ��â</span></button>

<div class="clearfix"></div>
<hr>

<table class="table table-bordered text-center">
    <p class="sr-only"><?php echo $g4['title']; ?> ���</p>
        <thead>
    <tr>
        <th class="text-center">�޴�</th>
        <th class="text-center">��ũ</th>
        <th class="text-center">��â</th>
        <th class="text-center">����</th>
        <th class="text-center">����</th>
    </tr>
        </thead>
        <tbody>
          <?php
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $bg = 'bg'.($i%2);
        $sub_menu_class = '';
        if(strlen($row['me_code']) == 4) {
            $sub_menu_class = ' sub_menu_class';
            $sub_menu_info = '<span class="sr-only">'.$row['me_name'].'�� ����</span>';
            $sub_menu_ico = '<span class="sub_menu_ico"></span>';
        }

        $search  = array('"', "'");  
        $replace = array('&#034;', '&#039;');  
        $row['me_name'] = str_replace($search, $replace, $row['me_name']);  
    ?>
    <tr class="<?php echo $bg; ?> menu_list menu_group_<?php echo substr($row['me_code'], 0, 2); ?>">
        <td class="td_category<?php echo $sub_menu_class; ?>">
            <input type="hidden" name="code[]" value="<?php echo substr($row['me_code'], 0, 2) ?>">
            <label for="me_name_<?php echo $i; ?>" class="sr-only"><?php echo $sub_menu_info; ?>�޴�</label>
            <input type="text" name="me_name[]" value="<?php echo $row['me_name'] ?>" id="me_name_<?php echo $i; ?>" required class="required frm_input full_input">
        </td>
        <td>
            <label for="me_link_<?php echo $i; ?>" class="sr-only">��ũ</label>
            <input type="text" name="me_link[]" value="<?php echo $row['me_link'] ?>" id="me_link_<?php echo $i; ?>" required class="required frm_input full_input">
        </td>
        <td>
            <label for="me_target_<?php echo $i; ?>" class="sr-only">��â</label>
            <select name="me_target[]" id="me_target_<?php echo $i; ?>">
                <option value="self"<?php echo get_selected($row['me_target'], 'self', true); ?>>������</option>
                <option value="blank"<?php echo get_selected($row['me_target'], 'blank', true); ?>>�����</option>
            </select>
        </td>
        <td>
            <label for="me_order_<?php echo $i; ?>" class="sr-only">����</label>
            <input type="text" name="me_order[]" value="<?php echo $row['me_order'] ?>" id="me_order_<?php echo $i; ?>" class="frm_input">
        </td>
        <td>
            <?php if(strlen($row['me_code']) == 2) { ?>
            <button type="button" class="btn_add_submenu">�߰�</button>
            <?php } ?>
            <button type="button" class="btn_del_menu">����</button>
        </td>
    </tr>
    <?php
    }

    if ($i==0)
        echo '<tr id="empty_menu_list"><td colspan="'.$colspan.'" class="empty_table">�ڷᰡ �����ϴ�.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<div class="text-center">
<button type="button" name="act_button" class="btn btn-success">Ȯ��</button>
</div>

</form>

<script>
$(function() {
    $(".btn_add_submenu").live("click", function() {
        var code = $(this).closest("tr").find("input[name='code[]']").val().substr(0, 2);
        add_submenu(code);
    });

    $(".btn_del_menu").live("click", function() {
        if(!confirm("�޴��� �����Ͻðڽ��ϱ�?"))
            return false;

        var $tr = $(this).closest("tr");
        if($tr.find("td.sub_menu_class").size() > 0) {
            $tr.remove();
        } else {
            var code = $(this).closest("tr").find("input[name='code[]']").val().substr(0, 2);
            $("tr.menu_group_"+code).remove();
        }

        if($("#menulist tr.menu_list").size() < 1) {
            var list = "<tr id=\"empty_menu_list\"><td colspan=\"<?php echo $colspan; ?>\" class=\"empty_table\">�ڷᰡ �����ϴ�.</td></tr>\n";
            $("#menulist table tbody").append(list);
        } else {
            $("#menulist tr.menu_list").each(function(index) {
                $(this).removeClass("bg0 bg1")
                    .addClass("bg"+(index % 2));
            });
        }
    });
});

function add_menu()
{
    var max_code = base_convert(0, 10, 36);
    $("#menulist tr.menu_list").each(function() {
        var me_code = $(this).find("input[name='code[]']").val().substr(0, 2);
        if(max_code < me_code)
            max_code = me_code;
    });

    var url = "./menu_form.php?code="+max_code+"&new=new";
    window.open(url, "add_menu", "left=100,top=100,width=550,height=650,scrollbars=yes,resizable=yes");
    return false;
}

function add_submenu(code)
{
    var url = "./menu_form.php?code="+code;
    window.open(url, "add_menu", "left=100,top=100,width=550,height=650,scrollbars=yes,resizable=yes");
    return false;
}

function base_convert(number, frombase, tobase) {
  //  discuss at: http://phpjs.org/functions/base_convert/
  // original by: Philippe Baumann
  // improved by: Rafa�� Kukawski (http://blog.kukawski.pl)
  //   example 1: base_convert('A37334', 16, 2);
  //   returns 1: '101000110111001100110100'

  return parseInt(number + '', frombase | 0)
    .toString(tobase | 0);
}

function fmenulist_submit(f)
{
    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
