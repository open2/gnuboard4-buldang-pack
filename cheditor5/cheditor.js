// ================================================================
//                       CHEditor 5.1.4
// ----------------------------------------------------------------
// Homepage: http://www.chcode.com
// EMail: support@chcode.com
// Copyright (c) 1997-2015 CHSOFT
// ================================================================
var GB = {
	colors:["#000000","#313131","#434343","#535353","#666666","#999999","#a0a0a0","#b5b5b5","#c0c0c0","#dcdcdc","#eeeeee","#ffffff",
            "#ff0000","#ff8000","#ffff00","#80ff00","#00ff00","#00ff99","#00ffff","#0080ff","#0000ff","#7f00ff","#ff00ff","#ff007f",
            "#ffcccc","#ffe5cc","#ffffcc","#e5ffcc","#ccffcc","#ccffe5","#ccffff","#cce5ff","#ccccff","#e5ccff","#ffccff","#ffcce5",
            "#ff9999","#ffcc99","#ffff99","#ccff99","#99ff99","#99ffcc","#99ffff","#99ccff","#9999ff","#cc99ff","#ff99ff","#ff99cc",
            "#ff6666","#ffb266","#ffff66","#b2ff66","#66ff66","#66ffb2","#66ffff","#66b2ff","#6666ff","#b266ff","#ff66ff","#ff66b2",
            "#ff3333","#ff9933","#ffff33","#99ff33","#33ff33","#33ff99","#33ffff","#3399ff","#3333ff","#9933ff","#ff33ff","#ff3399",
            "#cc0000","#cc6600","#cccc00","#66cc00","#00cc00","#00cc66","#00cccc","#0066cc","#0000cc","#6600cc","#cc00cc","#cc0066",
            "#990000","#994c00","#999900","#4c9900","#009900","#00994c","#009999","#004c99","#000099","#4c0099","#990099","#99004c",
            "#660000","#663300","#666600","#336600","#006600","#006633","#006666","#003366","#000066","#330066","#660066","#660033"],
	offElements : {
        img:1, hr:1, table:1, embed:1, object:1, input:1, form:1, select:1, textarea:1, button:1, fieldset:1
    },
	emptyElements : {
        area:1, base:1, basefont:1, col:1, frame:1, hr:1, img:1, br:1, input:1, isindex:1, link:1, meta:1,
        param:1, source:1, track:1, wbr:1, keygen:1, menuitem:1
    },
    textFormatting : {
        addr:1, acronym:1, b:1, bdo:1, big:1, cite:1, code:1, del:1, dfn:1, em:1, font:1, i:1, ins:1, kbd:1, q:1,
        samp:1, small:1, span:1, strike:1, strong:1, sub:1, sup:1, tt:1, u:1, 'var':1
    },
    newLineBefore : '|div|p|blockquote|table|tbody|tr|td|th|title|head|body|script|comment|li|meta|h1|h2|h3|h4|h5|h6|hr|ul|ol|link|',
    lineHeightBlock : '|address|blockquote|dd|div|dl|h1|h2|h3|h4|h5|h6|li|p|pre|td|th|code|section|aside|article|figcaption|',
    doctype : '<!DOCTYPE html>',
	popupWindow : {
        ImageUpload :   {tmpl : 'image.html',           width : 310, posv: 420, title : '내 PC 사진 넣기'},
		ImageUrl :      {tmpl : 'image_url.html',       width : 350, posv: 380, title : '웹 사진 넣기'},
		Embed :         {tmpl : 'media.html',           width : 430, posv: 380, title : '미디어'},
		Table :         {tmpl : 'table.html',           width : 430, posv: 390, title : '표 만들기'},
		ModifyTable :   {tmpl : 'table_modify.html',    width : 430, posv: 390, title : '표 고치기'},
		Layout :        {tmpl : 'layout.html',          width : 430, posv: 420, title : '레이아웃'},
		Link :          {tmpl : 'link.html',            width : 350, posv: 200, title : '하이퍼링크'},
		EmotionIcon :   {tmpl : 'icon.html',            width : 300, posv: 200, title : '표정 아이콘'},
		Symbol :        {tmpl : 'symbol.html',          width : 450, posv: 300, title : '특수 문자'},
		GoogleMap :     {tmpl : 'google_map.html',      width : 538, posv: 450, title : '구글 지도'},
		ColorPicker :   {tmpl : 'color_picker.html',    width : 420, posv: 200, title : '색상 선택'},
		FlashMovie :    {tmpl : 'flash.html',           width : 584, posv: 474, title : '플래쉬 동영상'}
    },
	fontName : {
        'kr' : ['맑은 고딕', '돋움', '굴림', '바탕', '궁서'],
		'en' : ['Arial', 'Comic Sans MS', 'Courier New', 'Georgia', 'Lucida Sans Unicode', 'Tahoma', 'Times New Roman', 'Verdana']
    },
    fontStyle : {
        'FontSize':'font-size', 'FontName':'font-family', 'ForeColor':'color', 'BackColor':'background-color'
    },
    textAlign : {
        'JustifyLeft':'', 'JustifyCenter':'center','JustifyRight':'right','JustifyFull':'justify'
    },
    listStyle : {
        'ordered' : {
            'decimal':'숫자', 'lower-alpha':'영문 소문자', 'upper-alpha':'영문 대문자', 'lower-roman':'로마 소문자', 'upper-roman':'로마 대문자'
        },
        'unOrdered' : {'desc':'동그라미', 'circle':'빈 원', 'square':'사각형'}
    },
    fontSize : {
        'pt' : [7, 8, 9, 10, 11, 12, 14, 16, 18, 20, 22, 24, 26, 28, 36],
        'px' : [9, 10, 11, 12, 14, 16, 18, 20, 22, 24, 26, 28, 36, 48, 72]
    },
    formatBlock : {
        'P' : 'Normal (P)',
		'H1' : 'Heading 1',
		'H2' : 'Heading 2',
		'H3' : 'Heading 3',
		'H4' : 'Heading 4',
		'H5' : 'Heading 5',
		'H6' : 'Heading 6',
		'ADDRESS' : 'Address',
        'DIV' : 'DIV',
		'PRE' : 'Preformatted (PRE)'
    },
	lineHeight : {
        '한 줄 간격': 1, '1.15': 1.15, '1.5': 1.5, '1.7': 1.7, '1.8': 1.8, '두 줄 간격': 2
    },
    textBlock : [
        ['1px #dedfdf solid','#f7f7f7'],
		['1px #aee8e8 solid','#bfffff'],
		['1px #d3bceb solid','#e6ccff'],
		['1px #e8e88b solid','#ffff99'],
		['1px #c3e89e solid','#d6ffad'],
		['1px #e8c8b7 solid','#ffdcc9'],
		['1px #666666 dashed','#ffffff'],
		['1px #d4d4d4 solid','#ffffff'],
		['1px #cccccc inset','#f7f7f7']
    ],
    node : {
        element: 1, attribute: 2, text: 3, cdata_section: 4, entity_reference: 5, entity: 6,
		processing_instruction: 7, comment: 8, document: 9, document_type: 10, document_fragment: 11,
		notation: 12
    },

	selection : { none: 1, text: 2, element: 3 },
    readyState : { 0: 'uninitialized', 1: 'loading', 2: 'loaded', 3: 'interactive', 4: 'complete' },

    prettify : null,
    dragWindow : null,
    readyEditor: 0,
    browser : {}
};

function isUndefined (obj) {
    return obj === void(0); // obj === undefined;
}

function detechBrowser () {
    function detect(ua) {
        function getFirstMatch(regex) {
            var match = ua.match(regex);
            return (match && match.length > 1 && match[1]) || '';
        }
        var iosdevice = getFirstMatch(/(ipod|iphone|ipad)/i).toLowerCase()
            ,likeAndroid = /like android/i.test(ua)
            ,android = !likeAndroid && /android/i.test(ua)
            ,versionIdentifier = getFirstMatch(/version\/(\d+(\.\d+)?)/i)
            ,tablet = /tablet/i.test(ua)
            ,mobile = !tablet && /[^\-]mobi/i.test(ua)
            ,result;

        if (/opera|opr/i.test(ua)) {
            result = {
                name: 'Opera', opera: true ,
                version: versionIdentifier || getFirstMatch(/(?:opera|opr)[\s\/](\d+(\.\d+)?)/i)
            };
        }
        else if (/windows phone/i.test(ua)) {
            result = {
                name: 'Windows Phone', windowsphone: true, msie: true,
                version: getFirstMatch(/iemobile\/(\d+(\.\d+)?)/i)
            };
        }
        else if (/msie|trident/i.test(ua)) {
            result = {
                name: 'Internet Explorer', msie: true, version: getFirstMatch(/(?:msie |rv:)(\d+(\.\d+)?)/i)
            };
        }
        else if (/edge/i.test(ua)) {
            result = {
                name: 'edge', edge: true, version: getFirstMatch(/(?:edge)\/(\d+(\.\d+)?)/i)
            };
        }
        else if (/chrome|crios|crmo/i.test(ua)) {
            result = {
                name: 'Chrome', chrome: true, version: getFirstMatch(/(?:chrome|crios|crmo)\/(\d+(\.\d+)?)/i)
            };
        }
        else if (iosdevice) {
            result = {
                name: iosdevice === 'iphone' ? 'iPhone' : iosdevice === 'ipad' ? 'iPad' : 'iPod'
            };
            if (versionIdentifier) { result.version = versionIdentifier; }
        }
        else if (/firefox|iceweasel/i.test(ua)) {
            result = {
                name: 'Firefox', firefox: true,
                version: getFirstMatch(/(?:firefox|iceweasel)[ \/](\d+(\.\d+)?)/i)
            };
            if (/\((mobile|tablet);[^\)]*rv:[\d\.]+\)/i.test(ua)) { result.firefoxos = true; }
        }
        else if (/silk/i.test(ua)) {
            result =  {
                name: 'Amazon Silk', silk: true, version : getFirstMatch(/silk\/(\d+(\.\d+)?)/i)
            };
        }
        else if (android) {
            result = { name: 'Android', version: versionIdentifier };
        }
        else if (/phantom/i.test(ua)) {
            result = {
                name: 'PhantomJS', phantom: true, version: getFirstMatch(/phantomjs\/(\d+(\.\d+)?)/i)
            };
        }
        else if (/blackberry|\bbb\d+/i.test(ua) || /rim\stablet/i.test(ua)) {
            result = {
                name: 'BlackBerry', blackberry: true,
                version: versionIdentifier || getFirstMatch(/blackberry[\d]+\/(\d+(\.\d+)?)/i)
            };
        }
        else if (/(web|hpw)os/i.test(ua)) {
            result = {
                name: 'WebOS' , webos: true ,
                version: versionIdentifier || getFirstMatch(/w(?:eb)?osbrowser\/(\d+(\.\d+)?)/i)
            };
            if (/touchpad\//i.test(ua)) { result.touchpad = true; }
        }
        else if (/safari/i.test(ua)) {
            result = {
                name: 'Safari', safari: true, version: versionIdentifier
            };
        }
        else {
            result = {};
        }

        if (/(apple)?webkit/i.test(ua)) {
            result.name = result.name || "Webkit";
            result.webkit = true;
            if (!result.version && versionIdentifier) { result.version = versionIdentifier; }
        }
        else if (!result.opera && /gecko\//i.test(ua)) {
            result.gecko = true;
            result.version = result.version || getFirstMatch(/gecko\/(\d+(\.\d+)?)/i);
            result.name = result.name || "Gecko";
        }
        if (android || result.silk) { result.android = true; }
        else if (iosdevice) {
            result[iosdevice] = true;
            result.ios = true;
        }

        var osVersion = '';
        if (iosdevice) {
            osVersion = getFirstMatch(/os (\d+([_\s]\d+)*) like mac os x/i);
            osVersion = osVersion.replace(/[_\s]/g, '.');
        }
        else if (android) { osVersion = getFirstMatch(/android[ \/\-](\d+(\.\d+)*)/i); }
        else if (result.windowsphone) { osVersion = getFirstMatch(/windows phone (?:os)?\s?(\d+(\.\d+)*)/i); }
        else if (result.webos) { osVersion = getFirstMatch(/(?:web|hpw)os\/(\d+(\.\d+)*)/i); }
        else if (result.blackberry) { osVersion = getFirstMatch(/rim\stablet\sos\s(\d+(\.\d+)*)/i); }

        if (osVersion) { result.osversion = osVersion; }

        var osMajorVersion = osVersion.split('.')[0];
        if (tablet || iosdevice === 'ipad' ||
            (android && (osMajorVersion === 3 || (osMajorVersion === 4 && !mobile))) ||
            result.silk)
        {
            result.tablet = true;
        }
        else if (mobile || iosdevice === 'iphone' || iosdevice === 'ipod' || android ||
                result.blackberry || result.webos)
        {
            result.mobile = true;
        }

        if ((result.msie && result.version >= 10) ||
            (result.chrome && result.version >= 20) ||
            (result.firefox && result.version >= 20.0) ||
            (result.safari && result.version >= 6) ||
            (result.opera && result.version >= 10.0) ||
            (result.ios && result.osversion && result.osversion.split(".")[0] >= 6) ||
            (result.blackberry && result.version >= 10.1))
        {
          result.a = true;
        }
        else if ((result.msie && result.version < 10) ||
            (result.chrome && result.version < 20) ||
            (result.firefox && result.version < 20.0) ||
            (result.safari && result.version < 6) ||
            (result.opera && result.version < 10.0) ||
            (result.ios && result.osversion && result.osversion.split(".")[0] < 6))
        {
            result.c = true;
        }
        else {
            result.x = true;
        }

        if (result.msie) {
            if (result.version > 10) {
                result.msie_a = true;
            }
            else if (result.version > 8) {
                result.msie_b = true;
            }
            else {
                result.msie_c = true;
            }
        }
        return result;
    }
    return detect(!isUndefined(navigator) ? navigator.userAgent : null);
}

function URI (uri) {
	this.scheme = null;
	this.authority = null;
	this.path = '';
	this.query = null;
	this.fragment = null;

	this.parseUri = function (uri) {
		var m = uri.match(/^(([A-Za-z][0-9A-Za-z+.\-]*)(:))?((\/\/)([^\/?#]*))?([^?#]*)((\?)([^#]*))?((#)(.*))?/);
		this.scheme = m[3] ? m[2] : null;
		this.authority = m[5] ? m[6] : null;
		this.path = m[7];
		this.query = m[9] ? m[10] : null;
		this.fragment = m[12]? m[13] : null;
		return this;
	};

	this.azToString = function () {
		var result = '';
		if (this.scheme !== null) { result = result + this.scheme + ':'; }
		if (this.authority !== null) { result = result +'//'+ this.authority; }
		if (this.path !== null) { result = result + this.path; }
		if (this.query !== null) { result = result + '?'+ this.query; }
		if (this.fragment !== null) { result = result + '#'+ this.fragment; }
		return result;
	};

	this.toAbsolute = function (location) {
		var baseUri = new URI(location);
		var URIAbs = this;
		var target = new URI();

		function removeDotSegments (path) {
			var result = '', rm;
			while (path) {
				if (path.substr(0,3) === '../' || path.substr(0,2) === './') {
					path = path.replace(/^\.+/,'').substr(1);
				}
				else if (path.substr(0,3) === '/./' || path === '/.') {
					path = '/'+path.substr(3);
				}
				else if (path.substr(0,4) === '/../' || path === '/..') {
					path = '/'+path.substr(4);
					result = result.replace(/\/?[^\/]*$/, '');
				}
				else if (path === '.' || path === '..') {
					path = '';
				}
				else {
					rm = path.match(/^\/?[^\/]*/)[0];
					path = path.substr(rm.length);
					result = result + rm;
				}
			}
			return result;
		}

		if (baseUri.scheme === null) { return false; }
		if (URIAbs.scheme !== null && URIAbs.scheme.toLowerCase() === baseUri.scheme.toLowerCase()) {
			URIAbs.scheme = null;
		}

		if (URIAbs.scheme !== null) {
			target.scheme = URIAbs.scheme;
			target.authority = URIAbs.authority;
			target.path = removeDotSegments(URIAbs.path);
			target.query = URIAbs.query;
		}
		else {
			if (URIAbs.authority !== null) {
				target.authority = URIAbs.authority;
				target.path = removeDotSegments(URIAbs.path);
				target.query = URIAbs.query;
			}
			else {
				if (URIAbs.path === '') {
					target.path = baseUri.path;
					target.query = URIAbs.query || baseUri.query;
				}
				else {
					if (URIAbs.path.substr(0,1) === '/') {
						target.path = removeDotSegments(URIAbs.path);
					}
					else {
						if (baseUri.authority !== null && baseUri.path === '') {
							target.path = '/' + URIAbs.path;
						}
						else {
							target.path = baseUri.path.replace(/[^\/]+$/,'') + URIAbs.path;
						}
						target.path = removeDotSegments(target.path);
					}
					target.query = URIAbs.query;
				}
				target.authority = baseUri.authority;
			}
			target.scheme = baseUri.scheme;
		}
		target.fragment = URIAbs.fragment;
		return target;
	};
	if (uri) {
        this.parseUri(uri);
    }
}

function setConfig () {
    var config = {
        editorWidth     : '100%',
        editorHeight    : '300px',
        editorFontSize  : '12px',
        editorFontName  : '맑은 고딕, 굴림, Malgun Gothic, gulim',
        editorFontColor : '#000',
        editorBgColor   : '#fff',
        imgCaptionText	: 'margin: 5px 0px; color: #333',
        lineHeight      : 1.6,
        editAreaMargin  : '5px 10px',
        tabIndex        : 0,
        editorPath      : null,
        fullHTMLSource  : false,
        linkTarget      : '_blank',
        showTagPath     : false,
        colorToHex		: true,
        imgMaxWidth     : 640,
        imgUploadNumber : 4,
        imgUploadSortName : false,
        imgSetAttrWidth : 1, // -1 = (width=100%, height=auto), 0 = 설정 안함, 1 = 원래대로
        imgSetAttrAlt   : true,
        makeThumbnail   : false,
        imgDefaultAlign : "left", // [left, center, right]
        thumbnailWidth  : 120,
        thumbnailHeight : 90,
        imgBlockMargin  : '5px 0px',
        includeHostname : false,
        paragraphCss    : false, // true = <p style="margin:0"></p>, false = <p></p>
        xhtmlLang		: 'utf-8',
        xhtmlEncoding	: 'utf-8',
        docTitle		: '내 문서',
        template        : 'template.xml',
        fontSizeValue   : 'px', // [pt, px]

        // 버튼 사용 유무
        useSource       : true,
        usePreview      : true,
        usePrint        : false,
        useNewDocument  : false,
        useUndo         : true,
        useRedo         : true,
        useCopy         : true,
        useCut          : true,
        usePaste        : true,
        usePasteFromWord: false,
        useSelectAll    : true,
        useStrikethrough: true,
        useUnderline    : true,
        useItalic       : true,
        useSuperscript  : false,
        useSubscript    : false,
        useJustifyLeft  : true,
        useJustifyCenter: true,
        useJustifyRight : true,
        useJustifyFull  : false,
        useBold         : true,
        useOrderedList  : false,
        useUnOrderedList: false,
        useOutdent      : false,
        useIndent       : false,
        useFontName     : true,
        useFormatBlock  : true,
        useFontSize     : true,
        useLineHeight   : true,
        useBackColor    : false,
        useForeColor    : true,
        useRemoveFormat : true,
        useClearTag     : true,
        useSymbol       : false,
        useLink         : true,
        useUnLink       : true,
        useFlash        : false,
        useMedia        : false,
        useImage        : true,
        useImageUrl     : false,
        useSmileyIcon   : false,
        useHR           : false,
        useTable        : true,
        useModifyTable  : true,
        useMap          : false,
        useTextBlock    : false,
        useFullScreen   : false,
        usePageBreak    : false,
        allowedScript   : true,
        allowedOnEvent  : false
    };

    if (config.editorPath === null) {
        var base = location.href, editorUri, locationAbs;
        var e = document.getElementsByTagName('base'), i;
        for (i=0; i<e.length; i++) {
            if (e[i].href) {
                base = e[i].href;
            }
        }
        e = document.getElementsByTagName('script');
        for (i=0; i < e.length; i++) {
            if (e[i].src) {
                editorUri = new URI(e[i].src);
                if(/\/cheditor\.js$/.test(editorUri.path)) {
                    locationAbs = editorUri.toAbsolute(base).azToString();
                    delete locationAbs.query;
                    delete locationAbs.fragment;
                    config.editorPath = locationAbs.replace(/[^\/]+$/, '');
                }
            }
        }
        if (config.editorPath === null) {
            throw "CHEditor 경로가 바르지 않습니다.\nmyeditor.config.editorPath를 설정하여 주십시오.";
        }
    }

	this.cheditor = {};
	this.inputForm = null;
	this.range = null;
	this.images = [];
	this.editImages = {};
	this.setFullScreenMode = false;
	this.modalElementZIndex = 1001;
	this.toolbar = {};
	this.pulldown = {};
	this.tempTimer = null;
	this.currentRS = {};
	this.resizeEditor = {};
    this.config = config;
    this.templateFile = config.template;
    this.templatePath = config.editorPath + config.template;
    this.storedSelections = [];
    this.bogusParagraph = [];
    this.W3CRange = window.getSelection;
}

function cheditor () {
    this.toType = (function(global) {
        var toString = cheditor.prototype.toString;
        var re = /^.*\s(\w+).*$/;
        return function(obj) {
            if (obj === global) {
                return "global";
            }
            return toString.call(obj).replace(re, '$1').toLowerCase();
        };
    }(this));

    this.undefined = isUndefined;
    GB.browser = this.browser = detechBrowser();

    var error = false;
	if (this.undefined(document.execCommand)) {
		error = true;
	}
    else {
        if (!(this.undefined(navigator.productSub))) {
            if (navigator.productSub < 20030107) {
                error = true;
            }
        }
        else if (GB.browser.msie && GB.browser.version < 6) {
            error = true;
        }
        else {
            error = false;
        }
    }

    if (error) {
        alert("CHEditor는 '"+this.browser.name + " " + this.browser.version + "' 버전을 지원하지 않습니다.");
		return null;
    }

    try {
        setConfig.call(this);
        this.cheditor.id = (this.undefined(GB.readyEditor)) ? 1 : GB.readyEditor++;
    } catch (e) {
        alert(e.toString());
        return null;
    }
    return this;
}

cheditor.prototype = {
//----------------------------------------------------------------
resetData : function () {
	this.resetEditArea();
	if (GB.browser.msie) {
		try { document.execCommand('BackgroundImageCache', false, true); }
		catch (ignore) {}
	}
},

appendContents : function (contents) {
	this.editAreaFocus();
	var div = this.doc.createElement('div');
	div.innerHTML = String(this.trimSpace(contents));

    while (div.hasChildNodes()) {
		this.doc.body.appendChild(div.firstChild);
    }
	this.editAreaFocus();
},

insertContents : function (contents) {
	this.editAreaFocus();
	this.doCmdPaste(String(this.trimSpace(contents)));
},

replaceContents : function (contents) {
	this.editAreaFocus();
	this.doc.body.innerHTML = '';
	this.loadContents(contents);
	this.editAreaFocus();
},

loadContents : function (contents) {
	if (typeof contents === 'string') {
		contents = this.trimSpace(contents);
		if (contents !== '') {
			this.cheditor.editArea.style.visibility = 'hidden';
			this.doc.body.innerHTML = contents;
			this.cheditor.editArea.style.visibility = 'visible';
		}
	}
},

loadScript : function (path) {
	var script = document.createElement('script');
	script.type = 'text/javascript';
	script.src = path;
	var head = document.getElementsByTagName("head")[0] || document.documentElement;
	var done = false;

	script.onload = script.onreadystatechange = function() {
		if (!done && (!this.readyState || this.readyState === "loaded" || this.readyState === "complete"))
		{
		    done = true;
		    head.removeChild(script);
		}
	};

	head.appendChild(script);
},

setFolderPath : function () {
	if (this.config.editorPath.charAt(this.config.editorPath.length-1) !== '/') {
		this.config.editorPath += '/';
    }
	this.config.iconPath = this.config.editorPath + 'icons/';
	this.config.cssPath = this.config.editorPath + 'css/';
	this.config.popupPath = this.config.editorPath + 'popup/';
},

checkInputForm : function () {
	var textarea = document.getElementById(this.inputForm);
	if (!textarea) {
		throw "ID가 '"+this.inputForm+"'인 textarea 개체를 찾을 수 없습니다.";
    }
	textarea.style.display = 'none';
	this.cheditor.textarea = textarea;
},

setDesignMode : function (designMode, doc) {
    if (!doc) {
        doc = this.doc;
    }
	if (GB.browser.msie) {
        doc.body.contentEditable = designMode;
	}
	else {
        doc.designMode  = designMode ? "on" : "off";
	}
},

openDoc : function (doc, contents) {
	doc.open();
	var html = '<html lang="ko" dir="ltr">'+
            '<head><title>'+this.config.docTitle+'</title>'+
            '<style></style></head><body>';

	if (typeof contents === 'string') {
		html += this.trimSpace(contents);
	}

	html += '</body></html>';
	doc.write(html);
	doc.close();
},

getWindowHandle : function (iframeObj) {
	var iframeWin;
	if (iframeObj.contentWindow) {
		iframeWin = iframeObj.contentWindow;
    }
	else {
		throw '현재 브라우저에서 에디터를 실행할 수 없습니다.';
    }
	return iframeWin;
},

resetDoc : function () {
	try {
        if (this.undefined(this.cheditor.editArea)) {
            return false;
        }
		this.editArea = this.getWindowHandle(this.cheditor.editArea);
		this.doc = GB.browser.msie ? this.editArea.document : this.cheditor.editArea.contentDocument;
		this.resetData();
		return true;
	} catch (e) {
		alert(e.toString());
		return false;
	}
},

resetEditArea : function () {
	this.openDoc(this.doc, this.cheditor.textarea.value);
	this.setDesignMode(true);

	var oSheet = this.doc.styleSheets[0];
    var bodyCss = 'font-size:' + this.config.editorFontSize +
            ';font-family:' + this.config.editorFontName +
            ';color:' + this.config.editorFontColor +
            ';margin:' + this.config.editAreaMargin +
            ';line-height:' + this.config.lineHeight +
            ';background-color:' + this.config.editorBgColor;
    var tableCss = 'font-size:' + this.config.editorFontSize + ';line-height:' + this.config.lineHeight;
	if (!this.W3CRange) {
		oSheet.addRule('body', bodyCss);
		oSheet.addRule('table', tableCss);
	}
	else {
		oSheet.insertRule('body {'+ bodyCss + '}', 0);
		oSheet.insertRule('table {'+ tableCss + '}', 1);
	}

    this.doc.body.setAttribute("spellcheck", "false");
    this.doc.body.setAttribute("hidefocus", "");
    this.cheditor['bogusSpacerName'] = "ch_bogus_spacer";
    this.cheditor['bogusParaName'] = "ch_bogus_para";

    var self = this;
    this.addEvent(self.doc.body, "paste", function(event) { self.handlePaste(event); });
    try {
        if (!GB.browser.msie) {
            this.doc.execCommand('defaultParagraphSeparator', false, 'p');
        }
    } catch(ignore) {}

    this.initDefaultParagraphSeparator();
},

initDefaultParagraphSeparator : function () {
    if (this.doc.body.firstChild && this.doc.body.firstChild.nodeName.toLowerCase() === 'br') {
        this.doc.body.removeChild(this.doc.body.firstChild);
    }
    if (this.W3CRange && !this.doc.body.hasChildNodes()) {
        var p = this.doc.createElement('p');
        this.doc.body.appendChild(p);
        if (!GB.browser.msie && !GB.browser.edge) {
            var br = this.doc.createElement('br');
            br.className = this.cheditor.bogusSpacerName;
            p.appendChild(br);
            this.placeCaretAt(p, false);
        }
        else {
            this.placeCaretAt(p, false);
        }
    }
},

handlePaste : function (ev) {
    if (this.cheditor.mode === "preview" ||
            (this.cheditor.paste !== 'text' && this.cheditor.mode === 'rich'))
    {
        return;
    }

    this.stopEvent(ev);
    var clip = (ev.originalEvent || ev).clipboardData;
    var text = this.trimSpace((this.undefined(clip) || clip === null) ? window.clipboardData.getData("Text") :
                clip.getData('text/plain'));

    if (text !== '') {
        text = text.replace(/\r/g, "");
        if (this.cheditor.mode === "code") {
            var div = this.doc.createElement('div');
            text = this.htmlEncode(text);
            text = text.replace(/\s{2,}/gm, '\n');
            text = text.replace(/[\u200b\ufeff\xa0\u3000]+/g, '');

            if (GB.browser.msie && GB.browser.version < 9) {
                text = text.replace(/\n/g, "<br />");
                text = text.replace(/\t/g, "__CHEDITOR_TAB_SPACE__");
                text = text.replace(/\s/gm, "&nbsp;");
            }
            div.innerHTML = text;
            div.id = "clipboardData";
            this.insertHTML(div);
            GB.prettify.initHighlightingOnLoad(['html', 'javascript', 'css'], this, true);
            return;
        }

        text = this.htmlEncode(text);
        var lines = text.split('\n');
        var i, len = lines.length;
        text = '<p>';
        for (i=0; i<len; i++) {
            lines[i] = lines[i].replace(/\s+$/g, '').replace(/\s/g, '&nbsp;');
            text += lines[i]+'<br />';
        }
        text += '</p>';
        this.insertHTML(text);
    }
},

editAreaFocus : function () {
    this.editArea.focus();
    this.doc.body.focus();
},

resizeGetY : function (ev) {
	return GB.browser.msie ?
			window.event.clientY + document.documentElement.scrollTop + document.body.scrollTop :
				ev.clientY + window.pageYOffset;
},

resizeStart : function (ev) {
	var self = this;
	self.currentRS.elNode = self.cheditor.editArea;
	self.currentRS.cursorStartY = self.resizeGetY(ev);
	self.currentRS.elStartTop = parseInt(self.currentRS.elNode.style.height, 10);

	if (isNaN(self.currentRS.elStartTop)) {
        self.currentRS.elStartTop = 0;
    }

	ev = ev || window.event;

	self.resizeEditor.stopFunc = function(event) { self.resizeStop(event); };
	self.resizeEditor.moveFunc = function(event) { self.resizeMove(event); };

	if (GB.browser.msie) {
        self.setDesignMode(false);
    }

	self.currentRS.elNode.style.visibility = 'hidden';
	self.addEvent(document, "mousemove", self.resizeEditor.moveFunc);
	self.addEvent(document, "mouseup", self.resizeEditor.stopFunc);
	self.stopEvent(ev);
},

resizeMove : function (ev) {
	var Y = this.resizeGetY(ev);
	var H = this.currentRS.elStartTop + Y - this.currentRS.cursorStartY;
	if (H < 1) {
		this.resizeStop(ev);
		H = 1;
	}
	this.config.editorHeight = this.currentRS.elNode.style.height  = H + 'px';
	this.stopEvent(ev);
},

resizeStop : function (ev) {
	this.removeEvent(document, "mouseup", this.resizeEditor.stopFunc);
	this.removeEvent(document, "mousemove", this.resizeEditor.moveFunc);
	this.stopEvent(ev);
	this.currentRS.elNode.style.visibility = 'visible';
    if (GB.browser.msie) {
        this.setDesignMode(true);
    }
	this.editAreaFocus();
},

switchEditorMode : function (changeMode) {
    this.editAreaFocus();
    var i, className;
	if (this.cheditor.mode === changeMode) { return; }

	for (i in this.cheditor.modetab) {
        if (this.cheditor.modetab.hasOwnProperty(i)) {
		    className = this.cheditor.modetab[i].className;
		    className = className.replace(/\-off$/,'');
		    if (i !== changeMode) {
			    this.cheditor.modetab[i].className = className + '-off';
		    }
		    else {
			    this.cheditor.modetab[i].className = className;
		    }
        }
	}

	switch (changeMode) {
		case 'rich' :
			this.richMode();
			this.showTagSelector(true);
			break;
		case 'code' :
            this.editMode();
			this.showTagSelector(false);
			break;
		case 'preview' :
			this.previewMode();
			this.showTagSelector(false);
			break;
		default : break;
	}
    this.cheditor.mode = changeMode;
},

initTemplate : function () {
    var self = this;
    var httpRequest = null;

    function showError(msg) {
        alert(self.templateFile + ' 파일 로딩 중 오류가 발생하였습니다.\n원인: ' + msg);
        throw '';
    }

    function templateReady() {
        if ( httpRequest.readyState === 4) {
            if (httpRequest.status === 200) {
                try {
                    self.xmlDoc =  httpRequest.responseXML ||  httpRequest;
                    self.loadTemplate(self.xmlDoc);
                    if (self.W3CRange) {
                        var event = document.createEvent("Event");
                        event.initEvent(self.cheditor.id, true, true);
                        document.dispatchEvent(event);
                    }
                    else {
                        document.documentElement.loadEvent = self.cheditor.id;
                    }
                } catch (e) {
                    showError(e.toString());
                }
            }
            else {
                showError("XMLHttpRequest. Status " + httpRequest.status);
            }
        }
    }

    if (window.XMLHttpRequest) {
         httpRequest = new XMLHttpRequest();
        if (httpRequest.overrideMimeType) {
             httpRequest.overrideMimeType('text/xml');
        }
        httpRequest.onreadystatechange = templateReady;
        try {
            httpRequest.open("GET", self.templatePath, true);
        }
        catch(e) {
            showError(e + '참고: 에디터를 웹 서버에서 실행하여 주십시오.');
        }
        httpRequest.send();
    }
    else if (window.ActiveXObject) {
         httpRequest = new window.ActiveXObject("Microsoft.XMLDOM");
         httpRequest.async = true;
         httpRequest.onreadystatechange = templateReady;
         httpRequest.load(self.templatePath);
    }
    else {
        showError("현재 브라우저에서 "+self.templateFile+" 파일을 사용할 수 없습니다.");
    }
},

getCDATASection : function (node) {
	if (node.hasChildNodes()) {
		var elem = node.firstChild;
		while (elem && elem.nodeType !== GB.node.cdata_section) {
			elem = elem.nextSibling;
		}

		if (elem && elem.nodeType === GB.node.cdata_section) {
			var data = elem.data;
			data = data.replace(/\n/g, '');
			data = data.replace(/(\s+?)<([^>]*)>/g, "<$2>");
			data = this.trimSpace(data);
			return data;
		}
	}
	return null;
},

setToolbarBgPosition : function (elem, attr) {
	elem.style.backgroundPosition = attr;
},

getToolbarBgPosition : function (elem) {
	var pos;
	switch (elem.className) {
		case 'cheditor-tb-bg'           : pos = 3; break;
		case 'cheditor-tb-bg-last'      : pos = 6; break;
		case 'cheditor-tb-bg-single'    : pos = 9; break;
		case 'cheditor-tb-bg30-first'   : pos = 12; break;
		case 'cheditor-tb-bg30'         : pos = 15; break;
		case 'cheditor-tb-bg30-last'    : pos = 18; break;
		case 'cheditor-tb-bg55'         : pos = 21; break;
		case 'cheditor-tb-bg40'         : pos = 24; break;
        case 'cheditor-tb-bg44'         : pos = 27; break;
        case 'cheditor-tb-bgcombo'      : pos = 30; break;
        case 'cheditor-tb-bgcombo-last' : pos = 33; break;
		default : pos = 0;
	}
	return pos;
},

toolbarMouseOverUp : function (elem) {
     if (elem.checked) {
        return;
    }

	this.setToolbarBgPosition(elem.button, "0 " + (~(((elem.pos + 1) * elem.height)) + 1) + 'px');
    if ((elem.name === "combobox" && elem.prev && elem.prev.checked) ||
            (elem.name === "combo" && elem.next && elem.next.checked)) {
        return;
    }

    var pos, obj;
    if (elem.type === "combobox") {
        if (elem.prev.checked) { return; }
            obj = elem.prev;
            pos = "0px " + (~(((obj.pos + 1) * obj.height)) + 1) + 'px';
            this.setToolbarBgPosition(obj.button, pos);
    }
    else if (elem.type === "combo") {
        if (elem.prev && !elem.prev.checked && !elem.prev.active) {
            obj = elem.prev;
            pos = (~(obj.width) + 1) + "px " + (~(obj.pos * obj.height) + 1) + 'px';
            this.setToolbarBgPosition(obj.button, pos);
        }

        if (elem.next) {
            if (elem.next.checked) {
                return;
            }
            obj = elem.next;
            pos = (~(obj.width) + 1) + "px " + (~(((obj.pos + 1) * obj.height)) + 1) + 'px';
            this.setToolbarBgPosition(obj.button, pos);
        }
    }
    else {
        if (!elem.prev || (elem.prev && elem.prev.checked)) {
            return;
        }
        obj = elem.prev;
        if (obj.className === 'cheditor-tb-bg-first') {
            pos = (~(obj.width)+1) + "px 0";
        }
        else {
            pos = (~(obj.width) + 1) + "px " + (~(obj.pos * obj.height) + 1) + 'px';
        }
        this.setToolbarBgPosition(obj.button, pos);
    }
},

toolbarMouseDownOut : function (elem, mousedown) {
    if (elem.next && elem.next.checked && !mousedown) {
        this.setToolbarBgPosition(elem.button, (~(elem.width*2) + 1) + "px " +
            (~(elem.pos * elem.height) + 1) + 'px');
    }

	if (elem.prev) {
        if (elem.prev.active || (elem.prev.type === "combo" && elem.checked)) {
            return;
        }
        if (elem.prev.checked) {
            this.setToolbarBgPosition(elem.prev.button, "0 " +
                (~((elem.prev.pos + 2) * elem.prev.height) + 1) + 'px');
            return;
        }

        if (mousedown) {
            this.setToolbarBgPosition(elem.prev.button, (~(elem.prev.width*2) + 1) + "px " +
                (~(elem.prev.pos * elem.prev.height) + 1) + 'px');
        }
        else {
            this.setToolbarBgPosition(elem.prev.button,
                "0 " + (~(elem.prev.pos * elem.prev.height) + 1) + 'px');
        }
    }
},

toolbarButtonChecked : function (elem) {
	this.setToolbarBgPosition(elem.button, "0 " + (~((elem.pos + 2) * elem.height) + 1) + 'px');
    if (elem.prev && elem.prev.type === "combo") {
        if (elem.prev.checked || elem.checked) {
            return;
        }
        this.setToolbarBgPosition(elem.prev.button, (~(elem.prev.width*2) + 1) + "px " +
            (~(elem.prev.pos * elem.prev.height) + 1) + 'px');
    }

    if (elem.prev && !elem.prev.checked) {
        if (elem.checked) {
            this.setToolbarBgPosition(elem.prev.button, (~(elem.prev.width*2) + 1) + "px " +
                (~(elem.prev.pos * elem.prev.height) + 1) + 'px');
        }
        else {
            this.setToolbarBgPosition(elem.prev.button, "0 " + (~(elem.prev.pos * elem.prev.height) + 1) + 'px');
        }
    }
},

toolbarButtonUnchecked : function (elem) {
    if (elem.type === "combobox" && !elem.checked) {
        if (elem.prev.checked) {
            this.setToolbarBgPosition(elem.button,
                (~(elem.width) + 1) + "px " + (~(((elem.pos + 1) * elem.height)) + 1) + 'px');
            return;
        }
        this.setToolbarBgPosition(elem.prev.button, "0 " + (~(elem.prev.pos * elem.prev.height) + 1) + 'px');
    }
    this.setToolbarBgPosition(elem.button, "0 " + (~(elem.pos * elem.height) + 1) + 'px');
    if (elem.prev && elem.prev.name === "BackColor") {
        this.setToolbarBgPosition(elem.prev.button, "0 " + (~(elem.prev.pos * elem.prev.height) + 1) + 'px');
    }
},

makeToolbarGrayscale : function (image) {
    var canvas = this.doc.createElement("canvas");
    var context = canvas.getContext("2d");
    var filter = function(pixels) {
        var d = pixels.data, i, r, g, b;

        for (i = 0; i < d.length; i += 4) {
            r = d[i];
            g = d[i + 1];
            b = d[i + 2];
            d[i] = d[i + 1] = d[i + 2] = (r+g+b)/3;
        }
        return pixels;
    };

    var imgWidth = image.width;
    var imgHeight = image.height;

    canvas.width = imgWidth;
    canvas.height =imgHeight;
    context.drawImage(image, 0, 0);

    var imageData = context.getImageData(0,0, imgWidth, imgHeight);
    filter(imageData);
    context.putImageData(imageData, 0, 0);

    return canvas.toDataURL();
},

toolbarSetBackgroundImage : function (elem, disable) {
    var css = elem.firstChild.className;
    css = css.replace(/-disable$/i, "");

    if (disable) {
        if (this.cheditor.toolbarGrayscale && elem.firstChild.style.backgroundImage) {
            elem.firstChild.style.backgroundImage = 'url('+this.cheditor.toolbarGrayscale+')';
        }
        css = css + "-disable";
        elem.style.cursor = 'default';
    }
    else {
        if (this.cheditor.toolbarGrayscale && elem.firstChild.style.backgroundImage) {
            elem.firstChild.style.backgroundImage = 'url('+this.toolbar.icon+')';
        }
        elem.style.cursor = 'pointer';
    }

    elem.firstChild.className = css;
},

toolbarDisable : function (elem, disable) {
	if (disable) {
		this.toolbarSetBackgroundImage(elem.button, true);
		this.toolbarButtonUnchecked(elem);
		this.toolbarMouseDownOut(elem);
		this.toolbar[elem.name]['disabled'] = true;
		return true;
	}

	this.toolbarSetBackgroundImage(elem.button, false);
	this.toolbar[elem.name]['disabled'] = false;
	return false;
},

colorConvert : function (color, which, opacity) {
    if (!which) {
        which = "rgba";
	}
    color = color.replace(/^\s*#|\s*$/g, "");
	if (color.length === 3) {
        color = color.replace( /(.)/g, "$1$1" );
	}

    color = color.toLowerCase();
	which = which.toLowerCase();

	var colorDefs = [{
        re: /^rgb\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3})\)$/,
            process: function (bits) {
                return [
                    parseInt(bits[1], 10),
                    parseInt(bits[2], 10),
                    parseInt(bits[3], 10),
                    1
                ];
            }
        },
        {
            re : /^rgba\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3}),\s*(\d+(?:\.\d+)?|\.\d+)\s*\)/,
            process: function (bits) {
                return [
                    parseInt(bits[1], 10),
                    parseInt(bits[2], 10),
                    parseInt(bits[3], 10),
                    parseFloat(bits[4])
                ];
            }
        },
        {
            re: /^([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})$/,
            process: function (bits) {
                return [
                    parseInt(bits[1], 16),
                    parseInt(bits[2], 16),
                    parseInt(bits[3], 16),
                    1
                ];
            }
        },
        {
            re: /^([0-9a-fA-F])([0-9a-fA-F])([0-9a-fA-F])$/,
            process: function (bits) {
                return [
                    parseInt(bits[1] * 2, 16),
                    parseInt(bits[2] * 2, 16),
                    parseInt(bits[3] * 2, 16),
                    1
                ];
            }
        }
    ];
    var r, g, b, a, i, re, processor, bits, channels, min, rData;
    r = g = b = a = rData = null;

    for (i = 0; i < colorDefs.length; i++) {
        re = colorDefs[i].re;
        processor = colorDefs[i].process;
        bits = re.exec(color);
        if (bits) {
            channels = processor(bits);
            r = channels[0];
            g = channels[1];
            b = channels[2];
            a = channels[3];
        }
    }

    r = (r < 0 || isNaN(r)) ? 0 : ((r > 255) ? 255 : r);
    g = (g < 0 || isNaN(g)) ? 0 : ((g > 255) ? 255 : g);
    b = (b < 0 || isNaN(b)) ? 0 : ((b > 255) ? 255 : b);
    a = (a < 0 || isNaN(a)) ? 0 : ((a > 1) ? 1 : a);

    function hex(x) {
        return ("0" + parseInt(x, 10).toString(16)).slice(-2);
    }

    switch (which) {
        case "rgba":
            if (opacity) {
                a = (255 - (min = Math.min(r, g, b))) / 255;
                r = ((r - min) / a).toFixed(0);
                g = ((g - min) / a).toFixed(0);
                b = ((b - min) / a).toFixed(0);
                a = a.toFixed(4);
            }
            rData = "rgba(" + r + "," + g + "," + b + "," + a + ")";
            break;
        case "rgb":
            rData = "rgb(" + r + "," + g + "," + b + ")";
            break;
        case "hex":
            rData = "#" + hex(r) + hex(g) + hex(b);
            break;
    }
    return rData;
},

