// ================================================================
//                       CHEditor 5
// ----------------------------------------------------------------
// Homepage: http://www.chcode.com
// Copyright (c) 1997-2014 CHSOFT
// ================================================================
var UploadScript = "";
var DeleteScript = "";

var activeImage = false;
var readyToMove = false;
var moveTimer = -1;
var dragDropDiv;
var insertionMarker;
var hideTimer = null;

var offsetX_marker = -3;
var offsetY_marker = -3;

var geckoOffsetX_marker = -3;
var geckoOffsetY_marker = -1;

var destinationObject = false;

var divXPositions = [];
var divYPositions = [];
var divWidth = [];
var divHeight = [];

var tmpLeft = 0;
var tmpTop = 0;

var eventDiff_x = 0;
var eventDiff_y = 0;

var modifyImages = [];
var uploadMaxNumber = 12;
var imageCompleted = 0;
var imageCompletedList = [];
var UploadImagePath = "";
var ShowThumbnailSize = { width: 120, height: 90 };
var oEditor = null;
var button;

var imageResizeWidth = 0;
var makeThumbnail = true;
var makeThumbnailWidth = 120;
var makeThumbnailHeight = 90;
var browser = null;

function createInsertionMaker() {
	var wrapper = document.getElementById('insertionMarker');
	var topIco = new Image();
	topIco.src = UploadImagePath + '/marker_top.gif';
	topIco.style.width = '6px';
	topIco.style.height = '1px';
	wrapper.appendChild(topIco);

	var middleIco = new Image();
	middleIco.src = UploadImagePath + '/marker_middle.gif';
	middleIco.style.height = '96px';
	middleIco.style.width = '6px';
	wrapper.appendChild(middleIco);

	var bottomIco = new Image();
	bottomIco.src = UploadImagePath + '/marker_bottom.gif';
	bottomIco.style.width = '6px';
	bottomIco.style.height = '1px';
	wrapper.appendChild(bottomIco);
}

function popupClose() {
// ----------------------------------------------------------------------------------
   	oEditor.popupWinCancel();
}

function showContents() {
	var spacer = function(id) {
		var clear = document.createElement('div');
		clear.style.height = '0px';
		clear.style.width = '0px';
		clear.className = 'clear';
		clear.id = 'spacer' + id;

		return clear;
	};

	var spacerNo = 1, i, imgBox, theImg, lastSpacer;
	for (i=0; i<uploadMaxNumber; i++) {
		if (i > 0 && ((i % 4) === 0)) {
			document.getElementById('imageListWrapper').appendChild(spacer(spacerNo++));
		}

		imgBox = document.createElement('div');
		imgBox.id = 'imgBox' + i;
		imgBox.className = 'imageBox';
		theImg = document.createElement('div');
		theImg.id = 'img_' + i;
		theImg.className = 'imageBox_theImage';
		imgBox.appendChild(theImg);

		document.getElementById('imageListWrapper').appendChild(imgBox);
		if (i === (uploadMaxNumber-1)) {
            lastSpacer = spacer(spacerNo);
            lastSpacer.style.height = "7px";
			document.getElementById('imageListWrapper').appendChild(lastSpacer);
		}
	}

    document.getElementById('imageListWrapper').style.padding = '5px 7px 0px 5px';
    document.getElementById('imageInfoBox').style.height = '298px';
    document.getElementById('imageInfoBox').style.width = '130px';
}

function setImageCount() {
	imageCompleted++;
	document.getElementById('imageCount').innerHTML = imageCompleted;
}

function getImageCount() {
	return imageCompleted;
}

function allowedMaxImage() {
    return uploadMaxNumber - getImageCount();
}

function getUploadedCount() {
    return document.getElementById('imageListWrapper').getElementsByTagName('img').length;
}

function uploadedImageCount() {
	imageCompleted = getUploadedCount();
	document.getElementById('imageCount').innerHTML = imageCompleted;
}

function uploadError(msg) {
    alert(msg);
}

function getTopPos(inputObj) {
// ----------------------------------------------------------------------------------
	var returnValue = inputObj.offsetTop;

    inputObj = inputObj.offsetParent;
  	while (inputObj) {
	  	if (inputObj.tagName.toLowerCase() !== 'html') {
	  		returnValue += (inputObj.offsetTop - inputObj.scrollTop);
			if (browser.msie) {
				returnValue += inputObj.clientTop;
            }
	  	}
        inputObj = inputObj.offsetParent;
	}
	return returnValue;
}

