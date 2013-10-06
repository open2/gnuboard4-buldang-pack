// http://blueb.co.kr/bbs.php?table=JS_15&query=view&uid=10
var clickmessage="그림에는 오른쪽마우스버튼을 사용할 수 없습니다."
function disableclick(e) {
    if (document.all) {
    if (event.button==2||event.button==3) {
    if (event.srcElement.tagName=="IMG"){
        alert(clickmessage);
        return false;
        }
    }
}
    else if (document.layers) {
    if (e.which == 3) {
        alert(clickmessage);
        return false;
    }
}
    else if (document.getElementById){
    if (e.which==3&&e.target.tagName=="IMG"){
        alert(clickmessage)
        return false
        }
    }
}
function associateimages(){
    for(i=0;i<document.images.length;i++)
        document.images[i].onmousedown=disableclick;
}
    if (document.all)
        document.onmousedown=disableclick
    else if (document.getElementById)
        document.onmouseup=disableclick
    else if (document.layers)
        associateimages()

// F5키를 금지하기, http://phpschool.com/gnuboard4/bbs/board.php?bo_table=tipntech&wr_id=68565
document.onkeydown = function(e) { 
  var evtK = (e) ? e.which : window.event.keyCode; 
  var isCtrl = ((typeof isCtrl != 'undefined' && isCtrl) || ((e && evtK == 17) || (!e && event.ctrlKey))) ? true : false; 

  if ((isCtrl && evtK == 82) || evtK == 116) { 
    if (e) { evtK = 505; } else { event.keyCode = evtK = 505; } 
  } 
  if (evtK == 505) { 
    return false; 
  }
}