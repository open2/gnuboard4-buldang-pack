// ================================================================
//                            CHEditor 5
// ----------------------------------------------------------------
// Homepage: http://www.chcode.com
// Copyright (c) 1997-2016 CHSOFT
// ================================================================
var button = [
	{ alt : "", img : 'play.gif', cmd : doPlay },              
	{ alt : "", img : 'submit.gif', cmd : doSubmit },
	{ alt : "", img : 'cancel.gif', cmd : popupClose }
];

var oEditor = null;
var iframeSource = false;

function init(dialog) {
	oEditor = this;
	oEditor.dialog = dialog;
	
	var dlg = new Dialog(oEditor);
	dlg.showButton(button);
	
	dlg.setDialogHeight();
}

function doPlay()
{
    var elem = oEditor.trimSpace(document.getElementById("fm_embed").value);
    elem = oEditor.trimSpace(elem);
    if (elem == '') {
        return;
    }
    
    if (elem.toLowerCase().indexOf("iframe") !== -1) {
        document.getElementById('fm_player').innerHTML = elem;
        iframeSource = true;
        return;
    }
    
    var embed = null;
	var div = document.createElement('div');
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
			movieWidth  = (isNaN(object.width) != true) ? object.width : 320;
			movieHeight = (isNaN(object.height)!= true) ? object.height: 240;
			var params = [];

			do {
				if ((child.nodeName === 'PARAM') &&  (typeof child.name !== 'undefined') && (typeof child.value !== 'undefined'))
				{
					params.push({key: (child.name == 'movie') ? 'src' : child.name, val: child.value});
				}
				child = child.nextSibling;
			}
			while (child);

			if (params.length > 0) {
				embed = document.createElement('embed');
				embed.setAttribute("width", movieWidth);
				embed.setAttribute("height", movieHeight);
				
				for (i=0; i<params.length; i++) {
                    embed.setAttribute(params[i].key, params[i].val);
                }
				embed.setAttribute("type", "application/x-shockwave-flash");
			}
		}
	}
		
	if (embed !== null) {
		document.getElementById('fm_player').appendChild(embed);
	}
}

function popupClose() {
	document.getElementById('fm_player').innerHTML = '';
    oEditor.popupWinCancel();
}

function doSubmit()
{
    var source = String(oEditor.trimSpace(document.getElementById("fm_embed").value));
    if (source === '') {
        popupClose();
    }
    
    if (iframeSource || source.indexOf("iframe") !== -1) {
        oEditor.insertHtmlPopup(source);
    }
    else {
		oEditor.insertFlash(source);
	}
	
	document.getElementById('fm_player').innerHTML = '';
    oEditor.popupWinClose();
}