function getLeftPos(inputObj) {
// ----------------------------------------------------------------------------------
	var returnValue = inputObj.offsetLeft;

    inputObj = inputObj.offsetParent;
  	while (inputObj) {
	  	if (inputObj.id !== 'imageListWrapper') {
	  		returnValue += inputObj.offsetLeft;
	  	}
        inputObj = inputObj.offsetParent;
	}
	return returnValue;
}

function getDivCoordinates() {
// ----------------------------------------------------------------------------------
	var imgBox = document.getElementById('imageListWrapper').getElementsByTagName('DIV');
    var i;
	for (i=0; i < imgBox.length; i++) {
		if ((imgBox[i].className === 'imageBox' || imgBox[i].className === 'imageBoxHighlighted') && imgBox[i].id)
		{
			divXPositions[imgBox[i].id] = getLeftPos(imgBox[i]);
			divYPositions[imgBox[i].id] = getTopPos(imgBox[i]);
			divWidth[imgBox[i].id]  = imgBox[i].offsetWidth;
			divHeight[imgBox[i].id] = imgBox[i].offsetHeight;
		}
	}
}

function reOrder() {
// ----------------------------------------------------------------------------------
	var wrapper = document.getElementById('imageListWrapper');
	var imgBox = wrapper.getElementsByTagName('div');
	var imgNum = 0, i, spacer, breakline = [];

	for (i=0; i < imgBox.length; i++) {
		if (imgBox[i].id.indexOf('imgBox') === -1) {
            continue;
        }

        imgBox[i].className = 'imageBox';
		imgBox[i].firstChild.className = 'imageBox_theImage';

        if (imgNum > 0 && (imgNum % 4) === 0) {
            breakline.push(imgBox[i].id);
        }

		imgNum++;
	}

    for (i=0; i<breakline.length; i++) {
		spacer = document.getElementById('spacer' + (i+1));
		if (i+1 === breakline.length) {
            wrapper.appendChild(spacer);
        }
        else {
            wrapper.insertBefore(spacer, document.getElementById(breakline[i]));
        }
	}
}

function setImageInfo(id) {
    if (!id) {
        document.getElementById('selectedImageWidth').innerHTML = 0;
        document.getElementById('selectedImageHeight').innerHTML = 0;
        document.getElementById('selectedImageName').innerHTML = "없음";
    }
    else {
        document.getElementById('selectedImageWidth').innerHTML = imageCompletedList[id]['width'];
        document.getElementById('selectedImageHeight').innerHTML = imageCompletedList[id]['height'];
        document.getElementById('selectedImageName').innerHTML = imageCompletedList[id]['origName'];
    }
}

function showDelete() {
// ----------------------------------------------------------------------------------
	getDivCoordinates();

	var self = this;
	var btn = document.getElementById('removeImageButton');

	self.className = 'imageBox_theImage_over';
	btn.style.left = (makeThumbnailWidth - parseInt(btn.style.width, 10) - 1) + 'px';
	btn.style.top = '-1px';

    self.appendChild(btn);
	btn.style.display = 'block';

    btn.onmouseover = function(e) {
        oEditor.stopEvent(e);
        this.style.display = 'block';
        setImageInfo(self.id);
        this.className = 'removeButton_over';
        self.className = 'imageBox_theImage_over';
    };
    btn.onmouseout = function() { this.className = 'removeButton'; };
	btn.onmousedown = function() {
        var images = self.getElementsByTagName('img');
        var i;
        for (i=0; i<images.length; i++) {
            self.removeChild(images[i]);
        }

		self.removeChild(self.firstChild);
		self.className = 'imageBox_theImage';

		if (self.parentNode.nextSibling && self.parentNode.nextSibling.id) {
			var wrapper = document.getElementById('imageListWrapper');
			var moveobj = self.parentNode.nextSibling;
			var target = self.parentNode;

			while (moveobj !== null) {
                if (moveobj.firstChild && !moveobj.firstChild.firstChild) {
                    break;
                }
                if (/^spacer/.test(moveobj.id)) {
                    moveobj = moveobj.nextSibling;
                    continue;
                }
                wrapper.insertBefore(moveobj, target);
				moveobj = target.nextSibling;
			}
		}

		reOrder();
        uploadedImageCount();
        setImageInfo(0);
        this.style.display = 'none';
        document.body.appendChild(this);
        oEditor.removeEvent(self, 'mouseover', showDelete);
	};
    setImageInfo(self.id);
}

