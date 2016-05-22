// ȸ�����̵� �˻�
function reg_mb_id_check() {
   var check_mb_id_val = document.fregisterform.mb_id.value;
   var check_mb_id_len = document.fregisterform.mb_id.value.length;
    if(check_mb_id_len >= 3){
      $.ajax({
        type: 'POST',
        url: member_skin_path+'/ajax_mb_id_check.php',
        data: {
            'reg_mb_id': encodeURIComponent(check_mb_id_val)
        },
        async: false,
        cache: false,
        success: function(msg){
            return_reg_mb_id_check(msg);
        }
      });
    } else {
        return_reg_mb_id_check('120');
    }
}

function return_reg_mb_id_check(req) {
    var msg = $('#msg_mb_id');

    switch(req) {
        case '110' : msg.text('������, ����, _ �� �Է��ϼ���.').css( "color", "red" ); break;
        case '120' : msg.text('�ּ� 3���̻� �Է��ϼ���.').css( "color", "red" ); break;
        case '130' : msg.text('�̹� ������� ���̵� �Դϴ�.').css( "color", "red" ); break;
        case '140' : msg.text('������ ����� �� ���� ���̵� �Դϴ�.').css( "color", "red" ); break;
        case '000' : msg.text('����ϼŵ� ���� ���̵� �Դϴ�.').css( "color", "blue" );break;
        default : alert( '�߸��� �����Դϴ�.\n\n' + req ); break;
    }
    $('#mb_id_enabled').val(req);    
}

// ���� �˻�
function reg_mb_nick_check() {
    var reg_mb_nick = $('#mb_nick').val();
    if (check_byte2(reg_mb_nick) < 4) {
        return_reg_mb_nick_check('120');
    }
    
    $.ajax({
        type: 'POST',
        url: member_skin_path + "/ajax_mb_nick_check.php",
        data: "reg_mb_nick="+encodeURIComponent(reg_mb_nick),
        async: false,
        success: function(msg){
            return_reg_mb_nick_check(msg);
        }
    });
}

function return_reg_mb_nick_check(req) {
    var msg = $('#msg_mb_nick');
    switch(req) {
        case '110' : msg.text('������ ������� �ѱ�, ����, ���ڸ� �Է� �����մϴ�.').css( "color", "red" ); break;
        case '120' : msg.text('�ѱ� 2����, ���� 4���� �̻� �Է� �����մϴ�.').css( "color", "red" ); break;
        case '130' : msg.text('�̹� �����ϴ� �����Դϴ�.').css( "color", "red" ); break;
        case '140' : msg.text('������ ����� �� ���� ���� �Դϴ�.').css( "color", "red" ); break;
        case '150' : msg.text('��Ÿ ������ �г����� ������ �� �����ϴ�.').css( "color", "red" ); break;
        case '000' : msg.text('����ϼŵ� ���� ���� �Դϴ�.').css( "color", "blue" ); break;
        default : alert( '�߸��� �����Դϴ�.\n\n' + req ); break;
    }
    $('#mb_nick_enabled').val(req);
}

// E-mail �ּ� �˻�
function reg_mb_email_check() {
    if($('#mb_email').val().length >= 4){
        $.ajax({
            type: 'POST',
            url: member_skin_path + "/ajax_mb_email_check.php",
            data: "reg_mb_email="+encodeURIComponent($('#mb_email').val())+"&"+"reg_mb_id="+encodeURIComponent($('#mb_id').val()),
            async: false,
            success: function(msg){
                return_reg_mb_email_check(msg);
            }
        });
    } else {
        return_reg_mb_email_check('120');
    }
}

function return_reg_mb_email_check(req) {
    var msg = $('#msg_mb_email');
    switch(req) {
        case '110' : msg.text('E-mail �ּҸ� �Է��Ͻʽÿ�.').css( "color", "red" ); break;
        case '120' : msg.text('E-mail �ּҰ� ���Ŀ� ���� �ʽ��ϴ�.').css( "color", "red" ); break;
        case '130' : msg.text('�̹� �����ϴ� E-mail �ּ��Դϴ�.').css( "color", "red" ); break;
        case '140' : msg.text('������ ����� �� ���� ���� �Դϴ�.').css( "color", "red" ); break;
        case '150' : msg.text('������ ���� ������ �Դϴ�.').css( "color", "red" ); break;
        case '000' : msg.text('����ϼŵ� ���� E-mail �ּ��Դϴ�.').css( "color", "blue" ); break;
        default : alert( '�߸��� �����Դϴ�.\n\n' + req ); break;
    }
    $('#mb_email_enabled').val(req);
}

// ȸ���̸� �˻�
function reg_mb_name_check() {
   if($('#mb_name').val().length >= 2){
      $.ajax({
        type: 'POST',
        url: member_skin_path + "/ajax_mb_name_check.php",
        data: "mb_name="+encodeURIComponent($('#mb_name').val()),
        async: false,
        success: function(msg){
            return_reg_mb_name_check(msg);
        }
      });
    } else {
        return_reg_mb_name_check('120');
    }
}

function return_reg_mb_name_check(req) {
    var msg = $('#msg_mb_name');
    switch(req) {
        case '110' : msg.text('�ѱ�, �����ڸ� �Է��ϼ���.').css( "color", "red" ); break;
        case '120' : msg.text('�ּ� 2���̻� �Է��ϼ���.').css( "color", "red" ); break;
        case '140' : msg.text('������ ����� �� ���� ���� �Դϴ�.').css( "color", "red" ); break;
        case '000' : msg.text('').css( "color", "blue" ); break;
        default : alert( '�߸��� �����Դϴ�.\n\n' + req ); break;
    }
    $('#mb_name_enabled').val(req);
}