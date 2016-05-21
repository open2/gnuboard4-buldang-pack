if (typeof(CAPSLOCK_JS) == 'undefined') // �ѹ��� ����
{
    if (typeof g4_path == 'undefined')
        alert('g4_path ������ ������� �ʾҽ��ϴ�. js/capslock.js');

    var CAPSLOCK_JS = true;

    var capslock_delay = 3000; // "CapsLock �� ���� �ֽ��ϴ�." �̹����� ���ʰ� ����� ������?
    var capslock_left = -4; // CaplsLock �̹����� X ��ǥ
    var capslock_top = 0; // CaplsLock �̹����� Y ��ǥ
    function check_capslock(e, elem_id) {
        var myKeyCode=0;
        var myShiftKey=false;

        if ( document.all ) {                   // Internet Explorer 4+
            myKeyCode=e.keyCode; 
            myShiftKey=e.shiftKey;
        } else if ( document.layers ) {         // Netscape 4
            myKeyCode=e.which;  
            myShiftKey=( myKeyCode == 16 ) ? true : false;
        } else if ( document.getElementById ) { // Netscape 6
            myKeyCode=e.which; 
            myShiftKey=( myKeyCode == 16 ) ? true : false;
        }

        // Upper case letters are seen without depressing the Shift key, therefore Caps Lock is on
        if ( ( myKeyCode >= 65 && myKeyCode <= 90 ) && !myShiftKey ) {
            set_capslock_on(elem_id);
        // Lower case letters are seen while depressing the Shift key, therefore Caps Lock is on
        } else if ( ( myKeyCode >= 97 && myKeyCode <= 122 ) && myShiftKey ) {
            set_capslock_on(elem_id);
        }
    }

    function set_capslock_on(elem_id) {
        set_capslock_info_position(elem_id);
        document.getElementById("capslock_info").style.display  = "inline";
        setTimeout("set_capslock_off()", capslock_delay);
    }

    function set_capslock_off(elem_id) {
        document.getElementById("capslock_info").style.display  = "none";
    }

    function set_capslock_info_position(elem_id) {
        var o = document.getElementById("capslock_info");
        var ref = document.getElementById(elem_id);
        //var s = ""; for (i in ref) {s =  s + i + " "; } alert(s);
        if ( typeof(o)=="object" && typeof(ref)=="object" ) {
            var x = get_real_left(ref);
            var y = get_real_top(ref);
            //o.style.pixelLeft = x + capslock_left;
            //o.style.pixelTop = y + ref.offsetHeight + capslock_top;
            o.style.left = x + capslock_left;
            o.style.top = y + ref.offsetHeight + capslock_top;
        }
    }

    function get_real_left(obj) {
        if ( obj.offsetParent == null ) return 0;
        return obj.offsetLeft + obj.clientLeft + get_real_left(obj.offsetParent);
    }

    function get_real_top(obj) {
        if ( obj.offsetParent == null ) return 0;
        return obj.offsetTop + obj.clientTop + get_real_top(obj.offsetParent);
    }

    // SPA �����ϵ��� HTML ��� ��� ����
    //document.write("<div id='capslock_info' style='display:none; position:absolute;'><img src='"+g4_path+"/img/capslock.gif'></div>");
    var capslockDiv = document.createElement('div');
    capslockDiv.id = 'capslock_info';
    capslockDiv.style = 'display:none; position:absolute;';
    document.body.appendChild(capslockDiv);
    document.getElementById('capslock_info').innerHTML = "<img src='" + g4_path + "/img/capslock.gif'>";
}