function hideDelete() {
// ----------------------------------------------------------------------------------
	document.getElementById('removeImageButton').style.display = 'none';
}

function startUpload(count) {
// ----------------------------------------------------------------------------------
	var el = document.getElementById('imageListWrapper').getElementsByTagName('div');
    var i, imgBox;
	for (i=0; i < el.length; i++) {
		imgBox = el[i];
		if (imgBox.className !== 'imageBox_theImage') {
			continue;
        }

		if (count === 0) {
            break;
        }

		if (!imgBox.firstChild || imgBox.firstChild.tagName.toLowerCase()  !== 'img') {
			imgBox.style.backgroundImage = "url('"+UploadImagePath+"/loader.gif')";
			count--;
		}
	}
}

function fileFilterError(file) {
	alert("선택하신 '" + file + "' 파일은 전송할 수 없습니다.\n" +
		  "gif, png, jpg 사진 파일만 전송할 수 있습니다.");
}

function imgComplete(img, imgSize, boxId) {
    img.setAttribute("border", 0);
    var resizeW, resizeH, M;

    if (imgSize.width > ShowThumbnailSize.width || imgSize.height > ShowThumbnailSize.height) {
        if (imgSize.width > imgSize.height) {
            resizeW = (imgSize.width > ShowThumbnailSize.width) ? ShowThumbnailSize.width : imgSize.width;
            resizeH = Math.round((imgSize.height * resizeW) / imgSize.width);
        }
        else {
            resizeH = (imgSize.height > ShowThumbnailSize.height) ? ShowThumbnailSize.height : imgSize.height;
            resizeW = Math.round((imgSize.width * resizeH) / imgSize.height);
        }

        if (resizeH > ShowThumbnailSize.height) {
            resizeH = (imgSize.height > ShowThumbnailSize.height) ? ShowThumbnailSize.height : imgSize.height;
            resizeW = Math.round((imgSize.width * resizeH) / imgSize.height);
        }

    }
    else {
        resizeW = imgSize.width;
        resizeH = imgSize.height;
    }

    img.style.width  = resizeW - 2+ 'px';
    img.style.height = resizeH - 2+ 'px';
    img.style.margin = "1px";

    if (resizeW < ShowThumbnailSize.width) {
        M = ShowThumbnailSize.width - resizeW;
        img.style.marginLeft = Math.round(M/2) + 'px';
    }

    if (resizeH < ShowThumbnailSize.height) {
        M = ShowThumbnailSize.height - resizeH;
        img.style.marginTop = Math.round(M/2) + 'px';
    }

    var elem = document.getElementById(boxId);
    elem.style.backgroundImage = "url('"+UploadImagePath+"/dot.gif')";
    oEditor.addEvent(elem, 'mouseover', showDelete);
    elem.onmouseout = function() {
        this.className = 'imageBox_theImage';
        setImageInfo(0);
        hideDelete();
    };

    setImageCount();
}

