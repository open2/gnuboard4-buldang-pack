</div><!-- �߰��� ���κ� �� -->

<script type="text/javascript">
// ȸ��ID ã��  
function popup_id(frm_name, ss_id, top, left) 
{ 
    url = './write_id.php?frm_name='+frm_name+'&ss_id='+ss_id; 
    opt = 'scrollbars=yes,width=320,height=470,top='+top+',left='+left; 
    window.open(url, "write_id", opt); 
} 

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
        alert(str + "�� ������ �ϳ� �̻� �����ϼ���.");
        return false;
    }
    return true;
}

// ������ �Խù� ����
function select_delete() {
    var f = document.fboardlist;

    str = "����";
    if (!check_confirm(str))
        return;

    if (!confirm("������ ������ ���� "+str+" �Ͻðڽ��ϱ�?\n\n�ѹ� "+str+"�� �ڷ�� ������ �� �����ϴ�"))
        return;

    f.action = "./memo2_form_delete.php";
    f.submit();
}

// ��� �Խù� ����
function all_delete_trash() {
    var f = document.fboardlist;

    str = "����";

    if (!confirm("��� ������ ���� "+str+" �Ͻðڽ��ϱ�?\n\n�ѹ� "+str+"�� �ڷ�� ������ �� �����ϴ�"))
        return;

    f.action = "./memo2_form_delete_all_trash.php";
    f.submit();
}
</script>