if (typeof(B4_COMMON_JS) == 'undefined') { // 한번만 실행
    var B4_COMMON_JS = true;

    // 곱슬최씨님의 mw_image_window를 이름오류 안나게 이름만 변경
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
   
      // zzzz님이 알려주셨어요. 감솨 ^^
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
    	var size = "이미지 사이즈 : "+w+" x "+h;
    	win.document.write ("<title>"+size+"</title> \n"); 
    	if(w >= screen.width || h >= screen.height) { 
     		win.document.write (js_url); 
    		var click = "ondblclick='window.close();' style='cursor:move' title=' "+size+" \n\n 이미지 사이즈가 화면보다 큽니다. \n 왼쪽 버튼을 클릭한 후 마우스를 움직여서 보세요. \n\n 더블 클릭하면 닫혀요. '"; 
    	} 
    	else 
    		var click = "onclick='window.close();' style='cursor:pointer' title=' "+size+" \n\n 클릭하면 닫혀요. '"; 

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
    
    // img_src 값으로 이미지창을 팝업 (팬+줌)
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

      // zzzz님이 알려주셨어요. 감솨 ^^
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
    	var size = "이미지 사이즈 : "+w+" x "+h;
    	win.document.write ("<title>"+size+"</title> \n"); 

    	if(w >= screen.width || h >= screen.height) { 
    		win.document.write (js_url);
    		var click = "ondblclick='window.close();' style='cursor:move' title=' "+size+" \n\n 이미지 사이즈가 화면보다 큽니다. \n 왼쪽 버튼을 클릭한 후 마우스를 움직여서 보세요. \n\n 더블 클릭하면 닫혀요. '"; 
    	} 
    	else
    		var click = "onclick='window.close();' style='cursor:pointer' title=' "+size+" \n\n 클릭하면 닫혀요. '"; 

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

    // 신고해지 창
    function win_unsingo(url)
    {
        win_open(url, "unsingo", "left=20, top=20, width=608, height=350, scrollbars=0");
    }

function htmlspecialchars_decode(string, quote_style) {
  //       discuss at: http://phpjs.org/functions/htmlspecialchars_decode/
  //      original by: Mirek Slugen
  //      improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //      bugfixed by: Mateusz "loonquawl" Zalega
  //      bugfixed by: Onno Marsman
  //      bugfixed by: Brett Zamir (http://brett-zamir.me)
  //      bugfixed by: Brett Zamir (http://brett-zamir.me)
  //         input by: ReverseSyntax
  //         input by: Slawomir Kaniecki
  //         input by: Scott Cariss
  //         input by: Francois
  //         input by: Ratheous
  //         input by: Mailfaker (http://www.weedem.fr/)
  //       revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // reimplemented by: Brett Zamir (http://brett-zamir.me)
  //        example 1: htmlspecialchars_decode("<p>this -&gt; &quot;</p>", 'ENT_NOQUOTES');
  //        returns 1: '<p>this -> &quot;</p>'
  //        example 2: htmlspecialchars_decode("&amp;quot;");
  //        returns 2: '&quot;'

  var optTemp = 0,
    i = 0,
    noquotes = false;
  if (typeof quote_style === 'undefined') {
    quote_style = 2;
  }
  string = string.toString()
    .replace(/&lt;/g, '<')
    .replace(/&gt;/g, '>');
  var OPTS = {
    'ENT_NOQUOTES'          : 0,
    'ENT_HTML_QUOTE_SINGLE' : 1,
    'ENT_HTML_QUOTE_DOUBLE' : 2,
    'ENT_COMPAT'            : 2,
    'ENT_QUOTES'            : 3,
    'ENT_IGNORE'            : 4
  };
  if (quote_style === 0) {
    noquotes = true;
  }
  if (typeof quote_style !== 'number') {
    // Allow for a single string or an array of string flags
    quote_style = [].concat(quote_style);
    for (i = 0; i < quote_style.length; i++) {
      // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
      if (OPTS[quote_style[i]] === 0) {
        noquotes = true;
      } else if (OPTS[quote_style[i]]) {
        optTemp = optTemp | OPTS[quote_style[i]];
      }
    }
    quote_style = optTemp;
  }
  if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
    string = string.replace(/&#0*39;/g, "'"); // PHP doesn't currently escape if more than one 0, but it should
    // string = string.replace(/&apos;|&#x0*27;/g, "'"); // This would also be useful here, but not a part of PHP
  }
  if (!noquotes) {
    string = string.replace(/&quot;/g, '"');
  }
  // Put this in last place to avoid escape being double-decoded
  string = string.replace(/&amp;/g, '&');

  return string;
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
  //  revised by: Rafał Kukawski (http://blog.kukawski.pl/)
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