function showUploadWindow() {
// ----------------------------------------------------------------------------------
  	var uploadWindow  = document.getElementById("uploadWindow");
  	var uploadWindowWidth  = 700;
  	var winWidth;

  	if (!(oEditor.undefined(window.innerWidth))) {
  		winWidth  = window.innerWidth;
  	}
	else if (document.documentElement && (!(oEditor.undefined(document.documentElement.clientWidth))) &&
            document.documentElement.clientWidth !== 0)
	{
		winWidth  = document.documentElement.clientWidth;
	}
	else if (document.body && (!(oEditor.undefined(document.body.clientWidth)))) {
		winWidth  = document.body.clientWidth;
	}
	else {
		alert('현재 브라우저를 지원하지 않습니다.');
		return;
	}

  	var left = winWidth / 2 - (uploadWindowWidth / 2) + 'px';

  	uploadWindow.style.left = left;
  	uploadWindow.style.display = "block";
  	uploadWindow.style.width = uploadWindowWidth + 'px';

  	if (modifyImages.length > 0) {
		var el = document.getElementById('imageListWrapper').getElementsByTagName('DIV');
        var i, j, imgBox, img;
	  	for (i=0; i < modifyImages.length; i++) {
			if (i > 7) {
                break;
            }

			for (j=0; j < el.length; j++) {
				imgBox = el[j];
				if (imgBox.className !== 'imageBox_theImage') {
                    continue;
                }

				if (imgBox.firstChild && (imgBox.firstChild.src === modifyImages[i])) {
					break;
				}

				if (imgBox.firstChild === null) {
					img = new Image();
					img.src = modifyImages[i];
					img.border = 0;
					img.alt = '';
					img.style.width = '120px';
					img.style.height = '90px';
					imgBox.appendChild(img);
					break;
				}
	  		}
	  	}
  	}
}

function removeImages() {
	var images = [], i, j, theImage, img, remove;
    document.body.appendChild(document.getElementById('removeImageButton'));

	for (i=0; i < uploadMaxNumber; i++) {
		theImage = document.getElementById('img_'+i);
		if (theImage.hasChildNodes() && theImage.firstChild.tagName.toLowerCase() === 'img') {
            images.push(theImage);
        }
	}

    for (i=0; i<images.length; i++) {
        img = images[i];
        if (img.firstChild !== null) {
            oEditor.removeEvent(img, 'mouseover', showDelete);
            remove = img.getElementsByTagName('img');

            for (j=0; j<remove.length; j++) {
                img.removeChild(remove[j]);
            }

            img.parentNode.className = 'imageBox';
            oEditor.removeEvent(img, 'mouseover', showDelete);
        }
    }
    uploadedImageCount();
    imageCompletedList = [];
}

function removeImage() {
// ----------------------------------------------------------------------------------
	var i, theImage, found = false;

	for (i=0; i < uploadMaxNumber; i++) {
		theImage = document.getElementById('img_'+i);
		if (theImage.hasChildNodes() && theImage.firstChild.tagName.toLowerCase() === 'img') {
            found = true;
            break;
        }
	}

	if (found) {
		if (!confirm('추가하신 사진이 있습니다. 사진 넣기를 취소하시겠습니까?')) {
			return false;
		}
        removeImages();
	}

	return true;
}

function closeWindow() {
// ----------------------------------------------------------------------------------
	if (removeImage()) {
        popupClose();
    }
}

function cancelEvent() {
// ----------------------------------------------------------------------------------
	return false;
}

function startMoveTimer() {
// ----------------------------------------------------------------------------------
	if (moveTimer >= 0 && moveTimer < 10) {
		moveTimer++;
		setTimeout('startMoveTimer()', 8);
	}

	if (moveTimer === 5) {
		getDivCoordinates();
		var subElements = dragDropDiv.getElementsByTagName('div');
		if (subElements.length > 0) {
			dragDropDiv.removeChild(subElements[0]);
		}

		dragDropDiv.style.display = 'block';
		var newDiv = activeImage.cloneNode(true);
		newDiv.className = 'imageBox';
        newDiv.style.opacity = 0.5;

		newDiv.id = '';
		newDiv.style.padding = '2px';
		dragDropDiv.appendChild(newDiv);

		dragDropDiv.style.top = tmpTop + 'px';
		dragDropDiv.style.left = tmpLeft + 'px';
	}

	return false;
}

function selectImage(e) {
// ----------------------------------------------------------------------------------
	if (browser.msie) {
		e = event;
    }

	var el = this.parentNode.firstChild.firstChild;
	if (!el) {
        return;
    }

	var obj = this.parentNode;
    hideDelete();
	if (activeImage) {
        activeImage.className = 'imageBox';
    }

	obj.className = 'imageBoxHighlighted';
	activeImage = obj;
	readyToMove = true;
	moveTimer = 0;

	tmpLeft = e.clientX + Math.max(document.body.scrollLeft,document.documentElement.scrollLeft);
	tmpTop = e.clientY + Math.max(document.body.scrollTop,document.documentElement.scrollTop);

	startMoveTimer();
	return false;
}

