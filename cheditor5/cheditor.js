// ================================================================
//                       CHEditor 5.09.4
// ----------------------------------------------------------------
// Author: Na Chang Ho
// Website: http://www.chcode.com
// EMail: support@chcode.com
// Copyright (c) 1997-2014 CHSOFT
// ================================================================
var userAgent = navigator.userAgent.toLowerCase();
var GB = {
	colors : ["#ffffff", "#000000", "#eeece1", "#1f497d", "#4f81bd", "#c0504d", "#9bbb59", "#8064a2", "#4bacc6", "#f79646",
			  "#f2f2f2", "#7f7f7f", "#ddd9c3", "#c6d9f0", "#dbe5f1", "#f2dcdb", "#ebf1dd", "#e5e0ec", "#dbeef3", "#fdeada",
			  "#d8d8d8", "#595959", "#c4bd97", "#8db3e2", "#b8cce4", "#e5b9b7", "#d7e3bc", "#ccc1d9", "#b7dde8", "#fbd5b5",
			  "#bfbfbf", "#3f3f3f", "#938953", "#548dd4", "#95b3d7", "#d99694", "#c3d69b", "#b2a2c7", "#92cddc", "#fac08f",
			  "#a5a5a5", "#262626", "#494429", "#17365d", "#366092", "#953734", "#76923c", "#5f497a", "#31859b", "#e36c09",
			  "#7f7f7f", "#0c0c0c", "#1d1b10", "#0f243e", "#244061", "#632423", "#4f6128", "#3f3151", "#205867", "#974806",
			  "#c00000", "#ff0000", "#ffc000", "#ffff00", "#92d050", "#00b050", "#00b0f0", "#0070c0", "#002060", "#7030a0"],

	htmlKey : ["!doctype", "a", "abbr", "acronym", "address", "applet", "area", "b", "base", "basefont", "bgsound", "bdo",
			   "big", "blink", "dl", "body", "br", "button", "caption", "center", "cite", "code", "col", "colgroup",
			   "comment", "dd", "del", "dfn", "dir", "div", "font", "dt", "em", "embed", "fieldset", "blockquote",
			   "form", "frame", "frameset", "h", "h1", "h2", "h3", "h4", "h5", "h6", "head", "hr",
			   "html", "i", "iframe", "img", "input", "ins", "isindex", "kbd", "label", "legend", "li", "link",
			   "listing", "map", "marquee", "menu", "meta", "multicol", "nextid", "nobr", "noframes", "noscript", "object", "ol",
			   "optgroup", "option", "p", "param", "plaintext","pre", "q", "s", "samp", "script", "select", "server",
			   "small", "sound", "spacer", "span", "strike", "strong", "style", "sub", "sup", "table", "tbody", "td",
			   "textarea", "title", "tfoot", "th", "thead", "textflow", "tr", "tt", "u", "ul", "var", "wbr", "xmp"],

	offElements : { IMG:1, HR:1, TABLE:1, EMBED:1, OBJECT:1, INPUT:1, FORM:1, SELECT:1, TEXTAREA:1, BUTTON:1, FIELDSET:1 },
	cantHaveChildren : { area:1, base:1, basefont:1, col:1, frame:1, hr:1, img:1, br:1, input:1, isindex:1, link:1, meta:1, param:1, source:1 },
    XhtmlNewLineBefore : '|div|p|table|tbody|tr|td|th|title|head|body|script|comment|li|meta|h1|h2|h3|h4|h5|h6|hr|ul|ol|option|link|',

	browser     : { msie    : /msie/.test(userAgent),
                    iegecko :  /like gecko/i.test(userAgent) && /rv:/.test(userAgent),
					ver     : (userAgent.match( /.+(?:rv|it|ra|ie|version)[\/: ]([\d.]+)/ ) || [0,'0'])[1],
					gecko   : /gecko/.test(userAgent),
					opera   : /opera/.test(userAgent),
					safari  : /webkit/.test(userAgent),
					mozilla : /mozilla/.test(userAgent) && !/(compatible|webkit)/.test(userAgent)
	},

	popupWindow : {ImageUpload :    { tmpl : 'image.html',          width : 700, title : '내 PC 사진 넣기' },
				   ImageUrl :       { tmpl : 'image_url.html',      width : 354, title : '웹 사진 넣기' },
				   Embed :          { tmpl : 'media.html',          width : 430, title : '미디어' },
				   Table :          { tmpl : 'table.html',          width : 431, title : '표 만들기' },
				   ModifyTable :    { tmpl : 'table_modify.html',   width : 431, title : '표 고치기' },
				   Layout :         { tmpl : 'layout.html',         width : 430, title : '레이아웃' },
				   Link :           { tmpl : 'link.html',           width : 420, title : '하이퍼링크' },
				   EmotionIcon :    { tmpl : 'icon.html',           width : 300, title : '표정 아이콘' },
				   Symbol :         { tmpl : 'symbol.html',         width : 450, title : '특수 문자' },
				   GoogleMap :      { tmpl : 'google_map.html',     width : 535, title : '구글 지도' },
				   ColorPicker :	{ tmpl : 'color_picker.html',	width : 460, title : '색상 선택' },
				   FlashMovie :     { tmpl : 'flash.html',          width : 502, title : '플래쉬 동영상' }},

	fontName    : { 'kr' : ['맑은 고딕', '돋움', '굴림', '바탕', '궁서'],
					'en' : ['Arial', 'Comic Sans MS', 'Courier New', 'Georgia', 
					        'Lucida Sans Unicode', 'Tahoma', 'Times New Roman', 'Verdana'] },

    fontSize    : { '1' : '8pt',
                    '2' : '10pt',
                    '3' : '12pt',
                    '4' : '14pt',
                    '5' : '18pt',
                    '6' : '24pt',
                    '7' : '36pt',
                    'null' : null,
                    '10px' : '8pt',
                    '12px' : '9pt',
                    '13px' : '10pt',
                    '16px' : '12pt',
                    '18px' : '14pt',
                    '24px' : '18pt',
                    '32px' : '24pt',
                    '48px' : '36pt'},

	paragraph   : { 'P' : 'Normal (P)',
					'H1' : 'Heading 1',
					'H2' : 'Heading 2',
					'H3' : 'Heading 3',
					'H4' : 'Heading 4',
					'H5' : 'Heading 5',
					'H6' : 'Heading 6',
					'ADDRESS' : 'Address',
					'PRE' : 'Preformatted (PRE)' },

   textBlock    : [['1px #dedfdf solid','#f7f7f7'],
				   ['1px #aee8e8 solid','#bfffff'],
				   ['1px #d3bceb solid','#e6ccff'],
				   ['1px #e8e88b solid','#ffff99'],
				   ['1px #c3e89e solid','#d6ffad'],
				   ['1px #e8c8b7 solid','#ffdcc9'],
				   ['1px #666666 dashed','#ffffff'],
				   ['1px #d4d4d4 solid','#ffffff'],
				   ['1px #cccccc inset','#f7f7f7']],

   node         : { ELEMENT_NODE                :  1,
					ATTRIBUTE_NODE              :  2,
					TEXT_NODE                   :  3,
					CDATA_SECTION_NODE          :  4,
					ENTITY_REFERENCE_NODE       :  5,
					ENTITY_NODE                 :  6,
					PROCESSING_INSTRUCTION_NODE :  7,
					COMMENT_NODE                :  8,
					DOCUMENT_NODE               :  9,
					DOCUMENT_TYPE_NODE          : 10,
					DOCUMENT_FRAGMENT_NODE      : 11,
					NOTATION_NODE               : 12 },

	selection   : { SELECTION_NONE              : 1,
					SELECTION_TEXT              : 2,
					SELECTION_ELEMENT           : 3 },
    readyState  : { 0 : 'uninitialized', 1 : 'loading', 2 : 'loaded', 3 : 'interactive', 4 : 'complete' }
};

