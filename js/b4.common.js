if (typeof(B4_COMMON_JS) == 'undefined') { // �ѹ��� ����
    var B4_COMMON_JS = true;

    // �����־����� mw_image_window�� �̸����� �ȳ��� �̸��� ����
    function image_window2(img, w, h)
    {
    	if (!w || !h)
    	{
            var w = img.tmp_width; 
            var h = img.tmp_height; 
    	}
    
    	var winl = (screen.width-w)/2; 
    	var wint = (screen.height-h)/3; 
    
    	if (w >= screen.width) { 
    		winl = 0; 
    		h = (parseInt)(w * (h / w)); 
    	} 
    
    	if (h >= screen.height) { 
    		wint = 0; 
    		w = (parseInt)(h * (w / h)); 
    	} 
    
    	var js_url = "<script language='JavaScript1.2'> \n"; 
    		js_url += "<!-- \n"; 
    		js_url += "var ie=document.all; \n"; 
    		js_url += "var nn6=document.getElementById&&!document.all; \n"; 
    		js_url += "var isdrag=false; \n"; 
    		js_url += "var x,y; \n"; 
    		js_url += "var dobj; \n"; 
    		js_url += "function movemouse(e) \n"; 
    		js_url += "{ \n"; 
    		js_url += "  if (isdrag) \n"; 
    		js_url += "  { \n"; 
    		js_url += "    dobj.style.left = nn6 ? tx + e.clientX - x : tx + event.clientX - x; \n"; 
    		js_url += "    dobj.style.top  = nn6 ? ty + e.clientY - y : ty + event.clientY - y; \n"; 
    		js_url += "    return false; \n"; 
    		js_url += "  } \n"; 
    		js_url += "} \n"; 
    		js_url += "function selectmouse(e) \n"; 
    		js_url += "{ \n"; 
    		js_url += "  var fobj      = nn6 ? e.target : event.srcElement; \n"; 
    		js_url += "  var topelement = nn6 ? 'HTML' : 'BODY'; \n"; 
    		js_url += "  while (fobj.tagName != topelement && fobj.className != 'dragme') \n"; 
    		js_url += "  { \n"; 
    		js_url += "    fobj = nn6 ? fobj.parentNode : fobj.parentElement; \n"; 
    		js_url += "  } \n"; 
    		js_url += "  if (fobj.className=='dragme') \n"; 
    		js_url += "  { \n"; 
    		js_url += "    isdrag = true; \n"; 
    		js_url += "    dobj = fobj; \n"; 
    		js_url += "    tx = parseInt(dobj.style.left+0); \n"; 
    		js_url += "    ty = parseInt(dobj.style.top+0); \n"; 
    		js_url += "    x = nn6 ? e.clientX : event.clientX; \n"; 
    		js_url += "    y = nn6 ? e.clientY : event.clientY; \n"; 
    		js_url += "    document.onmousemove=movemouse; \n"; 
    		js_url += "    return false; \n"; 
    		js_url += "  } \n"; 
    		js_url += "} \n"; 
    		js_url += "document.onmousedown=selectmouse; \n"; 
    		js_url += "document.onmouseup=new Function('isdrag=false'); \n"; 
    		js_url += "//--> \n"; 
    		js_url += "</"+"script> \n"; 
   
      // zzzz���� �˷��ּ̾��. ���� ^^
    	var j1_url = "<script language='JavaScript1.2'> \n"; 
    		j1_url += "<!-- \n"; 
    		j1_url += "function _ReSize() { ";
    		j1_url += "   $get = document.getElementById('_img').style; ";
        j1_url += "   var ratio = document.getElementById('_img').width / document.getElementById('_img').height; ";
    		j1_url += "   $get.width = document.body.clientWidth; ";
    		j1_url += "   $get.height = document.body.clientWidth / ratio; ";
    		j1_url += "   setTimeout('_ReSize()', 100); ";
    		j1_url += "} ";
    		j1_url += "//--> \n"; 
    		j1_url += "</"+"script> \n"; 

    	var j2_url = "<script language='JavaScript1.2'> \n"; 
    		j2_url += "<!-- \n"; 
    		j2_url += "_ReSize(); ";
    		j2_url += "//--> \n"; 
    		j2_url += "</"+"script> \n"; 
 
    	var settings;
    
    	if (g4_is_gecko) {
    		settings  ='width='+(w+10)+','; 
    		settings +='height='+(h+10)+','; 
    	} else {
    		settings  ='width='+w+','; 
    		settings +='height='+h+','; 
    	}
    	settings +='top='+wint+','; 
    	settings +='left='+winl+','; 
    	settings +='scrollbars=no,'; 
    	settings +='resizable=yes,'; 
    	settings +='status=no'; 
    
    	win=window.open("","image_window",settings); 
    	win.document.open(); 
    	win.document.write ("<html><head> \n<meta http-equiv='content-type' content='text/html; charset="+g4_charset+"'>\n"); 
    	var size = "�̹��� ������ : "+w+" x "+h;
    	win.document.write ("<title>"+size+"</title> \n"); 
    	if(w >= screen.width || h >= screen.height) { 
     		win.document.write (js_url); 
    		var click = "ondblclick='window.close();' style='cursor:move' title=' "+size+" \n\n �̹��� ����� ȭ�麸�� Ů�ϴ�. \n ���� ��ư�� Ŭ���� �� ���콺�� �������� ������. \n\n ���� Ŭ���ϸ� ������. '"; 
    	} 
    	else 
    		var click = "onclick='window.close();' style='cursor:pointer' title=' "+size+" \n\n Ŭ���ϸ� ������. '"; 

   		win.document.write (j1_url); 
   		
    	win.document.write ("<style>.dragme{position:relative;}</style> \n"); 
    	win.document.write ("</head> \n\n"); 
    	win.document.write ("<body oncontextmenu='return false' leftmargin=0 topmargin=0 bgcolor=#dddddd style='cursor:arrow;'> \n"); 
    	win.document.write ("<table width=100% height=100% cellpadding=0 cellspacing=0><tr><td align=center valign=middle><img id='_img' src='"+img.src+"' width='"+w+"' height='"+h+"' border=0 class='dragme' "+click+"></td></tr></table>");

      win.document.write (j2_url); 

    	win.document.write ("</body></html>"); 
    	win.document.close(); 
    
    	if(parseInt(navigator.appVersion) >= 4){win.window.focus();} 
    
    }
    
    // img_src ������ �̹���â�� �˾� (��+��)
    function image_window3(img_src, w, h)
    {
    	if (!w || !h)
    	{
            var w = img.tmp_width; 
            var h = img.tmp_height; 
    	}

    	var winl = (screen.width-w)/2; 
    	var wint = (screen.height-h)/3; 
    
    	if (w >= screen.width) { 
    		winl = 0; 
    		h = (parseInt)(w * (h / w)); 
    	} 
    
    	if (h >= screen.height) { 
    		wint = 0; 
    		w = (parseInt)(h * (w / h)); 
    	} 
    
    	var js_url = "<script language='JavaScript1.2'> \n"; 
    		js_url += "<!-- \n"; 
    		js_url += "var ie=document.all; \n"; 
    		js_url += "var nn6=document.getElementById&&!document.all; \n"; 
    		js_url += "var isdrag=false; \n"; 
    		js_url += "var x,y; \n"; 
    		js_url += "var dobj; \n"; 
    		js_url += "function movemouse(e) \n"; 
    		js_url += "{ \n"; 
    		js_url += "  if (isdrag) \n"; 
    		js_url += "  { \n"; 
    		js_url += "    dobj.style.left = nn6 ? tx + e.clientX - x : tx + event.clientX - x; \n"; 
    		js_url += "    dobj.style.top  = nn6 ? ty + e.clientY - y : ty + event.clientY - y; \n"; 
    		js_url += "    return false; \n"; 
    		js_url += "  } \n"; 
    		js_url += "} \n"; 
    		js_url += "function selectmouse(e) \n"; 
    		js_url += "{ \n"; 
    		js_url += "  var fobj      = nn6 ? e.target : event.srcElement; \n"; 
    		js_url += "  var topelement = nn6 ? 'HTML' : 'BODY'; \n"; 
    		js_url += "  while (fobj.tagName != topelement && fobj.className != 'dragme') \n"; 
    		js_url += "  { \n"; 
    		js_url += "    fobj = nn6 ? fobj.parentNode : fobj.parentElement; \n"; 
    		js_url += "  } \n"; 
    		js_url += "  if (fobj.className=='dragme') \n"; 
    		js_url += "  { \n"; 
    		js_url += "    isdrag = true; \n"; 
    		js_url += "    dobj = fobj; \n"; 
    		js_url += "    tx = parseInt(dobj.style.left+0); \n"; 
    		js_url += "    ty = parseInt(dobj.style.top+0); \n"; 
    		js_url += "    x = nn6 ? e.clientX : event.clientX; \n"; 
    		js_url += "    y = nn6 ? e.clientY : event.clientY; \n"; 
    		js_url += "    document.onmousemove=movemouse; \n"; 
    		js_url += "    return false; \n"; 
    		js_url += "  } \n"; 
    		js_url += "} \n"; 
    		js_url += "document.onmousedown=selectmouse; \n"; 
    		js_url += "document.onmouseup=new Function('isdrag=false'); \n";
    		js_url += "//--> \n"; 
    		js_url += "</"+"script> \n"; 

      // zzzz���� �˷��ּ̾��. ���� ^^
    	var j1_url = "<script type/text='JavaScript'> \n"; 
    		j1_url += "<!-- \n"; 
    		j1_url += "function _ReSize() { ";
    		j1_url += "   $get = document.getElementById('_img').style; ";
        j1_url += "   var ratio = document.getElementById('_img').width / document.getElementById('_img').height; ";
    		j1_url += "   $get.width = document.body.clientWidth; ";
    		j1_url += "   $get.height = document.body.clientWidth / ratio; ";
    		j1_url += "   window.setTimeout('_ReSize()', 100); ";
    		j1_url += "} ";
    		j1_url += "//--> \n"; 
    		j1_url += "</"+"script> \n"; 

    	var j2_url = "<script type/text='JavaScript'> \n"; 
    		j2_url += "<!-- \n"; 
    		j2_url += "window.onresize=_ReSize; ";
    		j2_url += "//--> \n"; 
    		j2_url += "</"+"script> \n"; 
    
    	var settings;
    
    	if (g4_is_gecko) {
    		settings  ='width='+(w+10)+','; 
    		settings +='height='+(h+10)+','; 
    	} else {
    		settings  ='width='+w+','; 
    		settings +='height='+h+','; 
    	}
    	settings +='top='+wint+','; 
    	settings +='left='+winl+','; 
    	settings +='scrollbars=no,'; 
    	settings +='resizable=yes,'; 
    	settings +='status=no'; 
    
    	win=window.open("","image_window",settings); 
    	win.document.open(); 
    	win.document.write ("<html><head> \n<meta http-equiv='content-type' content='text/html; charset="+g4_charset+"'>\n"); 
    	var size = "�̹��� ������ : "+w+" x "+h;
    	win.document.write ("<title>"+size+"</title> \n"); 

    	if(w >= screen.width || h >= screen.height) { 
    		win.document.write (js_url);
    		var click = "ondblclick='window.close();' style='cursor:move' title=' "+size+" \n\n �̹��� ����� ȭ�麸�� Ů�ϴ�. \n ���� ��ư�� Ŭ���� �� ���콺�� �������� ������. \n\n ���� Ŭ���ϸ� ������. '"; 
    	} 
    	else
    		var click = "onclick='window.close();' style='cursor:pointer' title=' "+size+" \n\n Ŭ���ϸ� ������. '"; 

   		win.document.write (j1_url); 

    	win.document.write ("<style>.dragme{position:relative;}</style> \n"); 
    	win.document.write ("</head> \n\n"); 
    	win.document.write ("<body oncontextmenu='return false' leftmargin=0 topmargin=0 bgcolor=#dddddd style='cursor:arrow;overflow:hidden;'> \n"); 
    	win.document.write ("<table width=100% height=100% cellpadding=0 cellspacing=0><tr><td align=center valign=middle><img id='_img' src='"+img_src+"' width='"+w+"' height='"+h+"' border=0 class='dragme' "+click+"></td></tr></table>");

    	win.document.write (j2_url);

    	win.document.write ("</body></html>"); 
    	win.document.close(); 
    
    	if(parseInt(navigator.appVersion) >= 4){win.window.focus();} 
    
    }

    // �Ű����� â
    function win_unsingo(url)
    {
        win_open(url, "unsingo", "left=20, top=20, width=608, height=350, scrollbars=0");
    }

function strip_tags(input, allowed) {
  //  discuss at: http://phpjs.org/functions/strip_tags/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Luke Godfrey
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //    input by: Pul
  //    input by: Alex
  //    input by: Marc Palau
  //    input by: Brett Zamir (http://brett-zamir.me)
  //    input by: Bobby Drake
  //    input by: Evertjan Garretsen
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: Onno Marsman
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: Eric Nagel
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: Tomasz Wesolowski
  //  revised by: Rafa�� Kukawski (http://blog.kukawski.pl/)
  //   example 1: strip_tags('<p>Kevin</p> <br /><b>van</b> <i>Zonneveld</i>', '<i><b>');
  //   returns 1: 'Kevin <b>van</b> <i>Zonneveld</i>'
  //   example 2: strip_tags('<p>Kevin <img src="someimage.png" onmouseover="someFunction()">van <i>Zonneveld</i></p>', '<p>');
  //   returns 2: '<p>Kevin van Zonneveld</p>'
  //   example 3: strip_tags("<a href='http://kevin.vanzonneveld.net'>Kevin van Zonneveld</a>", "<a>");
  //   returns 3: "<a href='http://kevin.vanzonneveld.net'>Kevin van Zonneveld</a>"
  //   example 4: strip_tags('1 < 5 5 > 1');
  //   returns 4: '1 < 5 5 > 1'
  //   example 5: strip_tags('1 <br/> 1');
  //   returns 5: '1  1'
  //   example 6: strip_tags('1 <br/> 1', '<br>');
  //   returns 6: '1 <br/> 1'
  //   example 7: strip_tags('1 <br/> 1', '<br><br/>');
  //   returns 7: '1 <br/> 1'

  allowed = (((allowed || '') + '')
      .toLowerCase()
      .match(/<[a-z][a-z0-9]*>/g) || [])
    .join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
  var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
    commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
  return input.replace(commentsAndPhpTags, '')
    .replace(tags, function($0, $1) {
      return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
    });
}

}