function dragDropEnd() {
// ----------------------------------------------------------------------------------
	readyToMove = false;
	moveTimer = -1;
	dragDropDiv.style.display = 'none';
	insertionMarker.style.display = 'none';

	if (destinationObject && destinationObject !== activeImage) {
		var parentObj = destinationObject.parentNode;
		var chkObj = destinationObject.previousSibling;
		var turn = false;

		if (chkObj === null) {
			chkObj = document.getElementById('imageListWrapper').firstChild;
			turn = true;
		}

		if (chkObj.id.indexOf('spacer') !== -1) {
			chkObj = chkObj.previousSibling;
		}

		if (chkObj.firstChild.firstChild === null) {
			reOrder();
			return;
		}

		if (chkObj && chkObj.id !== null) {
			while (chkObj) {
				if (chkObj.firstChild.firstChild !== null) {
					break;
				}
				chkObj = chkObj.previousSibling;
			}
			destinationObject = turn ? chkObj : chkObj.nextSibling;
		}

		parentObj.insertBefore(activeImage, destinationObject);
		reOrder();

		activeImage.className = 'imageBox';
		activeImage = false;
		destinationObject = false;
		getDivCoordinates();
		return false;
	}
	return true;
}

function dragDropMove(e) {
// ----------------------------------------------------------------------------------
	if (moveTimer === -1) {
        return;
    }

	if (browser.msie) {
        e = window.event;
    }

	var leftPos = e.clientX + document.documentElement.scrollLeft - eventDiff_x;
	var topPos = e.clientY + document.documentElement.scrollTop - eventDiff_y;
	dragDropDiv.style.top = topPos + 'px';
	dragDropDiv.style.left = leftPos + 'px';

	leftPos = leftPos + eventDiff_x;
	topPos = topPos + eventDiff_y;

	if (e.button !== 1 && browser.msie && browser.version < 11) {
        dragDropEnd();
    }

	var elementFound = false, prop, offsetX, offsetY;

	for (prop in divXPositions) {
		if (divXPositions[prop].className === 'clear') {
            continue;
        }

		if  (divXPositions[prop] < leftPos &&
			(divXPositions[prop] + divWidth[prop] * 0.7) > leftPos &&
			 divYPositions[prop] < topPos &&
			(divYPositions[prop] + divWidth[prop]) > topPos)
		{
			if (browser.msie) {
				offsetX = offsetX_marker;
				offsetY = offsetY_marker;
			}
			else {
				offsetX = geckoOffsetX_marker;
				offsetY = geckoOffsetY_marker;
			}

			insertionMarker.style.top = divYPositions[prop] + offsetY + 'px';
			insertionMarker.style.left = divXPositions[prop] + offsetX + 'px';
			insertionMarker.style.display = 'block';
			destinationObject = document.getElementById(prop);
			elementFound = true;
			break;
		}
	}

	if (!elementFound) {
		insertionMarker.style.display = 'none';
		destinationObject = false;
	}

	return false;
}

function saveImageOrder() {
// ----------------------------------------------------------------------------------
	var rData = [];
	var objects = document.getElementById('imageListWrapper').getElementsByTagName('DIV');
    var i;
	for (i=0; i < objects.length; i++) {
		if (objects[i].className === 'imageBox' ||
			objects[i].className === 'imageBoxHighlighted')
		{
			rData.push(objects[i].id);
		}
	}

	return rData;
}

function initGallery() {
// ----------------------------------------------------------------------------------
	var imgBox = document.getElementById('imageListWrapper').getElementsByTagName('DIV');
    var i;
	for (i=0; i < imgBox.length; i++) {
		if (imgBox[i].className === 'imageBox_theImage') {
            imgBox[i].onmousedown = selectImage;
		}
	}

	document.body.onselectstart = cancelEvent;
	document.body.ondragstart = cancelEvent;
	document.body.onmouseup = dragDropEnd;
	document.body.onmousemove = dragDropMove;

	dragDropDiv = document.getElementById('dragDropContent');
	insertionMarker = document.getElementById('insertionMarker');
	getDivCoordinates();
}

