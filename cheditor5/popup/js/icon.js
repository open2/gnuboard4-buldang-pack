// ================================================================
//                            CHEditor 5
// ----------------------------------------------------------------
// Homepage: http://www.chcode.com
// Copyright (c) 1997-2015 CHSOFT
// ================================================================
var oEditor = null;

function popupClose() {
    oEditor.popupWinCancel();
}

function insertIcon() {
	this.removeAttribute("className");
	this.removeAttribute("class");
	this.style.margin = '1px 4px';
  	oEditor.insertHtmlPopup(this.cloneNode(false));
    oEditor.popupWinClose();
}

function showContents() {
	var block = document.getElementById('iconBlock');
	var path = oEditor.config.iconPath + 'em/';
	var num = 80, i, br, img;

	for (i=40; i<num; i++) {
		if (i > 40 && (i % 10) === 0) {
			br = document.createElement('br');
			block.appendChild(br);
		}

		img = new Image();
		img.src = path + (i+1) + ".gif";
		img.style.width = '16px';
		img.style.height = '16px';
		img.style.margin = '5px 4px';
		img.style.verticalAlign = 'middle';
		img.setAttribute('alt', 'Emotion Icon');
		img.setAttribute('border', "0");
		img.className = 'handCursor';
		img.onclick = insertIcon;
		block.appendChild(img);
	}
}

function init(dialog) {
	oEditor = this;
	oEditor.dialog = dialog;
	var button = [ { alt : "", img : 'cancel.gif', cmd : popupClose } ];
	var dlg = new Dialog(oEditor);
	showContents();
	dlg.showButton(button);
	dlg.setDialogHeight();
}