toolbarUpdate : function () {
	var toolbar = this.toolbar;
	var range = this.getRange();
	var selectionType = GB.selection.text;
	var pNode, ancestors = [], ancestorsLen = 0;
	var bRangeText = true;

    if (this.W3CRange) {
		try {
            pNode = this.getW3CRangeElement(range);
            if (pNode.nodeType === GB.node.element) {
                selectionType = !(range.toString()) ? GB.selection.element : GB.selection.text;
            }
            else if (pNode.nodeType === GB.node.text) {
                selectionType = GB.selection.text;
                pNode = pNode.parentNode;
            }
            else {
                selectionType = GB.selection.none;
                pNode = pNode.parentNode;
            }
		} catch (e) {
            pNode = this.doc;
        }

        bRangeText = !range.toString();
        if (bRangeText && !range.collapsed) {
            bRangeText = !GB.offElements[pNode.tagName.toLowerCase()];
        }
	}
	else {
        selectionType = this.getSelectionType(range);
        if (selectionType === GB.selection.text || selectionType === GB.selection.none)
        {
			pNode = range.parentElement();
            bRangeText = range.text === '';
        }
        else if (selectionType === GB.selection.element) {
			pNode = range.item(0);
            bRangeText = !GB.offElements[pNode.tagName.toLowerCase()];
        }
        else {
			pNode = this.doc;
        }
	}

	var isControl = false, isTable = false, i, j, btn, cmd, autoOff, isDisable,
        el, wrapper, fontAttr, oldName, span, newAttr, defaultAttr, state, css;

	if (selectionType === GB.selection.element) {
		isControl = this.W3CRange ? GB.offElements[pNode.nodeName.toLowerCase()] : true;
	}
	else {
        var node = pNode;
		while (node && node.nodeType === GB.node.element && node.nodeName !== 'BODY') {
			ancestors.push(node);
			if (node.nodeName === 'TD' || node.nodeName === 'TH') {
                isTable = true;
            }
			node = node.parentNode;
		}
		ancestorsLen = ancestors.length;
	}

	var isNoOff = { 'Link':1 };

	if (!isTable && selectionType === GB.selection.element &&
        (pNode.nodeName === 'TABLE' || pNode.nodeName === 'TD' || pNode.nodeName === 'TH'))
	{
		isTable = true;
	}

    var alignment = { "JustifyCenter" : "center", "JustifyRight" : "right", "JustifyFull" : "justify" };

	for (i in toolbar) {
        if (!(toolbar.hasOwnProperty(i))) {
            continue;
        }

        btn = toolbar[i];
        if (!btn.cmd) {
            continue;
        }

        cmd = btn.cmd;

		autoOff = false;
		if (isControl && selectionType === GB.selection.element) {
			if (btn.group !== 'Alignment') {
				autoOff = !(pNode.nodeName === 'IMG' && isNoOff[cmd]);
			}
		}

		if (btn.name === 'ModifyTable') {
			autoOff = !isTable;
		}

		isDisable = this.toolbarDisable(btn, autoOff);

		if (btn.name === 'ForeColor' || btn.name === 'BackColor') {
            btn.button.lastChild.style.display = isDisable ? 'none' : 'block';
		}
		if (btn.autocheck === null) {
            continue;
        }

        switch (cmd) {
        case 'Copy' :
        case 'Cut'  :
            this.toolbarDisable(btn, bRangeText);
            break;
        case 'UnLink' :
            try { this.toolbarDisable(btn, !this.doc.queryCommandEnabled(cmd)); }
            catch(ignore) {}
            break;
        case 'FormatBlock' :
			wrapper = btn.button.firstChild;
			oldName = wrapper.firstChild;
			el = false;
            span = document.createElement('span');
			for (j=0; j < ancestorsLen; j++) {
				if (GB.formatBlock[ancestors[j].nodeName]) {
                    span.appendChild(document.createTextNode(ancestors[j].nodeName));
					wrapper.replaceChild(span, oldName);
					el = true;
					break;
				}
			}

			if (!el) {
                span.appendChild(document.createTextNode('스타일'));
				wrapper.replaceChild(span, oldName);
			}
            this.unselectionElement(span);
            break;
        case 'ForeColor' :
        case 'BackColor' :
			if (cmd === 'BackColor' && !GB.browser.msie) {
                cmd = 'HiliteColor';
            }
			try {
                fontAttr = this.doc.queryCommandValue(cmd);
                if (fontAttr && !/^[rgb|#]/.test(fontAttr)) {
                    fontAttr = (((fontAttr & 0x0000ff) << 16) | (fontAttr & 0x00ff00) | ((fontAttr & 0xff0000) >>> 16)).toString(16);
                    fontAttr = "#000000".slice(0, 7-fontAttr.length) + fontAttr;
                }
                else {
                    fontAttr = (cmd === 'ForeColor') ? this.config.editorFontColor : this.config.editorBgColor;
                }
				btn.button.lastChild.style.backgroundColor = fontAttr;
			} catch (ignore) {}
            break;
        case 'FontName' :
        case 'FontSize' :
            try {
                fontAttr = this.doc.queryCommandValue(cmd);
                wrapper = btn.button.firstChild;
                span = this.doc.createElement('span');
                if (cmd === 'FontSize') {
                    try {
                        fontAttr = pNode.style.fontSize;
                        if (!fontAttr) {
                            for (i=0; i<ancestors.length; i++) {
                                fontAttr = ancestors[i].style.fontSize;
                                if (fontAttr) {
                                    break;
                                }
                            }
                        }
                    } catch (ignore) {}
                }

                if (fontAttr) {
                    newAttr = fontAttr;
                    newAttr = newAttr.replace(/'/g, '');
                    span.appendChild(this.doc.createTextNode(newAttr));
                    wrapper.replaceChild(span, wrapper.firstChild);
                }
                if (!span.hasChildNodes()) {
                    defaultAttr = (cmd === 'FontSize') ? this.config.editorFontSize : this.config.editorFontName;
                    if (wrapper.hasChildNodes()) {
                        wrapper.removeChild(wrapper.firstChild);
                    }
                    defaultAttr = defaultAttr.replace(/'/g, '');
                    span.appendChild(this.doc.createTextNode(defaultAttr));
                    wrapper.appendChild(span);
                }
                this.unselectionElement(span);
            } catch (ignore) {}
            break;
        case 'LineHeight':
            wrapper = btn.button.firstChild;
            this.unselectionElement(wrapper.firstChild);
            break;
		default :
			try {
				state = this.doc.queryCommandState(cmd);
                if (GB.browser.msie && !state && alignment[cmd]) {
                    el = pNode;
                    while (el && el.nodeName !== 'body') {
                        if (GB.lineHeightBlock.indexOf('|' + el.nodeName.toLowerCase() + '|') !== -1) {
                            css = this.getCssValue(el);
                            if (css) {
                                for (j=0; j<css.length; j++) {
                                    if (css[j].name.toLowerCase() === 'text-align' && css[j].value === alignment[cmd]) {
                                        state = true;
                                        break;
                                    }
                                }
                            }
                        }
                        el = el.parentNode;
                    }
                }

				if (state) {
                    btn.checked = true;
					this.toolbarButtonChecked(btn);
                    if (btn.type === "combo" && btn.name === btn.next.node) {
                        btn.next.active = true;
                        this.setToolbarBgPosition(btn.next.button,
                            (~(btn.next.width) + 1) + "px " + (~(((btn.next.pos + 1) * btn.next.height)) + 1) + 'px');
                    }
				}
				else {
					this.toolbarButtonUnchecked(btn);
                    btn.checked = false;
                    btn.next.active = false;
                    if (btn.type === "combo" && btn.name === btn.next.node) {
                        this.toolbarButtonUnchecked(btn.next);
                    }
				}
			} catch (ignore) {}
        }
	}
},

createButton : function (name, attr, prev) {
	var self = this;
	var elem, icon, btnIcon, iconPos, method, cmd, check, type, node, btnHeight, btnWidth,
        text, span, obj, btnClass;

	method = attr.getElementsByTagName('Execution')[0].getAttribute('method');
	cmd = attr.getElementsByTagName('Execution')[0].getAttribute('value');
	check = attr.getAttribute('check');
    type = attr.getAttribute('type');
    node = attr.getAttribute('node');

    btnClass = attr.getAttribute('class');
	btnWidth = attr.getAttribute('width');
    btnHeight = attr.getAttribute('height');

    elem = document.createElement('div');
	elem.style.width = btnWidth + 'px';
	elem.setAttribute('name', name);
	elem.style.height = btnHeight + 'px';

    icon = attr.getElementsByTagName('Icon')[0];
    btnIcon = document.createElement('div');
    btnIcon.className = icon.getAttribute('class');
    btnIcon.style.marginLeft = icon.getAttribute('margin') || "3px";

    iconPos = icon.getAttribute('position');
    if (iconPos) {
        btnIcon.style.backgroundImage = 'url('+self.toolbar.icon+')';
        btnIcon.style.backgroundRepeat = 'no-repeat';
        self.setToolbarBgPosition(btnIcon, (~iconPos + 1) + 'px center');
    }
    else {
        text = icon.getAttribute('alt');
        if (text) {
            span = document.createElement('span');
            span.appendChild(document.createTextNode(text));
            btnIcon.appendChild(span);
        }
    }

    elem.appendChild(btnIcon);

	obj = { 'button' : elem,
            'width' : btnWidth,
            'height' : btnHeight,
			'disabled' : false,
			'name' : name,
			'method' : method,
			'cmd' : cmd,
			'checked' : false,
            'className' : btnClass,
            'type' : type,
            'node' : node,
			'group' : '',
            'prev' : prev || null,
            'next' : null,
            'num' : 0,
            'pos' : 0,
            'colorNode' : {},
			'autocheck' : check };

    if (prev) {
        prev.next = obj;
    }

    elem.attr = obj;
	self.toolbar[name] = obj;

	self.addEvent(elem, 'mouseover', function() {
		if (!obj.disabled) {
            if (!obj.checked) {
                self.toolbarMouseOverUp(obj);
            }
            else {
                self.toolbarMouseOverUp(obj);
            }
        }
	});

	/*self.addEvent(elem, 'mouseup', function(ev) {
		if (!obj.checked && !obj.disabled) {
            self.toolbarMouseOverUp(obj);
        }
        self.stopEvent(ev || window.event);
	});*/

	self.addEvent(elem, 'mousedown', function(ev) {
		if (!obj.checked && !obj.disabled) {
			self.toolbarButtonChecked(obj);
			self.toolbarMouseDownOut(obj, true);
            if (obj.prev && obj.prev.type === "combo" && !obj.prev.checked) {
                self.setToolbarBgPosition(obj.prev.button,
                    "0 " + (~((self.getToolbarBgPosition(obj.prev.button) + 1) * obj.prev.height) + 1) + 'px');
            }
        }
        self.stopEvent(ev || window.event);
	});

	self.addEvent(elem, 'click', function(ev) {
		if (obj.disabled) {
            return;
        }
		switch (obj.method) {
		case 'doCmd' :
            self.backupRange(self.getRange());
			self.doCmd(obj.cmd, null);
			break;
		case 'windowOpen' :
			self.windowOpen(obj.cmd);
			break;
		case 'showPulldown' :
			if (obj.checked) {
				obj.checked = false;
				self.boxHideAll();
                self.toolbarButtonUnchecked(obj);
                return;
			}
            obj.checked = true;
			self.showPulldown(obj.cmd, obj.button);
            self.toolbarButtonChecked(obj);
            self.toolbarMouseDownOut(obj, true);
			break;
		default :
            alert('지원하지 않는 명령입니다.');
		}

        self.stopEvent(ev || window.event);
	});

    var comboOut = function(combo, startPos) {
            self.setToolbarBgPosition(combo.button,
                startPos+'px '+(~(((self.getToolbarBgPosition(combo.button)+(combo.checked ? 2 : 1)) * combo.height))+1)+'px');
    };

	self.addEvent(elem, 'mouseout', function() {
		if (!obj.checked) {
            if (obj.type === "combo") {
                if (obj.next) {
                    if (!obj.next.checked) {
                        self.toolbarButtonUnchecked(obj.next);
                        self.toolbarMouseDownOut(obj.next, false);
                    }
                    else {
                        return;
                    }
                }
            }
            if (obj.type === "combobox" && obj.prev.checked) {
                self.setToolbarBgPosition(obj.button,
                    (~(obj.width) + 1) + "px " + (~(((obj.pos + 1) * obj.height)) + 1) + 'px');
                    return;
            }
             self.toolbarButtonUnchecked(obj);
             self.toolbarMouseDownOut(obj, false);
		}
        else {
            if (obj.node && obj.node === obj.prev.name) {
                if (!obj.prev.checked) {
                    self.setToolbarBgPosition(obj.prev.button,
                        "0 " + (~((self.getToolbarBgPosition(obj.prev.button) + 1) * obj.prev.height) + 1) + 'px');
                }
                comboOut(obj, 0);
            }
        }
	});

	return obj;
},

showToolbar : function (toolbar, toolbarWrapper) {
	var self = this;
    var i, grpName, btn, btnLen, prevObj, j, attr, btnName, btnObj=null, btnNum, spacer, currentColor, fullscreen;
	var toolbarIcon = toolbar.getElementsByTagName('Image').item(0).getAttribute('file');
	var group = toolbar.getElementsByTagName('Group');
	var grpNum = group.length;
	var tmpArr = toolbarIcon.split(/\./);

    self.toolbar.icon = self.config.iconPath + toolbarIcon;
	self.toolbar.iconDisable = self.config.iconPath + tmpArr[0] + '-disable' + '.' + tmpArr[1];
    toolbarWrapper.className = 'cheditor-tb-wrapper';

	var appendSpaceBlock = function(pNode) {
		var split = document.createElement('div');
		split.className = 'cheditor-tb-split';
		pNode.appendChild(split);
	};

    fullscreen = document.createElement('span');
    if (self.config.useFullScreen === true) {
        fullscreen.appendChild(document.createTextNode("\u00a0"));
        fullscreen.className = "cheditor-tb-fullscreen";
        fullscreen.setAttribute("title", "전체 화면");
        fullscreen.onclick = function() {
            if (self.setFullScreenMode) {
                this.className = "cheditor-tb-fullscreen";
                this.setAttribute("title", "전체 화면");
            }
            else {
                this.className = "cheditor-tb-fullscreen-actual";
                this.setAttribute("title", "이전 크기로 복원");
            }
            self.fullScreenMode();
        };
    }
    else {
        fullscreen.clsaaName = 'cheditor-tb-fullscreen-disable';
    }
    toolbarWrapper.appendChild(fullscreen);

	for (i=0; i < grpNum; i++) {
        grpName = group[i].getAttribute('name');
		if (grpName === 'Split') {
			 appendSpaceBlock(toolbarWrapper);
			 continue;
		}

		btn = group[i].getElementsByTagName('Button');
		btnLen = btn.length;
        btnNum = 0; btnObj = null;

		for (j=0; j < btnLen; j++) {
			attr = btn[j].getElementsByTagName('Attribute')[0];
			btnName = btn[j].getAttribute('name');

			if (!attr.getAttribute("node") && self.config['use'+btnName] !== true) {
				continue;
			}
            if (attr.getAttribute("type") === "combobox" && self.config['use'+attr.getAttribute("node")] !== true) {
                continue;
            }

			btnObj = self.createButton(btnName, attr, btnObj);
            self.toolbar[btnObj.name].num = btnNum++;
            self.toolbar[btnObj.name].group = grpName;

            if (btn[j].getAttribute('tooltip') !== null) {
				btnObj.button.setAttribute('title', btn[j].getAttribute('tooltip'));
			}

			if (btnObj.name === 'ForeColor' || btnObj.name === 'BackColor') {
				currentColor = document.createElement('div');
				currentColor.className = 'cheditor-tb-color-btn';
				currentColor.style.backgroundColor = attr.getAttribute('default');
				btnObj.button.appendChild(currentColor);
			}
            toolbarWrapper.appendChild(btnObj.button);
		}

        if (btnObj === null) {
            continue;
        }

        prevObj = btnObj.prev;

        if (!prevObj) {
            btnObj.button.className = btnObj.className;
            if (btnObj.className === 'cheditor-tb-bg') {
                btnObj.className = btnObj.className + '-single';
                btnObj.button.className = btnObj.className;
            }
            btnObj.pos = self.getToolbarBgPosition(btnObj.button);
        }
        else {
            btnObj.className = btnObj.className + '-last';
            btnObj.button.className = btnObj.className;
            btnObj.pos = self.getToolbarBgPosition(btnObj.button);
            while (prevObj) {
                prevObj.button.className = prevObj.className;
                prevObj.pos = self.getToolbarBgPosition(prevObj.button);
                btnObj = prevObj;
                prevObj = prevObj.prev;
            }
            btnObj.className = btnObj.className + '-first';
            btnObj.button.className = btnObj.className;
            btnObj.pos = self.getToolbarBgPosition(btnObj.button);
        }
		spacer = document.createElement('div');
		spacer.className = 'cheditor-tb-button-spacer';
		toolbarWrapper.appendChild(spacer);
	}

	appendSpaceBlock(toolbarWrapper);

	if (GB.browser.msie) {
		var child = toolbarWrapper.getElementsByTagName('div');
		var len = child.length;
		for (i=0; i < len; i++) {
			self.unselectionElement(child[i]);
		}
		self.unselectionElement(toolbarWrapper);
	}
	else {
		self.unselectionElement(toolbarWrapper);
	}
},

unselectionElement : function (elem) {
	if (GB.browser.msie) {
		elem.setAttribute('unselectable', 'on');
		elem.setAttribute('contentEditable', 'false');
	}
	else {
		elem.onselectstart = new Function('return false');
	}
},

createEditorElement : function (container, toolbar) {
	if (!container.hasChildNodes()) {
        return;
    }
	var child = container.firstChild;
	var self = this, i, id, tab, tabId, editArea, done = false;
    var viewMode = function() {
            self.backupRange(self.getRange());
            self.switchEditorMode(this.getAttribute("mode"));
    };
    var resizeStart = function(event) {
        self.resizeStart(event);
    };
	do {
		id = child.getAttribute('id');
		switch (id) {
		case 'toolbar' :
			self.showToolbar(toolbar, child);
			self.cheditor.toolbarWrapper = child;
			break;
		case 'viewMode' :
			self.cheditor[id] = child;
			self.cheditor.mode = 'rich';

			if (child.hasChildNodes()) {
				tab = child.childNodes;
				self.cheditor.modetab = {};
				for (i=0; i < tab.length; i++) {
					tabId = tab[i].getAttribute('id');
					if (!tabId) { continue; }
					if ((tabId === 'code' && self.config.useSource === false) ||
						(tabId === 'preview' && self.config.usePreview === false))
					{
						tab[i].style.display = 'none';
						tab[i].removeAttribute('id');
						continue;
					}

                    tab[i].setAttribute('mode', tabId);
                    tab[i].onclick = viewMode;
					tab[i].removeAttribute('id');
                    self.cheditor.modetab[tabId] = tab[i];
				}
				self.unselectionElement(child);
			}
			break;
		case 'editWrapper' :
			editArea = child.getElementsByTagName('IFRAME').item(0);
			editArea.style.height = self.config.editorHeight;

			if (isNaN(self.config.tagIndex) === false) {
				editArea.tabIndex = self.config.tabIndex;
			}

			self.cheditor.editArea = editArea;
			self.cheditor[id] = child;
			break;
		case 'modifyBlock' :
			self.cheditor.editBlock = child;
			break;
		case 'tagPath' :
			if (self.config.showTagPath) {
				self.cheditor.tagPath = child.firstChild;
				child.style.display = 'block';
			}
			break;
		case 'resizeBar' :
			self.cheditor.resizeBar = child;
			child.onmousedown = resizeStart;
			self.unselectionElement(child);
			break;
		default : break;
		}

		child.removeAttribute('id');
		child = child.nextSibling;
	}
	while (child);

	var pNode = self.cheditor.textarea.parentNode,
		nNode = self.cheditor.textarea.nextSibling;

	if (!nNode) {
        pNode.appendChild(container);
    }
	else {
        pNode.insertBefore(container, nNode);
    }

    function ready() {
        if (done) {
            return;
        }
        done = true;
    }

	if (GB.browser.msie) {
		var isFrame = false;
        try {
            isFrame = window.frameElement !== null;
        } catch (ignore) {}

        if (document.documentElement.doScroll && !isFrame) {
            var tryScroll = function() {
                if (done) {
                    return;
                }
                try {
                    document.documentElement.doScroll("left");
                    ready();
                } catch (e) {
                    setTimeout(tryScroll, 10);
                }
            };
            tryScroll();
        }

		self.addEvent(document, 'readystatechange', function() {
            if (document.readyState === "complete") {
				ready();
			}
		});
	}
	else {
		self.addEvent(document, 'DOMContentLoaded', function() {
            ready();
		});
	}

	container.style.width = self.config.editorWidth;
	self.cheditor.container = container;
},

loadTemplate : function (xmlDoc) {
	var self = this;
	var tmpl = xmlDoc.getElementsByTagName('Template').item(0);
	var toolbar = tmpl.getElementsByTagName('Toolbar').item(0);

	if (tmpl.getElementsByTagName('Image').item(0).getAttribute('file') === null) {
        throw '툴바 아이콘 이미지 파일 이름이 정의되지 않았습니다.';
    }

	var cdata = tmpl.getElementsByTagName('Container').item(0).getElementsByTagName('Html').item(0);
	var html = self.getCDATASection(cdata);
	var tmpDiv = document.createElement('div');
	tmpDiv.innerHTML = html;

	var container = tmpDiv.firstChild;
	self.createEditorElement(container, toolbar);

	cdata = tmpl.getElementsByTagName('PopupWindow').item(0).getElementsByTagName('Html').item(0);
	html = self.getCDATASection(cdata);
	tmpDiv.innerHTML = html;

	var popupWindow = tmpDiv.firstChild;
	self.cheditor.popupElem = popupWindow;

	var dragHandle = popupWindow.firstChild;
	self.cheditor.dragHandle = dragHandle;
	self.cheditor.popupTitle = dragHandle.getElementsByTagName('label')[0];
	self.cheditor.popupFrameWrapper = popupWindow.lastChild;

	container.appendChild(popupWindow);

	var modalFrame = document.createElement('div');
	modalFrame.className = 'cheditor-modalPopupTransparent';
	self.cheditor.modalBackground = modalFrame;
    self.cheditor.modalBackground.id = "popupModalBackground";
    self.cheditor.modalBackground.className = "cheditor-popupModalBackground";
	container.parentNode.insertBefore(modalFrame, container);

	self.cheditor.htmlEditable = document.createElement('iframe');
	self.cheditor.htmlEditable.style.display = 'none';
	self.cheditor.htmlEditable.style.width = '1px';
	self.cheditor.htmlEditable.style.height = '1px';
	self.cheditor.htmlEditable.style.visibility = 'hidden';
	container.appendChild(self.cheditor.htmlEditable);
},

imageEvent : function (img, action) {
	var self = this;
	if (GB.browser.msie) {
		if (!action) {
			img.onmouseup = null;
			return;
		}
		img.onmouseup = function() {
			self.cheditor.editBlock.style.display = "block";
			self.modifyImage(this);
		};
	}
	else {
		var imgev = function() {
			self.cheditor.editBlock.style.display = "block";
			self.modifyImage(this);
		};
		if (!action) {
			self.removeEvent(img, 'click', imgev);
			return;
		}
		this.addEvent(img, 'click', imgev);
	}
},

setImageEvent : function (action) {
	var images = this.doc.images;
	var len = images.length, i;

	for (i=0; i < len; i++) {
		this.imageEvent(images[i], action);
	}
},

run : function () {
    var self = this;
	this.setFolderPath();

	try { this.checkInputForm(); }
    catch (e) {
		alert(e.toString());
		return;
	}

	self.setDefaultCss({cssName: 'ui.css', doc: window.document});

    function showEditor() {
        if (!self.resetDoc()) {
            return;
        }
        self.editAreaFocus();
        self.setEditorEvent();
        self.setDefaultCss();

        if (GB.browser.msie && GB.browser.version > 9) {
            var image = new Image();
            image.onload = function() {
                self.cheditor.toolbarGrayscale = self.makeToolbarGrayscale(this);
                self.toolbarUpdate();
            };
            image.src = self.toolbar.icon;
            image.style.width = "750px"; image.style.height = "16px";
        }
        else {
            self.cheditor.toolbarGrayscale = null;
            self.toolbarUpdate();
        }
        self.setImageEvent(true);
    }

    if (this.W3CRange) {
        this.addEvent(document, this.cheditor.id, showEditor);
    }
    else {
        document.documentElement.loadEvent = 0;
        document.documentElement.attachEvent("onpropertychange", function(event) {
            if (event.propertyName === "loadEvent") {
                showEditor();
            }
        });
    }
    
    try { this.initTemplate(); }
    catch(ignore) {}
},

fullScreenMode : function () {
	var self = this;
	self.editAreaFocus();
	self.boxHideAll();

	var container = self.cheditor.container;
	self.cheditor.editArea.style.visibility = 'hidden';

	if (!self.setFullScreenMode) {
        var windowSize;
		container.className = 'cheditor-container-fullscreen';

		if (GB.browser.msie && GB.browser.version < 7) {
			self.cheditor.fullScreenFlag = document.createElement('span');
			self.cheditor.fullScreenFlag.style.display = 'none';
			container.parentNode.insertBefore(self.cheditor.fullScreenFlag, container);
			document.body.insertBefore(container, document.body.firstChild);
		}

        var child = container.firstChild, except = 0;
        while (child) {
            if (child.className !== "cheditor-editarea-wrapper" &&
                child.className !== 'cheditor-popup-window' &&
                child.className !== '')
            {
                except += child.offsetHeight;
            }
            child = child.nextSibling;
        }

		var containerReSize = function () {
            windowSize = self.getWindowSize();
            container.style.width = windowSize.width + 'px';
            self.cheditor.editArea.style.height = (windowSize.height - except - 6) + 'px';
		};

		window.onresize = containerReSize;
		containerReSize();

		self.cheditor.resizeBar.onmousedown = null;
		self.cheditor.resizeBar.className = "cheditor-resizebar-off";
	}
	else {
		window.onresize = null;
		container.removeAttribute('style');
		container.className = 'cheditor-container';
		container.style.width = self.config.editorWidth;

		var editorHeight = parseInt(self.config.editorHeight, 10);

		if (self.cheditor.mode !== 'rich') {
            var tbHeight = self.cheditor.toolbarWrapper.offsetHeight;
            if (tbHeight < 1) {
                tbHeight = parseInt(self.cheditor.toolbarWrapper.style.height, 10) +
                    parseInt(self.cheditor.toolbarWrapper.style.padding, 10);
            }
			editorHeight += tbHeight;
		}

		self.cheditor.editArea.style.height = editorHeight + 'px';
		self.cheditor.resizeBar.onmousedown = function(event) { self.resizeStart(event); };
		self.cheditor.resizeBar.className = "cheditor-resizebar";

		if (GB.browser.msie && GB.browser.version < 7) {
			self.cheditor.fullScreenFlag.parentNode.replaceChild(container, self.cheditor.fullScreenFlag);
		}
	}

	self.cheditor.editArea.style.visibility = 'visible';
	self.setFullScreenMode = !(self.setFullScreenMode);
	self.editAreaFocus();
},

showPulldown : function (cmd, btn) {

	switch (cmd) {
	case 'FontName' :
		this.showFontTypeMenu(btn);
		break;
	case 'FontSize' :
		this.showFontSizeMenu(btn);
		break;
	case 'FormatBlock' :
		this.showFormatBlockMenu(btn);
		break;
	case 'ForeColor' :
    case 'BackColor' :
        this.showColorMenu(btn);
        break;
	case 'TextBlock' :
		this.showTextBlockMenu(btn);
		break;
	case 'LineHeight' :
		this.showLineHeightMenu(btn);
		break;
	case 'OrderedList' :
    case 'UnOrderedList' :
		this.showOrderedListMenu(btn);
		break;
	default : break;
	}
},

setPulldownClassName : function (labels, pNode) {
    var i;
	for (i=0; i < labels.length; i++) {
		if (labels[i].getAttribute('name') === pNode.firstChild.firstChild.firstChild.nodeValue) {
			labels[i].parentNode.style.backgroundImage = 'url('+this.config.editorPath+'icons/checked.png)';
			labels[i].parentNode.style.backgroundPosition = '0 center';
			labels[i].parentNode.style.backgroundRepeat = 'no-repeat';
		}
		else {
			labels[i].parentNode.style.backgroundImage = '';
        }
		labels[i].parentNode.className = 'cheditor-pulldown-mouseout';
	}
},

showOrderedListMenu : function (pNode) {
    var self = this;
    var menu = pNode.getAttribute('name');
    var elem = self.pulldown[menu];
    if (!elem) {
        var i, div, label;
        var cmd = (menu === "UnOrderedListCombo") ? "InsertUnOrderedList" : "InsertOrderedList";
        var outputHtml = document.createElement('div');
        var cmdPopup = function() { self.doCmdPopup(cmd, this.id, self.toolbar[menu].prev.checked); };
        var mouseOver = function() { self.pulldownMouseOver(this); };
        var mouseOut = function() { self.pulldownMouseOut(this); };
        var list = (cmd === "InsertUnOrderedList") ? GB.listStyle.unOrdered : GB.listStyle.ordered;
        var li, ol;
        for (i in list) {
            if (list.hasOwnProperty(i)) {
                div = document.createElement('div');
                label = document.createElement('label');
                div.id = i;
                div.onclick = cmdPopup;
                div.onmouseover = mouseOver;
                div.onmouseout = mouseOut;
                self.pulldownMouseOut(div);

                label.style.fontFamily = "verdana";
                label.style.textAlign = "center";
                label.style.width = "15px";
                label.setAttribute('name', i);

                ol = document.createElement('ul');
                li = document.createElement('li');
                ol.style.width = '90px';
                ol.style.padding = "0 15px";
                ol.style.margin = "0px";
                try { ol.style.listStyleType = i; }
                catch(ignore) {}
                ol.style.cursor = 'default';
                ol.style.textAlign = 'left';
                li.appendChild(document.createTextNode(list[i]));
                ol.appendChild(li);
                label.appendChild(ol);
                div.appendChild(label);
                outputHtml.appendChild(div);
            }
        }
        self.createWindow(110, outputHtml);
        self.createPulldownFrame(outputHtml, menu);
        elem = self.pulldown[menu];
    }

    self.windowPos(pNode, menu);
    self.displayWindow(pNode, menu);
},

showColorMenu : function (pNode) {
	var menu = pNode.getAttribute('name');
	var elem = this.pulldown[menu];

	if (!elem) {
		var outputHtml = this.setColorTable(menu);
		this.createWindow(220, outputHtml);
		this.createPulldownFrame(outputHtml, menu);
		elem = this.pulldown[menu];
		elem.firstChild.className = 'cheditor-pulldown-color-container';
	}

    var selectedColor = this.colorConvert(pNode.lastChild.style.backgroundColor, "hex");
    this.toolbar[menu].colorNode.selectedValue.style.backgroundColor = selectedColor;
    this.toolbar[menu].colorNode.colorPicker.hidePicker();
    this.toolbar[menu].colorNode.colorPicker.fromString(selectedColor);
    this.toolbar[menu].colorNode.showPicker = false;

    var nodes = elem.getElementsByTagName('span');
    var i, len = nodes.length, node;
    for (i=0; i < len; i++) {
        node = nodes[i];
        node.style.backgroundImage = '';
        if (node.id && node.id.toLowerCase() === selectedColor.toLowerCase()) {
            node.style.backgroundImage = "url('"+this.config.iconPath + "/color_picker_tick.png')";
            node.style.backgroundRepeat = "no-repeat";
            node.style.backgroundPosition = "center center";

        }
    }
    this.toolbar[menu].colorNode.selectedValue.style.backgroundImage = "url('"+this.config.iconPath + "/color_picker_tick.png')";
    this.toolbar[menu].colorNode.selectedValue.style.backgroundRepeat = "no-repeat";
    this.toolbar[menu].colorNode.selectedValue.style.backgroundPosition = "center center";
    this.windowPos(pNode, menu);
	this.displayWindow(pNode, menu);
},

showFontTypeMenu : function (pNode) {
	var self = this;
	var menu = pNode.getAttribute('name');
	var elem = self.pulldown[menu];

	if (!elem) {
        var fonts = null, type, i, div, label;
		var outputHtml = self.doc.createElement('div');
        var cmdPopup = function() { self.doCmdPopup(menu, this.id);};
        var mouseOver = function() { self.pulldownMouseOver(this); };
        var mouseOut = function() { self.pulldownMouseOut(this); };

		for (type in GB.fontName) {
            if (GB.fontName.hasOwnProperty(type)) {
                fonts = GB.fontName[type];
			    for (i=0; i < fonts.length; i++) {
				    div = self.doc.createElement('div');
				    label = self.doc.createElement('label');
				    div.id = fonts[i];
				    div.onclick = cmdPopup;
				    div.onmouseover = mouseOver;
				    div.onmouseout = mouseOut;
				    label.style.fontFamily = fonts[i];//(type !== 'kr') ? fonts[i] : this.config.editorFontName;
				    label.appendChild(self.doc.createTextNode(fonts[i]));
				    label.setAttribute('name', fonts[i]);
				    div.appendChild(label);
				    outputHtml.appendChild(div);
			    }
            }
		}

		self.createWindow(155, outputHtml);
		self.createPulldownFrame(outputHtml, menu);
		elem = self.pulldown[menu];
	}
	self.setPulldownClassName(elem.firstChild.getElementsByTagName('LABEL'), pNode);
	self.windowPos(pNode, menu);
	self.displayWindow(pNode, menu);
},

showFormatBlockMenu : function (pNode) {
	var self = this;
	var menu = pNode.getAttribute('name');
	var elem = self.pulldown[menu];

	if (!elem) {
        var para, label, fontSize, div;
		var outputHtml = document.createElement('div');
        var cmdPopup = function() { self.doCmdPopup("FormatBlock", '<' + this.id + '>'); };
        var mouseOver = function() { self.pulldownMouseOver(this); };
        var mouseOut = function() { self.pulldownMouseOut(this); };
		for (para in GB.formatBlock) {
            if (GB.formatBlock.hasOwnProperty(para)) {
			    div = document.createElement('div');
			    div.id = para;
			    div.onclick = cmdPopup;
			    div.onmouseover = mouseOver;
			    div.onmouseout = mouseOut;

			    label = document.createElement('label');
			    if (para.match(/H[123456]/)) {
			    	fontSize = {'H1':'2em','H2':'1.5em','H3':'1.17em','H4':'1em','H5':'0.83em','H6':'0.75em'};
			    	label.style.fontWeight = 'bold';
			    	label.style.fontSize = fontSize[para];
                    label.style.lineHeight = 1.4;
			    }
			    else if (para === 'ADDRESS') {
			    	label.style.fontStyle = 'italic';
                }

			    label.appendChild(document.createTextNode(GB.formatBlock[para]));
			    div.appendChild(label);

			    label.setAttribute('name', GB.formatBlock[para]);
			    outputHtml.appendChild(div);
            }
		}
		self.createWindow(150, outputHtml);
		self.createPulldownFrame(outputHtml, menu);
		elem = self.pulldown[menu];
	}
	self.setPulldownClassName(elem.firstChild.getElementsByTagName('LABEL'), pNode);
	self.windowPos(pNode, menu);
	self.displayWindow(pNode, menu);
},

showFontSizeMenu : function (pNode) {
    var self = this;
    var menu = pNode.getAttribute('name');
    var elem = self.pulldown[menu];

    if (!elem) {
        var size, div, label, text, i;
        var value = GB.fontSize[this.config.fontSizeValue];
        var len = value.length;
        var outputHtml = document.createElement('div');
        var cmdPopup = function() { self.doCmdPopup(menu, this.id); };
        var mouseOver = function() { self.pulldownMouseOver(this); };
        var mouseOut = function() { self.pulldownMouseOut(this); };

        for (i=0; i < len; i++) {
            size = value[i];
            div = document.createElement('div');
            label = document.createElement('label');
            text = size > 48 ? '가' : (size > 28 ? '가나다' : '가나다라');
            size = size + this.config.fontSizeValue;
            div.id = size;
            div.onclick = cmdPopup;
            div.onmouseover = mouseOver;
            div.onmouseout = mouseOut;
            div.style.fontSize = size;

            label.style.fontFamily = this.config.editorFontName;
            label.setAttribute('name', size);
            label.appendChild(document.createTextNode(text+'('+size+')'));
            div.appendChild(label);
            outputHtml.appendChild(div);
        }
        self.createWindow(350, outputHtml);
        outputHtml.style.height = '300px';
        outputHtml.style.overflow = 'auto';
        self.createPulldownFrame(outputHtml, menu);
        elem = self.pulldown[menu];
    }
    self.setPulldownClassName(elem.firstChild.getElementsByTagName('LABEL'), pNode);
    self.windowPos(pNode, menu);
    self.displayWindow(pNode, menu);
},

showLineHeightMenu : function (pNode) {
    var self = this;
    var menu = pNode.getAttribute('name');
    var elem = self.pulldown[menu];

    if (!elem) {
        var i, div, label, text;
        var outputHtml = document.createElement('div');
        var cmdPopup = function() { self.doCmdPopup("LineHeight", this.id); };
        var mouseOver = function() { self.pulldownMouseOver(this); };
        var mouseOut = function() { self.pulldownMouseOut(this); };
        for (i in GB.lineHeight) {
            if (!(GB.lineHeight.hasOwnProperty(i))) {
                continue;
            }
            if (!GB.lineHeight[i]) {
                break;
            }
            div = document.createElement('div');
            label = document.createElement('label');
            text = i;

            div.id = GB.lineHeight[i];
            div.onclick = cmdPopup;
            div.onmouseover = mouseOver;
            div.onmouseout = mouseOut;

            label.style.fontFamily = this.config.editorFontName;
            label.setAttribute('name', GB.lineHeight[i]);
            label.appendChild(document.createTextNode(text));
            div.appendChild(label);
            outputHtml.appendChild(div);
        }
        self.createWindow(100, outputHtml);
        self.createPulldownFrame(outputHtml, menu);
        elem = self.pulldown[menu];
    }
    self.setPulldownClassName(elem.firstChild.getElementsByTagName('LABEL'), pNode);
    self.windowPos(pNode, menu);
    self.displayWindow(pNode, menu);
},

showTextBlockMenu : function (pNode) {
	var self = this;
	var menu = pNode.getAttribute('name');
	var elem = self.pulldown[menu];

	if (!elem) {
        var i, wrapper, div, label;
		var outputHtml = document.createElement('div');
        var cmdPopup = function() { self.boxStyle(this); };
        var mouseOver = function() { this.className = 'cheditor-pulldown-textblock-over'; };
        var mouseOut = function() { this.className = 'cheditor-pulldown-textblock-out'; };
		var quote = GB.textBlock;

		for (i=0; i < quote.length; i++) {
			wrapper = document.createElement('div');
			div = document.createElement('div');
			div.onclick = cmdPopup;
			wrapper.onmouseover = mouseOver;
			wrapper.onmouseout = mouseOut;
			wrapper.className = 'cheditor-pulldown-textblock-out';
            div.id = i;
			div.style.border = quote[i][0];
			div.style.backgroundColor = quote[i][1];
			div.style.fontFamily = self.config.editorFontName;

			label = document.createElement('label');
			label.appendChild(document.createTextNode('가나다라 ABC'));
			div.appendChild(label);
			wrapper.appendChild(div);
			outputHtml.appendChild(wrapper);

		}
		self.createWindow(160, outputHtml);
		self.createPulldownFrame(outputHtml, menu);
		elem = self.pulldown[menu];
		elem.firstChild.className = 'cheditor-pulldown-textblock-container';
	}
	self.windowPos(pNode, menu);
	self.displayWindow(pNode, menu);
},

createPulldownFrame : function (contents, id) {
	var div = document.createElement('div');
	div.className = 'cheditor-pulldown-frame';
	div.appendChild(contents);
	this.pulldown[id] = div;
	this.cheditor.container.firstChild.appendChild(div);
},

setDefaultCss : function (ar) {
	if (arguments.length < 1) {
		ar = {cssName: 'ui.css', doc: this.doc};
		//if (GB.browser.msie || GB.browser.opera) {
			ar = {cssName: 'editarea.css', doc: this.doc};
		//}
	}

	var cssFile = this.config.cssPath + ar.cssName,
            head = ar.doc.getElementsByTagName('head')[0], found = false;

	if (this.undefined(head)) {
		return;
    }

	if (head.hasChildNodes()) {
		var child = head.childNodes, i, href;
		for (i = 0; i < child.length; i++) {
			if (child[i].nodeName.toLowerCase() === 'link') {
				href = child[i].getAttribute('href');
				if (href && href === cssFile) {
					found = true;
					break;
				}
			}
		}
	}

	if (!found) {
		var css = head.appendChild(ar.doc.createElement('link'));
		css.setAttribute('type', 'text/css');
		css.setAttribute('rel', 'stylesheet');
		css.setAttribute('media', 'all');
		css.setAttribute('href', this.config.cssPath + ar.cssName);
	}
},

setEditorEvent : function () {
	var self = this;
    var keyDown = function(event) {
        if (self.cheditor.mode === "preview") {
            self.stopEvent(event);
            return;
        }
        self.doOnKeyDown(event);
    };
    self.addEvent(self.doc, "keydown", keyDown);

	var keyPress = function(event) {
        if (self.cheditor.mode === "preview") {
            self.stopEvent(event);
            return;
        }
        self.doOnKeyPress(event);
    };
	self.addEvent(self.doc, "keypress", keyPress);

	var keyUp = function(event) {
        if (self.cheditor.mode === "preview") {
            self.stopEvent(event);
            return;
        }
        self.doOnKeyUp(event);
    };
	self.addEvent(self.doc, "keyup", keyUp);

	var editorEvent = function(event) {
        if (self.cheditor.mode === "rich") {
            self.doEditorEvent();
            return;
        }
        if (self.cheditor.mode === "preview") {
            self.stopEvent(event);
        }
    };
	self.addEvent(self.doc, "mouseup", editorEvent);

	var hideBox = function(event) {
        if (self.cheditor.mode === "rich") {
            self.boxHideAll();
            return;
        }
        if (self.cheditor.mode === "preview") {
            self.stopEvent(event);
        }
    };
	self.addEvent(self.doc, "mousedown", hideBox);

    /*self.addEvent(self.doc, "mousemove", function(event) {
        var target = event.srcElement || event.target;
        var nodeName = target.nodeName;
        var elem = document.getElementById('targetNode');
        elem.innerHTML = target.nodeName;

    });*/
},

addEvent : function (evTarget, evType, evHandler) {
    if (evTarget.addEventListener) {
        evTarget.addEventListener(evType, evHandler, false);
    }
    else {
        evTarget.attachEvent("on"+evType, evHandler);
    }
},

removeEvent : function (elem, ev, func) {
	if (elem.removeEventListener) {
		elem.removeEventListener(ev, func, false);
    }
	else {
        elem.detachEvent("on" + ev, func);
    }
},

stopEvent : function (ev) {
    if (ev && ev.preventDefault) {
        ev.preventDefault();
        ev.stopPropagation();
    }
    else {
        ev = ev || window.event;
        ev.cancelBubble = true;
        ev.returnValue = false;
    }
},

toolbarButtonOut : function (elemButton, nTop) {
	elemButton.style.top = -nTop + 'px';
},

toolbarButtonOver : function (elemButton) {
	var nTop = elemButton.style.top.substring(0, elemButton.style.top.length - 2);
	elemButton.style.top = nTop - 22 + 'px';
},

getElement : function (elem, tag) {
    if (!elem || !tag) {
        return null;
    }

	while (elem && elem.nodeName.toLowerCase() !== tag.toLowerCase()) {
		if (elem.nodeName.toLowerCase() === 'body') { break; }
		elem = elem.parentNode;
	}
	return elem;
},

hyperLink: function (href, target, title) {
    this.editArea.focus();
    var self = this, links = null, i;

    var createLinks = function() {
        var range = null, selectedLinks = [], linkRange, selection = null;
        var container = null, k;

        range = self.restoreRange();
        self.backupRange(range);
        linkRange = self.createRange();

        if (self.W3CRange) {
            self.doc.execCommand("CreateLink", false, href);
            selection = self.getSelection();
            for (i=0; i < selection.rangeCount; ++i) {
                range = selection.getRangeAt(i);
                container = range.commonAncestorContainer;

                if (self.getSelectionType(range) === GB.selection.text) {
                    container = container.parentNode;
                }

                if (container.nodeName.toLowerCase() === 'a') {
                    selectedLinks.push(container);
                }
                else {
                    links = container.getElementsByTagName('a');
                    for (k=0; k < links.length; ++k) {
                        linkRange.selectNodeContents(links[k]);
                        if (linkRange.compareBoundaryPoints(range.END_TO_START, range) < 1 &&
                                linkRange.compareBoundaryPoints(range.START_TO_END, range) > -1)
                        {
                            selectedLinks.push(links[k]);
                        }
                    }
                }
            }
            linkRange.detach();
        }
        else {
            range = self.doc.selection.createRange();
            range.execCommand("UnLink", false);
            range.execCommand("CreateLink", false, href);

            switch (self.getSelectionType(range)) {
            case GB.selection.text :
                container = range.parentElement();
                break;
            case GB.selection.element :
                container = range.item(0).parentNode;
                break;
            default : return null;
            }

            if (container.nodeName.toLowerCase() === 'a') {
                selectedLinks.push(container);
            }
            else {
                links = container.getElementsByTagName('a');
                for (i=0; i < links.length; ++i) {
                    linkRange.moveToElementText(links[i]);
                    if (linkRange.compareEndPoints("StartToEnd", range) > -1 &&
                            linkRange.compareEndPoints("EndToStart", range) < 1)
                    {
                        selectedLinks.push(links[i]);
                    }
                }
            }

        }
        return selectedLinks;
    };

    links = createLinks();
    if (links) {
        for (i=0; i < links.length; ++i) {
            if (target) {
                links[i].setAttribute("target", target);
            }
            if (title) {
                links[i].setAttribute("title", title);
            }
        }
    }
},

getOffsetBox : function (el) {
    var box = el.getBoundingClientRect();
    var doc = this.doc.documentElement;
    var body = this.doc.body;
    var scrollTop = doc.scrollTop || body.scrollTop;
    var scrollLeft = doc.scrollLeft || body.scrollLeft;
    var clientTop = doc.clientTop || body.clientTop || 0;
    var clientLeft = doc.clientLeft || body.clientLeft || 0;
    var top = box.top + scrollTop - clientTop;
    var left = box.left + scrollLeft - clientLeft;

    return { top: Math.round(top), left: Math.round(left) };
},

makeSpacerElement : function () {
    var elem;
    var para = this.doc.createElement('p');
    if (GB.browser.msie && GB.browser.version < 11) {
        elem = this.doc.createComment(this.cheditor.bogusSpacerName);
    }
    else {
        elem = this.doc.createElement('br');
        elem.className = this.cheditor.bogusSpacerName;
    }
    para.appendChild(elem);
    return para;
},

boxStyle: function (el) {
	this.editAreaFocus();
	var range = this.range || this.getRange();
	var blockQuote = this.doc.createElement("blockquote");
    var elem;
    var para = null;

    blockQuote.style.border = GB.textBlock[el.id][0];
    blockQuote.style.backgroundColor = GB.textBlock[el.id][1];
	blockQuote.style.padding = "5px 10px";

    if (!this.W3CRange) {
		var ctx = range.htmlText;
		blockQuote.innerHTML = ctx || '&nbsp;';
        range.select();
        this.insertHTML(blockQuote);
        var textRange = this.getRange();
        elem = range.parentElement();
        textRange.moveToElementText(elem);
        textRange.collapse(false);
		textRange.select();
	}
	else {
        try {
            var frag = range.extractContents();
            if (!frag.firstChild) {
                para = this.makeSpacerElement();
                blockQuote.appendChild(para);
            }
            else {
                blockQuote.appendChild(frag);
            }
            range.insertNode(blockQuote);
            var pNode = blockQuote.parentNode;
            while (pNode && pNode.nodeName !== 'BODY') {
                if (pNode.nodeName === 'P' || pNode.nodeName === 'DIV') {
                    pNode.parentNode.insertBefore(blockQuote, pNode.nextSibling);
                    break;
                }
                pNode = pNode.parentNode;
            }
            this.placeCaretAt(para || blockQuote, false);
        } catch(ignore) {}
	}
	this.boxHideAll();
},

insertFlash: function (elem) {
    this.editArea.focus();
    this.backupRange(this.restoreRange());

	if (typeof elem === 'string') {
		var embed = null;
		var div = this.doc.createElement('div');
		elem = this.trimSpace(elem);

		var pos = elem.toLowerCase().indexOf("embed");
		if (pos !== -1) {
			var str = elem.substr(pos);
			pos = str.indexOf(">");
			div.innerHTML = "<" + str.substr(0, pos) + ">";
			embed = div.firstChild;
		}
		else {
			div.innerHTML = elem;
			var object = div.getElementsByTagName('OBJECT')[0];
			if (object && object.hasChildNodes()) {
				var child = object.firstChild;
				var movieHeight, movieWidth, i;
				movieWidth  = (isNaN(object.width) !== true) ? object.width : 320;
				movieHeight = (isNaN(object.height)!== true) ? object.height: 240;
				var params = [];

				do {
					if ((child.nodeName === 'PARAM') &&  (!this.undefined(child.name) && !this.undefined(child.value)))
					{
						params.push({key: (child.name === 'movie') ? 'src' : child.name, val: child.value});
					}
					child = child.nextSibling;
				}
				while (child);

				if (params.length > 0) {
					embed = this.doc.createElement('embed');
					embed.setAttribute("width", movieWidth);
					embed.setAttribute("height", movieHeight);
					for (i=0; i<params.length; i++) {
						embed.setAttribute(params[i].key, params[i].val);
                    }
					embed.setAttribute("type", "application/x-shockwave-flash");
				}
			}
		}

		if (embed) {
			if (this.W3CRange) {
                this.insertNodeAtSelection(embed);
			}
			else {
				this.doCmdPaste(embed.outerHTML);
			}
		}
	}
},

insertHtmlPopup: function (elem) {
    this.editArea.focus();
    this.backupRange(this.restoreRange());

    if (!this.W3CRange) {
         this.doCmdPaste((this.toType(elem) === 'string') ? elem : elem.outerHTML);
    }
    else {
        this.insertNodeAtSelection(elem);
    }

	this.clearStoredSelections();
},

insertHTML: function (html) {
	if (!this.W3CRange) {
		this.getRange().pasteHTML((this.toType(html) === 'string') ? html : html.outerHTML);
	}
	else {
        this.insertNodeAtSelection(html);
    }
},

placeCaretAt : function (elem, az) {
    var range = this.createRange();
    if (this.undefined(az)) {
        az = false;
    }

    if (this.W3CRange) {
        var selection = this.getSelection();
        selection.removeAllRanges();
        try {
            range.selectNodeContents(elem);
        } catch(e) {
            range.selectNode(elem);
        }
        range.collapse(az);
        selection.addRange(range);
    }
    else {
        try {
            range.moveToElementText(elem);
            range.collapse(az);
            range.select();
        } catch (ignore) {}
    }
},

selectNodeContents : function (node, pos) {
	var collapsed = !this.undefined(pos);
	var selection = this.getSelection();
	var range = this.getRange();

	if (node.nodeType === GB.node.element) {
		range.selectNode(node);
        if (collapsed) {
            range.collapse(pos);
        }
	}

	selection.removeAllRanges();
	selection.addRange(range);
	return range;
},

insertNodeAtSelection: function (insertNode) {
	var range = this.getRange();
    if (!range.collapsed) {
        range.deleteContents();
        range = this.getRange();
    }
    var selection = this.getSelection();
    var commonAncestorContainer = range.commonAncestorContainer;
    var startOffset = range.startOffset;
    var frag = this.doc.createDocumentFragment(), elem;
    var lastNode = null;
    var pNode = commonAncestorContainer;

    if (pNode.nodeType === GB.node.text) {
        pNode = pNode.parentNode;
    }

    this.removeBogusSpacer(pNode);

    if (typeof insertNode === "string") {
        var tmpWrapper = this.doc.createElement('div');
        tmpWrapper.innerHTML = insertNode;

        elem = tmpWrapper.firstChild;
        while (elem) {
            lastNode = frag.appendChild(elem);
            elem = tmpWrapper.firstChild;
        }
    }
    else {
        lastNode = frag.appendChild(insertNode);
    }

    if (startOffset < 1 && commonAncestorContainer.nodeType === GB.node.text) {
        commonAncestorContainer.parentNode.insertBefore(frag, commonAncestorContainer);
    }
    else {
        range.insertNode(frag);
    }

    if (lastNode) {
        range = range.cloneRange();
        range.setStartAfter(lastNode);
        selection.removeAllRanges();
        selection.addRange(range);
    }
    this.toolbarUpdate();
    return lastNode;
},

findBogusSpacer : function (elem, all) {
    var result = [];
    var self = this;
    (function _findBogusSpacer(elem) {
        var i, node;
        for (i=0; i < elem.childNodes.length; i++) {
            node = elem.childNodes[i];
            if ((node.nodeType === GB.node.element && node.className === self.cheditor.bogusSpacerName ) ||
                (node.nodeType === GB.node.comment && node.nodeValue === self.cheditor.bogusSpacerName))
            {
                result.push(node);
            }
            if (node.nodeType === GB.node.text && node.nodeValue === '\u00a0' && !node.nextSibling) {
                result.push(node);
            }
            if (all) {
                _findBogusSpacer(node);
            }
        }
    })(elem);
    return result;
},

removeBogusSpacer : function (elem, removeEmpty, all) {
    var remove = this.findBogusSpacer(elem, all);
    var i;
    for (i=0; i<remove.length; i++) {
        remove[i].parentNode.removeChild(remove[i]);
    }
    if (removeEmpty && !(elem.hasChildNodes())) {
        elem.parentNode.removeChild(elem);
    }
},

idGetRangeAt : function (range) {
    var self = this;
    function convert(result, bStart) {
        var point = range.duplicate();
        var span = self.doc.createElement('span');
        var parent = point.parentElement();
        var cursor = self.createRange();

        point.collapse(bStart);
        parent.appendChild(span);
        cursor.moveToElementText(span);

        var compareStr = bStart ? 'StartToStart' : 'StartToEnd';
        while (cursor.compareEndPoints(compareStr, point) > 0 && span.previousSibling) {
            parent.insertBefore(span, span.previousSibling);
            cursor.moveToElementText(span);
        }

        result.container = span.nextSibling || span.previousSibling;
        if (result.container === null) {
            result.container = span.parentNode;
        }
        parent.removeChild(span);
    }
    var start = {}, end = {};
    convert(start, true); convert(end, false);
    return { startContainer: start.container, endContainer: end.container };
},

doInsertImage : function (images, paragraph, useSpacer) {
	this.editAreaFocus();
	var range = this.restoreRange();
    this.backupRange(range);

	var i, j = 0, attr, image, lastNode = null, spacer, len = images.length;
    var fragment = this.doc.createDocumentFragment();
    range = this.getRange();
    var pNode = this.W3CRange ? range.commonAncestorContainer : range.parentElement();

    for (i in images) {
        if (!images.hasOwnProperty(i) || this.undefined(images[i])) {
            continue;
        }
        attr = images[i];
		image = this.doc.createElement('img');
		image.setAttribute('src', attr.fileUrl);

        if (this.config.imgSetAttrWidth === 1) {
            image.style.width = attr.width;
            image.style.height = attr.height;
        }
        else if (this.config.imgSetAttrWidth === -1) {
            image.style.width = "100%";
            image.style.height = "auto";
        }

        if (this.config.imgSetAttrAlt) {
            image.setAttribute('alt', attr.alt || attr.origName);
        }
        else {
            image.removeAttribute('alt');
        }

        if (paragraph) {
            lastNode = fragment.appendChild(this.doc.createElement('p'));
            if (attr.align !== 'left') { lastNode.style.textAlign = attr.align; }
            lastNode.appendChild(image);
            if (useSpacer) {
                spacer = this.doc.createElement('p');
                spacer.appendChild(this.doc.createTextNode('\u00a0'));
                fragment.appendChild(spacer);
            }
        }
        else {
            lastNode = fragment.appendChild(image);
            j++;
            if (useSpacer && j < len) {
                fragment.appendChild(this.doc.createTextNode('\u00a0'));
            }
        }
        this.images.push(attr);
	}

    if (lastNode) {
        var div;
        if (paragraph) {
            if (pNode.nodeName.toLowerCase() === 'p' || pNode.nodeName.toLowerCase() === 'div') {
                pNode.parentNode.insertBefore(fragment, pNode.nextSibling);
                this.removeBogusSpacer(pNode, true);
            }
            else {
                this.removeBogusSpacer(pNode, false);
                if (!this.W3CRange) {
                    div = this.doc.createElement('div');
                    div.appendChild(fragment);
                    range.pasteHTML(div.innerHTML);
                }
                else {
                    range.insertNode(fragment);
                }
            }
            this.placeCaretAt(lastNode, false);
        }
        else {
            if (!this.W3CRange) {
                div = this.doc.createElement('div');
                div.appendChild(fragment);
                range.pasteHTML(div.innerHTML);
            }
            else {
                range.deleteContents();
                range.insertNode(fragment);
                range = range.cloneRange();
                range.setStartAfter(lastNode);
                var selection = this.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);
            }
        }
        this.setImageEvent(true);
    }
},

showTagSelector : function (on) {
	if (this.config.showTagPath !== true) { return; }
	this.cheditor.tagPath.style.display = on ? 'block' : 'none';
},

richMode : function () {
	this.range = null;
	this.cheditor.editArea.style.visibility = 'hidden';
	this.setDesignMode(false);
    this.cheditor.toolbarWrapper.style.display = '';

    var content = (this.cheditor.mode === 'preview') ?
        this.getContents(this.config.fullHTMLSource) :
                this.makeHtmlContent();
    this.putContents(this.convertContentsSpacer(content));
    this.cheditor.toolbarWrapper.className = "cheditor-tb-wrapper";
	this.setDesignMode(true);
	this.setImageEvent(true);
    this.cheditor.editArea.style.visibility = 'visible';

    if (this.doc.body.hasChildNodes()) {
        var cursor = this.getRange(), selection, node;
        if (this.W3CRange) {
            selection = this.getSelection();
            node = cursor.commonAncestorContainer;
            cursor.selectNode(node);
            cursor.collapse(false);
            selection.removeAllRanges();
            selection.addRange(cursor);
        }
        else {
            node = this.doc.body.firstChild;
            if (node.nodeType === GB.node.text) {
                node = node.parentNode;
            }
            cursor.moveToElementText(node);
            cursor.collapse(false);
            cursor.select();
        }
    }
    this.editAreaFocus();
    if (this.doc.body.lastChild) {
        this.placeCaretAt(this.doc.body.lastChild, false);
    }
    else {
        this.initDefaultParagraphSeparator();
    }
	this.toolbarUpdate();
},

editMode : function () {
	this.range = null;
	this.cheditor.editArea.style.visibility = 'hidden';
	this.setDesignMode(false);
	var content = this.getContents(this.config.fullHTMLSource);

    if (content !== '') {
        if (this.W3CRange) {
            this.doc.body.textContent = content;
        }
        else {
            this.doc.body.innerText = content;
        }
        GB.prettify.initHighlightingOnLoad(['html', 'javascript', 'css'], this);
    }

    this.cheditor.toolbarWrapper.className = "cheditor-tb-wrapper-code";
	this.cheditor.editArea.style.visibility = 'visible';
	this.cheditor.editBlock.style.display = 'none';

	this.setDesignMode(true);
    this.editAreaFocus();
},

makeHtmlContent : function () {
    return this.doc.body.textContent || this.doc.body.innerText;
},

resetStatusBar : function () {
	if (this.config.showTagPath) {
		this.cheditor.tagPath.innerHTML = '&lt;html&gt; &lt;body&gt; ';
    }
},

previewMode : function () {
    var content;
    if (this.cheditor.mode === 'rich') {
        content = this.getContents(this.config.fullHTMLSource);
    }
    else {
        content = this.makeHtmlContent();
    }
	this.putContents(content);
	this.cheditor.editArea.style.visibility = 'hidden';
	this.cheditor.editBlock.style.display = 'none';
    this.cheditor.toolbarWrapper.className = "cheditor-tb-wrapper-preview";
	this.cheditor.editArea.style.visibility = 'visible';
	this.setImageEvent(false);
	this.setDesignMode(false);

    if (GB.browser.msie_b) {
        var oSheet = this.doc.styleSheets[0];
        oSheet.insertRule('p:after{content:"\u200b"}', oSheet.rules.length);
    }
},

convertContentsSpacer : function (content) {
    var self = this;
    content = content.replace(/[\r\n]/g, '');
    content = content.replace(/<([^>\/]+)>(&nbsp;|\s+)<\/([^>]+)>/gm,
        function(all, open, space, close) {
            var start = self.trimSpace(open.split(' ')[0]);
            var rdata = null;
            if (GB.lineHeightBlock.indexOf('|' + start + '|') !== -1) {
                rdata = '<' + open + '>';
                if (!(GB.browser.msie_b)) {
                    rdata += '<br class="' + self.cheditor.bogusSpacerName + '">';
                }
                rdata += '</' + close + '>';
            }
            return rdata || all;
        }
    );
    return content;
},

putContents : function (content) {
	if (this.config.fullHTMLSource) {
        content = content.substr(content.search(/<html/ig) + 1);
        content = content.substr(content.indexOf('>') + 1);
        content = '<html>' + content;
		this.doc.open();
		this.doc.write("<body>" + content + "</body>");
		this.doc.close();
	}
	else {
        content = "<span>remove_this</span>" + content;
		this.doc.body.innerHTML = content;
        this.doc.body.removeChild(this.doc.body.firstChild);
	}
},

getImages : function () {
	var img = this.doc.body.getElementsByTagName('img');
	var imgNumber = this.images.length;
	var imgArr = [], i, imgid, j;

	for (i=0; i<img.length; i++) {
		if (img[i].src) {
			imgid = img[i].src;
			imgid = imgid.slice(imgid.lastIndexOf("/") + 1);
			for (j=0; j<imgNumber; j++) {
				if (this.images[j]['fileName'] === imgid) {
					imgArr.push(this.images[j]);
					break;
				}
			}
		}
	}
	return imgArr.length > 0 ? imgArr : null;
},

getElementDefaultDisplay : function (elem) {
    return (window.getComputedStyle ? window.getComputedStyle(elem, null) : elem.currentStyle).display;
},

checkBogusSpacer : function (node, para) {
    var child, self = this;

    function checkBogus(elem) {
        var pNode = elem.parentNode;
        if (elem.className === self.cheditor.bogusSpacerName ||
            (elem.nodeType === GB.node.comment && elem.nodeValue === self.cheditor.bogusSpacerName))
        {
            pNode.removeChild(elem);
            if (pNode.hasChildNodes()) {
                return;
            }
            if (pNode === para) {
                pNode.appendChild(self.doc.createTextNode('\u00a0'));
                return;
            }

            while (pNode && (pNode !== para)) {
                if (GB.textFormatting[pNode.nodeName.toLowerCase()]) {
                    pNode.className = 'REMOVE_EMPTY_TAG';
                    pNode.parentNode.appendChild(self.doc.createTextNode('\u00a0'));
                }
                pNode = pNode.parentNode;
            }
        }
    }

    child = node.firstChild;

    if (para.className === this.cheditor.bogusParaName) {
        if (para.hasChildNodes()) {
            if (child.nodeType === GB.node.element && child.nodeName.toLowerCase() === 'br') {
                child.className = self.cheditor.bogusSpacerName;
            }
            para.removeAttribute('class');
        }
    }

    while (child) {
        this.checkBogusSpacer(child, para);
        child = child.nextSibling;
    }

    if (node.nodeType !== GB.node.text && node !== para) {
        if (node.className === this.cheditor.bogusParaName) {
            node.removeAttribute('class');
            child = node.firstChild;
            if (child) {
                if (child.nodeType === GB.node.element && child.nodeName.toLowerCase() === 'br') {
                    child.className = self.cheditor.bogusSpacerName;
                    node = child;
                }
            }
            else {
                node.className = 'REMOVE_EMPTY_TAG';
            }
        }
        checkBogus(node);
    }
},

xhtmlParse : function (node, lang, needNewLine) {
	var xhtmlText = '', innerText, tagName, child, parts, cssText, reMake=[], ct, kv, rs, i, j;
	var children = node.childNodes;
	var childrenLength = children.length;
	var doNewLine = needNewLine ? true : false;
    var attr, attrLen, attrValue, attrName, validAttr, attrLang = false, attrXmlLang = false, attrXmlns = false;
    var reComment = new RegExp();
    var reHyphen = new RegExp();
    var reParsedValue = this.makeRandomString();
    var embedParams, embedAlignCode, mediaAlign = '';
    reComment.compile("^<!--(.*)-->$");
    reHyphen.compile("-$");

	for (i=0; i < childrenLength; i++) {
		child = children[i];
        if (!child) {
            continue;
        }
        if (GB.browser.msie && GB.browser.version < 9) {
            if (child.getAttribute && child.getAttribute("re_parsed_attr")) {
                continue;
            }
            if (child.setAttribute) {
                child.setAttribute("re_parsed_attr", reParsedValue);
            }
        }

        if (child.parentNode && node.tagName.toLowerCase() !== child.parentNode.tagName.toLowerCase()) {
            continue;
        }

		switch (child.nodeType) {
			case GB.node.element :
                tagName = child.nodeName.toLowerCase();
				if (/^\W/.test(tagName) || tagName === '' || (tagName === 'meta' && child.name.toLowerCase() === 'generator')) {
                    break;
                }

				if (GB.browser.msie) {
					if (tagName === 'embed') {
						embedParams = /align=("[^"]*"|'[^']*'|[^"'\s]*)(\s|>)/i;
						embedAlignCode = child.outerHTML.match(embedParams);
						if (embedAlignCode) {
							//alignCode = embedAlignCode[1];
							mediaAlign = embedAlignCode.replace(/("|')/g, "");
						}
					}
					if (tagName === 'object' && !child.hasChildNodes()) {
						xhtmlText += this.replaceObjectCode(child.outerHTML);
						continue;
					}
				}

                if (tagName === '!') {
                    parts = reComment.exec(child.text);
                    if (parts) {
                        innerText = parts[1];
                        innerText = innerText.replace(/--/g, "__");
                        if (reHyphen.exec(innerText)) {
                            innerText += " ";
                        }
                         innerText = "<!--" + innerText + "-->";
                    }
                    break;
                }

                if (tagName === 'img' && !child.getAttributeNode('alt') && this.config.imgSetAttrAlt) {
                    child.setAttribute("alt", "");
                }

                if (tagName === 'script' && this.config.allowedScript !== true) {
                    continue;
                }

                if (child.className === 'REMOVE_EMPTY_TAG') {
                    continue;
                }

                if (tagName === 'p' && this.config.paragraphCss) {
                    child.style.margin = '0px';
                }

                if (GB.lineHeightBlock.indexOf('|' + tagName + '|') !== -1) {
                    if (child.hasChildNodes()) {
                        this.checkBogusSpacer(child, child);
                    }
                    else {
                        if (child.className === this.cheditor.bogusParaName) {
                            child.removeAttribute("class");
                        }
                        child.appendChild(this.doc.createTextNode('\u00a0'));
                    }
                }

                if (GB.newLineBefore.indexOf('|' + tagName + '|') !== -1) {
                    if ((this.doc.body.firstChild !== child && doNewLine) || (xhtmlText !== '')) {
                        if (!/\n$/.test(xhtmlText)) {
                            xhtmlText += "\n";
                        }
                    }
                    else {
                        doNewLine = true;
                    }
                }

                xhtmlText += '<' + tagName;
                attr = child.attributes;
                attrLen = attr.length;
                validAttr = attrLang = attrXmlLang = attrXmlns = false;
                attrValue = '';

                for (j=0; j < attrLen; j++) {
                    attrName = attr[j].nodeName.toLowerCase();
                    if (attrName === 're_parsed_attr') {
                        continue;
                    }
                    if (attr[j].specified === false && attrName !== 'selected' && attrName !== "style" && attrName !== "value" &&
                        attrName !== "shape" && attrName !== "coords" && /^on/.test(attrName) !== -1)
                    {
                        continue;
                    }
                    if ((attrName === "shape" || attrName === "coords") && tagName !== "area") {
                        continue;
                    }
                    if (tagName === "img" && attrName === "complete") {
                        continue;
                    }
                    if ((attrName === "selected" && !child.selected) || (attrName === "style" && child.style.cssText === '')) {
                        continue;
                    }
                    if (attrName === "_moz_dirty" || attrName === "_moz_resizing" || attrName === "_moz-userdefined" || attrName === "_moz_editor_bogus_node" ||
                        (tagName === "br" && attrName === "type" && attr[j].nodeValue === "_moz"))
                    {
                        continue;
                    }

                    validAttr = true;
                    switch (attrName) {
                        case "style" :
                            cssText = child.style.cssText.split(';');
							for (ct=0; ct < cssText.length; ct++) {
								kv = cssText[ct].split(/:/g);
								if (this.trimSpace(kv[0]) !== '' && this.trimSpace(kv[1]) !== '') {
                                    rs = kv.shift().toLowerCase();
                                    rs += ':' + kv.join(':');
									reMake.push(rs);
                                }
							}
                            attrValue = reMake.join(';');
                            reMake = [];
                            break;
                        case "class" :
							attrValue = child.className;
							break;
                        case "noshade" :
                        case "checked" :
                        case "selected" :
                        case "nowrap" :
                        case "disabled" :
                            attrValue = attrName;
                            break;
                        case "name" :
                            attrValue = child.name || child.getAttribute("name");
                            break;
                        case "for" :
                            attrValue = child.htmlFor;
                            break;
                        default :
                            try {
                                if (/^on/.test(attrName)) {
                                    if (this.config.allowedOnEvent) {
                                        attrValue = attr[j].nodeValue;
                                    }
                                    else {
                                        validAttr = false;
                                    }
                                }
                                else {
                                    attrValue = child.getAttribute(attrName, 2);
                                }
                            } catch (e) {
                                validAttr = false;
                            }
                    }

                    if (tagName === "embed") {
						switch (attrName) {
							case "align":
								attrValue = (mediaAlign !== '') ? mediaAlign : eval("child." + attrName);
								break;
							default:
                                attrValue = attr[j].nodeValue;
                                break;
						}
					}

					if (attrName === 'lang' && tagName === 'html') {
						attrLang = true;
						attrValue = lang;
					}

					if (attrName === 'xml:lang') {
						attrXmlLang = true;
						attrValue = lang;
					}

					if (attrName === 'xmlns') {
                        attrXmlns = true;
                    }

					if (tagName === 'object' && attrName === 'src' && GB.browser.msie) {
						attrValue = this.fixObjectSrc(child.outerHTML);
					}

					if (validAttr) {
						if (!(tagName === 'li' && attrName === 'value')) {
							xhtmlText += ' ' + attrName + "=";
							xhtmlText += (/"/.test(attrValue)) ? "'" + this.fixAttribute(attrValue) + "'" : '"' + attrValue + '"';
						}
					}
                }

                if (tagName === 'html') {
					if (!attrLang) {
                        xhtmlText += ' lang="' + lang + '"';
                    }
					if (!attrXmlLang) {
                        xhtmlText += ' xml:lang="' + lang + '"';
                    }
					if (!attrXmlns) {
                        xhtmlText += ' xmlns="http://www.w3.org/1999/xhtml"';
                    }
				}

                if (!GB.emptyElements[tagName] || child.hasChildNodes()) {
                    xhtmlText += '>';
                    innerText = '';
                    if (tagName === "style" || tagName === "title" || tagName === "script") {
                        innerText += (tagName === "script") ? child.text : child.innerHTML;
                        innerText = '\n'+this.trimSpace(innerText)+'\n';
                    }
                    else {
                        innerText += this.xhtmlParse(child, lang, doNewLine);
                    }

                    if (innerText) { xhtmlText += innerText; }
                    //if (doNewLine) { xhtmlText += '\n'; }
                    xhtmlText += '</' + tagName + '>';
                }
                else {
                    xhtmlText += ' />';
                    //if (tagName === 'br') { xhtmlText += '\n'; }
                }
				break;
			case GB.node.text :
                xhtmlText += this.htmlEncode(child.nodeValue);
				break;
            case GB.node.comment :
                xhtmlText += "<!-- " + this.trimSpace(child.nodeValue) + " -->";
                break;
			default: break;
		}
	}
	return xhtmlText;
},

htmlEncode : function (text) {
    //text = text.replace(/\n{2,}$/g, "\n");
    //text = text.replace(/&/g, "&amp;");
    text = text.replace(/</g, "&lt;");
    text = text.replace(/>/g, "&gt;");
    text = text.replace(/\u00a0/g, "&nbsp;");
    //text = text.replace(/\x22/g, "&quot;");
    return text;
},

fixAttribute : function (text) {
    return this.htmlEncode(text);
},

fixObjectSrc : function (text) {
	var obj = text.match(/<object ([^>]+)>/i);
	if (obj) {
		var value = obj[1].match(/src="([^"]+)"/i);
		if (!value) {
			value = obj[1].match(/src='([^']+)'/i);
			if (!value) {
                value = obj[1].match(/src=([^ ]+)/i);
            }
		}
		if (value) {
            return value[1];
        }
	}
	return '';
},

replaceObjectCode : function (text) {
	var tmpTxt = String(text);
	tmpTxt = tmpTxt.replace(/ style=/gi, 	' style=');
	tmpTxt = tmpTxt.replace(/ codeBase=/gi, ' codebase=');
	tmpTxt = tmpTxt.replace(/ height=/gi, 	' height=');
	tmpTxt = tmpTxt.replace(/ width=/gi, 	' width=');
	tmpTxt = tmpTxt.replace(/ align=/gi, 	' align=');
	tmpTxt = tmpTxt.replace(/ classid=/gi, 	' classid=');
	tmpTxt = tmpTxt.replace(/ src=/gi, 		' src=');
	tmpTxt = tmpTxt.replace(/ name=/gi, 	' name=');
	tmpTxt = tmpTxt.replace(/ value=/gi, 	' value=');
	tmpTxt = tmpTxt.replace(/ quality=/gi, 	' quality=');
	tmpTxt = tmpTxt.replace(/ type=/gi, 	' type=');
	tmpTxt = tmpTxt.replace(/ pluginspage=/gi, ' pluginspage=');
	tmpTxt = tmpTxt.replace(/<object /gi, 	'<object ');
	tmpTxt = tmpTxt.replace(/<\/object>/gi, '</object>');
	tmpTxt = tmpTxt.replace(/<param /gi, 	'<param ');
	tmpTxt = tmpTxt.replace(/<\/param>/gi, 	'</param>');
	tmpTxt = tmpTxt.replace(/<embed /gi, 	'<embed ');
	tmpTxt = tmpTxt.replace(/<\/embed>/gi, 	'</embed>');
	return tmpTxt;
},

needsClosingTag : function (el) {
	var closingTags = " head script style div span tr td tbody table em strong font a title ";
	return (closingTags.indexOf(" " + el.tagName.toLowerCase() + " ") !== -1);
},

stripBaseURL : function (url) {
	var baseURL = this.config.baseURL;
	baseURL = baseURL.replace(/[^\/]+$/, '');

	var baseRe = new RegExp(baseURL);
	url = url.replace(baseRe, "");

	baseURL = baseURL.replace(/^(https?:\/\/[^\/]+)(.*)$/, '$1');
	baseRe = new RegExp(baseURL);

	return url.replace(baseRe, "");
},

checkDocLinks : function () {
	var links = this.doc.links;
	var len = links.length;
	var host = location.host;
    var i, href;
	this.cheditor.links = [];

	for (i=0; i < len; i++) {
		if (!this.config.includeHostname) {
			href = links[i].href;
			if (href.indexOf(host) !== -1) {
				links[i].setAttribute('href', href.substring(href.indexOf(host) + host.length));
			}
		}

		if (this.config.linkTarget !== '' && this.config.linkTarget !== null) {
			if (!(links[i].getAttribute('target'))) {
				links[i].setAttribute('target', this.config.linkTarget);
			}
		}

		if (GB.browser.msie) {
			this.cheditor.links.push(links[i]);
		}
	}
},

checkDocImages : function () {
	var img = this.doc.images;
	var len = img.length;
	var host = location.host;
    var i, imgsrc;

	for (i=0; i < len; i++) {
		if (!this.config.includeHostname) {
			imgsrc = img[i].src;
			if (imgsrc) {
				if (imgsrc.indexOf(host) !== -1) {
					img[i].src = imgsrc.substring(imgsrc.indexOf(host) + host.length);
				}
			}
		}
		if (img[i].style.width) {
            img[i].removeAttribute('width');
        }
		if (img[i].style.height) {
            img[i].removeAttribute('height');
        }
	}
},

createTempDocument : function (contents) {
    var doc = GB.browser.msie ? this.cheditor.htmlEditable.contentWindow.document :
                this.cheditor.htmlEditable.contentDocument;
    contents = contents.replace(/[\n\r]+/g, '');
    doc.open();
    doc.write(contents);
    doc.close();
    return doc;
},

getContents : function (fullSource) {
	this.checkDocLinks();
	this.checkDocImages();

	var mydoc = '';
    var tmpDoc = this.createTempDocument(this.doc.documentElement.outerHTML);

	if (GB.browser.msie) {
        this.doc.body.removeAttribute('contentEditable');
    }

    if (fullSource) {
        mydoc =  GB.doctype + '\n';
        mydoc += this.xhtmlParse(tmpDoc.documentElement, this.config.xhtmlLang);
    }
    else {
        mydoc = this.xhtmlParse(tmpDoc.body, this.config.xhtmlLang);
    }
    mydoc = this.trimSpace(mydoc);

	if (GB.browser.msie || GB.browser.opera) {
        if (this.config.ieEnterMode === 'div') {
            mydoc = mydoc.replace(/<(\/?)p([^>]+)?>/gmi,
                    function (a, b, c) {
                        if (/^\S/.test(c)) { return a; }
                        return '<' + b + 'div' + c + '>';
                    });
        }
	}

	var self = this;
    if (this.config.colorToHex) {
        mydoc = mydoc.replace(/([color|background\-color]\s?[:=]).?(rgba?)\(\s*(\d+)\s*,\s*(\d+),\s*(\d+)\)/ig,
                function (a, b, c, d, e, f) {
                    return b + ' ' + self.colorConvert(c+'('+d+','+e+','+f+')', "hex");
                });
    }
    else {
        mydoc = mydoc.replace(/([color|background\-color]\s?[:=])(.?)#([a-fA-F0-9]+)/ig,
                function (a, b, c, d) {
                    return b + c + self.colorConvert(d, "rgb");
                });
    }

	return mydoc;
},

returnContents : function (mydoc) {
	this.setDesignMode(true);
	this.cheditor.textarea.value = mydoc;
	return mydoc;
},

makeAmpTag 	: function (str) { return str.replace(/&lt;/g, '&amp;lt;').replace(/&gt;/g, '&amp;gt;'); },
removeAmpTag: function (str) { return str.replace (/&amp;lt;/g, '&lt;').replace(/&amp;gt;/g,'&gt;'); },

getOutputContents : function (fullSource) {
	this.resetViewHTML();
	return this.removeAmpTag(this.getContents(fullSource));
},

outputHTML : function () {
	return this.returnContents(this.getOutputContents(true));
},

outputBodyHTML : function () {
	return this.returnContents(this.getOutputContents(false));
},

outputBodyText : function () {
	return this.returnContents(this.getBodyText());
},

getBodyText : function () {
	this.resetViewHTML();
	var rdata = String(GB.browser.msie ? this.doc.body.innerText : this.doc.body.textContent);
	return this.trimSpace(rdata);
},

returnFalse : function () {
	this.editAreaFocus();
	var img = this.doc.images, i;
	for (i=0; i<img.length; i++) {
		if (img[i].src) {
			if (img[i].getAttribute("onload")) {
				img[i].onload = 'true';
			}
		}
		else {
			img[i].removeAttribute("onload");
			img[i].removeAttribute("className");
		}
	}
	return false;
},

trimZeroSpace : function (str) {
    return str ? str.replace(/[\ufeff\u200b\xa0\u3000]+/g, '') : '';
},

trimSpace : function (str) {
	return str ? str.replace(/^[\s\ufeff\u200b\xa0\u3000]+|[\s\ufeff\u200b\xa0\u3000]+$/g, '') : '';
},

makeRandomString : function () {
	var chars = "_-$@!#0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	var len = 32;
	var clen = chars.length;
	var rData = '', i, rnum;
	for (i=0; i<len; i++) {
		rnum = Math.floor(Math.random() * clen);
		rData += chars.substring(rnum,rnum+1);
	}
	return rData;
},

strLength : function (str) {
	var len = str.length;
	var mbytes = 0, i, c;
	for (i=0; i<len; i++) {
		c = str.charCodeAt(i);
		if (c > 128) {
            mbytes++;
        }
	}
	return (len-mbytes) + (mbytes*2);
},

resetViewHTML : function () {
	if (this.cheditor.mode === 'code') {
		this.switchEditorMode('rich');
	}
},

contentsLengthAll : function () {
	return this.strLength(this.outputHTML());
},

contentsLength : function () {
	var content = String(this.trimSpace(this.outputBodyHTML()));
	if (!content || content === "") { return 0; }
	return this.strLength(content);
},

inputLength : function () {
	var content = this.getBodyText();
	if (content === '') { return 0; }
	return this.strLength(content);
},

displayWindow : function (pNode, id) {
	this.editAreaFocus();
    this.backupRange(this.getRange());
	this.boxHideAll(id);
	var pullDown = this.pulldown[id];
	pullDown.style.visibility = "visible";
	pullDown.style.zIndex = 10002;
    pullDown.focus();
},

pulldownMouseOver : function (el) {
	if (el.className === 'cheditor-pulldown-selected') { return; }
	el.className = "cheditor-pulldown-mouseover";
},
pulldownMouseOut  : function (el) {
	if (el.className === 'cheditor-pulldown-selected') { return; }
	el.className = "cheditor-pulldown-mouseout";
},

windowPos : function (pNode, id) {
	var L = pNode.offsetLeft;
	var boxEl = this.pulldown[id];
    if (this.toolbar[id].type === "combobox") {
        L -= parseInt(this.toolbar[this.toolbar[id].node].width, 10);
    }
    if (this.toolbar[id].prev && !this.toolbar[id].next) {
        L -= 1;
    }
	boxEl.style.left = L + 'px';
	boxEl.style.top  = pNode.offsetTop + parseInt(pNode.style.height, 10) + 'px';
},

boxHideAll : function (showId) {
    var menu, elem, ishide;
	for (menu in this.pulldown) {
        if (this.pulldown.hasOwnProperty(menu)) {
		    elem = this.pulldown[menu];
		    if (elem) {
		    	elem.style.visibility = 'hidden';
		    	ishide = this.undefined(showId) ? true : (menu !== showId);
		    	if (ishide && this.toolbar[menu].checked) {
		    		this.toolbar[menu].checked = false;
		    		this.toolbarButtonUnchecked(this.toolbar[menu]);
		    	}
		    }
        }
	}
},

createWindow : function (width, div) {
	div.className = 'cheditor-pulldown-container';
	div.style.width = width+'px';
},

setColorTable : function (menu) {
	var self = this;
	var pulldown = document.createElement('div');
	var len = GB.colors.length, i, cell, color=0;
	var container = document.createElement('div');
	var selected = document.createElement('input');
    selected.setAttribute("type", "text");
    selected.setAttribute("maxlength", '7');
	selected.className = 'cheditor-pulldown-color-selected';

	var selectedValue = document.createElement('input');
    selectedValue.setAttribute("type", "text");
    selectedValue.onfocus = function() { selected.focus(); };
    selectedValue.style.cursor = 'default';
	selectedValue.className = 'cheditor-pulldown-color-selected';
    selected.style.marginLeft = "-1px";
    selected.style.borderLeft = "none";
    selected.spellcheck = false;
    var cellWrapper = document.createElement('div');
    cellWrapper.style.margin = "2px";
    cellWrapper.style.position = 'relative';
    container.style.position = 'relative';
    var br = document.createElement('div');
    br.style.clear = 'both'; br.style.height = '0px';

    var colorPicker = new colorDropper.color(selected, {"iconDir": this.config.iconPath});
    var mouseOver = function() {
        colorPicker.fromString(this.id);
        this.parentNode.className = 'cheditor-pulldown-color-cell-over';
    };
    var mouseOut = function() {
        this.parentNode.className = 'cheditor-pulldown-color-cell';
    };
    var cellCmd = function() { self.doCmdPopup(menu, this.id); };
    var cellBorder;

    for (i=0; i < len; i++) {
		if (i % 13 === 0) {
			cellWrapper.appendChild(br.cloneNode(true));
            if (i === 26) {
                cellWrapper.lastChild.style.height = "4px";
            }
			len++;
			continue;
		}
        cellBorder = document.createElement('span');
        cellBorder.className = 'cheditor-pulldown-color-cell';
        cell = document.createElement('span');
        cell.id = GB.colors[color];
		cell.style.backgroundColor = GB.colors[color++];
		cell.onmouseover = mouseOver;
		cell.onmouseout = mouseOut;
		cell.onclick = cellCmd;
		cell.appendChild(document.createTextNode('\u00a0'));
        cellBorder.appendChild(cell);
		cellWrapper.appendChild(cellBorder);
	}
    cellWrapper.appendChild(br);
    cellWrapper.appendChild(selectedValue);
	cellWrapper.appendChild(selected);

    var reset = document.createElement('span');
    reset.appendChild(document.createTextNode('\u00a0'));
    reset.className = "cheditor-pulldown-color-reset";
    reset.onclick = function() { colorPicker.fromString(self.colorConvert(selectedValue.style.backgroundColor, "hex")); };
    cellWrapper.appendChild(reset);

    var showTooltip = '더 많은 색 보기'; var hideTooltip = '감추기';
    var pickerSwitch = document.createElement('span');
    pickerSwitch.appendChild(document.createTextNode('\u00a0'));
    pickerSwitch.className = "cheditor-pulldown-color-show-picker";
    pickerSwitch.setAttribute('title', showTooltip);
    pickerSwitch.onclick = function() {
        if (self.toolbar[menu].colorNode.showPicker) {
            colorPicker.hidePicker();
            self.toolbar[menu].colorNode.showPicker = false;
            pickerSwitch.setAttribute('title', showTooltip);
        }
        else {
            colorPicker.showPicker();
            self.toolbar[menu].colorNode.showPicker = true;
            pickerSwitch.setAttribute('title', hideTooltip);
        }
    };
    cellWrapper.appendChild(pickerSwitch);

    var button = document.createElement('img');
    button.className = "cheditor-pulldown-color-submit";
    button.src = this.config.iconPath + 'button/input_color.gif';
    button.onclick = function() { self.doCmdPopup(menu, selected.value); };
    cellWrapper.appendChild(button);
    container.appendChild(cellWrapper);

    self.toolbar[menu].colorNode.selectedValue = selectedValue;
    self.toolbar[menu].colorNode.colorPicker = colorPicker;

    pulldown.appendChild(container);
    return pulldown;
},

onKeyPressToolbarUpdate : function () {
    var self = this;
    if (self.tempTimer) { clearTimeout(self.tempTimer); }
    self.tempTimer = setTimeout(function() {
        self.toolbarUpdate();
        self.tempTimer = null;
    }, 100);
    if (self.config.showTagPath) { self.doEditorEvent(); }
},

doOnKeyDown : function (event) {
    var keyCode = event.keyCode;
    if (keyCode !== 8 && (keyCode < 33 || keyCode > 40)) { return; }
    if (this.cheditor.mode === "rich") {
        this.onKeyPressToolbarUpdate();
    }
},

doOnKeyUp : function (event) {
    var keyCode = event.keyCode;
    if (keyCode && keyCode === 13) {
        var rNode, nNode, child, br, node, comment, range, storedRange, currentNode, storedNode, self = this;

        if (this.cheditor.mode !== "rich" || (GB.browser.msie && GB.browser.version < 7)) {
            return;
        }

        comment = this.doc.createComment(this.cheditor.bogusSpacerName);
        if (GB.browser.msie && GB.browser.version < 9) {
            node = this.storedSelections[0];
            while (node.firstChild) { node = node.firstChild; }
            if (node.nodeType === GB.node.element) {
                try { node.appendChild(comment); }
                catch(ignore) {}
            }
            return;
        }

        range = this.getRange();
        storedRange = this.storedSelections[0];
        currentNode = range.commonAncestorContainer;
        storedNode = storedRange.commonAncestorContainer;
        br = this.doc.createElement('br');
        br.className = this.cheditor.bogusSpacerName;

        var prevNodeCheck = function() {
            if (storedRange.startOffset < 1) {
                node = storedNode;
                while (node) {
                    if (node.previousSibling) {
                        node = node.previousSibling;
                        break;
                    }
                    node = node.parentNode;
                }

                if (node.nodeType === GB.node.element) {
                    while (node.firstChild) { node = node.firstChild; }
                }

                if (node.nodeType === GB.node.element && node.nodeName.toLowerCase() === 'br') {
                    node.className = self.cheditor.bogusSpacerName;
                }
                else {
                    node.appendChild(br);
                }
            }
        };

        if (GB.browser.msie || GB.browser.edge) {
            if (!currentNode.hasChildNodes()) {
                currentNode.className = this.cheditor.bogusParaName;
            }
            if (storedNode.nodeType === GB.node.element) {
                rNode = storedNode.childNodes[storedRange.startOffset];
                if (!rNode) { return; }
                child = rNode;

                while (child.firstChild) { child = child.firstChild; }
                if (child.nodeType !== GB.node.element) { return; }

                child.appendChild(GB.browser.version < 11 ? comment : br);
                nNode = this.doc.createElement(rNode.nodeName);

                if (rNode.firstChild) {
                    nNode.appendChild(rNode.firstChild.cloneNode(true));
                }
                storedRange.insertNode(nNode);
                rNode.parentNode.removeChild(rNode);
            }
            return;
        }

        if (currentNode.lastChild && currentNode.lastChild.nodeName.toLowerCase() === 'br') {
            currentNode.lastChild.className = this.cheditor.bogusSpacerName;
        }
        else {
            prevNodeCheck();
        }
    }
},

doOnKeyPress : function (event) {
    var keyCode = event.keyCode;
    if (keyCode && keyCode === 13) {
        if (this.cheditor.mode !== "rich" || (GB.browser.msie && GB.browser.version < 7)) {
            return;
        }
        var range = this.getRange();
        if (GB.browser.msie && GB.browser.version < 9) {
            this.storedSelections[0] = range.parentElement();
        }
        else {
            this.backupRange(range);
        }

        if (GB.browser.msie && this.config.ieEnterMode === 'br' && !this.editArea.event.shiftKey)
        {
            var added = false;
            if (GB.browser.version > 8) {
                var br = this.doc.createElement("br");
                range.insertNode(br);
                range.setStartAfter(br);
                range.setEndAfter(br);
                added = true;
            }
            else {
                range.pasteHTML("<br>");
                range.select();
                range.moveEnd("character", 1);
                range.moveStart("character", 1);
                range.collapse(false);
                added = true;
            }
            if (added) {
                this.stopEvent(event);
            }
        }
    }

    if (this.cheditor.mode === "rich") {
        this.onKeyPressToolbarUpdate();
    }
},

setWinPosition : function (oWin, popupAttr, windowSize) {
	oWin.style.width = popupAttr.width + 'px';
    oWin.style.left = Math.round(((this.cheditor.editArea.clientWidth - popupAttr.width) / 2) + windowSize.offsetLeft) + 'px';
    oWin.style.top = Math.round(windowSize.offsetTop) + 'px';
},

getWindowSize : function () {
	var docMode = document.compatMode === 'CSS1Compat';
	var docBody = document.body;
	var docElem = document.documentElement;

	var rData = {
		width   : docMode ? docElem.clientWidth   : docBody.clientWidth,
		height  : docMode ? docElem.clientHeight  : docBody.clientHeight,
        scrollHeight : docMode ? docElem.scrollHeight : docBody.scrollHeight,
        scrollWidth : docMode ? docElem.scrollWidth : docBody.scrollWidth
	};

    if (this.undefined(window.pageXOffset)) {
        var factor = 1;
        if (docBody.getBoundingClientRect) {
            var rect = docBody.getBoundingClientRect ();
            var physicalW = rect.right - rect.left;
            var logicalW = document.body.offsetWidth;
            factor = Math.round ((physicalW / logicalW) * 100) / 100;
        }
        rData.scrollY = Math.round(docElem.scrollTop / factor);
        rData.scrollX = Math.round(docElem.scrollLeft / factor);
    }
    else {
        rData.scrollY = window.pageYOffset;
        rData.scrollX = window.pageXOffset;
    }

    var editAreaRect = this.cheditor.editArea.getBoundingClientRect();
    rData.clientTop = docElem.clientTop || docBody.clientTop || 0;
    rData.clientLeft = docElem.clientLeft || docBody.clientLeft || 0;
    rData.offsetTop = editAreaRect.top + rData.scrollY - rData.clientTop;
    rData.offsetLeft = editAreaRect.left + rData.scrollX - rData.clientLeft;
    return rData;
},

popupWinLoad : function (popupAttr) {
	var self = this;
    var windowSize = self.getWindowSize();

	if (self.cheditor.popupTitle.hasChildNodes()) {
        self.cheditor.popupTitle.removeChild(self.cheditor.popupTitle.firstChild);
    }

	self.cheditor.popupTitle.appendChild(document.createTextNode(popupAttr['title']));
	self.cheditor.popupElem.style.zIndex = 1002;
    self.setWinPosition(self.cheditor.popupElem, popupAttr, windowSize);

	var iframe = document.createElement("iframe");
	iframe.setAttribute('frameBorder', "0");
    iframe.setAttribute('height', "0");
    iframe.setAttribute('width', String(popupAttr['width'] - 22));
	iframe.setAttribute('name', popupAttr['tmpl']);
	iframe.setAttribute('src', self.config.popupPath + popupAttr['tmpl']);
	iframe.style.visibility = 'hidden';
	iframe.id = popupAttr['tmpl'];

	if (self.cheditor.popupFrameWrapper.hasChildNodes()) {
        self.cheditor.popupFrameWrapper.removeChild(self.cheditor.popupFrameWrapper.firstChild);
    }

	self.cheditor.popupFrameWrapper.appendChild(iframe);

	var popWinResizeHeight = function () {
        iframe.style.visibility = 'visible';
        iframe.contentWindow.focus();
        iframe.contentWindow.init.call(self, iframe, popupAttr['argv'] || null);
        var popupWinTop = Math.round((self.cheditor.editArea.clientHeight - self.cheditor.popupElem.clientHeight) / 2);
        self.cheditor.popupElem.style.top = Math.round(parseInt(self.cheditor.popupElem.style.top, 10) + popupWinTop) + 'px';
        self.cheditor.popupElem.style.visibility = 'visible';
	};

	if (GB.browser.msie && GB.browser.version < 11) {
		var done = false;
		iframe.onreadystatechange = function() {
			if (!done && (!this.readyState || this.readyState === "loaded" || this.readyState === "complete")) {
				done = true;
				popWinResizeHeight();
			}
		};
	}
	else {
		iframe.onload = popWinResizeHeight;
	}

    self.cheditor.popupElem.style.visibility = 'hidden';
	self.cheditor.popupElem.style.display = 'block';
	self.cheditor.modalBackground.style.zIndex = self.modalElementZIndex;

    if (GB.browser.msie && GB.browser.version < 10) {
        var modalResize = function() {
            self.cheditor.modalBackground.style.height = (windowSize.scrollHeight > windowSize.height) ?
                windowSize.scrollHeight : windowSize.height + 'px';

            if (window.scrollWidth > window.width) {
                self.cheditor.modalBackground.style.width = windowSize.width + (windowSize.scrollWidth - windowSize.width) + 'px';
            }
            else {
                self.cheditor.modalBackground.style.width = windowSize.width + 'px';
            }

            self.cheditor.modalBackground.style.left = windowSize.scrollX + 'px';
        };

        window.onresize = function() {
            windowSize = self.getWindowSize();
            modalResize();
        };

        modalResize();
        self.cheditor.modalBackground.style.filter = 'alpha(opacity=50)';
    }
    else {
        self.cheditor.modalBackground.style.opacity = 0.5;
    }

    var body = document.getElementsByTagName('body')[0];
	body.insertBefore(self.cheditor.modalBackground, body.firstChild);
	self.cheditor.modalBackground.style.display = 'block';
	body.insertBefore(self.cheditor.popupElem, body.firstChild);
	GB.dragWindow.init(self.cheditor.dragHandle, self.cheditor.popupElem);
},

popupWinCancel : function () {
    this.restoreRange();
    this.popupWinClose();
},

popupWinClose : function () {
	if (!this.cheditor.popupElem) {
		return;
    }
	this.cheditor.popupElem.style.display = 'none';
	this.cheditor.popupElem.style.zIndex = -1;
	this.cheditor.popupFrameWrapper.src = "";

    if (this.cheditor.popupFrameWrapper.hasChildNodes()) {
		this.cheditor.popupFrameWrapper.removeChild(this.cheditor.popupFrameWrapper.firstChild);
    }

	this.cheditor.modalBackground.style.display = 'none';
	this.cheditor.modalBackground.style.zIndex = -1;

	if (this.modalReSize !== null) {
		if (GB.browser.opera) {
			window.removeEventListener("resize", this.modaReSize, false);
		}
		this.modalReSize = null;
	}

    this.editAreaFocus();
},

clearStoredSelections : function () {
    this.storedSelections.splice(0, this.storedSelections.length);
},

restoreRange : function () {
    var range = null, selection = null;
    if (this.storedSelections[0]) {
        if (this.W3CRange) {
            selection = this.getSelection();
            selection.removeAllRanges();
            selection.addRange(this.storedSelections[0]);
            range = selection.getRangeAt(0);
        }
        else {
            range = this.getRange();
            if (this.storedSelections[0]) {
                if (typeof this.storedSelections[0] === "string") {
                    range.moveToBookmark(this.storedSelections[0]);
                }
                else {
                    range = this.storedSelections[0];
                }
            }
            range.select();
        }
    }
    return range;
},

backupRange: function (range) {
    var selection = null;
    if (this.W3CRange) {
        selection = this.getSelection();
		if (selection.rangeCount > 0) {
			selection.removeAllRanges();
			selection.addRange(range);
            this.storedSelections[0] = selection.getRangeAt(0);
		}
   }
   else {
        try {
            switch (this.getSelectionType(range)) {
                case GB.selection.none:
                case GB.selection.text:
                    this.storedSelections[0] = range.getBookmark();
                    break;
                case GB.selection.element:
                    this.storedSelections[0] = range;
                    break;
                default:
                    this.storedSelections[0] = null;
            }
        } catch (ignore) {}
   }
},

getSelection : function () {
    return this.W3CRange ? this.editArea.getSelection() : this.doc.selection;
},

getRange : function () {
   var selection = this.getSelection();
   var range = null;

    if (this.W3CRange) {
        if (selection.getRangeAt) {
            range = (selection.rangeCount > 0) ? selection.getRangeAt(0) : this.doc.createRange();
        }
        else {
            range = this.doc.createRange();
            range.setStart(selection.anchorNode, selection.anchorOffset);
            range.setEnd(selection.focusNode, selection.focusOffset);
        }
    }
    else {
         range = selection.createRange ? selection.createRange() : this.doc.createRange();
         if (!range) {
            range = this.doc.body.createTextRange();
         }
    }
    this.range = range;
    return range;
},

createRange : function () {
    return this.W3CRange ? this.doc.createRange() : this.doc.body.createTextRange();
},

getW3CRangeElement : function (range) {
    if (!this.W3CRange) { return null; } // IE < 9

    var rootNode = range.commonAncestorContainer;
    var startContainer = range.startContainer;
    var endContainer = range.endContainer;
    var startOffset = range.startOffset;
    var endOffset = range.endOffset;
    var node = startContainer;
    var len = rootNode.childNodes.length, i;

    if (GB.browser.msie) {
        if (!range.collapsed && rootNode.nodeType === GB.node.element) {
            if (rootNode === endContainer) {
                node = rootNode.childNodes[endOffset-1];
            }
            else if (rootNode === startContainer) {
                node = rootNode.childNodes[startOffset];
            }
            else {
                for (i=0; i<len; i++) {
                    if (rootNode.childNodes[i] === startContainer) {
                        node = rootNode.childNodes[i].nextSibling;
                        break;
                    }
                }
            }
        }
    }
    else {
        if (!range.collapsed &&
            startContainer === endContainer &&
            startContainer.nodeType === GB.node.element &&
            endOffset - startOffset === 1 &&
            startContainer.hasChildNodes())
        {
            node = startContainer.childNodes[startOffset];
        }
    }

    return node;
},

getSelectionType : function (range) {
	if (!range) {
        return null;
    }

    var selectionType = GB.selection.none;
    var selection = this.getSelection();
    if (!selection) {
        return selectionType;
    }

	if (this.W3CRange) {
        if (selection.rangeCount === 1) {
            var node = this.getW3CRangeElement(range);
            if (node.nodeType === GB.node.element) {
                selectionType = !(range.toString()) ? GB.selection.element : GB.selection.text;
            }
            else if (node.nodeType === GB.node.text) {
                selectionType = GB.selection.text;
            }
            else {
                selectionType = GB.selection.none;
            }
        }
	}
	else {
		try {
			var type = selection.type;
            switch (type) {
                case "Text": selectionType = GB.selection.text; break;
                case "Control": selectionType = GB.selection.element; break;
                case "None": selectionType = GB.selection.none; break;
                default: break;
            }

            if (selectionType === GB.selection.none && type.createRange().parentElement())
            {
                selectionType = GB.selection.text;
            }
		} catch(ignore) {}
	}

	return selectionType;
},

windowOpen : function (popupName) {
	this.editAreaFocus();
	this.boxHideAll();
	this.backupRange(this.getRange());
	if (!(this.undefined(GB.popupWindow[popupName]))) {
        var popup = GB.popupWindow[popupName];
        if (popupName === 'ImageUpload' && window.File && window.FileReader && window.FileList && window.Blob) {
            popup.tmpl = 'image.html5.html';
        }
		this.popupWinLoad(popup);
    }
	else {
        alert('사용할 수 없는 명령입니다.');
    }
},

doCmd : function (cmd, opt) {
    this.editAreaFocus();
	this.boxHideAll();

    var range = this.range;

    if (cmd === 'NewDocument') {
		if (confirm('글 내용이 모두 사라집니다. 계속하시겠습니까?')) {
            this.doc.body.innerHTML = '';
        }

		this.images = [];
		this.editImages = {};
        this.editAreaFocus();
		this.toolbarUpdate();
        this.initDefaultParagraphSeparator();
		return;
	}

	if (cmd === 'ClearTag') {
		if (confirm('모든 HTML 태그를 삭제합니다. 계속하시겠습니까?\n(P, DIV, BR 태그와 텍스트는 삭제하지 않습니다.)')) {
			var content = this.doc.body.innerHTML;
			this.doc.body.innerHTML = content.replace(/<(\/?)([^>]*)>/g,
					function(a, b, c) {
						var el = c.toLowerCase().split(/ /)[0];
						if (el !== 'p' && el !== 'div' && el !== 'br') {
                            return '';
                        }
						return '<'+b+el+'>';
					});
		}
		this.editAreaFocus();
		this.toolbarUpdate();
		return;
	}

	if (cmd === 'Print') {
		this.editArea.print();
		return;
	}

	if (cmd === 'PageBreak') {
		this.printPageBreak();
		this.editAreaFocus();
		return;
	}

	if (this.W3CRange || this.getSelectionType(range) === GB.selection.none) {
		range = this.doc;
	}

	if (!GB.browser.msie && (cmd === 'Cut' || cmd === 'Copy' || cmd === 'Paste')) {
		try {
            range.execCommand(cmd, false, opt);
        } catch (e) {
            var keyboard = '';
            var command = '';
            switch (cmd) {
                case 'Cut'  : keyboard = 'x'; command = '자르기'; break;
                case 'Copy' : keyboard = 'c'; command = '복사'; break;
                case 'Paste': keyboard = 'v'; command = '붙이기'; break;
            }

            alert('사용하고 계신 브라우저에서는 \'' + command + '\' 명령을 사용하실 수 없습니다. \n' +
            '키보드 단축키를 이용하여 주세요. (윈도 사용자: Ctrl + ' + keyboard + ', Mac 사용자: Apple + ' + keyboard + ')');
		}

		this.editAreaFocus();
		return;
	}

	try {
        var pNode, node, self = this;
		if (cmd === 'PasteFromWord') {
            var cleanPaste = function() {
                self.editArea.focus();
                var tmpDoc = self.cheditor.tmpdoc;
                tmpDoc.execCommand("SelectAll");
                tmpDoc.execCommand("Paste");
                return self.cleanFromWord(tmpDoc);
            };

			if (this.undefined(this.cheditor.tmpdoc)) {
				var tmpframe = this.doc.createElement('iframe');
				tmpframe.setAttribute('contentEditable', "true");
				tmpframe.style.visibility = 'hidden';
				tmpframe.style.height = tmpframe.style.width = '0px';
				tmpframe.setAttribute('frameBorder', "0");
				this.cheditor.editWrapper.appendChild(tmpframe);

				var tmpdoc = tmpframe.contentWindow.document;
				tmpdoc.designMode = 'On';
				tmpdoc.open();
				tmpdoc.close();
				this.cheditor.tmpdoc = tmpdoc;
			}

            if (this.W3CRange) {
                var html = cleanPaste();
                range = this.restoreRange();
                this.insertNodeAtSelection(html);
            }
            else {
                range = this.getRange();
                range.pasteHTML(cleanPaste());
                range.select();
            }
		}
		else if (cmd === 'Paste') {
            this.cheditor.paste = 'text';
            range.execCommand(cmd);
            this.cheditor.paste = 'html';
		}
        else if (cmd === 'InsertHorizontalRule') {
            var hr = this.doc.createElement('hr'), emptyPara;
            hr.style.height = '1px';
            hr.style.backgroundColor = "#999";
            hr.style.border = "0";

            if (!GB.browser.msie) {
                range.execCommand("InsertParagraph", false);
                range = this.getRange();
                node = range.commonAncestorContainer;
                pNode = node;
                while (pNode && pNode.nodeName.toLowerCase() !== 'p') {
                    pNode = pNode.parentNode;
                }
                if (pNode.nodeName.toLowerCase() === 'p') {
                    emptyPara = this.makeSpacerElement();
                    if (node.nodeType === GB.node.text) {
                        node = node.parentNode;
                    }
                    if (!node.firstChild || node.firstChild.nodeName.toLowerCase() === 'br') {
                        pNode.parentNode.replaceChild(hr, pNode);
                        if (!(hr.nextSibling)) {
                            hr.parentNode.insertBefore(emptyPara, hr.nextSibling);
                            this.placeCaretAt(emptyPara, false);
                        }
                    }
                    else {
                        pNode.parentNode.insertBefore(hr, pNode);
                    }
                }
            }
            else {
                var id = this.makeRandomString();
                range.execCommand("InsertParagraph", false, id);
                emptyPara = this.doc.getElementById(id);
                emptyPara.parentNode.replaceChild(hr, emptyPara);
                emptyPara = this.makeSpacerElement();
                if (!(hr.previousSibling)) {
                    hr.parentNode.insertBefore(emptyPara, hr);
                }
                if (!(hr.nextSibling)) {
                    hr.parentNode.insertBefore(emptyPara.cloneNode(true), hr.nextSibling);
                }
                if (hr.nextSibling.nodeType === GB.node.element) {
                    this.placeCaretAt(hr.nextSibling, false);
                }
            }
        }
		else {
            switch (cmd) {
                case 'JustifyLeft' :
                case 'JustifyCenter' :
                case 'JustifyRight' :
                case 'JustifyFull' :
                    range.execCommand(cmd, false, opt);
                    range = this.getRange();
                    pNode = this.W3CRange ? range.commonAncestorContainer : range.parentElement();

                    if (!GB.browser.c && pNode.nodeType === GB.node.element &&
                            pNode.nodeName.toLowerCase() === 'body')
                    {
                        pNode = pNode.childNodes[range.startOffset];
                    }

                    if (pNode.nodeType === GB.node.text) {
                        pNode = pNode.parentNode;
                    }

                    var css, i, found = false;
                    while (pNode && pNode.nodeName.toLowerCase() !== 'body') {
                        if (pNode.getAttribute('align')) {
                            pNode.removeAttribute('align');
                            found = true;
                        }
                        else {
                            css = this.getCssValue(pNode);
                            if (css) {
                                for (i=0; i<css.length; i++) {
                                    if (css[i].name.toLowerCase() === 'text-align') {
                                        found = true;
                                        break;
                                    }
                                }
                            }
                        }
                        if (found) {
                            pNode.style.textAlign = GB.textAlign[cmd];
                            pNode.removeAttribute('align');
                            break;
                        }
                        pNode = pNode.parentNode;
                    }
                    break;
                case 'InsertOrderedList' :
                case 'InsertUnOrderedList' :
                    range.execCommand(cmd, false, opt);
                    if (this.W3CRange) {
                        range = this.getRange();
                        node = range.commonAncestorContainer;
                        var isEmpty = false;
                        if (node.nodeType === GB.node.element && node.lastChild &&
                                node.lastChild.nodeName.toLowerCase() === 'br')
                        {
                            node.lastChild.className = this.cheditor.bogusSpacerName;
                            isEmpty = true;
                        }
                        found = false;
                        while (node) {
                            if (node.nodeName.toLowerCase() === 'ul' || node.nodeName.toLowerCase() === 'ol') {
                                found = true;
                                break;
                            }
                            node = node.parentNode;
                        }
                        if (found) {
                            node.style.listStyleType = '';
                            if (!GB.browser.msie) {
                                if (node.parentNode.nodeName.toLowerCase() === 'p' ||
                                        node.parentNode.nodeName.toLowerCase() === 'div')
                                {
                                    pNode = node.parentNode;
                                    if (pNode.lastChild && pNode.lastChild.nodeName.toLowerCase() === 'br') {
                                        pNode.removeChild(pNode.lastChild);
                                    }
                                    if (pNode.firstChild === node && pNode.lastChild === node) {
                                        pNode.parentNode.insertBefore(node, pNode);
                                        pNode.parentNode.removeChild(pNode);
                                        this.placeCaretAt(node.lastChild, isEmpty);
                                    }
                                }
                            }
                        }
                    }
                break;
            default :
                if (range.queryCommandSupported(cmd)) {
                    range.execCommand(cmd, false, opt);
                }
            }
		}
	} catch (e) {
		alert(cmd + ": 지원되지 않는 명령입니다.");
	}

    this.editAreaFocus();
    this.toolbarUpdate();
},

cleanFromWord : function (tmpDoc) {
	var doc = tmpDoc.body.innerHTML;
	doc = doc.replace(/MsoNormal/g, "");        doc = doc.replace(/<\\?\?xml[^>]*>/g, "");  doc = doc.replace(/<\/?o:p[^>]*>/g, "");
	doc = doc.replace(/<\/?v:[^>]*>/g, "");     doc = doc.replace(/<\/?o:[^>]*>/g, "");     doc = doc.replace(/<\/?st1:[^>]*>/g, "");
	doc = doc.replace(/<!--(.*)-->/g, "");      doc = doc.replace(/<!--(.*)>/g, "");        doc = doc.replace(/<!(.*)-->/g, "");
	doc = doc.replace(/<\\?\?xml[^>]*>/g, "");  doc = doc.replace(/<\/?o:p[^>]*>/g, "");    doc = doc.replace(/<\/?v:[^>]*>/g, "");
	doc = doc.replace(/<\/?o:[^>]*>/g, "");     doc = doc.replace(/<\/?st1:[^>]*>/g, "");   doc = doc.replace(/lang=.?[^" >]*/ig, "");
	doc = doc.replace(/type=.?[^" >]*/g, "");   doc = doc.replace(/href='#[^"]*'/g, "");    doc = doc.replace(/href="#[^"]*"/g, "");
	doc = doc.replace(/name=.?[^" >]*/g, "");   doc = doc.replace(/ clear="all"/g, "");     doc = doc.replace(/id="[^"]*"/g, "");
	doc = doc.replace(/title="[^"]*"/g, "");    doc = doc.replace(/\n/g, "");               doc = doc.replace(/\r/g, "");
    doc = doc.replace(/mso\-[^">;]*/g, "");     doc = doc.replace(/<p[^>]*/ig, "<p");       doc = doc.replace(/windowtext/ig, "#000000");
	doc = doc.replace(/class=table/ig, "");     doc = doc.replace(/<span[^>]*<\/span>/ig, "");
	return doc;
},

printPageBreak : function () {
	var hr = document.createElement('hr');
	hr.style.pageBreakAfter = 'always';
	hr.style.border = '1px #999 dotted';
	this.insertHTML(hr);
    var div = this.doc.createElement("div");
    div.appendChild(this.doc.createTextNode("\u00a0"));
    this.insertHTML(div);
},

doCmdPaste : function (html) {
    this.editAreaFocus();
    if (!this.W3CRange) {
        if (this.range.item) {
            var rng = this.doc.body.createTextRange();
            if (rng) {
                rng.moveToElementText(this.range.item(0));
                rng.select();
                this.range.item(0).outerHTML = html;
            }
            this.toolbarUpdate();
        }
        else {
            this.range.pasteHTML(html);
            this.range.select();
        }
    }
    else {
        this.insertNodeAtSelection(html);
    }
},

getPreviousLeaf : function (node) {
    while (!node.previousSibling) {
        node = node.parentNode;
        if (!node) {
            return node;
        }
    }
    var leaf = node.previousSibling;
    while (leaf.lastChild) {
        leaf = leaf.lastChild;
    }
    return leaf;
},

getNextLeaf : function (node, breakNode) {
    while (!node.nextSibling) {
        node = node.parentNode;
        if ((breakNode && breakNode === node) || !node) {
            return node;
        }
    }
    var leaf = node.nextSibling;
    while (leaf.firstChild) {
        leaf = leaf.firstChild;
    }
    return leaf;
},

isTextVisible : function (text) {
    var i, found = false, len = text.length;
	for (i=0; i < len; i++) {
		if (text.charAt(i) !== ' ' && text.charAt(i) !== '\t' && text.charAt(i) !== '\r' && text.charAt(i) !== '\n') {
			found = true;
            break;
        }
	}
	return found;
},

getCssValue : function (elem) {
    var i, k, style = [], len = 0, css;
    css = elem.getAttribute('style');
    if (!css) {
        return null;
    }
    if (typeof css === 'object') {
        css = css.cssText;
    }

    css = css.replace(/;$/, '').split(';');
    len = css.length;

    for (i=0; i<len; i++) {
        k = css[i].split(':');
        style.push({"name":this.trimSpace(k[0]), "value":this.trimSpace(k[1])});
    }
    return style;
},

makeFontCss : function (cmd, opt, elem) {
    switch (cmd) {
        case 'font-size' : elem.style.fontSize = opt; break;
        case 'font-family' : elem.style.fontFamily = opt; break;
        case 'color': elem.style.color = opt; break;
        case 'background-color': elem.style.backgroundColor = opt; break;
    }
},

doCmdPopup : function (cmd, opt, checked) {
    this.editAreaFocus();
    var self = this;
    this.restoreRange();
    var range = this.restoreRange();
    var selectionType = this.getSelectionType(range);
    var startLeaf, endLeaf;

    if (this.W3CRange) {
        range = this.doc;
    }
    else {
        range = (selectionType === GB.selection.none) ? this.doc : range;
    }

    try {
        var cursor, pNode, node, found;
        var startContainer, endContainer;

        if (cmd === "LineHeight") {
            range = this.getRange();

            var isBlockElement = function(elem) {
                return GB.lineHeightBlock.indexOf('|'+elem.toLowerCase()+'|') !== -1;
            };

            var getNextLeaf = function(elem, endLeaf, value) {
                while (!elem.nextSibling) {
                    elem = elem.parentNode;
                    if (!elem) {
                        return elem;
                    }
                }

                if (elem === endLeaf) {
                    return elem;
                }

                var leaf = elem.nextSibling;
                if (isBlockElement(leaf.nodeName)) {
                    leaf.style.lineHeight = value;
                }

                while (leaf.firstChild) {
                    leaf = leaf.firstChild;
                    if (leaf.nodeType !== GB.node.text && isBlockElement(leaf.nodeName)) {
                        leaf.style.lineHeight = value;
                    }
                }
                return leaf;
            };

            if (!this.W3CRange) {
                var ieRange = this.ieGetRangeAt(range);
                startContainer = ieRange.startContainer;
                endContainer = ieRange.endContainer;
            }
            else {
                startContainer = range.startContainer;
                endContainer = range.endContainer;
            }

            if (!this.doc.body.hasChildNodes() || !startContainer || !endContainer) {
                throw "Object Error";
            }

            if (startContainer && startContainer.nodeName === "BODY") {
                startContainer = startContainer.firstChild;
            }

            try {
                var para, nextNode;
                var applyBlockElement = function(elem) {
                    while (elem) {
                        if (elem.nodeName === "BODY") {
                            para = self.doc.createElement("p");
                            para.style.lineHeight = opt;

                            if (elem.firstChild) {
                                elem.insertBefore(para, elem.firstChild);
                            }
                            else {
                                elem.appendChild(para);
                                break;
                            }

                            nextNode = para.nextSibling;
                            while (nextNode) {
                                if (isBlockElement(nextNode.nodeName)) {
                                    break;
                                }
                                para.appendChild(nextNode);
                                nextNode = para.nextSibling;
                            }
                            break;
                        }

                        if (isBlockElement(elem.nodeName)) {
                            elem.style.lineHeight = opt;
                            break;
                        }
                        elem = elem.parentNode;
                    }
                };

                if (startContainer === endContainer) {
                    applyBlockElement(startContainer);
                }
                else {
                    startLeaf = startContainer;
                    while (startLeaf) {
                        if (startLeaf.nodeName === "BODY" || isBlockElement(startLeaf.nodeName)) {
                            break;
                        }
                        startLeaf = startLeaf.parentNode;
                    }

                    endLeaf = endContainer;
                    while (endLeaf) {
                        if (endLeaf.nodeName === "BODY" || isBlockElement(endLeaf.nodeName)) {
                            break;
                        }
                        endLeaf = endLeaf.parentNode;
                    }

                    if (startLeaf === endLeaf) {
                        if (isBlockElement(startLeaf.nodeName)) {
                            startLeaf.style.lineHeight = opt;
                        }
                        else {
                            para = this.doc.createElement("P");
                            para.style.lineHeight = opt;
                            startLeaf.insertBefore(para, startLeaf.firstChild);

                            nextNode = para.nextSibling;
                            while (nextNode) {
                                if (isBlockElement(nextNode.nodeName)) {
                                    break;
                                }
                                para.appendChild(nextNode);
                                nextNode = para.nextSibling;
                            }
                        }
                    }
                    else {
                        applyBlockElement(startLeaf);
                        var nextLeaf;
                        while (startLeaf) {
                            nextLeaf = getNextLeaf(startLeaf, endLeaf, opt);
                            if (startLeaf === endLeaf) {
                                break;
                            }
                            startLeaf = nextLeaf;
                        }
                    }
                }
            } catch (ignore) {}
        }
        else {
            if (cmd === "InsertOrderedList" || cmd === "InsertUnOrderedList") {
                if (checked !== true) {
                    range.execCommand(cmd, null, opt);
                    if (!GB.browser.msie) {
                        range = this.getRange();
                        node = range.commonAncestorContainer;
                        var isEmpty = false;
                        found = false;
                        if (node.nodeType === GB.node.element && node.lastChild &&
                                node.lastChild.nodeName.toLowerCase() === 'br')
                        {
                            node.lastChild.className = this.cheditor.bogusSpacerName;
                            isEmpty = true;
                        }
                        while (node) {
                            if (node.nodeName.toLowerCase() === 'ul' || node.nodeName.toLowerCase() === 'ol') {
                                found = true;
                                break;
                            }
                            node = node.parentNode;
                        }

                        if (found) {
                            if (node.parentNode.nodeName.toLowerCase() === 'p' ||
                                    node.parentNode.nodeName.toLowerCase() === 'div')
                            {
                                pNode = node.parentNode;
                                if (pNode.lastChild && pNode.lastChild.nodeName.toLowerCase() === 'br') {
                                    pNode.removeChild(pNode.lastChild);
                                }
                                if (pNode.firstChild === node && pNode.lastChild === node) {
                                    pNode.parentNode.insertBefore(node, pNode);
                                    pNode.parentNode.removeChild(pNode);
                                    this.placeCaretAt(node.lastChild, isEmpty);
                                }
                            }
                        }
                    }
                }
                cursor = this.getRange();
                pNode = this.W3CRange ? cursor.commonAncestorContainer : cursor.parentElement();
                if (pNode.nodeType === GB.node.text) {
                    pNode = pNode.parentNode;
                }
                while (pNode) {
                    if (pNode.nodeName.toLowerCase() === "ol" || pNode.nodeName.toLowerCase() === "ul") {
                        if (opt === 'desc' || opt === 'decimal') {
                            opt = '';
                        }
                        pNode.style.listStyleType = opt;
                        break;
                    }
                    pNode = pNode.parentNode;
                }
            }
            else if (cmd === 'FontSize' || cmd === 'FontName' || cmd === 'ForeColor' || cmd === 'BackColor') {
                range = this.getRange();
                var spantag = 'span';
                var startNode = this.doc.createElement(spantag);
                var span, nNode, cColor, css, pcss, i, j, len, child, endNode, cur, prev,
                    epoint, compare, collapsed = false, isRemove = [], selection;

                found = false;
                if (!this.W3CRange) {
                    var id = this.makeRandomString();
                    var bText = range.text === '';
                    cursor = range.duplicate();
                    cursor.collapse(false);
                    endNode = startNode.cloneNode();
                    endNode.id = id;
                    cursor.pasteHTML(endNode.outerHTML);
                    endNode = this.$(id);

                    range.collapse(true);
                    collapsed = range.compareEndPoints("StartToStart", cursor) === 0;

                    id += '_S';
                    if (collapsed || bText) {
                        startNode.id = id;
                        endNode.parentNode.insertBefore(startNode, endNode);
                    }
                    else {
                        startNode = endNode.cloneNode();
                        startNode.id = id;
                        range.pasteHTML(startNode.outerHTML);
                        startNode = this.$(id);
                    }
                }
                else {
                    collapsed = range.collapsed;
                    cursor = range.cloneRange();
                    range.collapse(true);
                    startNode.id = "startNode";

                    cursor.collapse(false);
                    endNode = startNode.cloneNode();
                    endNode.id = "endNode";

                    if (collapsed) {
                        cursor.insertNode(endNode);
                        endNode.parentNode.insertBefore(startNode, endNode);
                    }
                    else {
                        range.insertNode(startNode);
                        cursor.insertNode(endNode);
                    }

                    if (startNode.previousSibling && startNode.previousSibling.nodeType === GB.node.text &&
                            startNode.previousSibling.nodeValue === '')
                    {
                        startNode.previousSibling.parentNode.removeChild(startNode.previousSibling);
                    }
                    if (endNode.nextSibling && endNode.nextSibling.nodeType === GB.node.text &&
                            endNode.nextSibling.nodeValue === '')
                    {
                        endNode.nextSibling.parentNode.removeChild(endNode.nextSibling);
                    }
                }

                if (cmd === 'ForeColor' || cmd === 'BackColor') {
                    opt = this.colorConvert(opt, "hex");
                }
                else if (cmd === 'FontName' && opt === '맑은 고딕') {
                    opt += ", Malgun Gothic";
                }

                cmd = GB.fontStyle[cmd];
                startLeaf = startNode;

                var checkColorConvert = function (styleName, styleValue) {
                    if (styleName === 'color' || styleName === 'background-color') {
                        return self.colorConvert(styleValue, "hex");
                    }
                    return null;
                };

                var checkPreviousLeaf = function (node) {
                    if (node.nodeType !== GB.node.element || !node.firstChild) {
                        return node;
                    }
                    cur = node;
                    prev = node.previousSibling;
                    var plen = 0, clen = 0;
                    found = null;
                    while (prev && prev.nodeName.toLowerCase() === spantag && prev !== startNode) {
                        css = self.getCssValue(cur);
                        pcss= self.getCssValue(prev);
                        plen = clen = 0;
                        if (css && pcss) {
                            clen = css.length;
                            plen = pcss.length;
                            if (plen && clen) {
                                for (i=0; i < css.length; i++) {
                                    cColor = checkColorConvert(css[i].name, css[i].value);
                                    if (cColor) {
                                        css[i].value = cColor;
                                    }
                                    for (j=0; j < pcss.length; j++) {
                                        cColor = checkColorConvert(pcss[j].name, pcss[j].value);
                                        if (cColor) {
                                            pcss[j].value = cColor;
                                        }
                                        if (css[i].name === pcss[j].name && css[i].value === pcss[j].value) {
                                            clen--; plen--;
                                            break;
                                        }
                                    }
                                }
                            }

                            if (clen < 1 && plen < 1) {
                                child = cur.firstChild;
                                while (child) {
                                    prev.appendChild(child);
                                    child = cur.firstChild;
                                }
                                cur.parentNode.removeChild(cur);
                                found = prev;
                            }
                        }
                        cur = prev;
                        prev = prev.previousSibling;
                    }
                    return found || node;
                };

                var makeSpanText = function (node, pNode) {
                    span = self.doc.createElement(spantag);
                    self.makeFontCss(cmd, opt, span);
                    pNode.insertBefore(span, node);
                    span.appendChild(node);
                    return span;
                };

                var textCssAttrMatch = function (node, name, value) {
                    if (!node) {
                        return null;
                    }
                    var style = self.getCssValue(node), elem = null;
                    if (!style) {
                        return null;
                    }

                    len = style.length;
                    value= self.trimSpace(value);
                    name = self.trimSpace(name);

                    for (i=0; i < len; i++) {
                        cColor = checkColorConvert(style[i].name, style[i].value);
                        if (cColor !== null) {
                            style[i].value = cColor;
                        }
                        if (style[i].name === name && style[i].value === value) {
                            elem = node;
                            break;
                        }
                    }
                    return elem;
                };

                if (self.W3CRange) {
                    cursor = self.doc.createRange();
                    epoint = self.doc.createRange();
                    epoint.selectNode(endNode);
                }
                else {
                    cursor = self.doc.body.createTextRange();
                    epoint = self.doc.body.createTextRange();
                    epoint.moveToElementText(endNode);
                }

                var finish = function (elem) {
                    if (!elem || !elem.hasChildNodes() || elem === endNode) {
                        return;
                    }
                    child = elem;
                    if (self.W3CRange) {
                        cursor.selectNode(elem);
                        compare = cursor.compareBoundaryPoints(Range.START_TO_END, epoint);
                    }
                    else {
                        cursor.moveToElementText(elem);
                        compare = cursor.compareEndPoints("StartToEnd", epoint);
                    }

                    if (compare > 0) {
                        while (child.lastChild) {
                            child = child.lastChild;
                        }
                        if (child === endNode) {
                            compare = 0;
                        }
                    }

                    if (compare < 1 && elem.nodeName.toLowerCase() === spantag) {
                        self.makeFontCss(cmd, opt, elem);
                    }

                    var children = elem.childNodes;
                    var idx, count = children.length;
                    for (idx=0; idx < count; idx++) {
                        if (compare < 1) {
                            if (children[idx].nodeName.toLowerCase() === spantag) {
                                self.makeFontCss(cmd, '', children[idx]);
                            }
                        }
                        finish(children[idx]);
                    }

                    css = self.getCssValue(elem);
                    if (css) {
                        len = css.length;
                        for (j=0; j < css.length; j++) {
                            cColor = checkColorConvert(css[j].name, css[j].value);
                            if (cColor) {
                                css[j].value = cColor;
                            }
                            if (textCssAttrMatch(elem.parentNode, css[j].name, css[j].value)) {
                                self.makeFontCss(css[j].name, '', elem);

                            }
                        }
                        if (!self.getCssValue(elem)) {
                            node = elem.firstChild;
                            while (node) {
                                elem.parentNode.insertBefore(node, elem);
                                node = elem.firstChild;
                            }
                            isRemove.push(elem);
                        }
                    }
                };

	            var commonAncestor = null;
                if (startNode.parentNode === endNode.parentNode) {
                    commonAncestor = startNode.parentNode;
                    if (commonAncestor.firstChild === startNode && commonAncestor.lastChild === endNode &&
                        commonAncestor.nodeName.toLowerCase() === spantag)
                    {
                        this.makeFontCss(cmd, opt, commonAncestor);
                        pNode = commonAncestor.parentNode;
                        while (pNode && pNode.nodeName.toLowerCase() === "span") {
                            if (pNode.firstChild !== commonAncestor || pNode.lastChild !== commonAncestor) {
                                break;
                            }

                            css = this.getCssValue(commonAncestor);
                            len = css.length;

                            for (i=0; i<len; i++) {
                                this.makeFontCss(css[i].name, css[i].value, pNode);
                            }

                            while (commonAncestor.firstChild) {
                                commonAncestor.parentNode.insertBefore(commonAncestor.firstChild, commonAncestor);
                            }

                            commonAncestor.parentNode.removeChild(commonAncestor);
                            commonAncestor = pNode;
                            pNode = pNode.parentNode;
                        }
                    }
                }

                if (!collapsed) {
                    while (startLeaf && startLeaf !== endNode) {
                        node = this.getNextLeaf(startLeaf);
                        if (node.nodeType !== GB.node.text || !this.isTextVisible(node.nodeValue) ||
                            textCssAttrMatch(node.parentNode, cmd, opt))
                        {
                            startLeaf = node;
                            continue;
                        }

                        pNode = node.parentNode;

                        if (node.previousSibling === startNode) {
                            if (pNode.firstChild === startNode && pNode.lastChild === node &&
                                    pNode.nodeName.toLowerCase() === spantag)
                            {
                                this.makeFontCss(cmd, opt, pNode);
                            }
                            else {
                                node = makeSpanText(node, pNode);
                            }
                            startLeaf = node;
                            continue;
                        }

                        if (node.nextSibling === endNode) {
                            if (pNode.firstChild === node && pNode.lastChild === endNode &&
                                    pNode.nodeName.toLowerCase() === spantag)
                            {
                                this.makeFontCss(cmd, opt, pNode);
                            }
                            else {
                                node = makeSpanText(node, pNode);
                            }
                            startLeaf = node;
                            continue;
                        }

                        if (pNode.nodeName.toLowerCase() === spantag) {
                            found = false;
                            nNode = node.nextSibling;
                            while (nNode && nNode !== pNode) {
                                if (nNode.nodeType === GB.node.text) {
                                    found = true;
                                    break;
                                }
                                nNode = this.getNextLeaf(nNode, pNode);
                            }

                            if (!found && (textCssAttrMatch(pNode, cmd, opt)) === null) {
                                this.makeFontCss(cmd, opt, pNode);
                            }
                            else {
                                node = makeSpanText(node, pNode);
                            }
                        }
                        else {
                            node = makeSpanText(node, pNode);
                        }
                        startLeaf = node;
                    }

                    nNode = startNode.nextSibling;
                    if (nNode && nNode.nodeType === GB.node.text) {
                        nNode = nNode.nextSibling;
                    }
                    if (!nNode) {
                        nNode = startNode.parentNode.nextSibling;
                    }

                    while (nNode && nNode !== endNode) {
                        if (nNode && nNode.nodeType === GB.node.element) {
                            finish(nNode);
                        }
                        nNode = checkPreviousLeaf(nNode);
                        while (!nNode.nextSibling && nNode.nodeName.toLowerCase() !== "body") {
                            nNode = nNode.parentNode;
                        }
                        nNode = nNode.nextSibling;
                    }

                    for (i=0; i<isRemove.length; i++) {
                        isRemove[i].parentNode.removeChild(isRemove[i]);
                    }
                    isRemove = [];

                    if (this.W3CRange) {
                        selection = this.getSelection();
                        range = this.getRange();
                        range.setStartAfter(startNode);
                        range.setEndBefore(endNode);
                        selection.removeAllRanges();
                        selection.addRange(range);
                    }
                }
                else {
                    pNode = startNode.parentNode;
                    found = false;
                    while (pNode && pNode.nodeName.toLowerCase() === spantag && !found) {
                         css = this.getCssValue(pNode);
                         if (css) {
                             len = css.length;
                             for (i=0; i < len; i++) {
                                 cColor = checkColorConvert(css[i].name, css[i].value);
                                 if (cColor) {
                                     css[i].value = cColor;
                                 }
                                 if (css[i].name === cmd && css[i].value === opt) {
                                     found = true;
                                     break;
                                 }
                             }
                         }
                         pNode = pNode.parentNode;
                    }

                    if (!found) {
                        var zeroWidth = this.doc.createTextNode('\u200b');
                        span = this.doc.createElement(spantag);
                        this.makeFontCss(cmd, opt, span);
                        span.appendChild(zeroWidth);
                        endNode.parentNode.insertBefore(span, endNode);

                        if (this.W3CRange) {
                            selection = this.getSelection();
                            selection.collapse(zeroWidth, 1);
                        }
                        else {
                            range = this.getRange();
                            range.moveToElementText(span);
                            range.collapse(false);
                            range.select();
                        }
                    }
                }
                startNode.parentNode.removeChild(startNode);
                endNode.parentNode.removeChild(endNode);
            }
            else {
                range.execCommand(cmd, false, opt);
            }
        }
    } catch(ignore) {}

    if (this.tempTimer) {
        clearTimeout(this.tempTimer);
    }

    this.tempTimer = setTimeout(function() {
        self.toolbarUpdate();
        self.tempTimer = null;
    }, 50);

    this.boxHideAll();
},

modifyImage : function (img) {
	var self = this;
	var imageWidthOpt = {
        "default" : {"size": "default", "desc": "원본 크기"},
		"fitpage" : {"size": "100%", "desc": "페이지 크기에 맞춤"}
    };
	var imageAlignOpt = {"left" : "왼쪽", "right" : "오른쪽"};
    var idx, div, ico, fmSelectWidth, wrapTextSpan, wrapTextCheckBox, wrapTextIcon, wrapTextChecked,
        width = 0, height = 0, inputAlt;

    if (!(img.getAttribute('id'))) {
        img.setAttribute('id', 'image_' + Math.random());
    }

	fmSelectWidth = document.createElement('select');
	for (idx in imageWidthOpt) {
        if (imageWidthOpt.hasOwnProperty(idx)) {
		    fmSelectWidth.options[fmSelectWidth.options.length] = new Option(imageWidthOpt[idx].desc, idx);
        }
	}

	fmSelectWidth.onchange = function() {
		if (self.editImages[img.src] && self.editImages[img.src].width !== null) {
			width = self.editImages[img.src].width;
			if (self.editImages[img.src] && self.editImages[img.src].height !== null) {
				height = self.editImages[img.src].height;
			}
			else {
				height = img.height;
			}
		}
		else if (img.width) {
			width = img.width;
		}
		else {
			return;
		}

		switch (this.value) {
		case 'default' :
			width = width + 'px';
			height = (height || img.height) + 'px';
			break;
		case 'fitpage' :
			width = '100%';
			height = 'auto';
			break;
		}

		if (width) { img.style.width = width; }
		if (height) { img.style.height = height; }
    };

	div = document.createElement('div');
    div.style.textAlign = 'left';
	ico = new Image();
	ico.src = this.config.iconPath + 'image_resize.png';
	ico.className = 'cheditor-ico';
	div.appendChild(ico);
	div.appendChild(fmSelectWidth);

    var getCssFloat = function() {
        if (GB.browser.msie) { return img.style.styleFloat; }
        return img.style.cssFloat;
    };
    var setCssFloat = function(css) {
        if (GB.browser.msie) { img.style.styleFloat = css; }
        else { img.style.cssFloat = css; }
    };

    var applyWrapText = function() {
        if (this.checked) {
            document.getElementById('idWrapText-' + (this.name === 'left' ? 'right' : 'left')).checked = false;
            setCssFloat(this.name);
            if (this.name === 'left') {
                img.style.marginRight = '1em';
                img.style.marginLeft = '';
            }
            else {
                img.style.marginLeft = '1em';
                img.style.marginRight = '';
            }
        }
        else {
            setCssFloat('');
            img.style.margin = '';
        }
    };

    wrapTextChecked = getCssFloat();

	for (idx in imageAlignOpt) {
        if (imageAlignOpt.hasOwnProperty(idx)) {
            wrapTextCheckBox = document.createElement('input');
            wrapTextCheckBox.setAttribute('type', 'checkbox');
            wrapTextCheckBox.setAttribute('name', idx);
            wrapTextCheckBox.setAttribute('id', 'idWrapText-'+idx);
            wrapTextCheckBox.setAttribute('value', '1');
            wrapTextCheckBox.className = 'wrap-checked';
            wrapTextCheckBox.onclick = applyWrapText;
            wrapTextSpan = document.createElement('span');
            wrapTextIcon = new Image();
            wrapTextSpan.className = 'wrap-text-desc';

            if (idx === 'left') { wrapTextSpan.style.marginLeft = '20px'; }
            if (wrapTextChecked === idx) {
                wrapTextCheckBox.checked = "checked";
            }

            wrapTextSpan.appendChild(wrapTextCheckBox);
            wrapTextIcon.className = 'cheditor-ico';
            wrapTextIcon.src = this.config.iconPath + 'image_align_' + idx + '_wt.png';
            wrapTextSpan.appendChild(wrapTextIcon);

            div.appendChild(wrapTextSpan);
        }
	}

	if (self.undefined(self.editImages[img.src])) {
		self.editImages[img.src] = { width: img.width, height: img.height };
	}

	div.appendChild(document.createTextNode('\u00a0 대체 텍스트(alt):'));

	inputAlt = document.createElement('input');
	inputAlt.setAttribute('type', 'text');
	inputAlt.setAttribute('name', 'inputAlt');
	inputAlt.setAttribute('value', '');
	inputAlt.className = 'user-input-alt';
    inputAlt.onblur = function() {
        img.setAttribute('alt', self.trimSpace(this.value));
    };
	div.appendChild(inputAlt);

	if (img.getAttribute('alt')) {
		inputAlt.value = img.getAttribute('alt');
    }

	self.cheditor.editBlock.innerHTML = '';
	self.cheditor.editBlock.appendChild(div);
},

modifyCell : function (ctd) {
    var self = this;
    var ctb = ctd;
    var ctr = ctb;
    var tm = [], i, jr, j, jh, jv, rowIndex = 0, realIndex = 0, newc, newr, nc, tempr, rows;

    while (ctb !== null && ctb.tagName.toLowerCase() !== "table") { ctb = ctb.parentNode; }
    while (ctr !== null && ctr.tagName.toLowerCase() !== "tr") { ctr = ctr.parentNode; }

    var getCellMatrix = function () {
        rows = (ctb.rows && ctb.rows.length > 0) ? ctb.rows : ctb.getElementsByTagName("TR");
        for (i=0; i < rows.length; i++) {
            tm[i] = [];
        }
        for (i=0; i < rows.length; i++) {
            jr = 0;
            for (j=0; j < rows[i].cells.length; j++) {
                while (!(self.undefined(tm[i][jr]))) {
                    jr++;
                }
                for (jh=jr; jh < jr + (rows[i].cells[j].colSpan || 1); jh++) {
                    for (jv=i; jv < i + (rows[i].cells[j].rowSpan || 1); jv++) {
                        tm[jv][jh] = (jv === i) ? rows[i].cells[j].cellIndex : -1;
                    }
                }
            }
        }
        return tm;
    };

    var insertColumn = function() {
        tm = getCellMatrix();
        rows = (ctb.rows && ctb.rows.length > 0) ? ctb.rows : ctb.getElementsByTagName("TR");


        if (ctr.rowIndex >= 0) {
            rowIndex = ctr.rowIndex;
        }
        else {
            for(i=0; i < rows.length; i++) {
                if (rows[i] === ctr) {
                    rowIndex = i;
                    break;
                }
            }
        }

        for (j=0; j < tm[rowIndex].length; j++) {
            if (tm[rowIndex][j] === ctd.cellIndex) {
                realIndex = j;
                break;
            }
        }

        for (i=0; i < rows.length; i++) {
            if (tm[i][realIndex] !== -1) {
                if (rows[i].cells[tm[i][realIndex]].colSpan > 1) {
                    rows[i].cells[tm[i][realIndex]].colSpan++;
                }
                else {
                    newc = rows[i].insertCell(tm[i][realIndex]+1);
                    nc = rows[i].cells[tm[i][realIndex]].cloneNode(false);
                    nc.innerHTML = '&nbsp;';
                    rows[i].replaceChild(nc, newc);
                }
            }
        }
    };

    var insertRow = function(idx) {
        newr = ctb.insertRow(ctr.rowIndex + 1);
        for (i=0; i < ctr.cells.length; i++) {
            if (ctr.cells[i].rowSpan > 1) {
                ctr.cells[i].rowSpan++;
            }
            else {
                newc = ctr.cells[i].cloneNode(false);
                newc.innerHTML = '&nbsp;';
                newr.appendChild(newc);
            }
        }

        for (i=0; i < ctr.rowIndex; i++) {
            if (ctb.rows && ctb.rows.length > 0) {
                tempr = ctb.rows[i];
            }
            else {
                tempr = ctb.getElementsByTagName("tr")[i];
            }
            for (j=0; j < tempr.cells.length; j++) {
                if (tempr.cells[j].rowSpan > (ctr.rowIndex - i)) {
                    tempr.cells[j].rowSpan++;
                }
            }
        }
    };

    var deleteColumn = function () {
        tm = getCellMatrix(ctb);
        rows = (ctb.rows && ctb.rows.length>0) ? ctb.rows : ctb.getElementsByTagName("TR");
        rowIndex = 0; realIndex = 0;

        if (ctr.rowIndex >= 0) {
            rowIndex = ctr.rowIndex;
        }
        else {
            for(i=0; i < rows.length; i++) {
                if (rows[i] === ctr) {
                    rowIndex = i;
                    break;
                }
            }
        }

        if (tm[0].length <= 1) {
            ctb.parentNode.removeChild(ctb);
        }
        else {
            for (j=0; j < tm[rowIndex].length; j++) {
                if (tm[rowIndex][j] === ctd.cellIndex) {
                    realIndex = j;
                    break;
                }
            }

            for (i=0; i < rows.length; i++) {
                if (tm[i][realIndex] !== -1) {
                    if (rows[i].cells[tm[i][realIndex]].colSpan > 1) {
                        rows[i].cells[tm[i][realIndex]].colSpan--;
                    }
                    else {
                        rows[i].deleteCell(tm[i][realIndex]);
                    }
                }
            }
        }
    };

    var deleteRow = function () {
        tm = getCellMatrix(ctb);
        rows = (ctb.rows && ctb.rows.length>0) ? ctb.rows : ctb.getElementsByTagName("TR");
        rowIndex = 0;

        if (ctr.rowIndex >= 0) {
            rowIndex = ctr.rowIndex;
        }
        else {
            for(i=0; i < rows.length; i++) {
                if (rows[i] === ctr) {
                    rowIndex = i;
                    break;
                }
            }
        }

        if (rows.length <= 1) {
            ctb.parentNode.removeChild(ctb);
        }
        else {
            for (i=0; i < rowIndex; i++) {
                tempr = rows[i];
                for (j=0; j < tempr.cells.length; j++) {
                    if (tempr.cells[j].rowSpan > (rowIndex - i)) {
                        tempr.cells[j].rowSpan--;
                    }
                }
            }

            var curCI = -1, prevCI, ni, nrCI, cs, nj;
            for (i=0; i < tm[rowIndex].length; i++) {
                prevCI = curCI;
                curCI = tm[rowIndex][i];

                if (curCI !== -1 && curCI !== prevCI && ctr.cells[curCI].rowSpan>1 && (rowIndex+1) < rows.length) {
                    ni = i;
                    nrCI = tm[rowIndex+1][ni];
                    while (nrCI === -1) {
                        ni++;
                        nrCI = (ni < rows[rowIndex+1].cells.length) ? tm[rowIndex+1][ni] : rows[rowIndex+1].cells.length;
                    }

                    newc = rows[rowIndex+1].insertCell(nrCI);
                    rows[rowIndex].cells[curCI].rowSpan--;
                    nc = rows[rowIndex].cells[curCI].cloneNode(false);
                    rows[rowIndex+1].replaceChild(nc, newc);

                    cs = (ctr.cells[curCI].colSpan>1) ? ctr.cells[curCI].colSpan : 1;
                    nj = 0;

                    for (j=i; j < (i+cs); j++) {
                        tm[rowIndex+1][j] = nrCI;
                        nj = j;
                    }
                    for (j=nj; j < tm[rowIndex+1].length; j++) {
                        if (tm[rowIndex+1][j] !== -1) {
                            tm[rowIndex+1][j]++;
                        }
                    }
                }
            }

            if (ctb.rows && ctb.rows.length > 0) {
                ctb.deleteRow(rowIndex);
            }
            else {
                ctb.removeChild(rows[rowIndex]);
            }
        }
    };

    var mergeCellRight = function () {
        tm = getCellMatrix(ctb);
        rows = (ctb.rows && ctb.rows.length>0) ? ctb.rows : ctb.getElementsByTagName("TR");
        rowIndex = 0; realIndex = 0;

        if (ctr.rowIndex >= 0) {
            rowIndex = ctr.rowIndex;
        }
        else {
            for(i=0; i < rows.length; i++) {
                if (rows[i] === ctr) {
                    rowIndex = i;
                    break;
                }
            }
        }

        for (j=0; j < tm[rowIndex].length; j++) {
            if (tm[rowIndex][j] === ctd.cellIndex) {
                realIndex = j;
                break;
            }
        }

        if (ctd.cellIndex + 1 < ctr.cells.length) {
            var ccrs = ctd.rowSpan || 1;
            var cccs = ctd.colSpan || 1;
            var ncrs = ctr.cells[ctd.cellIndex+1].rowSpan || 1;
            var nccs = ctr.cells[ctd.cellIndex+1].colSpan || 1;
            j = realIndex;

            while (tm[rowIndex][j] === ctd.cellIndex) {
                j++;
            }

            if (tm[rowIndex][j] === ctd.cellIndex + 1) {
                if (ccrs === ncrs) {
                    if (rows.length > 1) { ctd.colSpan = cccs + nccs; }
                    var html = self.trimSpace(ctr.cells[ctd.cellIndex + 1].innerHTML);
                    html = html.replace(/^&nbsp;/, '');
                    ctd.innerHTML += html;
                    ctr.deleteCell(ctd.cellIndex + 1);
                }
            }
        }
    };

    var mergeCellDown = function () {
        tm = getCellMatrix(ctb);
        rows = (ctb.rows && ctb.rows.length > 0) ? ctb.rows : ctb.getElementsByTagName("TR");
        rowIndex = 0;
        var crealIndex = 0;

        if (ctr.rowIndex >=0 ) {
            rowIndex = ctr.rowIndex;
        }
        else {
            for(i=0; i < rows.length; i++) {
                if (rows[i] === ctr) {
                    rowIndex = i;
                    break;
                }
            }
        }

        for (i=0; i < tm[rowIndex].length; i++) {
            if (tm[rowIndex][i] === ctd.cellIndex) {
                crealIndex = i;
                break;
            }
        }

        var ccrs = ctd.rowSpan || 1;
        var cccs = ctd.colSpan || 1;

        if (rowIndex + ccrs < rows.length) {
            var ncellIndex = tm[rowIndex + ccrs][crealIndex];
            if (ncellIndex !== -1 &&
                (crealIndex === 0 || (crealIndex > 0 &&
                (tm[rowIndex + ccrs][crealIndex-1] !== tm[rowIndex + ccrs][crealIndex]))))
            {

                var ncrs = rows[rowIndex + ccrs].cells[ncellIndex].rowSpan || 1;
                var nccs = rows[rowIndex + ccrs].cells[ncellIndex].colSpan || 1;

                if (cccs === nccs) {
                    var html = self.trimSpace(rows[rowIndex + ccrs].cells[ncellIndex].innerHTML);
                    html = html.replace(/^&nbsp;/, '');
                    ctd.innerHTML += html;
                    rows[rowIndex + ccrs].deleteCell(ncellIndex);
                    ctd.rowSpan = ccrs + ncrs;
                }
            }
        }
    };

    var splitCellVertical = function () {
        var ri;
        tm = getCellMatrix();
        rowIndex = 0; realIndex = 0;

        rows = (ctb.rows && ctb.rows.length > 0) ? ctb.rows : ctb.getElementsByTagName("TR");

        if (ctr.rowIndex >= 0) {
            rowIndex = ctr.rowIndex;
        }
        else {
            for(ri = 0; ri < rows.length; ri++) {
                if (rows[ri] === ctr) {
                    rowIndex = ri;
                    break;
                }
            }
        }

        for (j=0; j < tm[rowIndex].length; j++) {
            if (tm[rowIndex][j] === ctd.cellIndex) {
                realIndex = j;
                break;
            }
        }

        if (ctd.colSpan > 1) {
            newc = rows[rowIndex].insertCell(ctd.cellIndex + 1);
            ctd.colSpan--;
            nc = ctd.cloneNode(false);
            nc.innerHTML = '&nbsp;';
            rows[rowIndex].replaceChild(nc, newc);
            ctd.colSpan = 1;
            ctd.removeAttribute('colSpan');
        }
        else {
            newc = rows[rowIndex].insertCell(ctd.cellIndex + 1);
            nc = ctd.cloneNode(false);
            nc.innerHTML = '&nbsp;';
            rows[rowIndex].replaceChild(nc, newc);
            var cs;
            for (i=0; i < tm.length; i++) {
                if (i !== rowIndex && tm[i][realIndex] !== -1) {
                    cs = (rows[i].cells[tm[i][realIndex]].colSpan > 1) ? rows[i].cells[tm[i][realIndex]].colSpan : 1;
                    rows[i].cells[tm[i][realIndex]].colSpan = cs + 1;
                }
            }
        }
    };

    var splitCellHorizontal = function () {
        tm = getCellMatrix();
        rowIndex = 0; realIndex = 0;
        rows = (ctb.rows && ctb.rows.length > 0) ? ctb.rows : ctb.getElementsByTagName("TR");

        if (ctr.rowIndex >= 0) {
            rowIndex = ctr.rowIndex;
        }
        else {
            for(i=0; i < rows.length; i++) {
                if (rows[i] === ctr) {
                    rowIndex = i;
                    break;
                }
            }
        }

        for (j=0; j < tm[rowIndex].length; j++) {
            if (tm[rowIndex][j] === ctd.cellIndex) {
                realIndex = j;
                break;
            }
        }

        if (ctd.rowSpan > 1) {
            i = realIndex;
            var ni;

            while (tm[rowIndex + 1][i] === -1) {
                i++;
            }

            ni = (i === tm[rowIndex + 1].length) ? rows[rowIndex + 1].cells.length : tm[rowIndex + 1][i];

            newc = rows[rowIndex + 1].insertCell(ni);
            ctd.rowSpan--;

            nc = ctd.cloneNode(false);
            nc.innerHTML = '&nbsp;';
            rows[rowIndex + 1].replaceChild(nc, newc);
            ctd.rowSpan = 1;
        }
        else {
            if (ctb.rows && ctb.rows.length > 0) {
                ctb.insertRow(rowIndex+1);
            }
            else {
                if (rowIndex<(rows.length - 1)) {
                    ctb.insertBefore(document.createElement("TR"), rows[rowIndex + 1]);
                }
                else {
                    ctb.appendChild(document.createElement("TR"));
                }
            }

            var rs;
            for (i=0; i < ctr.cells.length; i++) {
                if (i !== ctd.cellIndex) {
                    rs = ctr.cells[i].rowSpan > 1 ? ctr.cells[i].rowSpan : 1;
                    ctr.cells[i].rowSpan = rs + 1;
                }
            }

            for (i=0; i < rowIndex; i++) {
                tempr = rows[i];
                for (j=0; j < tempr.cells.length; j++) {
                    if (tempr.cells[j].rowSpan > (rowIndex - i)) {
                        tempr.cells[j].rowSpan++;
                    }
                }
            }

            newc = rows[rowIndex+1].insertCell(0);
            nc = ctd.cloneNode(false);
            nc.innerHTML = '&nbsp;';
            rows[rowIndex+1].replaceChild(nc, newc);
        }
    };

    var tblReflash = function() { self.editAreaFocus(); self.doEditorEvent(); };
    var funcs = {
            'add_cols_after' : { 'icon' : 'table_insert_column.png', 'title' : '열 삽입',
                'func' : function() { insertColumn(ctd.cellIndex); tblReflash(); }},
            'add_rows_after': { 'icon' : 'table_insert_row.png', 'title' : '행 삽입',
                'func' : function() { insertRow(ctr.rowIndex); tblReflash(); }},
            'remove_cols': { 'icon' : 'table_delete_column.png', 'title' : '열 삭제',
                'func' : function() { deleteColumn(ctd.cellIndex); tblReflash(); }},
            'remove_rows': { 'icon' : 'table_delete_row.png', 'title' : '행 삭제',
                'func' : function() { deleteRow(); tblReflash(); }},
            'sp1' : { 'icon' : 'dot.gif' },
            'merge_cell_right': { 'icon' : 'table_join_row.png', 'title' : '오른쪽 셀과 병합',
                'func' : function() { mergeCellRight(); tblReflash(); }},
            'merge_cell_down': { 'icon' : 'table_join_column.png', 'title' : '아래 셀과 병합',
                'func' : function() { mergeCellDown(); tblReflash(); }},
            'split_cell_v': { 'icon' : 'table_split_row.png', 'title' : '셀 열로 나누기',
                'func' : function() { splitCellVertical(); tblReflash(); }},
            'split_cell_h': { 'icon' : 'table_split_column.png', 'title' : '셀 행으로 나누기',
                'func' : function() { splitCellHorizontal(); tblReflash(); }}
    };

    self.cheditor.editBlock.innerHTML = '';
    var div = document.createElement('div'), span, icon;
    div.style.padding = '6px';

    for (i in funcs) {
        span = document.createElement('span');
        icon = document.createElement('img');
        icon.src = self.config.iconPath + funcs[i].icon;
        if (i === 'sp1' || i === 'sp2') {
            icon.className = 'edit-table-ico';
        }
        else {
            icon.setAttribute('title', funcs[i].title);
            icon.className = 'edit-table-ico';
            icon.setAttribute('alt', '');
            icon.onclick = funcs[i].func;
        }
        div.appendChild(span.appendChild(icon));
    }

    /*var deleteTable = function() {
        ctb.parentNode.removeChild(ctb);
        self.doEditorEvent();
    };*/

    var deleteSubmit = new Image();
    deleteSubmit.src = this.config.iconPath + 'delete_table.png';
    deleteSubmit.style.marginLeft = "22px";
    deleteSubmit.className = 'edit-table-ico';
    deleteSubmit.setAttribute('title', '테이블 삭제');
    deleteSubmit.onclick = function() {
        ctb.parentNode.removeChild(ctb);
        self.doEditorEvent();
    };

    div.appendChild(deleteSubmit);

    var attrFuncs = {
        'setWidth' : {
            'txt': '가로폭',
            'id' : 'fm_cell_width',
            'marginRight' : '10px',
            'value' : ctd.getAttribute('width')
        },
        'setHeight' : {
            'txt': '세로폭',
            'id' : 'fm_cell_height',
            'marginRight' : '10px',
            'value' : ctd.getAttribute('height')
        },
        'setBgcolor' : {
            'txt': '배경색',
            'id' : 'fm_cell_bgcolor',
            'marginRight' : '2px',
            'value' : ctd.getAttribute('bgcolor')
        }
    };

    var spliter = document.createElement('div'), txt, input;
    spliter.style.padding = '10px 0px 0px 0px';
    spliter.style.marginTop = '5px';
    spliter.style.borderTop = '1px solid #ccc';
    spliter.style.textAlign = 'center';

    for (i in attrFuncs) {
        txt = document.createTextNode(attrFuncs[i].txt + ' ');
        spliter.appendChild(txt);
        input = document.createElement('input');
        input.style.marginRight = attrFuncs[i].marginRight;
        input.setAttribute('type', 'text');
        input.setAttribute('name', i);
        input.setAttribute('id', attrFuncs[i].id);
        input.setAttribute('size', 7);
        input.setAttribute('value', attrFuncs[i].value || '');
        spliter.appendChild(input);
    }

    var colorPicker = new Image();
    colorPicker.src = this.config.iconPath + 'button/color_picker.gif';
    colorPicker.className = 'color-picker';
    colorPicker.onclick = function() {
        GB.popupWindow.ColorPicker.argv = { func :
            function(color) {
                ctd.setAttribute('bgColor', color);
                document.getElementById('fm_cell_bgcolor').value = color;
            },
            selectedCell : ctd
        };
        self.windowOpen('ColorPicker');
    };
    spliter.appendChild(colorPicker);

    var editSubmit = new Image();
    editSubmit.src = this.config.iconPath + 'button/edit_cell.gif';
    editSubmit.className = 'input-submit';
    editSubmit.style.verticalAlign = 'top';
    editSubmit.onclick = function() {
        ctd.setAttribute('width', self.$('fm_cell_width').value);
        ctd.setAttribute('height', self.$('fm_cell_height').value);
        ctd.setAttribute('bgcolor', self.$('fm_cell_bgcolor').value);
    };

    spliter.appendChild(editSubmit);
    div.appendChild(spliter);
    self.cheditor.editBlock.appendChild(div);
},

doEditorEvent : function () {
	var self = this;
	var modifyBlock = self.cheditor.editBlock;
	var oEditor = self.editArea;
	var cmd = null, el, pNode, ancestors = [];
	var rng = self.getRange();
	var nodeType = self.getSelectionType(rng);

	if (!self.W3CRange) {
		switch (nodeType) {
		case GB.selection.none :
		case GB.selection.text :
			pNode = rng.parentElement();
			break;
		case GB.selection.element :
			pNode = rng.item(0);
			break;
		default :
			pNode = oEditor.document.body;
		}
	}
	else {
		try {
			pNode = rng.commonAncestorContainer;
			if (!rng.collapsed &&
				rng.startContainer === rng.endContainer &&
				rng.startOffset - rng.endOffset < 2 &&
				rng.startContainer.hasChildNodes())
			{
				pNode = rng.startContainer.childNodes[rng.startOffset];
			}

			while (pNode.nodeType === GB.node.text) {
				pNode = pNode.parentNode;
			}
		} catch (e) { pNode= null; }
	}

	while (pNode && (pNode.nodeType === GB.node.element) && (pNode.tagName.toLowerCase() !== 'body')) {
		ancestors.push(pNode);
		if (pNode.tagName.toLowerCase() === 'img') {
			cmd = 'img';
			break;
		}
        if (pNode.tagName.toLowerCase() === 'td' || pNode.tagName.toLowerCase() === 'th') {
			cmd = 'cell';
			break;
		}
		pNode = pNode.parentNode;
	}

	if (!cmd) {
		modifyBlock.style.display = "none";
		modifyBlock.innerHTML = '';
	}
	else {
        if (cmd === "cell") {
            modifyBlock.style.display = "block";
            self.modifyCell(pNode);
        }
	}

	if (self.config.showTagPath) {
        var statusBar = self.cheditor.tagPath;
		statusBar.innerHTML = '';
		statusBar.appendChild(document.createTextNode('<html> <body> '));
        el = ancestors.pop();
        var alink, span, tag;

        var alinkClick = function () {
            self.$('removeSelected').style.display = 'inline'; self.tagSelector(this.el);
        };

        while (el) {
            statusBar.appendChild(document.createTextNode('<'));
            tag = el.tagName.toLowerCase();

            if (nodeType === GB.selection.text) {
                alink = document.createElement("a");
                alink.el = el;
                alink.href = "javascript:void%200";
                alink.className = 'cheditor-tag-path-elem';
                alink.title = el.style.cssText;
                alink.onclick  = alinkClick;
                alink.appendChild(document.createTextNode(tag));
                statusBar.appendChild(alink);
            }
            else {
                statusBar.appendChild(self.doc.createTextNode(tag));
            }

            statusBar.appendChild(document.createTextNode('> '));
            el = ancestors.pop();
        }

		var remove = document.createElement("a");
		remove.href = "javascript:void%200";
		remove.id = "removeSelected";
		remove.style.display = 'none';
		remove.className = 'cheditor-tag-path-elem';
		remove.style.color = '#cc3300';
		remove.appendChild(document.createTextNode('remove'));
		remove.onclick = function () {
            oEditor.document.execCommand("RemoveFormat", false, null);
			remove.style.display = 'none';
			oEditor.focus();
			self.doEditorEvent();
        };

		span = document.createElement('span');
		span.style.marginTop = '2px';
		span.appendChild(remove);
		self.cheditor.tagPath.appendChild(span);
	}

	var interval = 30;
	if (GB.browser.msie && rng.text !== '' && nodeType !== GB.selection.element) {
		interval = 10;
	}
	self.tempTimer = setTimeout(function() {
		self.toolbarUpdate();
		self.tempTimer = null;
	}, interval);
},

tagSelector : function (node) {
	this.editAreaFocus();
	var rng;

	if (GB.browser.msie) {
		rng = this.doc.body.createTextRange();
		if (rng) {
			rng.moveToElementText(node);
			rng.select();
		}
	}
	else {
		var sel = this.editArea.getSelection();
		if (this.undefined(sel)) {
			return;
        }
		try {
			rng = sel.getRangeAt(0);
		} catch(e) { return; }

		rng.selectNodeContents(node);
		sel.removeAllRanges();
		sel.addRange(rng);
	}
},

getBrowser : function () { return GB.browser; },
$ : function (id) { return this.doc.getElementById(id); }
};

var DragWindow = {
    obj : null,
    init : function (o, oRoot, minX, maxX, minY, maxY) {
        o.style.curser = 'default';
        o.onmousedown = DragWindow.start;
        o.onmouseover = function () { this.style.cursor = 'move'; };
        o.hmode = true ;
        o.vmode = true ;
        o.root = (oRoot && oRoot !== null) ? oRoot : o;
        o.transId = oRoot.id + '_Trans';

        if (o.hmode  && isNaN(parseInt(o.root.style.left,10))) {o.root.style.left = "0px";}
        if (o.vmode  && isNaN(parseInt(o.root.style.top,10))) {o.root.style.top = "0px";}
        if (!o.hmode && isNaN(parseInt(o.root.style.right,10))) {o.root.style.right = "0px";}
        if (!o.vmode && isNaN(parseInt(o.root.style.bottom,10))) {o.root.style.bottom = "0px";}

        o.minX = minX !== undefined ? minX : null;
        o.minY = minY !== undefined ? minY : null;
        o.maxX = maxX !== undefined ? maxX : null;
        o.maxY = maxY !== undefined ? maxY : null;

        o.root.onDragStart  = new Function();
        o.root.onDragEnd    = new Function();
        o.root.onDrag       = new Function();
    },

    start : function (e) {
        var o = DragWindow.obj = this;
        e = DragWindow.fixE(e);
        var y = parseInt(o.vmode ? o.root.style.top  : o.root.style.bottom, 10);
        var x = parseInt(o.hmode ? o.root.style.left : o.root.style.right, 10);
        o.root.onDragStart(x, y);

        o.lastMouseX = e.clientX;
        o.lastMouseY = e.clientY;

        document.onmousemove = DragWindow.drag;
        document.onmouseup   = DragWindow.end;

        if (o.root.lastChild.id === o.transId) { return false; }

        var dragTransBg = document.createElement('div');
        dragTransBg.className = 'cheditor-dragWindowTransparent';

        if (GB.browser.msie && GB.browser.version < 10) { dragTransBg.style.filter = 'alpha(opacity=0)'; }
        else { dragTransBg.style.opacity = 0; }
        dragTransBg.id = o.transId;
        dragTransBg.style.width = o.root.lastChild.firstChild.style.width;
        dragTransBg.style.height = o.root.lastChild.firstChild.style.height;
        o.root.appendChild(dragTransBg);

        return false;
    },

    drag : function (e) {
        e = DragWindow.fixE(e);
        var o = DragWindow.obj;
        var ey = e.clientY;
        var ex = e.clientX;
        var y = parseInt(o.vmode ? o.root.style.top : o.root.style.bottom,10);
        var x = parseInt(o.hmode ? o.root.style.left : o.root.style.right,10);
        var nx, ny;

        nx = x + ((ex - o.lastMouseX) * (o.hmode ? 1 : -1));
        ny = y + ((ey - o.lastMouseY) * (o.vmode ? 1 : -1));

        DragWindow.obj.root.style.left = nx + "px";
        DragWindow.obj.root.style.top = ny + "px";
        DragWindow.obj.lastMouseX  = ex;
        DragWindow.obj.lastMouseY  = ey;
        DragWindow.obj.root.onDrag(nx, ny);

        return false;
    },

    end : function () {
        document.onmousemove = null;
        document.onmouseup   = null;
        DragWindow.obj.root.onDragEnd(parseInt(DragWindow.obj.root.style[DragWindow.obj.hmode ? "left" : "right"],10),
                parseInt(DragWindow.obj.root.style[DragWindow.obj.vmode ? "top" : "bottom"],10));

        if (DragWindow.obj.root.lastChild.id === DragWindow.obj.transId) {
            DragWindow.obj.root.removeChild(DragWindow.obj.root.lastChild);
        }
        DragWindow.obj = null;
    },

    fixE : function (e) {
        if (e === undefined) { e = window.event; }
        if (e.layerX === undefined) { e.layerX = e.offsetX; }
        if (e.layerY === undefined) { e.layerY = e.offsetY; }
        return e;
    }
};
GB.dragWindow = DragWindow;
// --------------------------------------------------------------------------
// W3C DOM Range
//

// --------------------------------------------------------------------------
// Table
//

// --------------------------------------------------------------------------
// prettify;
//
GB.prettify = new function() {
    var LANGUAGES = {};
    var selected_languages = {};
    var doc = null, paste = null;

    function escape(value) { return value.replace(/&/gm, '&amp;').replace(/</gm, '&lt;').replace(/>/gm, '&gt;'); }
    function blockText(block, ignoreNewLines) {
        var result = '', i, chunk;
        for (i = 0; i < block.childNodes.length; i++) {
            if (block.childNodes[i].nodeType === GB.node.text) {
                chunk = block.childNodes[i].nodeValue;
                if (ignoreNewLines) {
                    chunk = chunk.replace(/\n/g, '');
                }
                result += chunk;
            }
            else if (block.childNodes[i].nodeName === 'BR') {
                result += '\n';
            }
            else {
                result += blockText(block.childNodes[i]);
            }
        }

        result = result.replace(/\r/g, '\n');
        return result;
    }

    function blockLanguage(block) {
        var classes = block.className.split(/\s+/), i, class_;
        classes = classes.concat(block.parentNode.className.split(/\s+/));
        for (i = 0; i < classes.length; i++) {
            class_ = classes[i].replace(/^language-/, '');
            if (LANGUAGES[class_] || class_ === 'no-prettify') {
                return class_;
            }
        }
    }

    function nodeStream(node) {
        var result = [];
        (function _nodeStream(node, offset) {
            var i;
            for (i = 0; i < node.childNodes.length; i++) {
                if (node.childNodes[i].nodeType === GB.node.text) {
                    offset += node.childNodes[i].nodeValue.length;
                }
                else if (node.childNodes[i].nodeName === 'BR') {
                    offset += 1;
                }
                else {
                    result.push({
                        event: 'start',
                        offset: offset,
                        node: node.childNodes[i]
                    });
                    offset = _nodeStream(node.childNodes[i], offset);
                    result.push({
                    event: 'stop',
                    offset: offset,
                    node: node.childNodes[i]
                    });
                }
            }
            return offset;
        })(node, 0);
        return result;
    }

    function mergeStreams(stream1, stream2, value) {
        var processed = 0;
        var result = '';
        var nodeStack = [];
        var i, current, node;

        function selectStream() {
            if (stream1.length && stream2.length) {
                if (stream1[0].offset !== stream2[0].offset) {
                    return (stream1[0].offset < stream2[0].offset) ? stream1 : stream2;
                }
                return (stream1[0].event === 'start' && stream2[0].event === 'stop') ? stream2 : stream1;
            }
            return stream1.length ? stream1 : stream2;
        }

        function open(node) {
            result = '<' + node.nodeName.toLowerCase();
            var attribute;
            for (i = 0; i < node.attributes.length; i++) {
                attribute = node.attributes[i];
                result += ' ' + attribute.nodeName.toLowerCase();
                if (attribute.nodeValue !== undefined) {
                    result += '="' + escape(attribute.nodeValue) + '"';
                }
            }
            return result + '>';
        }

        function close(node) { return '</' + node.nodeName.toLowerCase() + '>'; }

        while (stream1.length || stream2.length) {
            current = selectStream().splice(0, 1)[0];
            result += escape(value.substr(processed, current.offset - processed));
            processed = current.offset;
            if ( current.event === 'start') {
                result += open(current.node);
                nodeStack.push(current.node);
            }
            else if (current.event === 'stop') {
                i = nodeStack.length;
                do {
                    i--;
                    node = nodeStack[i];
                    result += close(node);
                } while (node !== current.node);

                nodeStack.splice(i, 1);
                while (i < nodeStack.length) {
                    result += open(nodeStack[i]);
                    i++;
                }
            }
        }

        result += value.substr(processed);
        return result;
    }

    function langRe(language, value, global) {
        var mode =  'm' + (language.case_insensitive ? 'i' : '') + (global ? 'g' : '');
        return new RegExp(value, mode);
    }

    function highlight(language_name, value) {
        var language = LANGUAGES[language_name];
        var modes = [language.defaultMode];
        var relevance = 0;
        var keyword_count = 0;
        var result = '';

        function subMode(lexem, mode) {
            var i;
            for (i = 0; i < mode.sub_modes.length; i++) {
                if (mode.sub_modes[i].beginRe.test(lexem)) {
                    return mode.sub_modes[i];
                }
            }
            return null;
        }

        function endOfMode(mode_index, lexem) {
            if (modes[mode_index].end && modes[mode_index].endRe.test(lexem)) {
                return 1;
            }
            if (modes[mode_index].endsWithParent) {
                var level = endOfMode(mode_index - 1, lexem);
                return level ? level + 1 : 0;
            }
            return 0;
        }

        function isIllegal(lexem, mode) {
            return mode.illegalRe && mode.illegalRe.test(lexem);
        }

        function compileTerminators(mode, language) {
            var terminators = [], i;

            for (i = 0; i < mode.sub_modes.length; i++) {
                terminators.push(mode.sub_modes[i].begin);
            }

            var index = modes.length - 1;
            do {
                if (modes[index].end) {
                    terminators.push(modes[index].end);
                }
                index--;
            } while (modes[index + 1].endsWithParent);

            if (mode.illegal) {
                terminators.push(mode.illegal);
            }

            return langRe(language, '(' + terminators.join('|') + ')', true);
        }

        function eatModeChunk(value, index) {
            var mode = modes[modes.length - 1];
            if (!mode.terminators) {
                mode.terminators = compileTerminators(mode, language);
            }
            mode.terminators.lastIndex = index;
            var match = mode.terminators.exec(value);
            if (match) {
                return [value.substr(index, match.index - index), match[0], false];
            }
            return [value.substr(index), '', true];
        }

        function keywordMatch(mode, match) {
            var match_str = language.case_insensitive ? match[0].toLowerCase() : match[0];
            var className, val;
            for (className in mode.keywordGroups) {
                if (!mode.keywordGroups.hasOwnProperty(className)) {
                    continue;
                }
                val = mode.keywordGroups[className].hasOwnProperty(match_str);
                if (val) {
                    return [className, val];
                }
            }
            return false;
        }

        function processKeywords(buffer, mode) {
            if (!mode.keywords || !mode.lexems) {
                return escape(buffer);
            }
            if (!mode.lexemsRe) {
                var lexems_re = '(' + mode.lexems.join('|') + ')';
                mode.lexemsRe = langRe(language, lexems_re, true);
            }

            result = '';
            var last_index = 0, match, keyword_match;
            mode.lexemsRe.lastIndex = 0;
            match = mode.lexemsRe.exec(buffer);
            while (match) {
                result += escape(buffer.substr(last_index, match.index - last_index));
                keyword_match = keywordMatch(mode, match);
                if (keyword_match) {
                    keyword_count += keyword_match[1];
                    result += '<span class="'+ keyword_match[0] +'">' + escape(match[0]) + '</span>';
                }
                else {
                    result += escape(match[0]);
                }
                last_index = mode.lexemsRe.lastIndex;
                match = mode.lexemsRe.exec(buffer);
            }
            result += escape(buffer.substr(last_index, buffer.length - last_index));
            return result;
        }

        function processBuffer(buffer, mode) {
            if (mode.subLanguage && selected_languages[mode.subLanguage]) {
                result = highlight(mode.subLanguage, buffer);
                keyword_count += result.keyword_count;
                relevance += result.relevance;
                return result.value;
            }
            return processKeywords(buffer, mode);
        }

        function startNewMode(mode, lexem) {
            var markup = mode.noMarkup ? '' : '<span class="' + mode.displayClassName + '">';
            if (mode.returnBegin) {
                result += markup;
                mode.buffer = '';
            }
            else if (mode.excludeBegin) {
                result += escape(lexem) + markup;
                mode.buffer = '';
            }
            else {
                result += markup;
                mode.buffer = lexem;
            }
            modes[modes.length] = mode;
        }

        function processModeInfo(buffer, lexem, end) {
            var current_mode = modes[modes.length - 1];
            if (end) {
                result += processBuffer(current_mode.buffer + buffer, current_mode);
                return false;
            }

            var new_mode = subMode(lexem, current_mode);
            if (new_mode) {
                result += processBuffer(current_mode.buffer + buffer, current_mode);
                startNewMode(new_mode, lexem);
                relevance += new_mode.relevance;
                return new_mode.returnBegin;
            }

            var end_level = endOfMode(modes.length - 1, lexem);
            if (end_level) {
                var markup = current_mode.noMarkup?'':'</span>';
                if (current_mode.returnEnd) {
                    result += processBuffer(current_mode.buffer + buffer, current_mode) + markup;
                }
                else if (current_mode.excludeEnd) {
                    result += processBuffer(current_mode.buffer + buffer, current_mode) + markup + escape(lexem);
                }
                else {
                    result += processBuffer(current_mode.buffer + buffer + lexem, current_mode) + markup;
                }

                while (end_level > 1) {
                    markup = modes[modes.length - 2].noMarkup?'':'</span>';
                    result += markup;
                    end_level--;
                    modes.length--;
                }

                var last_ended_mode = modes[modes.length - 1], i;
                modes.length--;
                modes[modes.length - 1].buffer = '';
                if (last_ended_mode.starts) {
                    for (i = 0; i < language.modes.length; i++) {
                        if (language.modes[i].className === last_ended_mode.starts) {
                            startNewMode(language.modes[i], '');
                            break;
                        }
                    }
                }
                return current_mode.returnEnd;
            }
            if (isIllegal(lexem, current_mode)) {
                throw 'Illegal';
            }
        }

        try {
            var index = 0, mode_info, return_lexem;
            language.defaultMode.buffer = '';
            do {
                mode_info = eatModeChunk(value, index);
                return_lexem = processModeInfo(mode_info[0], mode_info[1], mode_info[2]);
                index += mode_info[0].length;
                if (!return_lexem) {
                    index += mode_info[1].length;
                }
            } while (!mode_info[2]);

            if(modes.length > 1) { throw 'Illegal'; }
            return {
                language: language_name,
                relevance: relevance,
                keyword_count: keyword_count,
                value: result };
        } catch (e) {
            if (e === 'Illegal') {
                return {
                    language: null,
                    relevance: 0,
                    keyword_count: 0,
                    value: escape(value) };
            }
            throw e;
        }
    }

    function compileModes() {
        var modes, lang, len;

        function compileMode(mode, language) {
            if (mode.compiled) { return; }
            if (mode.begin) { mode.beginRe = langRe(language, '^' + mode.begin); }
            if (mode.end) { mode.endRe = langRe(language, '^' + mode.end); }
            if (mode.illegal) { mode.illegalRe = langRe(language, '^(?:' + mode.illegal + ')'); }
            if (mode.relevance === undefined) { mode.relevance = 1; }
            if (!mode.displayClassName) { mode.displayClassName = mode.className; }
            if (!mode.className) { mode.noMarkup = true; }

            var key, i, j;
            for (key in mode.keywords) {
                if (!mode.keywords.hasOwnProperty(key)) {
                    continue;
                }
                if (mode.keywords[key] instanceof Object) {
                    mode.keywordGroups = mode.keywords;
                }
                else {
                    mode.keywordGroups = {'keyword': mode.keywords};
                }
            }

            mode.sub_modes = [];
            if (mode.contains) {
                for (i = 0; i < mode.contains.length; i++) {
                    if (mode.contains[i] instanceof Object) {
                        mode.sub_modes.push(mode.contains[i]);
                    }
                    else {
                        for (j = 0; j < language.modes.length; j++) {
                            if (language.modes[j].className === mode.contains[i]) {
                                mode.sub_modes.push(language.modes[j]);
                            }
                        }
                    }
                }
            }
            mode.compiled = true;
            for (i = 0; i < mode.sub_modes.length; i++) {
                compileMode(mode.sub_modes[i], language);
            }
        }

        for (lang in LANGUAGES) {
            if (!LANGUAGES.hasOwnProperty(lang)) {
                continue;
            }
            modes = [LANGUAGES[lang].defaultMode].concat(LANGUAGES[lang].modes);
            for (len = 0; len < modes.length; len++) {
                compileMode(modes[len], LANGUAGES[lang]);
            }
        }
    }

    function initialize() {
        if (initialize.called) { return; }
        initialize.called = true;
        compileModes();
    }

    function highlightBlock(block, tabReplace, useBR) {
        initialize();
        var text = blockText(block, false), result, className;

        var language = blockLanguage(block);

        if (language === 'no-prettify') { return; }
        if (language) {
            result = highlight(language, text);
        }
        else {
            result = {language: '', keyword_count: 0, relevance: 0, value: escape(text)};
            var second_best = result, key, current;
            for (key in selected_languages) {
                if (!selected_languages.hasOwnProperty(key)) {
                    continue;
                }
                current = highlight(key, text);
                if (current.keyword_count + current.relevance > second_best.keyword_count + second_best.relevance) {
                    second_best = current;
                }
                if (current.keyword_count + current.relevance > result.keyword_count + result.relevance) {
                    second_best = result;
                    result = current;
                }
            }
        }

        var class_name = block.className;
        if (!class_name.match(result.language)) {
            class_name = class_name ? (class_name + ' ' + result.language) : result.language;
        }
        var original = nodeStream(block);
        if (original.length) {
            var pre = doc.createElement('code');
            pre.innerHTML = result.value;
            result.value = mergeStreams(original, nodeStream(pre), text);
        }

        result.value = result.value.replace(/__CHEDITOR_TAB_SPACE__/g, '&nbsp;&nbsp;&nbsp;&nbsp;'); // IE < 9

        if (tabReplace) {
            result.value = result.value.replace(/^((<[^>]+>|\t)+)/gm, function(match, p1, offset, s) {
                return p1.replace(/\t/g, tabReplace); });
        }
        else {
            result.value = result.value.replace(/\t/g, "<span class='cheditor-prettify-space' style='white-space: pre-wrap'>\t</span>");
        }

        if (useBR) {
            result.value = result.value.replace(/\n/g, "<br>\n");
        }

        block.className = class_name;
        block.dataset = {};
        block.dataset.result = {
            language: result.language,
            kw: result.keyword_count,
            re: result.relevance
        };

        if (result) {
            className = block.className;
            if (!className.match(language)) {
                className += ' ' + language;
            }
            result.value = result.value.replace(/^(\s{2,})/gm, "<span class='cheditor-prettify-space' style='white-space: pre-wrap'>$1</span>");

            if (paste) {
                var wrapper = doc.getElementById("clipboardData");
                var code = doc.createElement("code");
                code.className = className;
                code.innerHTML = result.value;
                wrapper.parentNode.replaceChild(code, wrapper);
            }
            else {
                doc.body.innerHTML = '<code class="' + className + '">' + result.value + '</code>';
            }
        }
    }

    function initHighlighting(lang) {
        initialize();
        var i, text;
        for (i=0; i<lang.length; i++) {
            if (LANGUAGES[lang[i]]) {
                selected_languages[lang[i]] = lang[i];
            }
        }

        text = paste ? doc.getElementById("clipboardData") : doc.body;
        highlightBlock(text, '    ', true);
    }

    function initHighlightingOnLoad(lang, editor, clip) {
        doc = editor.doc;
        paste = clip;
        initHighlighting(lang);
    }

    this.LANGUAGES = LANGUAGES;
    this.initHighlightingOnLoad = initHighlightingOnLoad;
    this.IMMEDIATE_RE = '\\b|\\B';
    this.IDENT_RE = '[a-zA-Z][a-zA-Z0-9_]*';
    this.UNDERSCORE_IDENT_RE = '[a-zA-Z_][a-zA-Z0-9_]*';
    this.NUMBER_RE = '\\b\\d+(\\.\\d+)?';
    this.C_NUMBER_RE = '(\\b0[xX][a-fA-F0-9]+|(\\b\\d+(\\.\\d*)?|\\.\\d+)([eE][-+]?\\d+)?)';
    this.RE_STARTERS_RE = '!|!=|!==|%|%=|&|&&|&=|\\*|\\*=|\\+|\\+=|,|\\.|-|-=|/|/=|:|;|<|<<|<<=|<=|=|==|===|>|>=|>>|>>=|>>>|>>>=|\\?|\\[|\\{|\\(|\\^|\\^=|\\||\\|=|\\|\\||~';
    this.APOS_STRING_MODE = { className: 'string', begin: '\'', end: '\'', illegal: '\\n', contains: ['escape'], relevance: 0 };
    this.QUOTE_STRING_MODE = { className: 'string', begin: '"', end: '"', illegal: '\\n', contains: ['escape'], relevance: 0 };
    this.BACKSLASH_ESCAPE = { className: 'escape', begin: '\\\\.', end: this.IMMEDIATE_RE, noMarkup: true, relevance: 0 };
    this.C_LINE_COMMENT_MODE = { className: 'comment', begin: '//', end: '$', relevance: 0 };
    this.C_BLOCK_COMMENT_MODE = { className: 'comment', begin: '/\\*', end: '\\*/' };
    this.HASH_COMMENT_MODE = { className: 'comment', begin: '#', end: '$' };
    this.NUMBER_MODE = { className: 'number', begin: this.NUMBER_RE, end: this.IMMEDIATE_RE, relevance: 0 };
    this.C_NUMBER_MODE = { className: 'number', begin: this.C_NUMBER_RE, end: this.IMMEDIATE_RE, relevance: 0 };

    this.inherit = function(parent, obj) {
        var result = {}, key;
        for (key in parent) {
             if (parent.hasOwnProperty(key)) {
                result[key] = parent[key];
             }
        }
        if (obj) {
            for (key in obj) {
                if (obj.hasOwnProperty(key)) {
                    result[key] = obj[key];
                }
            }
        }
        return result;
    };

    var XML_IDENT_RE = '[A-Za-z0-9\\._:-]+';
    var PI = { className: 'pi', begin: '<\\?', end: '\\?>', relevance: 10 };
    var DOCTYPE = { className: 'doctype', begin: '<!DOCTYPE', end: '>', relevance: 10 };
    var COMMENT = { className: 'comment', begin: '<!--', end: '-->' };
    var TAG = { className: 'tag', begin: '</?', end: '/?>', contains: ['title', 'tag_internal'] };
    var TITLE = { className: 'title', begin: XML_IDENT_RE, end: this.IMMEDIATE_RE };
    var TAG_INTERNAL = { className: 'tag_internal', begin: this.IMMEDIATE_RE, endsWithParent: true, noMarkup: true, contains: ['attribute', 'value_container'], relevance: 0 };
    var ATTR = { className: 'attribute', begin: XML_IDENT_RE, end: this.IMMEDIATE_RE, relevance: 0 };
    var VALUE_CONTAINER_QUOT = { className: 'value_container', begin: '="', returnBegin: true, end: '"', noMarkup: true,
        contains: [{ className: 'value', begin: '"', endsWithParent: true}] };
    var VALUE_CONTAINER_APOS = {className: 'value_container', begin: '=\'', returnBegin: true, end: '\'', noMarkup: true,
        contains: [{className: 'value', begin: '\'', endsWithParent: true}] };

    this.LANGUAGES.xml = { defaultMode: { contains: ['pi', 'doctype', 'comment', 'cdata', 'tag'] },
        case_insensitive: true, modes: [ { className: 'cdata', begin: '<\\!\\[CDATA\\[', end: '\\]\\]>', relevance: 10 },
      PI, DOCTYPE, COMMENT, TAG, this.inherit(TITLE, {relevance: 1.75}), TAG_INTERNAL, ATTR, VALUE_CONTAINER_QUOT, VALUE_CONTAINER_APOS ] };

    var HTML_TAGS = { 'code': 1, 'kbd': 1, 'font': 1, 'noscript': 1, 'style': 1, 'img': 1, 'title': 1, 'menu': 1, 'tt': 1, 'tr': 1, 'param': 1, 'li': 1, 'tfoot': 1,
    'th': 1, 'input': 1, 'td': 1, 'dl': 1, 'blockquote': 1, 'fieldset': 1, 'big': 1, 'dd': 1, 'abbr': 1, 'optgroup': 1, 'dt': 1, 'button': 1,
    'isindex': 1, 'p': 1, 'small': 1, 'div': 1, 'dir': 1, 'em': 1, 'frame': 1, 'meta': 1, 'sub': 1, 'bdo': 1, 'label': 1, 'acronym': 1, 'sup': 1, 'body': 1,
    'basefont': 1, 'base': 1, 'br': 1, 'address': 1, 'strong': 1, 'legend': 1, 'ol': 1, 'script': 1, 'caption': 1, 's': 1, 'col': 1, 'h2': 1, 'h3': 1,
    'h1': 1, 'h6': 1, 'h4': 1, 'h5': 1, 'table': 1, 'select': 1, 'noframes': 1, 'span': 1, 'area': 1, 'dfn': 1, 'strike': 1, 'cite': 1, 'thead': 1,
    'head': 1, 'option': 1, 'form': 1, 'hr': 1, 'var': 1, 'link': 1, 'b': 1, 'colgroup': 1, 'ul': 1, 'applet': 1, 'del': 1, 'iframe': 1, 'pre': 1,
    'frameset': 1, 'ins': 1, 'tbody': 1, 'html': 1, 'samp': 1, 'map': 1, 'object': 1, 'a': 1, 'xmlns': 1, 'center': 1, 'textarea': 1, 'i': 1, 'q': 1,
    'u': 1, 'section': 1, 'nav': 1, 'article': 1, 'aside': 1, 'hgroup': 1, 'header': 1, 'footer': 1, 'figure': 1, 'figurecaption': 1, 'time': 1,
    'mark': 1, 'wbr': 1, 'embed': 1, 'video': 1, 'audio': 1, 'source': 1, 'canvas': 1, 'datalist': 1, 'keygen': 1, 'output': 1, 'progress': 1,
    'meter': 1, 'details': 1, 'summary': 1, 'command': 1 };

    this.LANGUAGES.html = { defaultMode: { contains: ['comment', 'pi', 'doctype', 'vbscript', 'tag'] },
        case_insensitive: true,
        modes: [
        { className: 'tag', begin: '<style', end: '>', lexems: [this.IDENT_RE],  keywords: {'style': 1}, contains: ['tag_internal'], starts: 'css' },
        { className: 'tag', begin: '<script', end: '>', lexems: [this.IDENT_RE],  keywords: {'script': 1}, contains: ['tag_internal'], starts: 'javascript' },
        { className: 'css', end: '</style>', returnEnd: true, subLanguage: 'css' },
        { className: 'javascript', end: '</script>', returnEnd: true, subLanguage: 'javascript' },
        { className: 'vbscript', begin: '<%', end: '%>', subLanguage: 'vbscript' },
        COMMENT, PI, DOCTYPE, this.inherit(TAG), this.inherit(TITLE, { lexems: [this.IDENT_RE], keywords: HTML_TAGS }),
        this.inherit(TAG_INTERNAL), ATTR, VALUE_CONTAINER_QUOT, VALUE_CONTAINER_APOS,
        { className: 'value_container', begin: '=', end: this.IMMEDIATE_RE,
            contains: [{className: 'unquoted_value', displayClassName: 'value', begin: '[^\\s/>]+', end: this.IMMEDIATE_RE }] }] };

    this.LANGUAGES.javascript = { defaultMode: { lexems: [this.UNDERSCORE_IDENT_RE], contains: ['string', 'comment', 'number', 'regexp_container', 'function'],
        keywords: { 'keyword': {'in': 1, 'if': 1, 'for': 1, 'while': 1, 'finally': 1, 'var': 1, 'new': 1, 'function': 1, 'do': 1, 'return': 1, 'void': 1,
                'else': 1, 'break': 1, 'catch': 1, 'instanceof': 1, 'with': 1, 'throw': 1, 'case': 1, 'default': 1, 'try': 1, 'this': 1, 'switch': 1,
                'continue': 1, 'typeof': 1, 'delete': 1}, 'literal': {'true': 1, 'false': 1, 'null': 1} }},
        modes: [ this.C_LINE_COMMENT_MODE, this.C_BLOCK_COMMENT_MODE, this.C_NUMBER_MODE, this.APOS_STRING_MODE, this.QUOTE_STRING_MODE, this.BACKSLASH_ESCAPE,
        { className: 'regexp_container', begin: '(' + this.RE_STARTERS_RE + '|case|return|throw)\\s*', end: this.IMMEDIATE_RE, noMarkup: true,
            lexems: [this.IDENT_RE], keywords: {'return': 1, 'throw': 1, 'case': 1},
            contains: [ 'comment', { className: 'regexp', begin: '/.*?[^\\\\/]/[gim]*', end: this.IMMEDIATE_RE } ], relevance: 0 },
        { className: 'function', begin: '\\bfunction\\b', end: '{', lexems: [this.UNDERSCORE_IDENT_RE], keywords: {'function': 1},
            contains: [{ className: 'title', begin: '[A-Za-z$_][0-9A-Za-z$_]*', end: this.IMMEDIATE_RE },
            { className: 'params', begin: '\\(', end: '\\)', contains: ['string', 'comment'] }] }]
    };

    this.LANGUAGES.css = { defaultMode: { contains: ['at_rule', 'id', 'class', 'attr_selector', 'pseudo', 'rules', 'comment'], keywords: this.HTML_TAGS,
    lexems: [this.IDENT_RE], illegal: '=' }, case_insensitive: true,
    modes: [
    { className: 'at_rule', begin: '@', end: '[{;]', excludeEnd: true, lexems: [this.IDENT_RE], keywords: {'import': 1, 'page': 1, 'media': 1, 'charset': 1, 'font-face': 1},
      contains: ['function', 'string', 'number', 'pseudo'] },
    { className: 'id', begin: '\\#[A-Za-z0-9_-]+', end: this.IMMEDIATE_RE },
    { className: 'class', begin: '\\.[A-Za-z0-9_-]+', end: this.IMMEDIATE_RE, relevance: 0 },
    { className: 'attr_selector', begin: '\\[', end: '\\]', illegal: '$' },
    { className: 'pseudo', begin: ':(:)?[a-zA-Z0-9\\_\\-\\+\\(\\)\\"\\\']+', end: this.IMMEDIATE_RE },
    { className: 'rules', begin: '{', end: '}',
        contains: [{className: 'rule', begin: '[A-Z\\_\\.\\-]+\\s*:', end: ';', endsWithParent: true, lexems: ['[A-Za-z-]+'],
        keywords: {'play-during': 1, 'counter-reset': 1, 'counter-increment': 1, 'min-height': 1, 'quotes': 1, 'border-top': 1, 'pitch': 1, 'font': 1,
              'pause': 1, 'list-style-image': 1, 'border-width': 1, 'cue': 1, 'outline-width': 1, 'border-left': 1, 'elevation': 1, 'richness': 1,
              'speech-rate': 1, 'border-bottom': 1, 'border-spacing': 1, 'background': 1, 'list-style-type': 1, 'text-align': 1, 'page-break-inside': 1,
              'orphans': 1, 'page-break-before': 1, 'text-transform': 1, 'line-height': 1, 'padding-left': 1, 'font-size': 1, 'right': 1, 'word-spacing': 1,
              'padding-top': 1, 'outline-style': 1, 'bottom': 1, 'content': 1, 'border-right-style': 1, 'padding-right': 1, 'border-left-style': 1,
              'voice-family': 1, 'background-color': 1, 'border-bottom-color': 1, 'outline-color': 1, 'unicode-bidi': 1, 'max-width': 1, 'font-family': 1,
              'caption-side': 1, 'border-right-width': 1, 'pause-before': 1, 'border-top-style': 1, 'color': 1, 'border-collapse': 1, 'border-bottom-width': 1,
              'float': 1, 'height': 1, 'max-height': 1, 'margin-right': 1, 'border-top-width': 1, 'speak': 1, 'speak-header': 1, 'top': 1, 'cue-before': 1,
              'min-width': 1, 'width': 1, 'font-variant': 1, 'border-top-color': 1, 'background-position': 1, 'empty-cells': 1, 'direction': 1, 'border-right': 1,
              'visibility': 1, 'padding': 1, 'border-style': 1, 'background-attachment': 1, 'overflow': 1, 'border-bottom-style': 1, 'cursor': 1, 'margin': 1,
              'display': 1, 'border-left-width': 1, 'letter-spacing': 1, 'vertical-align': 1, 'clip': 1, 'border-color': 1, 'list-style': 1, 'padding-bottom': 1,
              'pause-after': 1, 'speak-numeral': 1, 'margin-left': 1, 'widows': 1, 'border': 1, 'font-style': 1, 'border-left-color': 1, 'pitch-range': 1,
              'background-repeat': 1, 'table-layout': 1, 'margin-bottom': 1, 'speak-punctuation': 1, 'font-weight': 1, 'border-right-color': 1, 'page-break-after': 1,
              'position': 1, 'white-space': 1, 'text-indent': 1, 'background-image': 1, 'volume': 1, 'stress': 1, 'outline': 1, 'clear': 1, 'z-index': 1,
              'text-decoration': 1, 'margin-top': 1, 'azimuth': 1, 'cue-after': 1, 'left': 1, 'list-style-position': 1},
          contains: [{ className: 'value', begin: this.IMMEDIATE_RE, endsWithParent: true, excludeEnd: true, contains: ['function', 'number', 'hexcolor', 'string', 'important', 'comment'] }]
        }, 'comment'],
        illegal: '[^\\s]' },
    this.C_BLOCK_COMMENT_MODE, { className: 'number', begin: this.NUMBER_RE, end: this.IMMEDIATE_RE },
    { className: 'hexcolor', begin: '\\#[0-9A-F]+', end: this.IMMEDIATE_RE },
    { className: 'function', begin: this.IDENT_RE + '\\(', end: '\\)',
        contains: [
        { className: 'params', begin: this.IMMEDIATE_RE, endsWithParent: true, excludeEnd: true, contains: ['number', 'string'] }]},
        { className: 'important', begin: '!important', end: this.IMMEDIATE_RE }, this.APOS_STRING_MODE, this.QUOTE_STRING_MODE, this.BACKSLASH_ESCAPE ]};
}();

// --------------------------------------------------------------------------
// Color Picker
//
var colorDropper = { images : { pad : [ 181, 101 ], sld : [ 16, 101 ], cross : [ 15, 15 ], arrow : [ 7, 11 ] },
	fetchElement : function (mixed) { return typeof mixed === 'string' ? document.getElementById(mixed) : mixed; },

	addEvent : function(el, evnt, func) {
		if (el.addEventListener) {
			el.addEventListener(evnt, func, false);
		}
        else if (el.attachEvent) {
			el.attachEvent('on'+evnt, func);
		}
	},

	fireEvent : function (el, evnt) {
		if (!el) {
			return;
		}
        var ev;
		if (document.createEvent) {
			ev = document.createEvent('HTMLEvents');
			ev.initEvent(evnt, true, true);
			el.dispatchEvent(ev);
		}
        else if (document.createEventObject) {
			ev = document.createEventObject();
			el.fireEvent('on'+evnt, ev);
		}
        else if (el['on'+evnt]) {
			el['on'+evnt]();
		}
	},

	getElementPos : function (e) {
		var e1 = e, e2 = e, x = 0, y = 0;
		if (e1.offsetParent) {
			do {
				x += e1.offsetLeft;
				y += e1.offsetTop;
                e1 = e1.offsetParent;
			} while(e1);
		}

		while (e2 && e2.nodeName.toLowerCase() !== 'body') {
			x -= e2.scrollLeft;
			y -= e2.scrollTop;
            e2 = e2.parentNode;
		}
		return [x, y];
	},

	getElementSize : function (e) {
		return [e.offsetWidth, e.offsetHeight];
	},

	getRelMousePos : function (e) {
		var x = 0, y = 0;
		if (!e) { e = window.event; }
		if (typeof e.offsetX === 'number') {
			x = e.offsetX;
			y = e.offsetY;
		}
        else if (typeof e.layerX === 'number') {
			x = e.layerX;
			y = e.layerY;
		}
		return { x: x, y: y };
	},

	color : function (target, prop) {
		this.required = true;
		this.adjust = true;
		this.hash = true;
		this.caps = false;
		this.valueElement = target;
		this.styleElement = target;
		this.onImmediateChange = null;
		this.hsv = [0, 0, 1];
		this.rgb = [1, 1, 1];
		this.minH = 0;
		this.maxH = 6;
		this.minS = 0;
		this.maxS = 1;
		this.minV = 0;
		this.maxV = 1;

		this.pickerOnfocus = true;
		this.pickerMode = 'HSV';
		this.pickerFace = 3;
		this.pickerFaceColor = '#fff';
		this.pickerInset = 1;
		this.pickerInsetColor = '#999';
		this.pickerZIndex = 10003;

        var p;
        var self = this;

		var modeID = this.pickerMode.toLowerCase() === 'hvs' ? 1 : 0;
		var abortBlur = false;
		var valueElement = colorDropper.fetchElement(this.valueElement), styleElement = colorDropper.fetchElement(this.styleElement);
		var holdPad = false, holdSld = false, touchOffset = {};
		var leaveValue = 1<<0, leaveStyle = 1<<1, leavePad = 1<<2, leaveSld = 1<<3;

		colorDropper.addEvent(target, 'blur', function() {
			if (!abortBlur) {
				window.setTimeout(function(){ abortBlur || blurTarget(); abortBlur = false; }, 0);
			}
            else {
				abortBlur = false;
			}
		});


		for(p in prop) {
			if (prop.hasOwnProperty(p)) {
				this[p] = prop[p];
			}
		}

		this.hidePicker = function() {
			if (isPickerOwner()) {
				removePicker();
			}
		};

		this.showPicker = function() {
			if (!isPickerOwner()) {
				drawPicker();
			}
		};

		this.importColor = function() {
			if (!valueElement) {
				this.exportColor();
			}
            else {
				if (!this.adjust) {
					if (!this.fromString(valueElement.value, leaveValue)) {
						styleElement.style.backgroundImage = styleElement.jscStyle.backgroundImage;
						styleElement.style.backgroundColor = styleElement.jscStyle.backgroundColor;
						styleElement.style.color = styleElement.jscStyle.color;
						this.exportColor(leaveValue | leaveStyle);
					}
				}
                else if (!this.required && /^\s*$/.test(valueElement.value)) {
					valueElement.value = '';
					styleElement.style.backgroundImage = styleElement.jscStyle.backgroundImage;
					styleElement.style.backgroundColor = styleElement.jscStyle.backgroundColor;
					styleElement.style.color = styleElement.jscStyle.color;
					this.exportColor(leaveValue | leaveStyle);

				}
                else if(this.fromString(valueElement.value)) {
				}
                else {
					this.exportColor();
				}
			}
		};

		this.exportColor = function (flags) {
			if (!(flags & leaveValue) && valueElement) {
				var value = this.toString();
				if (this.caps) { value = value.toUpperCase(); }
				if (this.hash) { value = '#'+value; }
				valueElement.value = value;
			}
			if (!(flags & leaveStyle) && styleElement) {
				styleElement.style.backgroundImage = "none";
				styleElement.style.backgroundColor = '#'+this.toString();
				styleElement.style.color = 0.213 * this.rgb[0] + 0.715 * this.rgb[1] + 0.072 * this.rgb[2] < 0.5 ? '#FFF' : '#000';
			}
			if (!(flags & leavePad) && isPickerOwner()) {
				redrawPad();
			}
			if (!(flags & leaveSld) && isPickerOwner()) {
				redrawSld();
			}
		};

		this.fromHSV = function (h, s, v, flags) {
			if (h) { h = Math.max(0.0, this.minH, Math.min(6.0, this.maxH, h)); }
			if (s) { s = Math.max(0.0, this.minS, Math.min(1.0, this.maxS, s)); }
			if (v) { v = Math.max(0.0, this.minV, Math.min(1.0, this.maxV, v)); }

			this.rgb = this.HSV_RGB(
				h === null ? this.hsv[0] : (this.hsv[0] = h),
				s === null ? this.hsv[1] : (this.hsv[1] = s),
				v === null ? this.hsv[2] : (this.hsv[2] = v)
			);
			this.exportColor(flags);
		};


		this.fromRGB = function (r, g, b, flags) {
			if (r) { r = Math.max(0.0, Math.min(1.0, r)); }
			if (g) { g = Math.max(0.0, Math.min(1.0, g)); }
			if (b) { b = Math.max(0.0, Math.min(1.0, b)); }

			var hsv = this.RGB_HSV(
				r === null ? this.rgb[0] : r,
				g === null ? this.rgb[1] : g,
				b === null ? this.rgb[2] : b
			);
			if(hsv[0] !== null) {
				this.hsv[0] = Math.max(0.0, this.minH, Math.min(6.0, this.maxH, hsv[0]));
			}
			if(hsv[2] !== 0) {
				this.hsv[1] = hsv[1] === null ? null : Math.max(0.0, this.minS, Math.min(1.0, this.maxS, hsv[1]));
			}
			this.hsv[2] = hsv[2] === null ? null : Math.max(0.0, this.minV, Math.min(1.0, this.maxV, hsv[2]));

			var rgb = this.HSV_RGB(this.hsv[0], this.hsv[1], this.hsv[2]);
			this.rgb[0] = rgb[0];
			this.rgb[1] = rgb[1];
			this.rgb[2] = rgb[2];

			this.exportColor(flags);
		};

		this.fromString = function (hex, flags) {
			var m = hex.match(/^\W*([0-9A-F]{3}([0-9A-F]{3})?)\W*$/i);
			if (!m) {
				return false;
			}
            if (m[1].length === 6) {
                this.fromRGB(
                    parseInt(m[1].substr(0,2),16) / 255,
                    parseInt(m[1].substr(2,2),16) / 255,
                    parseInt(m[1].substr(4,2),16) / 255,
                    flags
                );
            }
            else {
                this.fromRGB(
                    parseInt(m[1].charAt(0)+m[1].charAt(0),16) / 255,
                    parseInt(m[1].charAt(1)+m[1].charAt(1),16) / 255,
                    parseInt(m[1].charAt(2)+m[1].charAt(2),16) / 255,
                    flags
                );
            }
			return true;
		};

		this.toString = function() {
			return (
				(0x100 | Math.round(255*this.rgb[0])).toString(16).substr(1) +
				(0x100 | Math.round(255*this.rgb[1])).toString(16).substr(1) +
				(0x100 | Math.round(255*this.rgb[2])).toString(16).substr(1)
			);
		};

		this.RGB_HSV = function(r, g, b) {
			var n = Math.min(Math.min(r, g), b);
			var v = Math.max(Math.max(r, g), b);
			var m = v - n;
			if (m === 0) { return [ null, 0, v ]; }
			var h = r === n ? 3 + (b - g) / m : (g === n ? 5 + (r - b)/ m : 1 + (g - r) / m);
			return [ h === 6 ? 0 : h, m / v, v ];
		};

		this.HSV_RGB = function(h, s, v) {
			if (h === null) { return [ v, v, v ]; }
			var i = Math.floor(h);
			var f = i % 2 ? h-i : 1-(h-i);
			var m = v * (1 - s);
			var n = v * (1 - s*f);
			switch(i) {
				case 6:
				case 0: return [v,n,m];
				case 1: return [n,v,m];
				case 2: return [m,v,n];
				case 3: return [m,n,v];
				case 4: return [n,m,v];
				case 5: return [v,m,n];
			}
		};

		function removePicker() {
			delete colorDropper.picker.owner;
            colorDropper.picker.boxB.parentNode.removeChild(colorDropper.picker.boxB);
		}

		function drawPicker () {
            var i = 0, seg, segSize;
			if (!colorDropper.picker) {
				colorDropper.picker = {
					box : document.createElement('div'),
					boxB : document.createElement('div'),
					pad : document.createElement('div'),
					padB : document.createElement('div'),
					padM : document.createElement('div'),
					sld : document.createElement('div'),
					sldB : document.createElement('div'),
					sldM : document.createElement('div')
				};
				for (i=0, segSize=2; i < colorDropper.images.sld[1]; i += segSize) {
					seg = document.createElement('div');
					seg.style.height = segSize+'px';
					seg.style.fontSize = '1px';
					seg.style.lineHeight = '0px';
					colorDropper.picker.sld.appendChild(seg);
				}
				colorDropper.picker.sldB.appendChild(colorDropper.picker.sld);
				colorDropper.picker.box.appendChild(colorDropper.picker.sldB);
				colorDropper.picker.box.appendChild(colorDropper.picker.sldM);
				colorDropper.picker.padB.appendChild(colorDropper.picker.pad);
				colorDropper.picker.box.appendChild(colorDropper.picker.padB);
				colorDropper.picker.box.appendChild(colorDropper.picker.padM);
				colorDropper.picker.boxB.appendChild(colorDropper.picker.box);
			}

			p = colorDropper.picker;
			p.box.onmouseup = p.box.onmouseout = function() { target.focus(); };
			p.box.onmousedown = function() { abortBlur=true; };
			p.box.onmousemove = function(e) {
				if (holdPad || holdSld) {
					holdPad && setPad(e);
					holdSld && setSld(e);
					if (document.selection) {
						document.selection.empty();
					}
                    else if (window.getSelection) {
						window.getSelection().removeAllRanges();
					}
					dispatchImmediateChange();
				}
			};

			if ('ontouchstart' in window) {
				var handle_touchmove = function(e) {
					var event={ 'offsetX': e.touches[0].pageX-touchOffset.X, 'offsetY': e.touches[0].pageY-touchOffset.Y
					};
					if (holdPad || holdSld) {
						holdPad && setPad(event);
						holdSld && setSld(event);
						dispatchImmediateChange();
					}
					e.stopPropagation();
					e.preventDefault();
				};
				p.box.removeEventListener('touchmove', handle_touchmove, false);
				p.box.addEventListener('touchmove', handle_touchmove, false);
			}
			p.padM.onmouseup = p.padM.onmouseout = function() {
                if (holdPad) { holdPad=false; colorDropper.fireEvent(valueElement,'change'); }
            };
			p.padM.onmousedown = function(e) {
				switch(modeID) {
					case 0:
						if (self.hsv[2] === 0) {
							self.fromHSV(null, null, 1.0);
						}
						break;
					case 1:
						if (self.hsv[1] === 0) {
							self.fromHSV(null, 1.0, null);
						}
						break;
				}
				holdSld = false;
				holdPad = true;
				setPad(e);
				dispatchImmediateChange();
			};

			if ('ontouchstart' in window) {
				p.padM.addEventListener('touchstart', function(e) {
					touchOffset = { 'X': getOffsetParent(e.target).Left, 'Y': getOffsetParent(e.target).Top };
					this.onmousedown({ 'offsetX':e.touches[0].pageX-touchOffset.X, 'offsetY':e.touches[0].pageY-touchOffset.Y });
				});
			}
			p.sldM.onmouseup = p.sldM.onmouseout = function() {
                if (holdSld) { holdSld = false; colorDropper.fireEvent(valueElement,'change'); }
            };
			p.sldM.onmousedown = function(e) {
				holdPad = false;
				holdSld = true;
				setSld(e);
				dispatchImmediateChange();
			};
			if ('ontouchstart' in window) {
				p.sldM.addEventListener('touchstart', function(e) {
					touchOffset = { 'X': getOffsetParent(e.target).Left, 'Y': getOffsetParent(e.target).Top };
					this.onmousedown({ 'offsetX':e.touches[0].pageX-touchOffset.X, 'offsetY':e.touches[0].pageY-touchOffset.Y });
				});
			}

			var dims = getPickerDims(self);
			p.box.style.width = dims[0] + 'px';
			p.box.style.height = dims[1] + 'px';

			p.boxB.style.position = 'relative';
			p.boxB.style.clear = 'both';
			p.boxB.style.border = 'none';
			p.boxB.style.background = self.pickerFaceColor;

			p.pad.style.width = colorDropper.images.pad[0]+'px';
			p.pad.style.height = colorDropper.images.pad[1]+'px';

			p.padB.style.position = 'absolute';
			p.padB.style.left = self.pickerFace+'px';
			p.padB.style.top = self.pickerFace+'px';
			p.padB.style.border = self.pickerInset+'px solid';
			p.padB.style.borderColor = self.pickerInsetColor;

			p.padM.style.position = 'absolute';
			p.padM.style.left = '0';
			p.padM.style.top = '0';
			p.padM.style.width = self.pickerFace + 2*self.pickerInset + colorDropper.images.pad[0] + colorDropper.images.arrow[0] + 'px';
			p.padM.style.height = p.box.style.height;
			p.padM.style.cursor = 'crosshair';

			p.sld.style.overflow = 'hidden';
			p.sld.style.width = "13px";
			p.sld.style.height = colorDropper.images.sld[1]+'px';

			p.sldB.style.position = 'absolute';
			p.sldB.style.right = self.pickerFace+'px';
			p.sldB.style.top = self.pickerFace+'px';
			p.sldB.style.border = self.pickerInset+'px solid';
			p.sldB.style.borderColor = self.pickerInsetColor;

			p.sldM.style.position = 'absolute';
			p.sldM.style.right = '0';
			p.sldM.style.top = '0';
			p.sldM.style.width = 14 + colorDropper.images.arrow[0] + self.pickerFace + 2*self.pickerInset + 'px';
			p.sldM.style.height = p.box.style.height;

            try {
				p.sldM.style.cursor = 'pointer';
			} catch(e) {
				p.sldM.style.cursor = 'hand';
			}

            var padImg = modeID ? 'color_picker_hv.png' : 'color_picker_hs.png';
			p.padM.style.backgroundImage = "url('"+self.iconDir+"/color_picker_cross.gif')";
			p.padM.style.backgroundRepeat = "no-repeat";
			p.sldM.style.backgroundImage = "url('"+self.iconDir+"/color_picker_arrow.gif')";
			p.sldM.style.backgroundRepeat = "no-repeat";
			p.pad.style.backgroundImage = "url('"+self.iconDir+"/"+padImg+"')";
			p.pad.style.backgroundRepeat = "no-repeat";
			p.pad.style.backgroundPosition = "0 0";
            redrawPad();
			redrawSld();
			colorDropper.picker.owner = self;
            target.parentNode.parentNode.appendChild(p.boxB);
		}

		function getPickerDims(o) {
			var dims = [
				2*o.pickerInset + 2*o.pickerFace + colorDropper.images.pad[0] +
                        2*o.pickerInset + 2*colorDropper.images.arrow[0] + colorDropper.images.sld[0],
					2*o.pickerInset + 2*o.pickerFace + colorDropper.images.pad[1]
			];
			return dims;
		}

		function redrawPad() {
            var yComponent;
			switch(modeID) {
				case 0: yComponent = 1; break;
				case 1: yComponent = 2; break;
			}
			var x = Math.round((self.hsv[0]/6) * (colorDropper.images.pad[0]-1));
			var y = Math.round((1-self.hsv[yComponent]) * (colorDropper.images.pad[1]-1));
			colorDropper.picker.padM.style.backgroundPosition =
				(self.pickerFace+self.pickerInset+x - Math.floor(colorDropper.images.cross[0]/2)) + 'px ' +
				(self.pickerFace+self.pickerInset+y - Math.floor(colorDropper.images.cross[1]/2)) + 'px';

			var seg = colorDropper.picker.sld.childNodes;
            var i=0, rgb, s, c;
			switch(modeID) {
				case 0:
					rgb = self.HSV_RGB(self.hsv[0], self.hsv[1], 1);
                    if (window.File && window.FileReader) {
                        colorDropper.picker.sld.style.background = "linear-gradient(rgb("+
                                (rgb[0]*(1-i/seg.length)*100)+"%,"+
                                (rgb[1]*(1-i/seg.length)*100)+"%,"+
                                (rgb[2]*(1-i/seg.length)*100)+"%), black)";
                    }
                    else {
                        for (i=0; i<seg.length; i+=1) {
                            seg[i].style.backgroundColor = 'rgb('+
                                (rgb[0]*(1-i/seg.length)*100)+'%,'+
                                (rgb[1]*(1-i/seg.length)*100)+'%,'+
                                (rgb[2]*(1-i/seg.length)*100)+'%)';
                        }
                    }
					break;
				case 1:
					c = [ self.hsv[2], 0, 0 ];
					i = Math.floor(self.hsv[0]);
					var f = i % 2 ? self.hsv[0]-i : 1-(self.hsv[0]-i);
					switch (i) {
						case 6:
						case 0: rgb=[0,1,2]; break;
						case 1: rgb=[1,0,2]; break;
						case 2: rgb=[2,0,1]; break;
						case 3: rgb=[2,1,0]; break;
						case 4: rgb=[1,2,0]; break;
						case 5: rgb=[0,2,1]; break;
					}

					for (i=0; i<seg.length; i+=1) {
						s = 1 - 1/(seg.length-1)*i;
						c[1] = c[0] * (1 - s*f);
						c[2] = c[0] * (1 - s);
						seg[i].style.backgroundColor = 'rgb('+
							(c[rgb[0]]*100)+'%,'+
							(c[rgb[1]]*100)+'%,'+
							(c[rgb[2]]*100)+'%)';
					}
					break;
			}
		}

        function getOffsetParent(el) {
            var parent = el.offsetParent;
            var top = 0, left = 0;
            while (parent) {
                top += parent.offsetTop;
                left += parent.offsetLeft;
                parent = parent.offsetParent;
            }
            return {Left: left, Top: top};
        }

		function redrawSld() {
            var yComponent;
			switch(modeID) {
				case 0: yComponent = 2; break;
				case 1: yComponent = 1; break;
			}
			var y = Math.round((1-self.hsv[yComponent]) * (colorDropper.images.sld[1]-1));
			colorDropper.picker.sldM.style.backgroundPosition =
				'0 ' + (self.pickerFace+self.pickerInset+y - Math.floor(colorDropper.images.arrow[1]/2)) + 'px';
		}

		function isPickerOwner() {
			return colorDropper.picker && colorDropper.picker.owner === self;
		}

		function blurTarget() {
			if(valueElement === target) {
				self.importColor();
			}
		}

		function blurValue() {
			if(valueElement !== target) {
				self.importColor();
			}
		}

		function setPad(e) {
			var mpos = colorDropper.getRelMousePos(e);
			var x = mpos.x - self.pickerFace - self.pickerInset;
			var y = mpos.y - self.pickerFace - self.pickerInset;
			switch(modeID) {
				case 0: self.fromHSV(x*(6/(colorDropper.images.pad[0]-1)), 1 - y / (colorDropper.images.pad[1]-1), null, leaveSld); break;
				case 1: self.fromHSV(x*(6/(colorDropper.images.pad[0]-1)), null, 1 - y / (colorDropper.images.pad[1]-1), leaveSld); break;
			}
		}

		function setSld(e) {
			var mpos = colorDropper.getRelMousePos(e);
			var y = mpos.y - self.pickerFace - self.pickerInset;
			switch(modeID) {
				case 0: self.fromHSV(null, null, 1 - y / (colorDropper.images.sld[1]-1), leavePad); break;
				case 1: self.fromHSV(null, 1 - y / (colorDropper.images.sld[1]-1), null, leavePad); break;
			}
		}

		function dispatchImmediateChange() {
			if (self.onImmediateChange) {
				var callback;
				if (typeof self.onImmediateChange === 'string') {
					callback = new Function (self.onImmediateChange);
				}
                else {
					callback = self.onImmediateChange;
				}
				callback.call(self);
			}
		}

		if(valueElement) {
			var updateField = function() {
				self.fromString(valueElement.value, leaveValue);
				dispatchImmediateChange();
			};
			colorDropper.addEvent(valueElement, 'keyup', updateField);
			colorDropper.addEvent(valueElement, 'input', updateField);
			colorDropper.addEvent(valueElement, 'blur', blurValue);
			valueElement.setAttribute('autocomplete', 'off');
		}

		this.importColor();
	}
};