function doSubmit() {
// ----------------------------------------------------------------------------------
	var el = document.getElementById('imageListWrapper').getElementsByTagName('DIV');
	var imageArray = [];
	var num = 0;
    var elem = document.getElementById('id_alignment').elements;
    var imgParagraph = false;
    var useSpacer = false;
	var imgAlign = 'top', i, imgBox;
    var input;

	for (i=0; i < elem.length; i++) {
        input = elem[i];
        switch (input.name) {
            case "alignment" :
                if (input.checked) {
                    imgAlign = input.value;
                }
                break;
            case "para" :
                imgParagraph = input.checked;
                break;
            case "use_spacer" :
                useSpacer = input.checked;
                break;
        }
    }

	for (i=0; i < el.length; i++) {
		imgBox = el[i];
		if (imgBox.className !== 'imageBox_theImage') {
			continue;
        }

		if (imgBox.firstChild !== null) {
			imageArray[num] = imageCompletedList[imgBox.id];

			if (imgAlign === 'break' ) {
				imageArray[num]['alt'] = "break";
			}
			else {
				imageArray[num]['alt'] = "";
				imageArray[num]['align'] = imgAlign;
			}

			num++;
		}
	}

	if (imageArray.length > 0) {
		oEditor.doInsertImage(imageArray, imgParagraph, useSpacer);
    }
	oEditor.popupWinClose();
}

var selectedFilesNum = 0;
var uploadedFiles = [];

function getDateTime() {
    var date = new Date();
    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    var day = date.getDate();
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var seconds = date.getSeconds();
    return String(10000 * year + 100 * month + day +
            ('0'+hours).slice(-2) + ('0'+minutes).slice(-2) + ('0'+seconds).slice(-2));
}

function makeFilename() {
	var chars = "abcdefghiklmnopqrstuvwxyz";
	var len = 8;
	var clen = chars.length;
	var rData = '', i, rnum;
	for (i=0; i<len; i++) {
		rnum = Math.floor(Math.random() * clen);
		rData += chars.substring(rnum,rnum+1);
	}
	return getDateTime() + '_' + rData;
}

function doUpload() {
    var reader = new FileReader();
    var file = uploadedFiles[selectedFilesNum];

    reader.onload = function(readerEvent) {
        var origImage = new Image();

        origImage.onload = function(imageEvent) {
            var canvas = document.createElement("canvas");
            var width = origImage.width;
            var height = origImage.height;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', UploadScript, true);
            var data = new FormData();

            if (imageResizeWidth > 0 && origImage.width > imageResizeWidth) {
                width = imageResizeWidth;
                height = (imageResizeWidth / origImage.width) * origImage.height;

                canvas.width = width;
                canvas.height = height;
                canvas.getContext("2d").drawImage(origImage, 0, 0, width, height);

                var bitmapData = canvas.toDataURL(readerEvent.target.filetype);
                data.append('filehtml5', bitmapData.replace(/^(.*)base64,/, ''));
            }
            else {
                data.append('file', readerEvent.target.file);
            }

            var fileFormat = readerEvent.target.filename.substring(readerEvent.target.filename.lastIndexOf('.'));
            data.append('origname', readerEvent.target.filename);
            data.append('randomname', makeFilename() + fileFormat.toLowerCase());

            var el = document.getElementById('imageListWrapper').getElementsByTagName('div');
            var imgBox = null, imgInfo;

            xhr.onreadystatechange = function(xhrEvent) {
                if (this.readyState === 4 && this.status === 200) {
                    var jsonText = decodeURI(oEditor.trimSpace(this.responseText));
                    jsonText = jsonText.replace(/\+/g, ' ').replace(/\\/g, '\\\\');
                    var jsonData = JSON.parse(jsonText);
                    var img = new Image(), i;

                    img.onload = function() {
                        for (i=0; i < el.length; i++) {
                            imgBox = el[i];

                            if (imgBox.className !== 'imageBox_theImage') {
                                continue;
                            }
                            if (!imgBox.firstChild || imgBox.firstChild.tagName.toLowerCase() !== 'img') {
                                imgInfo = { "width":    this.width,
                                            "height":   this.height,
                                            "fileName": jsonData.fileName,
                                            "fileUrl":  jsonData.fileUrl,
                                            "origName": readerEvent.target.filename,
                                            "filePath": jsonData.filePath,
                                            "fileSize": jsonData.fileSize };

                                imageCompletedList[imgBox.id] = imgInfo;
                                imgComplete(this, imgInfo, imgBox.id);
                                imgBox.appendChild(img);
                                break;
                            }
                        }

                        if (selectedFilesNum < uploadedFiles.length) {
                            doUpload();
                        }
                        else {
                            uploadedFiles = [];
                            selectedFilesNum = 0;
                        }
                    };
                    img.src = decodeURIComponent(jsonData.fileUrl);
                }
            };

            selectedFilesNum++;
            xhr.send(data);
        };

        origImage.src = readerEvent.target.result;
    };

    reader.file = file;
    reader.filename = file.name;
    reader.filetype = file.type;
    reader.readAsDataURL(file);
}

