var reg_mb_id_check = function() {
    $.ajax({
        type: 'POST',
        url: member_skin_path+'/ajax_mb_id_check.php',
        data: {
            'reg_mb_id': encodeURIComponent($('#reg_mb_id').val())
        },
        cache: false,
        async: false,
        success: function(result) {
            var msg = $('#msg_mb_id');
            switch(result) {
                case '110' : msg.html('������, ����, _ �� �Է��ϼ���.').css('color', 'red'); break;
                case '120' : msg.html('�ּ� 3���̻� �Է��ϼ���.').css('color', 'red'); break;
                case '130' : msg.html('�̹� ������� ���̵� �Դϴ�.').css('color', 'red'); break;
                case '140' : msg.html('������ ����� �� ���� ���̵� �Դϴ�.').css('color', 'red'); break;
                case '000' : msg.html('����ϼŵ� ���� ���̵� �Դϴ�.').css('color', 'blue'); break;
                default : alert( '�߸��� �����Դϴ�.\n\n' + result ); break;
            }
            $('#mb_id_enabled').val(result);
        }
    });
}

var reg_mb_nick_check = function() {
    $.ajax({
        type: 'POST',
        url: member_skin_path+'/ajax_mb_nick_check.php',
        data: {
            'reg_mb_nick': ($('#reg_mb_nick').val())
        },
        cache: false,
        async: false,
        success: function(result) {
            var msg = $('#msg_mb_nick');
            switch(result) {
                case '110' : msg.html('������ ������� �ѱ�, ����, ���ڸ� �Է� �����մϴ�.').css('color', 'red'); break;
                case '120' : msg.html('�ѱ� 2����, ���� 4���� �̻� �Է� �����մϴ�.').css('color', 'red'); break;
                case '130' : msg.html('�̹� �����ϴ� �����Դϴ�.').css('color', 'red'); break;
                case '000' : msg.html('����ϼŵ� ���� ���� �Դϴ�.').css('color', 'blue'); break;
                default : alert( '�߸��� �����Դϴ�.\n\n' + result ); break;
            }
            $('#mb_nick_enabled').val(result);
        }
    });
}

var reg_mb_email_check = function() {
    $.ajax({
        type: 'POST',
        url: member_skin_path+'/ajax_mb_email_check.php',
        data: {
            'reg_mb_id': encodeURIComponent($('#reg_mb_id').val()),
            'reg_mb_email': $('#reg_mb_email').val()
        },
        cache: false,
        async: false,
        success: function(result) {
            var msg = $('#msg_mb_email');
            switch(result) {
                case '110' : msg.html('E-mail �ּҸ� �Է��Ͻʽÿ�.').css('color', 'red'); break;
                case '120' : msg.html('E-mail �ּҰ� ���Ŀ� ���� �ʽ��ϴ�.').css('color', 'red'); break;
                case '130' : msg.html('�̹� �����ϴ� E-mail �ּ��Դϴ�.').css('color', 'red'); break;
                case '000' : msg.html('����ϼŵ� ���� E-mail �ּ��Դϴ�.').css('color', 'blue'); break;
                default : alert( '�߸��� �����Դϴ�.\n\n' + result ); break;
            }
            $('#mb_email_enabled').val(result);
        }
    });
}