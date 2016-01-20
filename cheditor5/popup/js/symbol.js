// ================================================================
//                            CHEditor 5
// ----------------------------------------------------------------
// Homepage: http://www.chcode.com
// Copyright (c) 1997-2016 CHSOFT
// ================================================================
var c = null;
var curView = null;
var S1 = '�� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� ��';
var S2 = '�� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� ��';
var S3 = '�� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� ��';
var S4 = '�� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� ��';
var S5 = '�� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� ��';
var japan1 = '�� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� ��';
var japan2 = '�� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� �� ��';

c = S1.split(' ');
var button = [ { alt : "", img : 'input.gif', 	cmd : inputChar },
               { alt : "", img : 'cancel.gif', 	cmd : popupClose } ];

var oEditor = null;

function init(dialog) {
	oEditor = this;
	oEditor.dialog = dialog;

	var dlg = new Dialog(oEditor);
	dlg.showButton(button);

	setupEvent();
	dlg.setDialogHeight();
}

function hover(obj, val) {
  	obj.style.backgroundColor = val ? "#5579aa" : "#fff";
  	obj.style.color = val ? "#fff" : "#000";
}

function showTable() {
  	var k = 0;
  	var len = c.length;
  	var w = 9;
  	var h = 20;
  	var span, i, j, tr, td;

  	var table = document.createElement('table');
  	table.border = 0;
  	table.cellSpacing = 1;
  	table.cellPadding = 0;
  	table.align = 'center';

    var getChar = function() {
        document.getElementById('fm_input').value = document.getElementById('fm_input').value + c[this.id];
    };
    var mouseOver = function() {
        hover(this, true);
    };
    var mouseOut = function() {
        hover(this, false);
    };
  	for (i=0; i < w; i++) {
  		tr = table.insertRow(i);
    	for (j = 0; j < h; j++) {
    		td = tr.insertCell(j);
    		td.className = 'schar';

        	if ( len < k+1) {
        		td.appendChild(document.createTextNode('\u00a0'));
        	}
        	else {
        		td.style.cursor = 'pointer';
        		td.id = k;
        		td.onclick = getChar;
        		td.onmouseover = mouseOver;
        		td.onmouseout = mouseOut;
                span = document.createElement("span");
                span.style.fontSize = "13px";
                span.appendChild(document.createTextNode(c[k]));
        		td.appendChild(span);
        	}
      		k++;
    	}
  	}

  	var output = document.getElementById('output');
  	if (output.hasChildNodes()) {
  		for (i=0; i<output.childNodes.length; i++) {
  			output.removeChild(output.firstChild);
  		}
  	}
  	output.appendChild(table);
}

function sp1 () {
	c = S1.split(' ');
	showTable();
}

function sp2 () {
	c = S2.split(' ');
	showTable();
}

function sp3 () {
	c = S3.split(' ');
	showTable();
}

function sp4 () {
	c = S4.split(' ');
	showTable();
}

function sp5 () {
	c = S5.split(' ');
	showTable();
}

function sp6 () {
	c = japan1.split(' ').concat(japan2.split(' '));
	showTable();
}

function inputChar() {
	oEditor.insertHtmlPopup(document.getElementById('fm_input').value);
	oEditor.popupWinClose();
}

function popupClose() {
    oEditor.popupWinCancel();
}

function setupEvent() {
	var el = document.body.getElementsByTagName('LABEL');
    var i;
    var tab = function() {
        document.getElementById(this.id).style.fontWeight = 'bold';
        switch (this.id) {
        case 's1' : sp1(); break;
        case 's2' : sp2(); break;
        case 's3' : sp3(); break;
        case 's4' : sp4(); break;
        case 's5' : sp5(); break;
        default : sp6();
        }

        if (curView != this.id) {
            document.getElementById(curView).style.fontWeight = 'normal';
        }
        curView = this.id;
    };

	for (i=0; i < el.length; i++) {
		el[i].className = 'handCursor';
		el[i].style.fontSize = '9pt';
		el[i].style.margin = (i==0) ? '0px 0px 5px 5px' : '0px 0px 5px 0px';
		el[i].onclick = tab;
	}

	if (curView == null) {
		showTable();
		curView = 's1';
		document.getElementById(curView).style.fontWeight = 'bold';
		document.getElementById('output').style.visibility = 'visible';
	}
	document.getElementById("fm_input").value = "";
}