function fileSelect(selected) {
    var files = selected.files;
    var file, i, num = files.length;

    for (i=0; i < num; i++) {
        file = files[i];
        if (!file.type.match('image.*')) {
            fileFilterError(file.name);
            continue;
        }

        uploadedFiles.push(file);
    }

    if (num < 1 || uploadedFiles.length < 1) {
        return;
    }

    startUpload(num);
    doUpload();

    var inputFile = document.getElementById('inputImageUpload');
    var theForm = document.createElement('form');
    var fileSelectButton = document.getElementById('fileSelectButton');
    theForm.appendChild(inputFile);
    theForm.reset();
    fileSelectButton.parentNode.insertBefore(inputFile, fileSelectButton);
    fileSelectButton.style.marginLeft = '-1px';
}

function fileSelectDrop(ev) {
    oEditor.stopEvent(ev);
    this.className = "imageListWrapperHtml5";

    var files = ev.dataTransfer.files;
    var file, i, num = files.length;

    for (i=0; i < num; i++) {
        file = files[i];
        if (!file.type.match('image.*')) {
            fileFilterError(file.name);
            continue;
        }
        uploadedFiles.push(file);
    }

    if (uploadedFiles.length < 1) {
        return;
    }

    startUpload(num);
    doUpload();
}

function dragOver(ev) {
    oEditor.stopEvent(ev);
    this.className = "dragOver";
}

function dragOut(ev) {
    oEditor.stopEvent(ev);
    this.className = "imageListWrapperHtml5";
}

function init(dialog) {
	oEditor = this;
	oEditor.dialog = dialog;
	var dlg = new Dialog(oEditor);
    browser = oEditor.getBrowser();

	UploadImagePath = oEditor.config.iconPath + 'imageUpload';
    uploadMaxNumber = oEditor.config.imgUploadNumber;
	UploadScript = oEditor.config.editorPath + 'imageUpload/upload.php';
	DeleteScript = oEditor.config.editorPath + 'imageUpload/delete.php';

	imageResizeWidth = oEditor.config.imgMaxWidth;
    makeThumbnail = oEditor.config.makeThumbnail;
    makeThumbnailWidth = oEditor.config.thumbnailWidth;
    makeThumbnailHeight = oEditor.config.thumbnailHeight;

    document.getElementById("maxImageNum").appendChild(document.createTextNode(uploadMaxNumber));

    button = [ { alt : "", img : 'submit.gif', cmd : doSubmit, hspace : 2 },
               { alt : "", img : 'cancel.gif', cmd : closeWindow, hspace : 2 } ];

	dlg.setDialogHeight(370);
	dlg.showButton(button);
	showContents();
	initGallery();
	showUploadWindow();
	createInsertionMaker();

    var dropTarget = document.getElementById("imageListWrapper");
    oEditor.addEvent(dropTarget, 'dragover', dragOver);
    oEditor.addEvent(dropTarget, 'dragleave', dragOut);
    oEditor.addEvent(dropTarget, 'drop', fileSelectDrop);

    var elem = document.getElementById('id_alignment').elements;
    var i = 0;
    for (; i<elem.length; i++) {
        if (elem[i].name === "alignment" && elem[i].value === oEditor.config.imgDefaultAlign) {
            elem[i].checked = "checked";
            break;
        }
    }

    if (browser.mobile) {
        var input = document.getElementById('inputImageUpload');
        input.setAttribute('capture', 'gallery');
    }
}