function URI (uri) {
	this.scheme    = null;
	this.authority = null;
	this.path      = '';
	this.query     = null;
	this.fragment  = null;

	this.parseUri = function (uri) {
		var m = uri.match(/^(([A-Za-z][0-9A-Za-z+.-]*)(:))?((\/\/)([^\/?#]*))?([^?#]*)((\?)([^#]*))?((#)(.*))?/);
		this.scheme    = m[3] ? m[2] : null;
		this.authority = m[5] ? m[6] : null;
		this.path      = m[7];
		this.query     = m[9] ? m[10] : null;
		this.fragment  = m[12]? m[13] : null;
		return this;
	};

	this.azToString = function () {
		var result = '';
		if (this.scheme    !== null) result = result +      this.scheme + ':';
		if (this.authority !== null) result = result +'//'+ this.authority;
		if (this.path      !== null) result = result +      this.path;
		if (this.query     !== null) result = result + '?'+ this.query;
		if (this.fragment  !== null) result = result + '#'+ this.fragment;
		return result;
	};

	this.toAbsolute = function (location) {
		var baseUri = new URI(location);
		var URIAbs = this;
		var target = new URI();
		var removeDotSegments = function (path) {
			var result = '';
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
					var rm = path.match(/^\/?[^\/]*/)[0];
					path = path.substr(rm.length);
					result = result + rm;
				}
			}
			return result;
		};
		
		if (baseUri.scheme === null) return false;
		if (URIAbs.scheme !== null && URIAbs.scheme.toLowerCase() === baseUri.scheme.toLowerCase()) {
			URIAbs.scheme = null;
		}

		if (URIAbs.scheme !== null) {
			target.scheme    = URIAbs.scheme;
			target.authority = URIAbs.authority;
			target.path      = removeDotSegments(URIAbs.path);
			target.query     = URIAbs.query;
		}
		else {
			if (URIAbs.authority !== null) {
				target.authority = URIAbs.authority;
				target.path      = removeDotSegments(URIAbs.path);
				target.query     = URIAbs.query;
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
						if (baseUri.authority !== null && baseUri.path == '') {
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

	if (uri) this.parseUri(uri);
}

function setConfig () {
    var config = {
        // 불당팩
        boTable         : '',

        editorWidth     : '100%',
        editorHeight    : '300px',
        editorFontSize  : '9pt',
        editorFontName  : '맑은 고딕,굴림',
        editorFontColor : '#000',
        editorBgColor   : '#fff',
        imgCaptionText	: 'margin: 5px 0px; color: #333',
        lineHeight      : 1.5,
        editAreaMargin  : '5px 10px',
        tabIndex        : 0,
        editorPath      : null,
        fullHTMLSource  : false,
        linkTarget      : '_blank',
        showTagPath     : false,
        colorToHex		  : true,
        imgMaxWidth     : 640,
        imgBlockMargin  : '5px 0px',
        imgReSize       : true,
        includeHostname : false,
        ieEnterMode     : 'br', // [css, div, br, default(p)]
        outputXhtml     : true,
        xhtmlLang		    : 'utf-8',
        xhtmlEncoding	  : 'utf-8',
        docTitle		    : '내 문서',
        template        : 'template.xml',

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
        useItalic       : false,
        useSuperscript  : false,
        useSubscript    : false,
        useJustifyLeft  : true,
        useJustifyCenter: true,
        useJustifyRight : false,
        useJustifyFull  : false,
        useBold         : true,
        useOrderedList  : false,
        useUnOrderedList: false,
        useOutdent      : false,
        useIndent       : false,
        useFontName     : true,
        useParagraph    : false,
        useFontSize     : true,
        useBackColor    : false,
        useForeColor    : true,
        useRemoveFormat : false,
        useClearTag     : true,
        useSymbol       : false,
        useLink         : true,
        useUnLink       : true,
        useFlash        : false,
        useMedia        : false,
        useImage        : true,
        useImageUrl     : false,
        useSmileyIcon   : true,
        useHR           : false,
        useTable        : true,
        useModifyTable  : true,
        useMap          : false,
        useTextBlock    : false,
        useFullScreen   : true,
        usePageBreak    : false,
        allowedScript   : false,
        allowedMaxImgSize : 0
    };

    if (config.editorPath === null) {
        var base = location.href;
        var e = document.getElementsByTagName('base'), i;
        for (i=0; i<e.length; i++) {
            if (e[i].href)
                base = e[i].href;
        }
        e = document.getElementsByTagName('script');
        for (i=0; i<e.length; i++) {
            if (e[i].src) {
                var editorUri = new URI(e[i].src);
                if(/\/cheditor\.js$/.test(editorUri.path)) {
                    var locationAbs = editorUri.toAbsolute(base).azToString();
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

	this.cheditor           = {};
	this.inputForm          = null;
	this.range              = null;
	this.images             = [];
	this.editImages         = {};
	this.setFullScreenMode  = false;
	this._modalReSize       = null;
	this.modalElementZIndex = 1001;
	this.xmlDoc             = null;
	this.toolbar            = {};
	this.pulldown           = {};
	this.tempTimer          = null;
	this.currentRS          = {};
	this.resizeEditor       = {};
    this.config             = config;
    this.templateFile       = config.template;
    this.templatePath       = config.editorPath + config.template;
    this.storedSelections   = [];
    this.W3CRange           = window.getSelection;
}

function cheditor () {
    var msg = null;
	if (typeof(document.execCommand) == 'undefined') {
		msg = "현재 브라우저가 execCommand를 지원하지 않습니다.\nCHEditor를 사용할 수 없습니다.";
	}
	if (GB.browser.msie && GB.browser.ver < 6) {
		msg = "\nCHEditor는 MSIE 5.x 이하 버전을 지원하지 않습니다.";
	}
	if (GB.browser.gecko) {
		if (navigator.productSub < 20030107) {
			msg = "CHEditor는 현재 사용 중인 브라우저를 지원하지 않습니다.";
        }
	}

    if (msg !== null) {
        alert(msg);
		return null;
    }

    try {
	    setConfig.call(this);
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
		catch (e) {}
	}
},

appendContents : function (contents) {
	this.editArea.focus();
	var div = this.doc.createElement('div');
	div.innerHTML = '' + this.trimSpace(contents);

    while (div.hasChildNodes()) {
		this.doc.body.appendChild(div.firstChild);
    }
    
	this.editArea.focus();
},

insertContents : function (contents) {
	this.editArea.focus();
	this.doCmdPaste(''+this.trimSpace(contents));
},

replaceContents : function (contents) {
	this.editArea.focus();
	this.doc.body.innerHTML = '';
	this.loadContents(contents);
	this.editArea.focus();
},

loadContents : function (contents) {
	if (typeof(contents) === 'string') {
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
		if (!done && (!this.readyState || this.readyState == "loaded" || this.readyState == "complete"))
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

	this.config.iconPath  = this.config.editorPath + 'icons/';
	this.config.cssPath   = this.config.editorPath + 'css/';
	this.config.popupPath = this.config.editorPath + 'popup/';
},

checkInputForm : function () {
	var textarea = this.$(this.inputForm);
	if (!textarea) {
		throw("ID가 '"+this.inputForm+"'인 textarea 개체를 찾을 수 없습니다.");
    }

	textarea.style.display = 'none';
	this.cheditor.textarea = textarea;
},

setDesignMode : function (designMode) {
	if (GB.browser.msie) {
		this.doc.body.contentEditable = designMode;
	}
	else {
		this.doc.designMode  = designMode ? "on" : "off";
	}
},

openDoc : function (doc, contents) {
	doc.open();
	var html = '<html><head><title>'+this.config.docTitle+'</title><style></style></head><body>';

	if (typeof(contents) === 'string') {
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
		this.editArea = this.getWindowHandle(this.cheditor.editArea);
		this.doc = GB.browser.msie ? this.editArea.document : this.cheditor.editArea.contentDocument;
		this.resetData();
		return true;
	}
	catch (e) {
		alert(e.toString());
		return false;
	}
},

resetEditArea : function () {
	this.openDoc(this.doc, this.cheditor.textarea.value);
	this.setDesignMode(true);

	var oSheet = this.doc.styleSheets[0];
	if (GB.browser.msie) {
		oSheet.addRule('body', 'font-size:' + this.config.editorFontSize +
			';font-family:' + this.config.editorFontName +
			';color:' + this.config.editorFontColor +
			';margin:' + this.config.editAreaMargin +
			';line-height:' + this.config.lineHeight +
			';background-color:' + this.config.editorBgColor);
		oSheet.addRule('table', 'font-size:' + this.config.editorFontSize +
			';line-height:' + this.config.lineHeight);
	}
	else {
		oSheet.insertRule('body {font-size: ' + this.config.editorFontSize +
			';font-family: ' + this.config.editorFontName +
			';color: ' + this.config.editorFontColor +
			';margin: ' + this.config.editAreaMargin +
			';line-height:' + this.config.lineHeight +
			';background-color:' + this.config.editorBgColor + '}', 0);
		oSheet.insertRule('table {font-size: ' + this.config.editorFontSize +
			';line-height:' + this.config.lineHeight + '}', 1);
	}

	this.cheditor.editArea.style.padding = '0px';
	this.cheditor.editArea.style.margin  = '0px';
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
	self.currentRS.elStartTop = parseInt(self.currentRS.elNode.style.height);

	if (isNaN(self.currentRS.elStartTop)) self.currentRS.elStartTop = 0;

	ev = ev || window.event;

	self.resizeEditor.stopFunc = function() { self.resizeStop.call(self, ev); };
	self.resizeEditor.moveFunc = function(event) { self.resizeMove.call(self, event); };

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
	this.editArea.focus();
},

switchEditorMode : function (changeMode) {
	this.editArea.focus();
	if (this.cheditor.mode == changeMode) return;

	for (var i in this.cheditor.modetab) {
        if (this.cheditor.modetab.hasOwnProperty(i)) {
		    var className = this.cheditor.modetab[i].className;
		    className = className.replace(/\-off/,'');
		    if (i != changeMode) {
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
    var xmlDoc = null;
    var outputError = function(msg) {
        alert(self.templateFile + ' 파일 로딩 중 오류가 발생하였습니다.\n원인: ' + msg);
    };

    if (window.ActiveXObject) {
        xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
        xmlDoc.async = "false";
        xmlDoc.onreadystatechange = function() {
            if (xmlDoc.readyState == 4) {
                try {
                    self.xmlDoc = xmlDoc;
                    self.loadTemplate.call(self, xmlDoc);
                }
                catch(e) {
                    outputError(e.toString());
                }
            }
        };

        xmlDoc.load(self.templatePath);
    }
    else if (window.XMLHttpRequest) {
        xmlDoc = new XMLHttpRequest();
        if (xmlDoc.overrideMimeType)
            xmlDoc.overrideMimeType('text/xml');

        xmlDoc.onload = function() {
            try {
                self.xmlDoc = xmlDoc.responseXML;
                self.loadTemplate.call(self, xmlDoc.responseXML);
            }
            catch(e) {
                outputError(e.toString());
            }
        };

        xmlDoc.open("GET", self.templatePath, false);
        xmlDoc.send('');
    }
    else {
        outputError("현재 브라우저에서 "+self.templateFile+" 파일을 사용할 수 없습니다.");
    }
},

getCDATASection : function (node) {
	if (node.hasChildNodes()) {
		var elem = node.firstChild;
		while (elem && elem.nodeType != GB.node.CDATA_SECTION_NODE) {
			elem = elem.nextSibling;
		}

		if (elem && elem.nodeType == GB.node.CDATA_SECTION_NODE) {
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
		case 'cheditor-tb-bg30'         : pos = 12; break;
		case 'cheditor-tb-bg30-last'    : pos = 15; break;
		case 'cheditor-tb-bg55'         : pos = 18; break;
		case 'cheditor-tb-bg40'         : pos = 21; break;
		default : pos = 0;
	}
	return pos;
},

toolbarPreviousChecked : function (elem) {
	var previousName = elem.previousSibling.getAttribute('name');
	if (previousName && this.toolbar[previousName]['checked'] == true) {
		return this.toolbar[previousName].button;
	}
	return false;
},

toolbarMouseOverUp : function (elem) {
	this.setToolbarBgPosition(elem, "0 " + (~(((this.getToolbarBgPosition(elem) + 1) * elem.getAttribute('btnHeight'))) + 1) + 'px');

	if (elem.getAttribute('name') == 'ForeColor' && elem.previousSibling.checked) {
			this.setToolbarBgPosition(elem.previousSibling,
				(~(elem.previousSibling.getAttribute('btnWidth')) + 1) + "px " +
					(~((this.getToolbarBgPosition(elem.previousSibling)+2) * elem.previousSibling.getAttribute('btnHeight')) + 1) + 'px');
		return;
	}

	if (elem.previousSibling && typeof elem.previousSibling.getAttribute('btnWidth') != null) {
		var previous = this.toolbarPreviousChecked(elem);
		if (previous)
		{
			this.setToolbarBgPosition(previous, (~(previous.getAttribute('btnWidth')) + 1) + "px " +
					(~((this.getToolbarBgPosition(previous) + 2) * previous.getAttribute('btnHeight')) + 1) + 'px');
			return;
		}

		if (elem.previousSibling.className == 'cheditor-tb-bg-first') {
			this.setToolbarBgPosition(elem.previousSibling,  (~(elem.previousSibling.getAttribute('btnWidth')) + 1) + "px 0");
		}
		else {
			this.setToolbarBgPosition(elem.previousSibling,
					(~(elem.previousSibling.getAttribute('btnWidth')) + 1) + "px " +
						(~(this.getToolbarBgPosition(elem.previousSibling) * elem.previousSibling.getAttribute('btnHeight')) + 1) + 'px');
		}
	}
},


toolbarMouseDownOut : function (elem) {
	if (elem.previousSibling == null || typeof elem.previousSibling.getAttribute('btnWidth') == null)
		return;

	var previous = this.toolbarPreviousChecked(elem);
	if (previous) {
		this.setToolbarBgPosition(previous, "0 " +
				(~((this.getToolbarBgPosition(previous) + 2) * previous.getAttribute('btnHeight')) + 1) + 'px');
		return;
	}

	this.setToolbarBgPosition(elem.previousSibling,
			"0 " + (~(this.getToolbarBgPosition(elem.previousSibling) * elem.previousSibling.getAttribute('btnHeight')) + 1) + 'px');
},

toolbarButtonChecked : function (elem) {
	this.setToolbarBgPosition(elem, "0 " + (~((this.getToolbarBgPosition(elem) + 2) * elem.getAttribute('btnHeight')) + 1) + 'px');
},

toolbarButtonUnchecked : function (elem) {
	this.setToolbarBgPosition(elem, "0 " + (~(this.getToolbarBgPosition(elem) * elem.getAttribute('btnHeight')) + 1) + 'px');
},

toolbarSetBackgroundImage : function (elem, disable) {
    var css = elem.firstChild.className;
    css = css.replace(/-disable$/i, "");
    
    if (disable) {
        css = css + "-disable";
        elem.style.cursor = 'default';
    }
    else {
        elem.style.cursor = 'pointer';
    }
    
    elem.firstChild.className = css;
},

toolbarDisable : function (elem, disable) {
	if (disable) {
		this.toolbarSetBackgroundImage(elem, true);
		this.toolbarButtonUnchecked(elem);
		this.toolbarMouseDownOut(elem);
		this.toolbar[elem.getAttribute('name')]['disabled'] = true;
		return true;
	}

	this.toolbarSetBackgroundImage(elem, false);
	this.toolbar[elem.getAttribute('name')]['disabled'] = false;
	return false;
},

colorConvert : function (color, which, opacity) {
    if (!which) {
        which = "rgba";
	}
    color = color.replace( /^\s*#|\s*$/g, "" );
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
            re: /^([0-9a-fA-F]{1})([0-9a-fA-F]{1})([0-9a-fA-F]{1})$/,
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
        return ("0" + parseInt(x).toString(16)).slice(-2);
    }
    
    switch (which) {
        case "rgba":
            if (opacity) {
                a = (255 - (min = Math.min(r, g, b))) / 255;
                r = (0 || (r - min) / a).toFixed(0);
                g = (0 || (g - min) / a).toFixed(0);
                b = (0 || (b - min) / a).toFixed(0);
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
	var rng = this.getRange();
	var nodeType = this.getSelectionType(rng);
	var pNode, ancestors = [], ancestorsLen = 0;
	var rngtext = this.W3CRange ? rng.toString() === '' : rng.text === '';

    if (!this.W3CRange) {
        if (nodeType === GB.selection.SELECTION_TEXT || nodeType === GB.selection.SELECTION_NONE) {
			pNode = rng.parentElement();
        }
        else if (nodeType === GB.selection.SELECTION_ELEMENT) {
			pNode = rng.item(0);
        }
        else {
			pNode = this.doc;
        }
	}
	else {
		try {
			pNode = rng.commonAncestorContainer;
			if (!rng.collapsed &&
				rng.startContainer == rng.endContainer &&
				rng.startOffset - rng.endOffset < 2 &&
				rng.startContainer.hasChildNodes())
			{
				pNode = rng.startContainer.childNodes[rng.startOffset];
			}
			if (nodeType == GB.selection.SELECTION_TEXT) {
				pNode = pNode.parentNode;
            }
			if (rngtext) {
				rngtext = GB.offElements[pNode.nodeName] ? false : true;
            }
		}
		catch (e) { pNode= this.doc; }
	}

	var isControl = false, isTable = false;

	if (nodeType == GB.selection.SELECTION_ELEMENT) {
		isControl = this.W3CRange ? GB.offElements[pNode.nodeName] : true;
	}
	else {
		while (pNode && pNode.nodeType === GB.node.ELEMENT_NODE && pNode.nodeName !== 'BODY') {
			ancestors.push(pNode);
			if (pNode.nodeName === 'TD' || pNode.nodeName === 'TH') {
                isTable = true;
            }
			pNode = pNode.parentNode;
		}
		ancestorsLen = ancestors.length;
	}

	var isNoOff = { 'ImageUpload':1, 'ImageUrl':1, 'EmotionIcon':1, 'Link':1, 'GoogleMap':1 };

	if (!isTable && nodeType === GB.selection.SELECTION_ELEMENT && 
        (pNode.nodeName === 'TABLE' || pNode.nodeName === 'TD' || pNode.nodeName === 'TH'))
	{
		isTable = true;
	}

	for (var i in toolbar) {
        var btn, cmd;
        
        if (toolbar.hasOwnProperty(i) !== true) {
            continue;
        }

        btn = toolbar[i];
        
        cmd = btn.cmd;
		if (!cmd) {
            continue;
        }

		var autoOff = false;
		if (isControl && nodeType === GB.selection.SELECTION_ELEMENT) {
			if (btn.btnGroup !== 'Alignment') {
				autoOff = !(pNode.nodeName === 'IMG' && isNoOff[cmd]);
			}
		}

		if (btn.name === 'ModifyTable') {
			autoOff = !isTable;
		}
        
		var isDisable = this.toolbarDisable(btn.button, autoOff);

		if (btn.name === 'ForeColor' || btn.name === 'BackColor') {
            btn.button.lastChild.style.display = isDisable ? 'none' : 'block';
		}

		if (btn.autocheck === null) {
            continue;
        }

        var el, wrapper, fontAttr, oldName, span;

        switch (cmd) {
        case 'Copy' :
        case 'Cut'  :
            this.toolbarDisable(btn.button, rngtext);
            break;
        case 'UnLink' :
            el = true;
            for (var j=0; j < ancestorsLen; j++) {
                if (ancestors[j].nodeName === 'A') {
                    el = false;
                    break;
                }
            }

            this.toolbarDisable(btn.button, el);
            break;
        case 'Paragraph' :
			wrapper = btn.button.firstChild;
			oldName = wrapper.firstChild;
			el = false;
            span = document.createElement('span');
            
			for (j=0; j < ancestorsLen; j++) {
				if (GB.paragraph[ancestors[j].nodeName]) {
                    span.appendChild(document.createTextNode(GB.paragraph[ancestors[j].nodeName]));
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
			if (cmd === 'BackColor' && !GB.browser.msie) cmd = 'HiliteColor';
			try {
                wrapper = btn.button.lastChild;
                fontAttr = this.doc.queryCommandValue(cmd);
				if (!fontAttr) {
                    fontAttr = (cmd == 'ForeColor') ? this.config.editorFontColor : this.config.editorBgColor;
                }
                else if (!/^[rgb|\#]/.test(fontAttr)) {
                    fontAttr = (((fontAttr & 0x0000ff) << 16) | (fontAttr & 0x00ff00) | ((fontAttr & 0xff0000) >>> 16)).toString(16);
                    fontAttr = this.colorConvert("#000000".slice(0, 7-fontAttr.length)+fontAttr, 'rgb');
                }
				wrapper.style.backgroundColor = fontAttr;
			}
            catch(e) {}
            break;
        case 'FontName' :
        case 'FontSize' :
            span = document.createElement('span');
            try {
                wrapper = toolbar[i].button.firstChild;
                el = false;
                fontAttr = this.doc.queryCommandValue(cmd);

                if (fontAttr) {
                    var newAttr = (cmd === 'FontSize') ?
                        (GB.fontSize[fontAttr] ? GB.fontSize[fontAttr] : fontAttr) :
                            fontAttr;

                    span.appendChild(document.createTextNode(newAttr));
                    wrapper.replaceChild(span, wrapper.firstChild);
                    el = true;
                }

                if (!el) {
                    var defaultAttr = (cmd === 'FontSize') ? this.config.editorFontSize : this.config.editorFontName;
                    if (wrapper.hasChildNodes()) {
                        wrapper.removeChild(wrapper.firstChild);
                    }
                    span.appendChild(document.createTextNode(defaultAttr));
                    wrapper.appendChild(span);
                }
                this.unselectionElement(span);
            }
            catch(e) {}
            break;
		default :
			try {
				var state = this.doc.queryCommandState(cmd);
				if (state && (nodeType === GB.selection.SELECTION_TEXT || nodeType === GB.selection.SELECTION_NONE)) {
					this.toolbarButtonChecked(btn.button);
                    btn.checked = true;
				}
				else {
					this.toolbarButtonUnchecked(btn.button);
                    btn.checked = false;
				}
			}
			catch(e) {}
        }
	}
},

createButton : function (name, attr, group) {
	var self = this;
	var elem = null;
	var method = attr.getElementsByTagName('Execution')[0].getAttribute('method');
	var cmd = attr.getElementsByTagName('Execution')[0].getAttribute('value');
	var check = attr.getAttribute('check');

	elem = document.createElement('div');
	elem.className = attr.getAttribute('class');

	var btnWidth = attr.getAttribute('width');
	elem.style.width = btnWidth + 'px';
	elem.setAttribute('btnWidth', btnWidth);
	elem.setAttribute('name', name);
	elem.setAttribute('method', method);

	var btnHeight = attr.getAttribute('height');
	elem.style.height = btnHeight + 'px';
	elem.setAttribute('btnHeight', btnHeight);

	var obj = {
			'button'    : elem,
			'disabled'  : false,
			'name'      : name,
			'method'    : method,
			'cmd'       : cmd,
			'checked'   : false,
			'btnGroup'  : group,
			'autocheck' : check
	};

	self.toolbar[name] = obj;
	self.addEvent(elem, 'mouseover', function() {
		if ((obj.checked === false && obj.disabled === false)) {
			self.toolbarMouseOverUp(elem);
        }
	});
    
	self.addEvent(elem, 'mouseup', function(ev) {
		if (obj.checked === false && obj.disabled === false) {
			self.toolbarMouseOverUp(elem);
		}
        self.stopEvent(ev || window.event);
	});
    
	self.addEvent(elem, 'mousedown', function(ev) {
		if (obj.checked === false && obj.disabled === false) {
			self.toolbarButtonChecked(elem);
			self.toolbarMouseDownOut(elem);
        }
        self.stopEvent(ev || window.event);
	});
    
	self.addEvent(elem, 'click', function(ev) {
		if (obj.disabled) {
            return false;
        }
		switch (obj.method) {
		case 'doCmd' :
            self.setRange(self.getRange());
			self.doCmd(obj.cmd, null);
			break;
		case 'windowOpen' :
			self.windowOpen(obj.cmd);
			break;
		case 'showPulldown' :
			if (obj.checked) {
				obj.checked = false;
				self.boxHideAll();
			}
			else {
				obj.checked = true;
				self.showPulldown(obj.cmd, obj.button);
			}

			if (obj.checked) {
				self.toolbarButtonChecked(elem);
				self.toolbarMouseDownOut(elem);
			}
			break;
		default :
            alert('지원하지 않는 명령입니다.');
		}
        self.stopEvent(ev || window.event);
	});
    
	self.addEvent(elem, 'mouseout', function() {
		if (obj.checked === false) {
			self.toolbarButtonUnchecked(elem);
			self.toolbarMouseDownOut(elem);
		}
	});

	return elem;
},

showToolbar : function (toolbar, toolbarWrapper) {
	var self = this;
	toolbarWrapper.className = 'cheditor-tb-wrapper';

	var toolbarIcon = toolbar.getElementsByTagName('Image').item(0).getAttribute('file');
	var group = toolbar.getElementsByTagName('Group');
	var grpNum = group.length;
	var tmpArr = toolbarIcon.split(/\./);
	self.toolbar.icon = self.config.iconPath + toolbarIcon;
	self.toolbar.iconDisable = self.config.iconPath + tmpArr[0] + '-disable' + '.' + tmpArr[1];

	var appendSpaceBlock = function(pNode) {
		var div = document.createElement('div');
		div.className = 'cheditor-tb-split';
		pNode.appendChild(div);
	};

	for (var i=0; i < grpNum; i++) {
		var grpName = group[i].getAttribute('name');
		if (grpName == 'Split') {
			 appendSpaceBlock(toolbarWrapper);
			 continue;
		}

		var btn = group[i].getElementsByTagName('Button');
		var btnNum = btn.length;
		var grpDiv = document.createElement('div');

		for (var j=0; j < btnNum; j++) {
			var attr = btn[j].getElementsByTagName('Attribute')[0];
			var btnName = btn[j].getAttribute('name');

			if (self.config['use'+btnName] != true) {
				continue;
			}

			var btnIcon = document.createElement('div');
			var btnElem = self.createButton(btnName, attr, grpName);
			var icon = attr.getElementsByTagName('Icon')[0];

			btnIcon.className = icon.getAttribute('class');

			if (btn[j].getAttribute('tooltip') !== null) {
				btnElem.setAttribute('title', btn[j].getAttribute('tooltip'));
			}

			var pos = icon.getAttribute('position');
			if (pos !== null) {
				btnIcon.style.backgroundImage = 'url('+self.toolbar.icon+')';
				btnIcon.style.backgroundRepeat = 'no-repeat';
				self.setToolbarBgPosition(btnIcon, (~pos + 1) + 'px center');
			}
			else {
				var txt = icon.getAttribute('alt');
				if (txt !== null) {
                    var span = document.createElement('span');
                    span.appendChild(document.createTextNode(txt));
					btnIcon.appendChild(span);
				}
			}

			btnElem.appendChild(btnIcon);

			if (btnName == 'ForeColor' || btnName == 'BackColor') {
				var currentColor = document.createElement('div');
				currentColor.className = 'cheditor-tb-color-btn';
				currentColor.style.backgroundColor = attr.getAttribute('default');
				btnElem.appendChild(currentColor);
			}

			grpDiv.appendChild(btnElem);
		}

		if (grpDiv.hasChildNodes() == false)
			continue;

		if (grpDiv.childNodes.length > 1) {
			grpDiv.firstChild.className = grpDiv.firstChild.className + '-first';
			grpDiv.lastChild.className = grpDiv.lastChild.className + '-last';
			while (grpDiv.hasChildNodes()) {
				toolbarWrapper.appendChild(grpDiv.firstChild);
			}
		}
		else {
			if (grpDiv.firstChild.className == 'cheditor-tb-bg') {
				grpDiv.firstChild.className = grpDiv.firstChild.className + '-single';
			}
			toolbarWrapper.appendChild(grpDiv.firstChild);
		}

		var spacer = document.createElement('div');
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
	if (!container.hasChildNodes())
		return;

	var child = container.firstChild;
	var self = this;
	
	do {
		var id = child.getAttribute('id');
		switch (id) {
		case 'toolbar' :
			this.showToolbar(toolbar, child);
			this.cheditor.toolbarWrapper = child;
			break;
		case 'viewMode' :
			this.cheditor[id] = child;
			this.cheditor.mode = 'rich';
			if (child.hasChildNodes()) {
				var tab = child.childNodes;
				this.cheditor.modetab = {};

				for (var i=0; i<tab.length; i++) {
					var tabId = tab[i].getAttribute('id');
					if (!tabId) continue;
					if ((tabId == 'code' && this.config.useSource == false) ||
						(tabId == 'preview' && this.config.usePreview == false) ||
						(tabId == 'fullscreen' && this.config.useFullScreen == false))
					{
						tab[i].style.display = 'none';
						tab[i].removeAttribute('id');
						continue;
					}

					if (tabId == 'fullscreen') {
						tab[i].onclick = function() {self.fullScreenMode();};
					}
					else {
						tab[i].setAttribute('mode', tabId);
						tab[i].onclick = function() {self.switchEditorMode(this.getAttribute('mode'));};
						this.cheditor.modetab[tabId] = tab[i];
					}

					tab[i].removeAttribute('id');
				}
				this.unselectionElement(child);
			}
			break;
		case 'editWrapper' :
			var editArea = child.getElementsByTagName('IFRAME').item(0);
			editArea.style.height = this.config.editorHeight;

			if (isNaN(this.config.tagIndex) == false) {
				editArea.tabIndex = this.config.tabIndex;
			}

			this.cheditor.editArea = editArea;
			this.cheditor[id] = child;
			break;
		case 'modifyBlock' :
			this.cheditor.editBlock = child;
			break;
		case 'tagPath' :
			if (this.config.showTagPath) {
				this.cheditor.tagPath = child.firstChild;
				child.style.display = 'block';
			}
			break;
		case 'resizeBar' :
			this.cheditor.resizeBar = child;
			child.onmousedown = function(ev) { self.resizeStart(ev); };
			this.unselectionElement(child);
			break;
		default : break;
		}

		child.removeAttribute('id');
		child = child.nextSibling;
	}
	while (child);

	var pNode = this.cheditor.textarea.parentNode,
		nNode = this.cheditor.textarea.nextSibling;

	if (!nNode) pNode.appendChild(container);
	else pNode.insertBefore(container, nNode);

    var done = false;
    self = this;
    function ready() {
        if (done) return;
        done = true;
        self.cheditor.toolbarWrapperHeight = self.cheditor.toolbarWrapper.offsetHeight;
    }

	if (GB.browser.msie) {
		var isFrame = false;
        try {
            isFrame = window.frameElement != null;
        } catch(e) {}

        if (document.documentElement.doScroll && !isFrame) {
            function tryScroll() {
                if (done) return;
                try {
                    document.documentElement.doScroll("left");
                    ready();
                } catch(e) {
                    setTimeout(tryScroll, 10);
                }
            }
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

/*	if (isNaN(self.cheditor.toolbarWrapperHeight) || self.cheditor.toolbarWrapperHeight == 0) {
		self.cheditor.toolbarWrapperHeight = 58;
	}*/
	
	container.style.width = this.config.editorWidth;
	this.cheditor.container = container;
},

loadTemplate : function (xmlDoc) {
	var self = this;
	var tmpl = xmlDoc.getElementsByTagName('Template').item(0);
	var toolbar = tmpl.getElementsByTagName('Toolbar').item(0);

	if (tmpl.getElementsByTagName('Image').item(0).getAttribute('file') == null)
		throw '툴바 아이콘 이미지 파일 이름이 정의되지 않았습니다.';

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
		if (action == false) {
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

		if (action == false) {
			self.removeEvent(img, 'click', imgev);
			return;
		}

		this.addEvent(img, 'click', imgev);
	}
},

setImageEvent : function (action) {
	var images = this.doc.images;
	var len = images.length;

	for (var i=0; i<len; i++) {
		this.imageEvent(images[i], action);
	}
},

run : function () {
	this.setFolderPath();

	try { this.checkInputForm(); }
	catch(e) {
		alert(e.toString());
		return;
	}

	this.setDefaultCss({cssName: 'ui.css', doc: window.document});
	this.initTemplate();

	if (!(this.resetDoc()))
        return;

	this.editArea.focus();
	this.setEditorEvent();
	this.setDefaultCss();
	this.toolbarUpdate();
	this.setImageEvent(true);
},

fullScreenMode : function () {
	var self = this;
	self.editArea.focus();
	self.boxHideAll();

	var container = self.cheditor.container;
	self.cheditor.editArea.style.visibility = 'hidden';

	if (self.setFullScreenMode == false) {
		container.className = 'cheditor-container-fullscreen';
		container.style.width = '100%';

		if (GB.browser.msie && GB.browser.ver < 7) {
			self.cheditor.fullScreenFlag = document.createElement('span');
			self.cheditor.fullScreenFlag.style.display = 'none';
			container.parentNode.insertBefore(self.cheditor.fullScreenFlag, container);
			document.body.insertBefore(container, document.body.firstChild);
		}

		var containerReSize = function () {
			var editorHeight = self.cheditor.resizeBar.offsetHeight +
				self.cheditor.viewMode.offsetHeight + 6;

			if (self.cheditor.mode == 'rich') editorHeight += self.cheditor.toolbarWrapperHeight;
			if (self.config.showTagPath) editorHeight += self.cheditor.tagPath.parentNode.offsetHeight;

			self.cheditor.editArea.style.height = self.getWindowSize().height - editorHeight + 'px';
		};

		window.onresize = containerReSize;
		containerReSize();

		self.cheditor.resizeBar.onmousedown = null;
		self.cheditor.resizeBar.style.cursor = 'default';
	}
	else {
		window.onresize = null;
		container.removeAttribute('style');
		container.className = 'cheditor-container';
		container.style.width = self.config.editorWidth;

		var editorHeight = parseInt(self.config.editorHeight);
		if (self.cheditor.mode != 'rich') {
			editorHeight += self.cheditor.toolbarWrapperHeight;
		}

		self.cheditor.editArea.style.height = editorHeight + 'px';
		self.cheditor.resizeBar.onmousedown = function(event) { self.resizeStart(event); };
		self.cheditor.resizeBar.style.cursor = 's-resize';

		if (GB.browser.msie && GB.browser.ver < 7) {
			self.cheditor.fullScreenFlag.parentNode.replaceChild(container, self.cheditor.fullScreenFlag);
		}
	}

	self.cheditor.editArea.style.visibility = 'visible';
	self.setFullScreenMode = !(self.setFullScreenMode);
	self.editArea.focus();
},

showPulldown : function (menu, pNode) {
	switch (menu) {
	case 'FontName' :
		this.showFontTypeMenu(pNode);
		break;
	case 'FontSize' :
		this.showFontSizeMenu(pNode);
		break;
	case 'Paragraph' :
		this.showParagraphMenu(pNode);
		break;
	case 'BackColor' :
        this.showColorMenu(pNode);
        break;
	case 'ForeColor' :
		this.showColorMenu(pNode);
		break;
	case 'TextBlock' :
		this.showTextBlockMenu(pNode);
		break;
	default : break;
	}
},

setPulldownClassName : function (labels, pNode) {
	for (var i=0; i < labels.length; i++) {
		if (labels[i].getAttribute('name') === pNode.firstChild.firstChild.firstChild.nodeValue) {
			labels[i].parentNode.style.backgroundImage = 'url('+this.config.editorPath+'icons/checked.png)';
			labels[i].parentNode.style.backgroundPosition = '0 center';
			labels[i].parentNode.style.backgroundRepeat = 'no-repeat';
		}
		else
			labels[i].parentNode.style.backgroundImage = '';

		labels[i].parentNode.className = 'cheditor-pulldown-mouseout';
	}
},

showColorMenu : function (pNode) {
	var menu = pNode.getAttribute('name');
	var elem = this.pulldown[menu];

	if (!elem) {
		var outputHtml = this.setColorTable(pNode, menu);
		this.createWindow(190, outputHtml);
		this.createPulldownFrame(outputHtml, menu);
		elem = this.pulldown[menu];
		elem.firstChild.className = 'cheditor-pulldown-color-container';
	}

	this.windowPos(pNode, menu);
	this.displayWindow(pNode, menu);
},

showFontTypeMenu : function (pNode) {
	var self = this;
	var menu = pNode.getAttribute('name');
	var elem = self.pulldown[menu];

	if (!elem) {
        var fonts = null;
		var outputHtml = self.doc.createElement('div');
		for (var type in GB.fontName) {
            if (GB.fontName.hasOwnProperty(type)) {
                fonts = GB.fontName[type];
			    for (var i=0; i < fonts.length; i++) {
				    var div = self.doc.createElement('div');
				    var label = self.doc.createElement('label');
				    div.id = fonts[i];
				    div.onclick = function() { self.doCmdPopup("FontName", this.id); };
				    div.onmouseover = function() { self.pulldownMouseOver(this); };
				    div.onmouseout = function() { self.pulldownMouseOut(this); };
				    label.style.fontFamily = fonts[i];//(type !== 'kr') ? fonts[i] : this.config.editorFontName;
				    label.appendChild(self.doc.createTextNode(fonts[i]));
				    label.setAttribute('name', fonts[i]);
				    div.appendChild(label);
				    outputHtml.appendChild(div);
			    }
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

showParagraphMenu : function (pNode) {
	var self = this;
	var menu = pNode.getAttribute('name');
	var elem = self.pulldown[menu];

	if (!elem) {
		var outputHtml = document.createElement('div');
		for (var para in GB.paragraph) {
            if (GB.paragraph.hasOwnProperty(para)) {
			    var div = document.createElement('div');
			    div.id = para;
			    div.onclick = function() { self.doCmdPopup("FormatBlock", '<' + this.id + '>'); };
			    div.onmouseover = function() { self.pulldownMouseOver(this); };
			    div.onmouseout = function() { self.pulldownMouseOut(this); };

			    var label = document.createElement('label');
			    if (para.match(/H[123456]/)) {
			    	var fontSize = {'H1':'2em','H2':'1.5em','H3':'1.17em','H4':'1em','H5':'0.83em','H6':'0.75em'};
			    	label.style.fontWeight = 'bold';
			    	label.style.fontSize = fontSize[para];
                    label.style.lineHeight = 1.4;
			    }
			    else if (para === 'ADDRESS') {
			    	label.style.fontStyle = 'italic';
                }

			    label.appendChild(document.createTextNode(GB.paragraph[para]));
			    div.appendChild(label);

			    label.setAttribute('name', GB.paragraph[para]);
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
        var outputHtml = document.createElement('div');
        for (var size in GB.fontSize) {
            if (GB.fontSize[size] == null) break;
            var div = document.createElement('div');
            var label = document.createElement('label');
            var text = size == 7 ? '가나다' : '가나다라';
            
            div.id = size;
            div.onclick = function() { self.doCmdPopup("FontSize", this.id); };
            div.onmouseover = function() { self.pulldownMouseOver(this); };
            div.onmouseout = function() { self.pulldownMouseOut(this); };
            div.style.fontSize = GB.fontSize[size];

            label.style.fontFamily = this.config.editorFontName;
            label.setAttribute('name', GB.fontSize[size]);
            label.appendChild(document.createTextNode(text+'('+GB.fontSize[size] +')'));
            div.appendChild(label);
            outputHtml.appendChild(div);
        }
        self.createWindow(320, outputHtml);
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
		var outputHtml = document.createElement('div');
		var quote = GB.textBlock;

		for (var i=0; i < quote.length; i++) {
			var wrapper = document.createElement('div');
			var div = document.createElement('div');
			div.onclick = function() { self.boxStyle(this); };
			wrapper.onmouseover = function() { this.className = 'cheditor-pulldown-textblock-over'; };
			wrapper.onmouseout = function() { this.className = 'cheditor-pulldown-textblock-out'; };
			wrapper.className = 'cheditor-pulldown-textblock-out';
			div.style.border = quote[i][0];
			div.style.backgroundColor = quote[i][1];
			div.style.fontFamily = self.config.editorFontName;

			var label = document.createElement('label');
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
	if (arguments.length == 0) {
		ar = {cssName: 'ui.css', doc: this.doc};
		//if (GB.browser.msie || GB.browser.opera) {
			ar = {cssName: 'editarea.css', doc: this.doc};
		//}
	}

	var cssFile = this.config.cssPath + ar.cssName,
		head = ar.doc.getElementsByTagName('head')[0],
		found = false;

	if (typeof head == 'undefined')
		return;

	if (head.hasChildNodes()) {
		var child = head.childNodes;
		for (var i = 0; i < child.length; i++) {
			if (child[i].tagName == 'LINK') {
				var href = child[i].getAttribute('href');
				if (href != null && href == cssFile) {
					found = true;
					break;
				}
			}
		}
	}

	if (found == false) {
		var css = head.appendChild(ar.doc.createElement('link'));
		css.setAttribute('type', 'text/css');
		css.setAttribute('rel', 'stylesheet');
		css.setAttribute('media', 'all');
		css.setAttribute('href', this.config.cssPath + ar.cssName);
	}
},

setEditorEvent : function () {
	var self = this;
    
    var keyArrow = function(event) { self.doOnArrowKeyPress(event); };
    self.addEvent(self.doc, "keydown", keyArrow);    
    
	var keyPress = function(event) { self.doOnKeyPress(event); };
	self.addEvent(self.doc, "keypress", keyPress);

	var editorEvent = function() { self.doEditorEvent(); };
	self.addEvent(self.doc, "mouseup", editorEvent);

	var hideBox = function() { self.boxHideAll(); };
	self.addEvent(self.doc, "mousedown", hideBox);
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
        elem.detachEvent("on"+ev, func);
    }
},

stopEvent : function (ev) {
    if (ev.preventDefault) {
        ev.preventDefault();
        ev.stopPropagation();
    }
    else {
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

changeFontColor : function (color, type) {
	if (type == 'BackColor' && !GB.browser.msie) {
		type = 'HiliteColor';
	}
	this.doCmdPopup(type, this.colorConvert(color, 'hex'));
},

getElement : function (elem, tag) {
    if (!elem || !tag)
        return null;
    
	while (elem !== null && elem.nodeName.toLowerCase() !== tag.toLowerCase()) {
		if (elem.nodeName.toLowerCase() === 'body') break;
		elem = elem.parentNode;
	}
	return elem;
},

hyperLink: function (href, target, title) {
    var self = this;
	self.editArea.focus();
    var links = null;
    var createLinks = function() {
        var range = null, selectedLinks = [], selection = null;
        var container = null, sType = null, i=0, k=0;
        
        range = self.restoreRange();
        linkRange = self.createRange();
        
        if (self.W3CRange) {
            self.doc.execCommand("CreateLink", false, href);
            
            selection = self.getSelection();
            for (; i<selection.rangeCount; ++i) {
                range = selection.getRangeAt(i);
                container = range.commonAncestorContainer;
                sType = self.getSelectionType(range);
                if (sType === GB.selection.SELECTION_TEXT) {
                    container = container.parentNode;
                }
                if (container.nodeName.toLowerCase() === 'a') {
                    selectedLinks.push(container);
                }
                else {
                    links = container.getElementsByTagName('a');
                    for (; k<links.length; ++k) {
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
            
            sType = self.getSelectionType(range);
            switch (sType) {
            case GB.selection.SELECTION_TEXT :
                container = range.parentElement();
                break;
            case GB.selection.SELECTION_ELEMENT :
                container = range.item(0).parentNode;
                break;
            default : return null;
            }
     
            if (container.nodeName.toLowerCase() === 'a') {
                selectedLinks.push(container);
            }
            else {
                links = container.getElementsByTagName('a');
                for (; i<links.length; ++i) {
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
        for (i=0; i<links.length; ++i) {
            if (target) {
                links[i].setAttribute("target", target);
            }
            if (title) {
                links[i].setAttribute("title", title);
            }
        }
    }
},

boxStyle: function (el) {
	this.editArea.focus();
	var range = this.range || this.getRange();
	var quote = this.doc.createElement("blockquote");

	if (el.currentStyle) {
		quote.style.border = el.currentStyle['border-left-style'];
        quote.style.borderWidth = el.currentStyle['border-left-width'];
        quote.style.borderColor = el.currentStyle['border-left-color'];
        quote.style.backgroundColor = el.currentStyle.backgroundColor;
	}
	else if (window.getComputedStyle) {
		var compStyle = document.defaultView.getComputedStyle(el, null);
		quote.style.border = compStyle.getPropertyValue('border-top-style');
		quote.style.borderWidth = compStyle.getPropertyValue('border-top-width');
		quote.style.borderColor = compStyle.getPropertyValue('border-top-color');
		quote.style.backgroundColor = compStyle.getPropertyValue("background-color");
	}
	else {
		alert('현재 브라우저는 이 기능을 지원하지 않습니다.');
		return;
	}

	quote.style.padding = "5px 10px";

	if (!this.W3CRange) {
		var ctx = range.htmlText, pNode = null;
		quote.innerHTML = ctx ? ctx: '\u00a0';
        range.select();
		this.insertHTML(quote);
        var textRange = this.getRange();
        textRange.moveToElementText(range.parentElement());
        textRange.collapse(false);
		textRange.select();
	}
	else {
        this.setRange(range);
		quote.appendChild((range.toString().length > 0) ? range.extractContents() : this.doc.createElement('br'));
        range.insertNode(quote);
        
        var nNode = quote.nextSibling;
        if (nNode === null) {
            quote.parentNode.appendChild(this.doc.createElement('br'));
        }
        else if (nNode.nodeType === GB.node.TEXT_NODE && nNode.nodeValue === '') {
            quote.parentNode.insertBefore(this.doc.createElement('br'), nNode);
        }
        this.selectNodeContents(quote, 0);
	}
    
	this.boxHideAll();
},

insertFlash: function (elem) {
	this.editArea.focus();
    this.restoreRange();
    
	if (typeof elem === 'string') {
		var embed = null;
		var div = this.doc.createElement('DIV');
		elem = this.trimSpace(elem);

		var pos = elem.toLowerCase().indexOf("embed");
		if (pos != -1) {
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
				var movieHeight, movieWidth;
				movieWidth  = (isNaN(object.width) != true) ? object.width : 320;
				movieHeight = (isNaN(object.height)!= true) ? object.height: 240;
				var params = [];

				do {
					if ((child.nodeName == 'PARAM') &&  (typeof child.name != 'undefined') && (typeof child.value != 'undefined'))
					{
						params.push({key: (child.name == 'movie') ? 'src' : child.name, val: child.value});
					}
					child = child.nextSibling;
				}
				while (child);

				if (params.length > 0) {
					embed = this.doc.createElement('embed');
					embed.setAttribute("width", movieWidth);
					embed.setAttribute("height", movieHeight);

					for (var i=0; i<params.length; i++)
						embed.setAttribute(params[i].key, params[i].val);

					embed.setAttribute("type", "application/x-shockwave-flash");
				}
			}
		}

		if (embed != null) {
			if (this.W3CRange) {
                this.insertNodeAtSelection(embed);
				
			}
			else {
				this.doCmdPaste(embed.outerHTML);
			}
		}
	}
	this.editArea.focus();
},

insertHtmlPopup: function (elem) {
    this.editArea.focus();
    this.restoreRange();
    
    if (!this.W3CRange) {
        if (typeof elem === 'string') {
            this.doCmdPaste(elem);
        }
        else {
            this.doCmdPaste(elem.outerHTML);
        }
    }
    else {
        if (typeof elem === 'string') {
            var div = this.doc.createElement('div');
            div.innerHTML = elem;
			if (div.hasChildNodes) {
            	this.insertNodeAtSelection(div.firstChild);
			}
        }
        else {
            this.insertNodeAtSelection(elem);
        }
    }

	this.clearStoredSelections();
    this.editArea.focus();
},

insertHTML: function (html) {
	if (!this.W3CRange) {
		this.getRange().pasteHTML((typeof(html) === 'string') ? html : html.outerHTML);
	}
	else {
		var div = this.doc.createElement('div');
		if (typeof html === 'string') div.innerHTML = html;
		else div.appendChild(html);
		this.insertNodeAtSelection(div.firstChild);
	}
},

insertNodeAtSelection: function (insertNode) {
	var selection = this.getSelection();
	var range = this.getRange();

	selection.removeAllRanges();
	range.deleteContents();

	var container = range.startContainer;
	var pos = range.startOffset;

	if (container.nodeType == GB.node.TEXT_NODE && insertNode.nodeType == GB.node.TEXT_NODE) {
		container.insertData(pos, insertNode.data);
		try {
			range.setEnd(container, pos + insertNode.length);
			range.setStart(container, pos + insertNode.length);
			selection.addRange(range);
		}
		catch (e) {}
	}
	else {
		var afterNode, beforeNode, isNode;
		isNode = insertNode;

		if (container.nodeType === GB.node.TEXT_NODE) {
			var textNode    = container;
			var text        = textNode.nodeValue;
			var textBefore  = text.substr(0, pos);
			var textAfter   = text.substr(pos);

			container   = textNode.parentNode;
			beforeNode  = this.doc.createTextNode(textBefore);
			afterNode   = this.doc.createTextNode(textAfter);

			container.insertBefore(afterNode, textNode);
			container.insertBefore(insertNode, afterNode);
			container.insertBefore(beforeNode, insertNode);
			container.removeChild(textNode);
            
			this.toolbarUpdate();
			this.selectNodeContents(isNode, 0);
		}
		else {
			afterNode = container.childNodes[pos];
            if (typeof afterNode === 'undefined') {
                container.appendChild(insertNode);
            }
            else {
                container.insertBefore(insertNode, afterNode);
            }
			
            this.toolbarUpdate();
            
			if (insertNode.nodeType == GB.node.TEXT_NODE) {
				range.setEnd(container, pos + insertNode.length);
				range.setStart(container, pos + insertNode.length);
				selection.addRange(range);
			}
			else {
				this.selectNodeContents(isNode, 0);
			}
		}
	}
},

selectNodeContents : function (node, pos) {
	var collapsed = (typeof pos !== 'undefined');
	var selection = this.getSelection();
	var range = this.getRange();

	if (node.nodeType === GB.node.ELEMENT_NODE) {
		range.selectNode(node);
        if (collapsed) {
            range.collapse(pos);
        }
	}

	selection.removeAllRanges();
	selection.addRange(range);
	return range;
},

doInsertImage : function (images) {
	this.editArea.focus();
	var range = this.restoreRange();
	var len = images.length, tmpWrapper, br;
	if (len < 1)
		return;

	tmpWrapper = this.doc.createElement('div');

	for (var i=0; i < images.length; i++) {
		var attr = images[i];
		var img = new Image();
		img.setAttribute('src', attr.src);
		img.setAttribute('width', attr.width);
		img.setAttribute('height', attr.height);
		img.setAttribute('alt', attr.alt ? attr.alt : attr.info.origName);
		img.style.border = 'none';

		this.resizeImage(img);

		var div = this.doc.createElement('div');
		div.style.textAlign = attr.align;
		div.appendChild(img);

		if (attr.align === 'left' || attr.align === 'right') {
			var cssFloat = GB.browser.msie ? img.style.styleFloat : img.style.cssFloat;
			if (cssFloat)
				cssFloat = attr.align;
		}

		br = this.doc.createElement('div');
		br.appendChild(this.doc.createTextNode('\u00a0'));

		if (!this.W3CRange) {
			tmpWrapper.appendChild(div);
			tmpWrapper.appendChild(br);
		}
		else {
			range.insertNode(div);
			range = this.selectNodeContents(div, 0);
			div.parentNode.insertBefore(br, div);
			div.parentNode.insertBefore(div, br);
		}

		if (typeof attr.info !== 'undefined') {
			this.images.push(attr.info);
		}
	}

	if (!this.W3CRange) {
		range.pasteHTML(tmpWrapper.innerHTML);
	}
	else {
		this.selectNodeContents(br, 0);
	}

	this.setImageEvent(true);
},

resizeImage : function (img) {
	if (this.config.imgReSize && this.resizeImageComplete(img)) {
		var szRandom = Math.random();
		var imgId = 'image_'+szRandom;
		imgId = imgId.replace(/\./,'');
		img.setAttribute('id', imgId);
		img.className = 'chimg_photo';
	}
},

resizeImageComplete : function (img) {
	var maxWidth = isNaN(this.config.imgMaxWidth) ? 640 : this.config.imgMaxWidth;
	if (img.getAttribute('width') <= maxWidth)
		return false;

	img.setAttribute('height', ''+Math.round((img.height * maxWidth) / img.width));
	img.setAttribute('width', ''+maxWidth);
	return true;
},

showTagSelector : function (on) {
	if (this.config.showTagPath != true) return;
	this.cheditor.tagPath.style.display = on ? 'block' : 'none';
},

richMode : function () {
	this.range = null;
	this.cheditor.editArea.style.visibility = 'hidden';
	this.setDesignMode(false);
	this.cheditor.toolbarWrapper.style.display = '';

	if (this.cheditor.mode == 'code') {
		this.putContents(this.makeHtmlContent());
	}

	var tmpHeight = this.cheditor.editArea.offsetHeight - this.cheditor.toolbarWrapper.offsetHeight;
	this.cheditor.editArea.style.height = tmpHeight + 'px';
	this.cheditor.editArea.style.visibility = 'visible';
	this.setDesignMode(true);
	this.editArea.focus();
	this.setImageEvent(true);
	this.toolbarUpdate();
},

editMode : function () {
	this.range = null;
	this.cheditor.editArea.style.visibility = 'hidden';
	this.cheditor.editBlock.style.display = 'none';
	
	var content = this.getContents(this.config.fullHTMLSource);
	var key = GB.htmlKey.join ("|");
	var reg1 = new RegExp ("(&lt;\/?)("+key+")( ?\/?)(&gt;)", "ig");
	var reg2 = new RegExp ("(&lt;)("+key+") +([a-zA-Z]+)=(.+?)(\/?)(&gt;)", "ig");
	content = content.replace(/</g,'&lt;').replace(/>/g,'&gt;');
	content = content.replace(/&nbsp;/g, '&amp;nbsp;');
	content = content.replace(reg2, "<span style=\"color:#0000c8\">$1$2</span> <span style=\"color:#b40000\">$3</span>=<span style=\"color:#248f00\">$4</span><span style=\"color:#0000c8\">$5$6</span>");
	content = content.replace(reg1, "<span style=\"color:#0000cc\">$1$2$3$4</span>");
	content = content.replace(/\n/g, '<br />');

	this.doc.body.innerHTML = content;

	var tmpHeight = this.cheditor.editArea.offsetHeight + this.cheditor.toolbarWrapper.offsetHeight;
	this.cheditor.toolbarWrapper.style.display = "none";
	this.cheditor.editArea.style.height = tmpHeight + 'px';
	this.cheditor.editArea.style.visibility = 'visible';
	this.setDesignMode(true);
	this.editArea.focus();
},

makeHtmlContent : function () {
	if (GB.browser.msie) {
		return this.doc.body.innerText;
	}

	var html = this.doc.createRange();
	html.selectNodeContents(this.doc.body);

	return html.toString();
},

resetStatusBar : function () {
	if (this.config.showTagPath)
		this.cheditor.tagPath.innerHTML = '&lt;html&gt; &lt;body&gt; ';
},

previewMode : function () {
	this.range = null;

	if (this.config.useSource && this.cheditor.mode == 'code') {
		this.putContents(this.makeHtmlContent());
	}

	this.cheditor.editArea.style.visibility = 'hidden';
	this.cheditor.editBlock.style.display = 'none';

	var tmpHeight = this.cheditor.editArea.offsetHeight;
	if (this.cheditor.mode == 'rich')
		tmpHeight += this.cheditor.toolbarWrapper.offsetHeight;

	this.cheditor.toolbarWrapper.style.display = "none";
	this.cheditor.editArea.style.height =  tmpHeight+'px';
	this.cheditor.editArea.style.visibility = 'visible';
	this.setImageEvent(false);
	this.setDesignMode(false);
},

putContents : function (content) {
	if (this.config.fullHTMLSource) {
		if (this.config.outputXhtml) {
			content = content.substr(content.search(/<html/ig) + 1);
    		content = content.substr(content.indexOf('>') + 1);
    		content = '<html>' + content;
		}
		
		this.doc.open();
		this.doc.write(content);
		this.doc.close();
	}
	else {
		this.doc.body.innerHTML = content;
	}
},

getImages : function () {
	var img = this.doc.body.getElementsByTagName('img');
	var imgNumber = this.images.length;
	var imgArr = [];

	for (var i=0; i<img.length; i++) {
		if (img[i].src) {
			var imgid = img[i].src;
			imgid = imgid.slice(imgid.lastIndexOf("/") + 1);
			for (var j=0; j<imgNumber; j++) {
				if (this.images[j]['fileName'] == imgid) {
					imgArr.push(this.images[j]);
					break;
				}
			}
		}
	}
	return imgArr.length > 0 ? imgArr : null;
},

xhtmlParse : function (node, lang, encoding, needNewLine, inside_pre) {
	var i;
	var xhtmlText = '';
	var children = node.childNodes;
	var len = children.length;
	var newLine = needNewLine ? true : false;
	var mediaAlign = '';

	for (i = 0; i < len; i++) {
		var child = children[i];

		switch (child.nodeType) {
			case 1: {
				var elemName = String(child.tagName).toLowerCase();
				if (/^\W/.test(elemName) || elemName == '') break;
				
				if (elemName == 'meta') {
					var meta_name = String(child.name).toLowerCase();
					if (meta_name == 'generator') break;
				}

				if (elemName == 'html') {
					xhtmlText = '<?xml version="1.0" encoding="' + encoding + '"?>\n<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\n';
				}

				if (GB.browser.msie) {
					if (elemName == 'object' && !child.hasChildNodes()) {
						xhtmlText += this.replaceObjectCode(child.outerHTML);
						continue;
					}
					
					if (elemName == 'embed') {
						var parameter = /align=("[^"]*"|'[^']*'|[^"'\s]*)(\s|>)/i;
						var align_code = child.outerHTML.match(parameter);
						if (align_code) {
							align_code = align_code[1];
							mediaAlign = align_code.replace(/("|')/g, "");
						}
					}
				}

				if (GB.XhtmlNewLineBefore.indexOf('|' + elemName + '|') != -1) {
					if ((newLine || xhtmlText != '') && !inside_pre) xhtmlText += '\n';
					else newLine = true;
				}

				xhtmlText += '<' + elemName;

				var attr = child.attributes;
				var attrLength = attr.length;
				var attrValue = '';
				var attrLang = false;
				var attrXmlLang = false;
				var attrXmlns = false;
				var isAltAttr = false;

				for (var j = 0; j < attrLength; j++) {
					var attrName = attr[j].nodeName.toLowerCase();

					if (attrName == 'reparsed_attr') continue;
					if (!attr[j].specified && attrName != 'selected' && attrName != 'style' && attrName != 'value' &&
						attrName != 'shape' && attrName != 'coords') 
					{
						continue;
					}
					if ((attrName == 'shape' || attrName == 'coords') && elemName != 'area') continue;
					if (attrName == 'selected' && !child.selected || attrName == 'style' && child.style.cssText == '') continue;
					if (attrName == '_moz_dirty' || attrName == '_moz_resizing' || attrName == '_moz-userdefined' ||
						attrName == '_moz_editor_bogus_node' || (elemName == 'br' && attrName == 'type' &&
						child.getAttribute('type') == '_moz'))
					{
						continue;
					}
					if (elemName == 'img' && attrName == 'complete') continue;

					var validAttr = true;
					
					switch (attrName) {
						case "style" :
							var cssText = child.style.cssText.split(';');
							var reMake = [];
							var attrLen = cssText.length;
							
							for (var ct=0; ct < attrLen; ct++) {
								var kv = cssText[ct].split(':');
								if (kv[0] != '' && typeof kv[1] != 'undefined')
									reMake.push(kv[0].toLowerCase() + ':' + kv[1]);
							}
							
							attrValue = reMake.join(';');
							break;
						case "class" :
							attrValue = child.className;
							break;
						case "http-equiv":
							attrValue = child.httpEquiv;
							break;
						case "noshade":
						case "checked":
						case "selected":
						case "multiple":
						case "nowrap":
						case "disabled":
							attrValue = attrName;
							break;
						default:
							try {
								attrValue = child.getAttribute(attrName);
							}
							catch (e) {
								validAttr = false;
							}
					}

					if (elemName == 'embed') {
						switch (attrName) {
							case 'align':
								if (mediaAlign) attrValue = mediaAlign;
									else attrValue = eval('child.' + attrName);
								break;
							case 'showstatusbar':
							case 'showcontrols':
							case 'autostart':
							case 'type':
								attrValue = attr[j].nodeValue;
								break;
							default:
								break;
						}
					}

					if (attrName == 'lang' && elemName == 'html') {
						attrLang = true;
						attrValue = lang;
					}

					if (attrName == 'xml:lang') {
						attrXmlLang = true;
						attrValue = lang;
					}

					if (attrName == 'xmlns') attrXmlns = true;

					if (elemName == 'object' && attrName == 'src' && GB.browser.msie) {
						attrValue = this.fixObjectSrc(child.outerHTML);
					}

					if (validAttr) {
						if (!(elemName == 'li' && attrName == 'value')) {
							xhtmlText += ' ' + attrName + "=";
							xhtmlText += (/"/.test(attrValue)) ? "'" + this.fix_attribute(attrValue) + "'" : '"' + attrValue + '"';
						}
					}

					if (attrName == 'alt') isAltAttr = true;
				}

				if (elemName == 'img' && !isAltAttr) xhtmlText += ' alt=""';

				if (elemName == 'html') {
					if (!attrLang) xhtmlText += ' lang="' + lang + '"';
					if (!attrXmlLang) xhtmlText += ' xml:lang="' + lang + '"';
					if (!attrXmlns) xhtmlText += ' xmlns="http://www.w3.org/1999/xhtml"';
				}

                if (!GB.cantHaveChildren[elemName] || child.hasChildNodes()) {
                    xhtmlText += '>'; 
                    var innerTxt = '' + this.xhtmlParse(child, lang, encoding, newLine, inside_pre || (elemName == 'pre') ? true : false);

                    switch (elemName) {
                    case 'style' : innerTxt = String(child.innerHTML).replace(/[\n\r]+/g, '\n'); break;
                    case 'script' : innerTxt = child.text; break;
                    default : break;
                    }

                    if (innerTxt) xhtmlText += innerTxt;
                    xhtmlText += '</' + elemName + '>'; 
                }    
                else {
                    xhtmlText += ' />';
                }
				break;
			}
			case 3: {
				var nodeText = child.nodeValue.replace(/^\n/g, '');
				xhtmlText += nodeText.replace(/\n{2,}$/g, '\n').replace(/&/g, "&amp;").replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\u00A0/g, "&nbsp;");
				break;
			}
			default:
				break;
		}
	}
	return xhtmlText;
},

fix_attribute : function (text) {
	return String(text).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;");
},

fixObjectSrc : function (text) {
	var obj = text.match(/<object ([^>]+)>/i);
	if (obj) {
		var value = obj[1].match(/src="([^"]+)"/i);
		if (!value) {
			value = obj[1].match(/src='([^']+)'/i);
			if (!value) value = obj[1].match(/src=([^ ]+)/i);
		}
		if (value) return value[1];
	}
	return '';
},

replaceObjectCode : function (text) {
	var tmpTxt = ''+text;
	tmpTxt = tmpTxt.replace(/ style=/gi, 	' style=');
	tmpTxt = tmpTxt.replace(/ codeBase=/gi, ' codebase=');
	tmpTxt = tmpTxt.replace(/ height=/gi, 	' height=');
	tmpTxt = tmpTxt.replace(/ width=/gi, 	' width=');
	tmpTxt = tmpTxt.replace(/ align=/gi, 	' align=');
	tmpTxt = tmpTxt.replace(/ classid=/gi, 	' classid=');
	tmpTxt = tmpTxt.replace(/ src=/gi, 		' src=');
	tmpTxt = tmpTxt.replace(/ NAME=/gi, 	' name=');
	tmpTxt = tmpTxt.replace(/ VALUE=/gi, 	' value=');
	tmpTxt = tmpTxt.replace(/ quality=/gi, 	' quality=');
	tmpTxt = tmpTxt.replace(/ TYPE=/gi, 	' type=');
	tmpTxt = tmpTxt.replace(/ PLUGINSPAGE=/gi, ' pluginspage=');
	tmpTxt = tmpTxt.replace(/<OBJECT /gi, 	'<object ');
	tmpTxt = tmpTxt.replace(/<\/OBJECT>/gi, '</object>');
	tmpTxt = tmpTxt.replace(/<PARAM /gi, 	'<param ');
	tmpTxt = tmpTxt.replace(/<\/PARAM>/gi, 	'</param>');
	tmpTxt = tmpTxt.replace(/<EMBED /gi, 	'<embed ');
	tmpTxt = tmpTxt.replace(/<\/EMBED>/gi, 	'</embed>');
	return tmpTxt;
},

checkDocLinks : function () {
	var links = this.doc.links;
	var len = links.length;
	var host = location.host;
	this.cheditor.links = [];

	for (var i=0; i < len; i++) {
		if (this.config.includeHostname == false) {
			var href = links[i].href;
			if (href.indexOf(host) != -1) {
				links[i].setAttribute('href', href.substring(href.indexOf(host) + host.length));
			}
		}

		if (this.config.linkTarget != '' && this.config.linkTarget != null) {
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

	for (var i=0; i < len; i++) {
		if (this.config.includeHostname == false) {
			var imgsrc = img[i].src;
			if (imgsrc) {
				if (imgsrc.indexOf(host) != -1) {
					img[i].src = imgsrc.substring(imgsrc.indexOf(host) + host.length);
				}
			}
		}
		if (img[i].style.width) img[i].removeAttribute('width');
		if (img[i].style.height) img[i].removeAttribute('height');
	}
},

getContents : function (fullSource) {
	this.checkDocLinks();
	this.checkDocImages();

	if ((GB.browser.msie || GB.browser.opera) && this.config.ieEnterMode == 'css') {
		var para = this.doc.body.getElementsByTagName('P');
		var len = para.length;
		for (var i=0; i < len; i++) {
			if (para[i].style.cssText.toLowerCase().indexOf("margin") == -1) {
				para[i].style.margin = '0px';
			}
		}
	}

	if (this.config.allowedScript == false) {
		var script = this.doc.body.getElementsByTagName('script');
		var remove = [];

		for (i=0; i < script.length; i++) {
			remove.push(script[i]);
		}

		for (i=0; i < remove.length; i++) {
			remove[i].parentNode.removeChild(remove[i]);
		}
	}

	var mydoc = '';
	
	if (GB.browser.msie) this.doc.body.removeAttribute('contentEditable');
	
	if (fullSource) {
		var content = this.doc.documentElement;
       	if (GB.browser.msie) {
       		mydoc = content.outerHTML;
       	}
       	else {
       		var div = document.createElement('div');
           	div.appendChild(content.cloneNode(true));
           	mydoc = div.innerHTML;
        }
    }
    else {
    	mydoc = this.doc.body.innerHTML;
    }

    if (this.config.outputXhtml) {
	   	var tmpDoc = GB.browser.msie ? this.cheditor.htmlEditable.contentWindow.document : 
	    		this.cheditor.htmlEditable.contentDocument;
	    tmpDoc.open();
	    tmpDoc.write(mydoc);
	    tmpDoc.close();
	    mydoc = this.xhtmlParse(fullSource ? tmpDoc : tmpDoc.body, this.config.xhtmlLang, this.config.xhtmlEncoding);
	}

	if ((GB.browser.msie || GB.browser.opera) && this.config.ieEnterMode == 'div') {
		mydoc = mydoc.replace(/<(\/?)p([^>]*)>/ig,
				function (a, b, c) {
					if (/^\S/.test(c)) return a;
					return '<' + b + 'div' + c + '>';
				});
	}
	
    if (!GB.browser.msie && this.config.colorToHex) {
        mydoc = mydoc.replace(/([color|background\-color]\s*:)\s*rgba?\(\s*(\d+)\s*,\s*(\d+),\s*(\d+)\)/ig,
                function (a, b, c, d, e) { 
                    return b + ' #' + (1 << 24 | c << 16 | d << 8 | e).toString(16).substr(1);
                });  
    }
    
	return this.makeAmpTag(mydoc);
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
	var rdata = '' + (GB.browser.msie ? this.doc.body.innerText : this.doc.body.textContent);
	return this.trimSpace(rdata);
},

returnFalse : function () {
	this.editArea.focus();
	var img = this.doc.images;
	for (var i=0; i<img.length; i++) {
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

trimSpace : function (str) {
	str = str ? '' + str : '';
	return str.replace(/^\s+|\s+$/g, '');
},

makeRandomString : function () {
	var chars = "_-$@!#0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	var len = 32;
	var clen = chars.length;
	var rData = '';
	for (var i=0; i<len; i++) {
		var rnum = Math.floor(Math.random() * clen);
		rData += chars.substring(rnum,rnum+1);
	}
	return rData;
},
        
strLength : function (str) {
	var len = str.length;
	var mbytes = 0;
	var i = 0;

	for (; i<len; i++) {
		var c = str.charCodeAt(i);
		if (c > 128) mbytes++;
	}

	return (len-mbytes) + (mbytes*2);
},

resetViewHTML : function () {
	if (this.cheditor.mode == 'code') {
		this.switchEditorMode('rich');
	}
},

contentsLengthAll : function () {
	return this.strLength(this.outputHTML());
},

contentsLength : function () {
	var content = '' + this.trimSpace(this.outputBodyHTML());
	if (!content || content == "") return 0;
	return this.strLength(content);
},

inputLength : function () {
	var content = this.getBodyText();
	if (content == '') return 0;
	return this.strLength(content);
},

displayWindow : function (pNode, id) {
	this.editArea.focus();
    this.setRange(this.getRange());
	this.boxHideAll(id);

	var pullDown = this.pulldown[id];
	pullDown.style.visibility = "visible";
	pullDown.style.zIndex = 1002;
},

pulldownMouseOver : function (el) {
	if (el.className == 'cheditor-pulldown-selected') return;
	el.className = "cheditor-pulldown-mouseover";
},
pulldownMouseOut  : function (el) {
	if (el.className == 'cheditor-pulldown-selected') return;
	el.className = "cheditor-pulldown-mouseout";
},

windowPos : function (pNode, id) {
	var L = pNode.offsetLeft;
	if (GB.browser.msie && GB.browser.ver > 7) L -= 1;

	var boxEl = this.pulldown[id];
	boxEl.style.left = L + 'px';
	boxEl.style.top  = pNode.offsetTop + parseInt(pNode.style.height) + 1 + 'px';
},

boxHideAll : function (showId) {
	for (var menu in this.pulldown) {
        if (this.pulldown.hasOwnProperty(menu)) {
		    var elem = this.pulldown[menu];
		    if (elem !== null) {
		    	elem.style.visibility = 'hidden';
		    	var ishide = (typeof showId === 'undefined') ? true : menu !== showId;
		    	if (this.toolbar !== null && ishide) {
		    		this.toolbar[menu].checked = false;
		    		this.toolbarButtonUnchecked(this.toolbar[menu].button);
		    	}
		    }
        }
	}
},

createWindow : function (width, div) {
	div.className = 'cheditor-pulldown-container';
	div.style.width = width+'px';
},

setColorTable : function (pNode, menu) {
	var self = this;
	var pulldown = document.createElement('div');
	pulldown.className = 'cheditor-pulldown-color-wrapper';

	var k = 0, h = 70;
	var container = document.createElement('div');
	var selected = document.createElement('div');
	selected.style.backgroundColor = pNode.lastChild.style.backgroundColor;
	var label = document.createElement('label');
	selected.appendChild(label);
	selected.className = 'cheditor-pulldown-color-selected';

	if (label.hasChildNodes())
		label.removeChild(selected.firstChild);

	label.appendChild(document.createTextNode(pNode.lastChild.style.backgroundColor));
	container.appendChild(selected);

	for (var j=0; j < h; j++) {
		var cell = document.createElement('div');

		if (!(j+1 % 10)) {
			cell.style.clear = 'both';
			container.appendChild(cell);
			h++;
			continue;
		}

		cell.className = 'cheditor-pulldown-color-cell';
		cell.style.backgroundColor = GB.colors[k++];
		cell.onmouseover = function() {
			this.className = 'cheditor-pulldown-color-over';
			selected.style.backgroundColor = this.style.backgroundColor;
			label.replaceChild(document.createTextNode(this.style.backgroundColor.toUpperCase()), label.firstChild);
		};
		cell.onmouseout = function() {
			this.className = 'cheditor-pulldown-color-cell';
		};
		cell.onclick = function() {
			self.changeFontColor(this.style.backgroundColor, menu);
		};

		cell.appendChild(document.createTextNode('\u00a0'));
		container.appendChild(cell);

		if (j == 9 || j == 59) {
			cell = document.createElement('div');
			cell.className = 'cheditor-pulldown-color-spacer';
			container.appendChild(cell);
		}
	}

	pulldown.appendChild(container);
	return pulldown;
},

onKeyPressToolbarUpdate : function () {
    var self = this;
    if (self.tempTimer) clearTimeout(self.tempTimer);
    self.tempTimer = setTimeout(function() {
        self.toolbarUpdate();
        self.tempTimer = null;
    }, 70);
    if (self.config.showTagPath) self.doEditorEvent();
},

doOnArrowKeyPress : function (ev) {
    var key = ev.keyCode;
    if (key != 8 && (key < 33 || key > 40)) return;
    this.onKeyPressToolbarUpdate();
},

doOnKeyPress : function (ev) {
    if (GB.browser.msie && this.config.ieEnterMode == 'br') {
        var key = this.editArea.event.keyCode;
        if (key && key == 13) {
            if (this.editArea.event.shiftKey == false) {
                var rng = this.getRange();
                this.editArea.event.returnValue = false;
                this.editArea.event.cancelBubble = true;
                rng.pasteHTML('<br>');
                rng.select();
                rng.moveEnd("character", 1);
                rng.moveStart("character", 1);
                rng.collapse(false);
                return false;
            }
            else {
                return this.editArea.event.keyCode = 13;
            }
        }
    }
    this.onKeyPressToolbarUpdate();
},

setWinPosition : function (oWin, oWinWidth) {
	var obj = this.cheditor.container;
	var L = 0;
	var T = 0;
	var S = this.getWindowSize();

	if (obj.offsetParent) {
        while (obj) {
            T += obj.offsetTop;
            obj = obj.offsetParent;
        }
    }
    
	T += this.cheditor.toolbarWrapperHeight;

	if (GB.browser.msie) L /= 2;
	L = (S.width / 2) - (oWinWidth / 2);
	oWin.style.left = L + 'px';
	oWin.style.top = T + 'px';
	oWin.style.width = oWinWidth + 'px';
},

getWindowSize : function () {
	var docMode = document.compatMode == 'CSS1Compat';
	var docBody = document.body;
	var docElem = document.documentElement;

	return {
		scrollX : window.pageXOffset|| (docMode ? docElem.scrollLeft    : docBody.scrollLeft),
		scrollY : window.pageYOffset|| (docMode ? docElem.scrollTop     : docBody.scrollTop),
		width   : window.innerWidth || (docMode ? docElem.clientWidth   : docBody.clientWidth),
		height  : window.innerHeight|| (docMode ? docElem.clientHeight  : docBody.clientHeight)
	};
},

popupWinLoad : function (popupAttr) {
	var self = this;
	if (self.cheditor.popupTitle.hasChildNodes())
		self.cheditor.popupTitle.removeChild(self.cheditor.popupTitle.firstChild);

	self.cheditor.popupTitle.appendChild(document.createTextNode(popupAttr['title']));
	self.cheditor.popupElem.style.zIndex = 1002;
	self.setWinPosition(self.cheditor.popupElem, popupAttr['width']);

	var iframe = document.createElement("iframe");
	iframe.setAttribute('frameBorder', "0");
	iframe.setAttribute('name', popupAttr['tmpl']);
	iframe.style.width  = '100%';
	iframe.style.height = '100px';
	iframe.style.border = "0px";
	iframe.setAttribute('src', self.config.popupPath + popupAttr['tmpl']);
	iframe.style.visibility = 'hidden';
	iframe.id = popupAttr['tmpl'];

	if (self.cheditor.popupFrameWrapper.hasChildNodes())
		self.cheditor.popupFrameWrapper.removeChild(self.cheditor.popupFrameWrapper.firstChild);

	self.cheditor.popupFrameWrapper.appendChild(iframe);

	var popWinResizeHeight = function () {
		iframe.style.visibility = 'visible';
		iframe.contentWindow.focus();
		iframe.contentWindow.init.call(self, iframe, popupAttr['argv'] ? popupAttr['argv'] : null);
	};

	if (GB.browser.msie && iframe.onreadystatechange) {
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

	self.cheditor.popupElem.style.display = 'block';

	var modalSize = self.getWindowSize();
	self.cheditor.modalBackground.style.zIndex = self.modalElementZIndex;
	if (GB.browser.opera) {
		self.modalReSize = function() {
			var modalSize = self.getWindowSize.call(self);
			self.cheditor.modalBackground.style.height = modalSize.height + 'px';
		};
		window.addEventListener("resize", self.modalReSize, false);
	}

	if (GB.browser.msie) {
		if (GB.browser.ver < 7) self.cheditor.modalBackground.style.height = modalSize.height + 'px';
        if (GB.browser.ver < 10) self.cheditor.modalBackground.style.filter = 'alpha(opacity=50)';
        else self.cheditor.modalBackground.style.opacity = .5;
	}
	else
		self.cheditor.modalBackground.style.opacity = .5;

	document.body.insertBefore(self.cheditor.modalBackground, document.body.firstChild);
	self.cheditor.modalBackground.style.display = 'block';
	document.body.insertBefore(self.cheditor.popupElem, document.body.firstChild);
	DragWindow.init(self.cheditor.dragHandle, self.cheditor.popupElem);
},

popupWinCancel : function () {
    this.restoreRange();
    this.popupWinClose();
},

popupWinClose : function () {
	if (this.cheditor.popupElem == null) {
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
			window.removeEventListener("resize", self.modaReSize, false);
		}
		this.modalReSize = null;
	}
	this.editArea.focus();
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
            range.moveToBookmark(this.storedSelections[0]);
            range.select();
        }
    }
    return range;
},

setRange : function (range) {
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
            if (range) {
                this.storedSelections[0] = range.getBookmark();
            }
        } catch(e) {}
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
        range = (selection.createRange ? selection.createRange() : this.doc.createRange()) || this.doc.body.createTextRange();
    }
    
    this.range = range;
    return range;
},

createRange : function () {
    var range = null;
    
    if (this.W3CRange) {
        range = this.doc.createRange();
    }
    else {
        range = this.doc.createRange ? this.doc.createRange() : this.doc.body.createTextRange();
    }
    
    return range;
},

getSelectionType : function (range) {
	if (!range) {
        return null;
    }
	
	var type = null;
	if (this.W3CRange) {
		type = GB.selection.SELECTION_TEXT;
		if (range.startContainer === range.endContainer && range.startContainer.nodeType === GB.node.ELEMENT_NODE) {
			type = GB.selection.SELECTION_ELEMENT;
		}        
	}
	else {
		type = GB.selection.SELECTION_NONE;
		try {
			var selectionType = this.doc.selection.type;
			type = (selectionType === 'Text') ?
					GB.selection.SELECTION_TEXT :
						((selectionType === 'Control') ?
							GB.selection.SELECTION_ELEMENT : GB.selection.SELECTION_NONE);

			if (type === GB.selection.SELECTION_NONE && selectionType.createRange().parentElement()) {
				type = GB.selection.SELECTION_TEXT;
			}
		} catch (e) {}
	}
	return type;
},

windowOpen : function (popupName) {
	this.editArea.focus();
	this.boxHideAll();
	this.setRange(this.getRange());

	if (typeof GB.popupWindow[popupName] != 'undefined')
		this.popupWinLoad(GB.popupWindow[popupName]);
	else
		alert('사용할 수 없는 명령입니다.');
},

doCmd : function (cmd, opt) {
    this.editArea.focus();
	this.boxHideAll();

    var range = null;
    range = this.range;

    if (cmd == 'NewDocument') {
		if (confirm('글 내용이 모두 사라집니다. 계속하시겠습니까?'))
			this.doc.body.innerHTML = '';

		this.images = [];
		this.editImages = {};
		this.editArea.focus();
		this.toolbarUpdate();
		return;
	}

	if (cmd == 'ClearTag') {
		if (confirm('모든 HTML 태그를 삭제합니다. 계속하시겠습니까?\n(P, DIV, BR 태그와 텍스트는 삭제하지 않습니다.)')) {
			var content = this.doc.body.innerHTML;
			this.doc.body.innerHTML = content.replace(/<(\/?)([^>]*)>/g,
					function(a, b, c) {
						var el = c.toLowerCase().split(/ /)[0];
						if (el != 'p' && el != 'div' && el != 'br') return '';

						return '<'+b+el+'>';
					});
		}

		this.editArea.focus();
		this.toolbarUpdate();
		return;
	}

	if (cmd == 'Print') {
		this.editArea.print();
		return;
	}

	if (cmd == 'PageBreak') {
		this.printPageBreak();
		this.editArea.focus();
		return;
	}
    
	if (this.W3CRange || (!this.W3CRange && (this.getSelectionType(range) === GB.selection.SELECTION_NONE))) {
		range = this.doc;
	}
    
	if (!GB.browser.msie && (cmd == 'Cut' || cmd == 'Copy' || cmd == 'Paste' || cmd == 'PasteFromWord')) {
		try { range.execCommand(cmd, false, opt); }
		catch (e) {
			var keyboard = '';
			var command = '';
			switch (cmd) {
				case 'Cut'  : keyboard = 'x'; command = '자르기'; break;
				case 'Copy' : keyboard = 'c'; command = '복사'; break;
				case 'Paste': keyboard = 'v'; command = '붙이기'; break;
			}

			alert('사용하고 계신 브라우저에서는 \'' + command + '\' 명령을 사용하실 수 없습니다. \n' +
			'키보드 단축키를 이용하여 주세요. \(윈도 사용자: Ctrl + ' + keyboard + ', 애플 사용자: Apple + ' + keyboard + '\)');
		}

		this.editArea.focus();
		return;
	}

	try {
		if (cmd == 'PasteFromWord') {
			if (typeof this.cheditor.tmpdoc == 'undefined') {
				var tmpframe = document.createElement('iframe');
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

			range = this.getRange();
			var tmpDoc = this.cheditor.tmpdoc;
			tmpDoc.execCommand("SelectAll");
			tmpDoc.execCommand("Paste");

			range.pasteHTML(this.cleanFromWord(tmpDoc));
			range.select();
		}
		else if (cmd == 'Paste') {
			range.execCommand(cmd);
		}
		else {
            range.execCommand(cmd, false, opt);
		}
	}
	catch (e) {
		alert(cmd + ": 지원되지 않는 명령입니다.");
	}
    
    this.editArea.focus();
    this.toolbarUpdate();
},

cleanFromWord : function (tmpDoc) {
	for (var i=0; i < tmpDoc.body.all.length; i++) {
		tmpDoc.body.all[i].removeAttribute("className");
	}

	var doc = tmpDoc.body.innerHTML;
	doc = doc.replace(/MsoNormal/g, "");
	doc = doc.replace(/<\\?\?xml[^>]*>/g, "");
	doc = doc.replace(/<\/?o:p[^>]*>/g, "");
	doc = doc.replace(/<\/?v:[^>]*>/g, "");
	doc = doc.replace(/<\/?o:[^>]*>/g, "");
	doc = doc.replace(/<\/?st1:[^>]*>/g, "");
	doc = doc.replace(/<!--(.*)-->/g, "");
	doc = doc.replace(/<!--(.*)>/g, "");
	doc = doc.replace(/<!(.*)-->/g, "");
	doc = doc.replace(/<\\?\?xml[^>]*>/g, "");
	doc = doc.replace(/<\/?o:p[^>]*>/g, "");
	doc = doc.replace(/<\/?v:[^>]*>/g, "");
	doc = doc.replace(/<\/?o:[^>]*>/g, "");
	doc = doc.replace(/<\/?st1:[^>]*>/g, "");
	doc = doc.replace(/lang=.?[^" >]*/ig, "");
	doc = doc.replace(/type=.?[^" >]*/g, "");
	doc = doc.replace(/href='#[^"]*'/g, "");
	doc = doc.replace(/href="#[^"]*"/g, "");
	doc = doc.replace(/name=.?[^" >]*/g, "");
	doc = doc.replace(/ clear="all"/g, "");
	doc = doc.replace(/id="[^"]*"/g, "");
	doc = doc.replace(/title="[^"]*"/g, "");
	doc = doc.replace(/\n/g, "");
	doc = doc.replace(/\r/g, "");
	doc = doc.replace(/mso\-[^">;]*/g, "");
	doc = doc.replace(/<p[^>]*/ig, "<p");
	doc = doc.replace(/windowtext/ig, "#000000");
	doc = doc.replace(/class=table/ig, "");
	doc = doc.replace(/<span[^>]*<\/span>/ig, "");
	return doc;
},

printPageBreak : function () {
	var hr = document.createElement('hr');
	hr.style.pageBreakAfter = 'always';
	hr.style.border = '1px #999 dotted';
	this.insertHTML(hr);
},

doCmdPaste : function (html) {
    this.editArea.focus();
    if (!this.W3CRange) {
        if (this.range.item) {
            var rng = this.doc.body.createTextRange();
            if (rng) {
                rng.moveToElementText(this.range.item(0));
                rng.collapse(false);
                rng.select();
                this.range.item(0).outerHTML = html;
            }
            this.toolbarUpdate();
        }
        else {
            this.range.pasteHTML(html);
            this.range.collapse(false);
            this.range.select();
        }
    }
    else {
        this.insertNodeAtSelection(html);
    }
},

doCmdPopup : function (cmd, opt) {
    var self = this, range = null, sType = null;
    
    self.editArea.focus();
    range = self.restoreRange();
    sType = self.getSelectionType(range);
    
    if (self.W3CRange) {
        range = self.doc;
    }
    else {
        range = (sType === GB.selection.SELECTION_NONE) ? self.doc : range;
    }

    try {
        range.execCommand(cmd, false, opt);
    }
    catch (e) { alert(e.toString()); }

    if (self.tempTimer) {
        clearTimeout(self.tempTimer);
    }

    self.tempTimer = setTimeout(function() {
        self.toolbarUpdate();
        self.tempTimer = null;
    }, 50);

    self.boxHideAll();
},

modifyImage : function (img) {
	var self = this;
	var widthOptions = {'default'   : { size: 'default', desc: '원본 크기'},
						'custom'    : { size: 'input', desc: '직접 입력:'},
						'fitpage'   : { size: '100%', desc: '페이지 크기에 맞춤'},
						'px160'     : { size: 160, desc: '썸네일, 160 픽셀'},
						'px320'     : { size: 320, desc: '작은 크기, 320 픽셀'},
						'px640'     : { size: 640, desc: '중간 크기, 640 픽셀'},
						'px1024'    : { size: 1024, desc: '크게, 1024 픽셀'},
						'px1600'    : { size: 1600, desc: '아주 크게, 1600 픽셀'}
	};
	var imageAlign = {'left'        : '왼쪽',
					  'center'      : '가운데',
					  'right'       : '오른쪽'
	};

	if (img.id == '') img.id = 'image_' + Math.random();

	var selectedWidth = document.createElement('select');
	for (var idx in widthOptions) {
        if (widthOptions.hasOwnProperty(idx)) {
		    selectedWidth.options[selectedWidth.options.length] = new Option(widthOptions[idx].desc, idx);
        }
	}

	selectedWidth.onchange = function() {
		if (this.value == 'custom') {
			inputWidthWrapper.style.display = '';
			inputWidth.focus();
		}
		else {
			inputWidthWrapper.style.display = 'none';
			editImageSubmit();
		}
	};

	var div = document.createElement('div');
	div.style.textAlign="left";
	var ico = new Image();
	ico.src = this.config.iconPath + 'image_resize.png';
	ico.className = 'cheditor-ico';
	div.appendChild(ico);
	div.appendChild(selectedWidth);

	var inputWidthWrapper = document.createElement('span');
	var inputWidth = document.createElement('input');
	inputWidth.setAttribute('type', 'text');
	inputWidth.setAttribute('name', 'inputWidth');
	inputWidth.className = 'user-input-width';

	inputWidthWrapper.appendChild(inputWidth);
	inputWidthWrapper.appendChild(document.createTextNode(' 픽셀'));
	div.appendChild(inputWidthWrapper);

	selectedWidth.value = 'custom';

	var alignIco = new Image();
	alignIco.src = this.config.iconPath + 'image_align_left.png';
	alignIco.className = 'cheditor-ico';
	alignIco.style.marginLeft = '20px';
	div.appendChild(alignIco);

	var selectedAlign = document.createElement('select');
	selectedAlign.style.marginRight = '5px';

	div.appendChild(selectedAlign);

	for (idx in imageAlign) {
        if (imageAlign.hasOwnProperty(idx)) {
		    selectedAlign.options[selectedAlign.options.length] = new Option(imageAlign[idx], idx);
        }
	}

	selectedAlign.onchange = function() {
		alignIco.src = self.config.iconPath + 'image_align_' + this.value + '.png';

		if (this.value == 'center') {
			wrapspan.style.display = 'none';
		}
		else {
			wrapspan.style.display = '';
			wrapIcon.src = self.config.iconPath + 'image_align_' + selectedAlign.value + '_wt.png';
		}
		wrapcheck.checked = false;
	};

	var wrapspan = document.createElement('span');
	var wrapcheck = document.createElement('input');
	wrapcheck.setAttribute('type', 'checkbox');
	wrapcheck.setAttribute('name', 'wrapText');
	wrapcheck.setAttribute('value', '1');
	wrapcheck.className = 'wrap-checked';

	wrapcheck.onclick = function() {
		alignIco.src = self.config.iconPath + 'image_align_' + selectedAlign.value + (this.checked ? '_wt.png' : '.png');
	};

	var cssFloat = GB.browser.msie ? img.style.styleFloat : img.style.cssFloat;
	if (cssFloat) {
		wrapcheck.checked = true;
		selectedAlign.value = cssFloat.toLowerCase();
	}
	else {
		wrapcheck.checked = false;
		var wrapper = img.parentNode;
		if ((wrapper.nodeName == 'DIV' || wrapper.nodeName == 'P') && wrapper.style.textAlign) {
			selectedAlign.value = wrapper.style.textAlign.toLowerCase();
		}
	}

	wrapspan.className = 'wrap-text-desc';
	wrapspan.style.display = (selectedAlign.value != 'center') ? '' : 'none';
	wrapspan.appendChild(wrapcheck);

	var wrapIcon = new Image();
	wrapIcon.className = 'cheditor-ico';
	wrapIcon.src = this.config.iconPath + 'image_align_' + selectedAlign.value + '_wt.png';
	wrapspan.appendChild(wrapIcon);
	div.appendChild(wrapspan);

	var submit = new Image();
	submit.src = this.config.iconPath + 'button/edit_image.gif';
	submit.className = 'input-submit';
	submit.onclick = function() { editImageSubmit(); };
	div.appendChild(submit);

	var deleteSubmit = new Image();
	deleteSubmit.src = this.config.iconPath + 'button/delete_cross.gif';
	deleteSubmit.className = 'delete-submit';
	deleteSubmit.onclick = function() {
		var wrapper = img.parentNode;
		if (wrapper.firstChild == wrapper.lastChild && wrapper.nodeName != 'BODY')
			wrapper.parentNode.removeChild(wrapper);
		else {
			wrapper.removeChild(img);
		}
		self.doEditorEvent();
	};
	div.appendChild(deleteSubmit);

	if (typeof self.editImages[img.src] == 'undefined') {
		self.editImages[img.src] = { width: img.width, height: img.height };
	}

	var editImageSubmit = function() {
		var alt = self.trimSpace(inputAlt.value);
		if (alt != '') img.setAttribute('alt', alt);
		else img.setAttribute('alt', '');

		var setFloat = function(value) {
			var pNode = img.parentNode;
			if (GB.browser.msie) {
				img.style.styleFloat = value;
				if (pNode && pNode.id == img.id + '_caption') {
					pNode.style.styleFloat = value;
				}
			}
			else {
				img.style.cssFloat = value;
				if (pNode && pNode.id == img.id + '_caption') {
					pNode.style.cssFloat = value;
				}
			}
		};

		var setWrapper = function(value) {
			var pNode = img.parentNode;
			if (pNode.id == img.id + '_caption') {
				switch (value) {
				case 'left' : 
					pNode.style.margin = '';
					break;
				case 'right' : 
					pNode.style.margin = '0px 0px auto auto';
					break;
				default:
					pNode.style.margin = '0px auto';
					break;
				}
				pNode = pNode.parentNode;
			}

			if (pNode.nodeName != 'DIV' && pNode.nodeName != 'P') {
				var newNode = document.createElement('div');
				img.parentNode.insertBefore(newNode, img);
				newNode.appendChild(img);
			}

			pNode.style.textAlign = value;
			pNode.removeAttribute('align');
		};

		if (wrapcheck.checked && selectedAlign.value != 'center') {
			setWrapper('left');
			setFloat(selectedAlign.value);

			if (selectedAlign.value == 'left') {
				img.style.marginRight = '1em';
				img.style.marginLeft = '';
			}
			else if (selectedAlign.value == 'right') {
				img.style.marginLeft = '1em';
				img.style.marginRight = '';
			}
		}
		else {
			setWrapper(selectedAlign.value);
			setFloat('');
			img.style.margin = '';
		}

		var caption = self.trimSpace(fmInputCaption.value);
        var pNode, captionText;
		if (caption != '') {
			pNode = img.parentNode;
			var captionId = img.id + '_caption';
			if (pNode && pNode.id == captionId) {
				captionWrapper = pNode;
				if (GB.browser.msie && img.style.styleFloat != '') {
					captionWrapper.style.styleFloat = img.style.styleFloat;
				}
				if (!GB.browser.msie && img.style.cssFloat != '') {
					captionWrapper.style.cssFloat = img.style.cssFloat;
				}

				captionText = getCaptionText();
				captionText.innerHTML = '';
				captionText.appendChild(document.createTextNode(caption));
			}
			else {
				var captionWrapper = document.createElement('div');
				captionWrapper.style.width = img.width + 'px';
				captionWrapper.id = img.id + '_caption';
				captionWrapper.style.textAlign = 'center';
				captionWrapper.appendChild(img);

				captionText = document.createElement('div');
				captionText.setAttribute('style', self.config.imgCaptionText);
				captionText.id = img.id + '_text';
				captionText.appendChild(document.createTextNode(caption));
				captionWrapper.appendChild(captionText);
				pNode.appendChild(captionWrapper);
			}
		}
		else {
			pNode = img.parentNode;
			if (pNode.id == img.id + '_caption') {
				pNode.parentNode.appendChild(img);
				pNode.parentNode.removeChild(pNode);
			}
		}

		var width = null;
		var height = null;

		if (self.editImages[img.src] && self.editImages[img.src].width != null) {
			width = self.editImages[img.src].width;
			if (self.editImages[img.src] && self.editImages[img.src].height != null) {
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

		switch (selectedWidth.value) {
		case 'default' :
			width = width + 'px';
			height = (height ? height : img.height) + 'px';
			break;
		case 'fitpage' :
			width = '100%';
			height = 'auto';
			break;
		case 'custom' :
			width = self.trimSpace(inputWidth.value);
			if (width == '') return;

			width = parseInt(inputWidth.value);
			if (isNaN(width) && img.height) {
				width = width + 'px';
				height = height + 'px';
			}
			else {
				height =  Math.round((img.height * width) / img.width) + 'px';
				width += 'px';
			}
			break;
		default:
			width = widthOptions[selectedWidth.value].size;
			if (img.height) {
				height =  Math.round((img.height * width) / img.width) + 'px';

			}
			width += 'px';
		}

		if (width) img.style.width = width;
		if (height) img.style.height = height;
		self.editArea.focus();
		self.doEditorEvent();
	};

	div.appendChild(document.createElement('br'));
	div.appendChild(document.createTextNode('Alt:'));
	var inputAlt = document.createElement('input');
	inputAlt.setAttribute('type', 'text');
	inputAlt.setAttribute('name', 'inputAlt');
	inputAlt.setAttribute('value', '');
	inputAlt.className = 'user-input-alt';
	div.appendChild(inputAlt);

	div.appendChild(document.createTextNode('캡션:'));
	var fmInputCaption = document.createElement('input');
	fmInputCaption.setAttribute('type', 'text');
	fmInputCaption.setAttribute('name', 'inputCaption');
	fmInputCaption.setAttribute('value', '');
	fmInputCaption.className = 'user-input-caption';
    div.appendChild(fmInputCaption);

	if (img.getAttribute('alt') != '' && img.getAttribute('alt') != null)
		inputAlt.value = img.getAttribute('alt');

	var getCaptionText = function() {
		var ct = img.nextSibling;
		var found = false;
		while (ct) {
			if (ct.id == (img.id + '_text')) {
				found = true;
				break;
			}
			ct = ct.nextSibling;
		}
		return (found) ? ct : null;
	};
	
	var captionText = getCaptionText();
	fmInputCaption.value = captionText ? captionText.innerHTML : '';

	self.cheditor.editBlock.innerHTML = '';
	self.cheditor.editBlock.appendChild(div);
},

modifyCell : function (ctd) {
    var self = this;
    var ctb = ctd;
    var ctr = ctb;

    while (ctb != null && ctb.tagName.toLowerCase() != "table") ctb = ctb.parentNode;
    while (ctr != null && ctr.tagName.toLowerCase() != "tr") ctr = ctr.parentNode;

    var getCellMatrix = function () {
        var tm = new Array();
        var rows = (ctb.rows && ctb.rows.length > 0) ? ctb.rows : ctb.getElementsByTagName("TR");

        for (var i=0; i < rows.length; i++) {
            tm[i] = new Array();
        }

        for (var i=0; i < rows.length; i++) {
            var jr = 0;
            for (var j=0; j < rows[i].cells.length; j++) {
                while (typeof tm[i][jr] != 'undefined')
                    jr++;

                for (var jh=jr; jh < jr + (rows[i].cells[j].colSpan ? rows[i].cells[j].colSpan : 1); jh++) {
                    for (var jv=i; jv < i + (rows[i].cells[j].rowSpan ? rows[i].cells[j].rowSpan : 1); jv++) {
                        tm[jv][jh] = (jv == i) ? rows[i].cells[j].cellIndex : -1;
                    }
                }
            }
        }
        return tm;
    };

    var insertColumn = function() {
        var tm = getCellMatrix();
        var rows = (ctb.rows && ctb.rows.length > 0) ? ctb.rows : ctb.getElementsByTagName("TR");
        var rowIndex = 0, realIndex = 0;

        if (ctr.rowIndex >= 0) {
            rowIndex = ctr.rowIndex;
        }
        else {
            for(var i=0; i < rows.length; i++) {
                if (rows[i] == ctr) {
                    rowIndex = i;
                    break;
                }
            }
        }

        for (var j=0; j < tm[rowIndex].length; j++) {
            if (tm[rowIndex][j] == ctd.cellIndex) {
                realIndex = j;
                break;
            }
        }

        for (var i=0; i < rows.length; i++) {
            if (tm[i][realIndex] != -1) {
                if (rows[i].cells[tm[i][realIndex]].colSpan > 1) {
                    rows[i].cells[tm[i][realIndex]].colSpan++;
                }
                else {
                    var newc = rows[i].insertCell(tm[i][realIndex]+1);
                    var nc = rows[i].cells[tm[i][realIndex]].cloneNode(false);
                    nc.innerHTML = '&nbsp;';
                    rows[i].replaceChild(nc, newc);
                }
            }
        }
    };

    var insertRow = function(idx) {
        var newr = ctb.insertRow(ctr.rowIndex + 1);
        for (var i=0; i < ctr.cells.length; i++) {
            if (ctr.cells[i].rowSpan > 1) {
                ctr.cells[i].rowSpan++;
            }
            else {
                var newc = ctr.cells[i].cloneNode(false);
                newc.innerHTML = '&nbsp;';
                newr.appendChild(newc);
            }
        }

        for (var i=0; i < ctr.rowIndex; i++) {
            var tempr;
            if (ctb.rows && ctb.rows.length > 0) {
                tempr = ctb.rows[i];
            }
            else {
                tempr = ctb.getElementsByTagName("tr")[i];
            }
            for (var j=0; j < tempr.cells.length; j++) {
                if (tempr.cells[j].rowSpan > (ctr.rowIndex - i))
                    tempr.cells[j].rowSpan++;
            }
        }
    };

    var deleteColumn = function () {
        var tm = getCellMatrix(ctb);
        var rows = (ctb.rows && ctb.rows.length>0) ? ctb.rows : ctb.getElementsByTagName("TR");
        var rowIndex = 0, realIndex = 0;

        if (ctr.rowIndex >= 0) {
            rowIndex = ctr.rowIndex;
        }
        else {
            for(var i=0; i < rows.length; i++) {
                if (rows[i] == ctr) {
                    rowIndex = i;
                    break;
                }
            }
        }

        if (tm[0].length <= 1) {
            ctb.parentNode.removeChild(ctb);
        }
        else {
            for (var j=0; j < tm[rowIndex].length; j++) {
                if (tm[rowIndex][j] == ctd.cellIndex) {
                    realIndex = j;
                    break;
                }
            }

            for (var i=0; i < rows.length; i++) {
                if (tm[i][realIndex] != -1) {
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
        var tm = getCellMatrix(ctb);
        var rows = (ctb.rows && ctb.rows.length>0) ? ctb.rows : ctb.getElementsByTagName("TR");
        var rowIndex = 0;

        if (ctr.rowIndex >= 0) {
            rowIndex = ctr.rowIndex;
        }
        else {
            for(var i=0; i < rows.length; i++) {
                if (rows[i] == ctr) {
                    rowIndex = i;
                    break;
                }
            }
        }

        if (rows.length <= 1) {
            ctb.parentNode.removeChild(ctb);
        }
        else {
            for (var i=0; i < rowIndex; i++) {
                var tempr = rows[i];
                for (var j=0; j < tempr.cells.length; j++) {
                    if (tempr.cells[j].rowSpan > (rowIndex - i))
                        tempr.cells[j].rowSpan--;
                }
            }

            var curCI = -1;
            for (var i=0; i < tm[rowIndex].length; i++) {
                var prevCI = curCI;
                curCI = tm[rowIndex][i];

                if (curCI != -1 && curCI != prevCI && ctr.cells[curCI].rowSpan>1 && (rowIndex+1) < rows.length) {
                    var ni = i;
                    var nrCI = tm[rowIndex+1][ni];
                    while (nrCI == -1) {
                        ni++;
                        nrCI = (ni < rows[rowIndex+1].cells.length) ? tm[rowIndex+1][ni] : rows[rowIndex+1].cells.length;
                    }

                    var newc = rows[rowIndex+1].insertCell(nrCI);
                    rows[rowIndex].cells[curCI].rowSpan--;
                    var nc = rows[rowIndex].cells[curCI].cloneNode(false);
                    rows[rowIndex+1].replaceChild(nc, newc);

                    var cs = (ctr.cells[curCI].colSpan>1) ? ctr.cells[curCI].colSpan : 1;
                    var nj = 0;

                    for (var j=i; j < (i+cs); j++) {
                        tm[rowIndex+1][j] = nrCI;
                        nj = j;
                    }
                    for (var j=nj; j < tm[rowIndex+1].length; j++) {
                        if (tm[rowIndex+1][j] != -1)
                            tm[rowIndex+1][j]++;
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
        var tm = getCellMatrix(ctb);
        var rows = (ctb.rows && ctb.rows.length>0) ? ctb.rows : ctb.getElementsByTagName("TR");
        var rowIndex = 0, realIndex = 0;

        if (ctr.rowIndex >= 0) {
            rowIndex = ctr.rowIndex;
        }
        else {
            for(var i=0; i < rows.length; i++) {
                if (rows[i] == ctr) {
                    rowIndex = i;
                    break;
                }
            }
        }

        for (var j=0; j < tm[rowIndex].length; j++) {
            if (tm[rowIndex][j] == ctd.cellIndex) {
                realIndex = j;
                break;
            }
        }

        if (ctd.cellIndex + 1 < ctr.cells.length) {
            var ccrs = ctd.rowSpan ? ctd.rowSpan : 1;
            var cccs = ctd.colSpan ? ctd.colSpan : 1;
            var ncrs = ctr.cells[ctd.cellIndex+1].rowSpan ? ctr.cells[ctd.cellIndex+1].rowSpan : 1;
            var nccs = ctr.cells[ctd.cellIndex+1].colSpan ? ctr.cells[ctd.cellIndex+1].colSpan : 1;
            var j = realIndex;

            while (tm[rowIndex][j] == ctd.cellIndex)
                j++;

            if (tm[rowIndex][j] == ctd.cellIndex + 1) {
                if (ccrs == ncrs) {
                    if (rows.length > 1) ctd.colSpan = cccs + nccs;
                    var html = self.trimSpace(ctr.cells[ctd.cellIndex + 1].innerHTML);
                    html = html.replace(/^&nbsp;/, '');
                    ctd.innerHTML += html;
                    ctr.deleteCell(ctd.cellIndex + 1);
                }
            }
        }
    };

    var mergeCellDown = function () {
        var tm = getCellMatrix(ctb);
        var rows = (ctb.rows && ctb.rows.length > 0) ? ctb.rows : ctb.getElementsByTagName("TR");
        var rowIndex = 0, crealIndex = 0;

        if (ctr.rowIndex >=0 ) {
            rowIndex = ctr.rowIndex;
        }
        else {
            for(var i=0; i < rows.length; i++) {
                if (rows[i] == ctr) {
                    rowIndex = i;
                    break;
                }
            }
        }

        for (var i=0; i < tm[rowIndex].length; i++) {
            if (tm[rowIndex][i] == ctd.cellIndex) {
                crealIndex = i;
                break;
            }
        }

        var ccrs = ctd.rowSpan ? ctd.rowSpan : 1;
        var cccs = ctd.colSpan ? ctd.colSpan : 1;

        if (rowIndex + ccrs < rows.length) {
            var ncellIndex = tm[rowIndex + ccrs][crealIndex];
            if (ncellIndex != -1 &&
                (crealIndex == 0 || (crealIndex > 0 && (tm[rowIndex + ccrs][crealIndex-1] != tm[rowIndex + ccrs][crealIndex]))))
            {

                var ncrs = rows[rowIndex + ccrs].cells[ncellIndex].rowSpan ? rows[rowIndex + ccrs].cells[ncellIndex].rowSpan : 1;
                var nccs = rows[rowIndex + ccrs].cells[ncellIndex].colSpan ? rows[rowIndex + ccrs].cells[ncellIndex].colSpan : 1;

                if (cccs == nccs) {
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
        var tm = getCellMatrix();
        var rowIndex = 0, realIndex = 0;

        rows = (ctb.rows && ctb.rows.length > 0) ? ctb.rows : ctb.getElementsByTagName("TR");

        if (ctr.rowIndex >= 0) {
            rowIndex = ctr.rowIndex;
        }
        else {
            for(var ri = 0; ri < rows.length; ri++) {
                if (rows[ri] == ctr) {
                    rowIndex = ri;
                    break;
                }
            }
        }

        for (var j=0; j < tm[rowIndex].length; j++) {
            if (tm[rowIndex][j] == ctd.cellIndex) {
                realIndex = j;
                break;
            }
        }

        if (ctd.colSpan > 1) {
            var newc = rows[rowIndex].insertCell(ctd.cellIndex + 1);
            ctd.colSpan--;
            var nc = ctd.cloneNode(false);
            nc.innerHTML = '&nbsp;';
            rows[rowIndex].replaceChild(nc, newc);
            ctd.colSpan = 1;
            ctd.removeAttribute('colSpan');
        }
        else {
            var newc = rows[rowIndex].insertCell(ctd.cellIndex + 1);
            var nc = ctd.cloneNode(false);
            nc.innerHTML = '&nbsp;';
            rows[rowIndex].replaceChild(nc, newc);

            for (var i=0; i < tm.length; i++) {
                if (i != rowIndex && tm[i][realIndex] != -1) {
                    var cs = (rows[i].cells[tm[i][realIndex]].colSpan > 1) ? rows[i].cells[tm[i][realIndex]].colSpan : 1;
                    rows[i].cells[tm[i][realIndex]].colSpan = cs + 1;
                }
            }
        }
    };

    var splitCellHorizontal = function () {
        var tm = getCellMatrix();
        var rowIndex = 0, realIndex = 0;
        var rows = (ctb.rows && ctb.rows.length > 0) ? ctb.rows : ctb.getElementsByTagName("TR");

        if (ctr.rowIndex >= 0) {
            rowIndex = ctr.rowIndex;
        }
        else {
            for(var i=0; i < rows.length; i++) {
                if (rows[i] == ctr) {
                    rowIndex = i;
                    break;
                }
            }
        }

        for (var j=0; j < tm[rowIndex].length; j++) {
            if (tm[rowIndex][j] == ctd.cellIndex) {
                realIndex = j;
                break;
            }
        }

        if (ctd.rowSpan > 1) {
            var i = realIndex;
            var ni;

            while (tm[rowIndex + 1][i] == -1) {
                i++;
            }

            ni = (i == tm[rowIndex + 1].length) ? rows[rowIndex + 1].cells.length : tm[rowIndex + 1][i];

            var newc = rows[rowIndex + 1].insertCell(ni);
            ctd.rowSpan--;

            var nc = ctd.cloneNode(false);
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
            for (var i=0; i < ctr.cells.length; i++) {
                if (i != ctd.cellIndex) {
                    rs = ctr.cells[i].rowSpan > 1 ? ctr.cells[i].rowSpan : 1;
                    ctr.cells[i].rowSpan = rs + 1;
                }
            }

            for (var i=0; i < rowIndex; i++) {
                var tempr = rows[i];
                for (var j=0; j < tempr.cells.length; j++) {
                    if (tempr.cells[j].rowSpan > (rowIndex - i)) {
                        tempr.cells[j].rowSpan++;
                    }
                }
            }

            var newc = rows[rowIndex+1].insertCell(0);
            var nc = ctd.cloneNode(false);
            nc.innerHTML = '&nbsp;';
            rows[rowIndex+1].replaceChild(nc, newc);
        }
    };

    var tblReflash = function() { self.editArea.focus(); self.doEditorEvent(); };
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
    var div = document.createElement('div');
    div.style.padding = '6px';

    for (var i in funcs) {
        var span = document.createElement('span');
        var icon = document.createElement('img');
        icon.src = self.config.iconPath + funcs[i].icon;
        if (i == 'sp1' || i == 'sp2') {
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

    var spliter = document.createElement('div');
    spliter.style.padding = '10px 0px 0px 0px';
    spliter.style.marginTop = '5px';
    spliter.style.borderTop = '1px solid #ccc';
    spliter.style.textAlign = 'center';

    for (var i in attrFuncs) {
        var txt = document.createTextNode(attrFuncs[i].txt + ' ');
        spliter.appendChild(txt);
        var input = document.createElement('input');
        input.style.marginRight = attrFuncs[i].marginRight;
        input.setAttribute('type', 'text');
        input.setAttribute('name', i);
        input.setAttribute('id', attrFuncs[i].id);
        input.setAttribute('size', 7);
        input.setAttribute('value', attrFuncs[i].value ? attrFuncs[i].value : '');
        spliter.appendChild(input);
    }

    var colorPicker = new Image();
    colorPicker.src = this.config.iconPath + 'button/color_picker.gif';
    colorPicker.className = 'color-picker';
    colorPicker.onclick = function() {
        GB.popupWindow.ColorPicker.argv = { func :
            function(color) {
                ctd.setAttribute('bgColor', color);
                self.$('fm_cell_bgcolor').value = color;
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
	var self        = this;
	var statusBar   = self.cheditor.tagPath;
	var modifyBlock = self.cheditor.editBlock;
	var oEditor     = self.editArea;
	var cmd = null, el, pNode, ancestors = [];
	var rng = self.getRange();
	var nodeType = self.getSelectionType(rng);

	if (!self.W3CRange) {
		switch (nodeType) {
		case GB.selection.SELECTION_NONE :
		case GB.selection.SELECTION_TEXT :
			pNode = rng.parentElement();
			break;
		case GB.selection.SELECTION_ELEMENT :
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
				rng.startContainer == rng.endContainer &&
				rng.startOffset - rng.endOffset < 2 &&
				rng.startContainer.hasChildNodes())
			{
				pNode = rng.startContainer.childNodes[rng.startOffset];
			}

			while (pNode.nodeType == GB.node.TEXT_NODE) {
				pNode = pNode.parentNode;
			}
		}
		catch (e) { pNode= null; }
	}

	while (pNode && (pNode.nodeType == GB.node.ELEMENT_NODE) && (pNode.tagName.toLowerCase() != 'body')) {
		ancestors.push(pNode);
		if (pNode.tagName.toLowerCase() == 'img') {
			cmd = 'img';
			break;
		}
		else if (pNode.tagName.toLowerCase() == 'td' || pNode.tagName.toLowerCase() == 'th') {
			cmd = 'cell';
			break;
		}
		pNode = pNode.parentNode;
	}

	if (cmd === null) {
		modifyBlock.style.display = "none";
		modifyBlock.innerHTML = '';
	}
	else {
        if (cmd == "cell") {
            modifyBlock.style.display = "block";
            self.modifyCell(pNode);
        }
	}

	if (self.config.showTagPath) {
		statusBar.innerHTML = '';
		statusBar.appendChild(document.createTextNode('<html> <body> '));
        el = ancestors.pop();
        while (el) {
            var tag = el.tagName.toUpperCase();
            var a = document.createElement("a");
            a.el = el;
            a.href = "javascript:void%200";
            a.className = 'cheditor-tag-path-elem';
            a.title = el.style.cssText;
            a.onclick  = function () { self.$('removeSelected').style.display = 'inline'; self.tagSelector(this.el); };
            a.appendChild(document.createTextNode(tag.toLowerCase()));
            statusBar.appendChild(document.createTextNode('<'));
            statusBar.appendChild(a);
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
		remove.onclick = function () {  oEditor.document.execCommand("Cut", false, null);
										remove.style.display = 'none';
										oEditor.focus();
										self.doEditorEvent(); };

		var span = document.createElement('span');
		span.style.marginTop = '2px';
		span.appendChild(remove);
		self.cheditor.tagPath.appendChild(span);
	}

	var interval = 50;
	if (GB.browser.msie && rng.text !== '' && nodeType !== GB.selection.SELECTION_ELEMENT) {
		interval = 100;
	}
	self.tempTimer = setTimeout(function() {
		self.toolbarUpdate();
		self.tempTimer = null;
	}, interval);
},

tagSelector : function (node) {
	this.editArea.focus();
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
		if (typeof sel == 'undefined') {
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

$ : function (id) { return document.getElementById(id); },
_$ : function (id) { return this.doc.getElementById(id); }
};

var DragWindow = {
	    obj : null,
	    init : function (o, oRoot, minX, maxX, minY, maxY) {
	        o.onmousedown = DragWindow.start;
	        o.onmouseover = function () { this.style.cursor = 'move'; };
	        o.hmode = true ;
	        o.vmode = true ;
	        o.root = oRoot && oRoot != null ? oRoot : o;
	        o.transId = oRoot.id + '_Trans';

	        if (o.hmode  && isNaN(parseInt(o.root.style.left  ))) o.root.style.left   = "0px";
	        if (o.vmode  && isNaN(parseInt(o.root.style.top   ))) o.root.style.top    = "0px";
	        if (!o.hmode && isNaN(parseInt(o.root.style.right ))) o.root.style.right  = "0px";
	        if (!o.vmode && isNaN(parseInt(o.root.style.bottom))) o.root.style.bottom = "0px";

	        o.minX = typeof minX != 'undefined' ? minX : null;
	        o.minY = typeof minY != 'undefined' ? minY : null;
	        o.maxX = typeof maxX != 'undefined' ? maxX : null;
	        o.maxY = typeof maxY != 'undefined' ? maxY : null;

	        o.root.onDragStart  = new Function();
	        o.root.onDragEnd    = new Function();
	        o.root.onDrag       = new Function();
	    },

	    start : function (e) {
	        var o = DragWindow.obj = this;
	        e = DragWindow.fixE(e);
	        var y = parseInt(o.vmode ? o.root.style.top  : o.root.style.bottom);
	        var x = parseInt(o.hmode ? o.root.style.left : o.root.style.right );
	        o.root.onDragStart(x, y);

	        o.lastMouseX = e.clientX;
	        o.lastMouseY = e.clientY;

	        document.onmousemove = DragWindow.drag;
	        document.onmouseup   = DragWindow.end;

	        if (o.root.lastChild.id == o.transId) return false;

	        var dragTransBg = document.createElement('div');
	        dragTransBg.className = 'cheditor-dragWindowTransparent';

	        if (GB.browser.msie && GB.browser.ver < 10) dragTransBg.style.filter = 'alpha(opacity=0)';
	        else dragTransBg.style.opacity = 0;

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
	        var y = parseInt(o.vmode ? o.root.style.top  : o.root.style.bottom);
	        var x = parseInt(o.hmode ? o.root.style.left : o.root.style.right );
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
	        DragWindow.obj.root.onDragEnd(parseInt(DragWindow.obj.root.style[DragWindow.obj.hmode ? "left" : "right"]),
	                parseInt(DragWindow.obj.root.style[DragWindow.obj.vmode ? "top" : "bottom"]));

	        if (DragWindow.obj.root.lastChild.id == DragWindow.obj.transId) {
	            DragWindow.obj.root.removeChild(DragWindow.obj.root.lastChild);
            }
	        DragWindow.obj = null;
	    },

	    fixE : function (e) {
	        if (typeof e == 'undefined') e = window.event;
	        if (typeof e.layerX == 'undefined') e.layerX = e.offsetX;
	        if (typeof e.layerY == 'undefined') e.layerY = e.offsetY;
	        return e;
	    }
	};

// --------------------------------------------------------------------------
// TODO: W3C DOM Range
//

// --------------------------------------------------------------------------
// TODO: Table
//
