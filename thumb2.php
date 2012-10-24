<?
include_once("./lib/thumb.lib.php");

// $file_name   : 파일명
// $width       : 썸네일의 폭
// $height      : 썸네일의 높이 (지정하지 않으면 썸네일의 넓이를 사용)
//                * $width, $height에 모두 값이 없으면, 이미지 사이즈 그대로 thumb을 생성
// $is_create   : 썸네일이 이미 있을 때, 새로 생성할지 여부를 결정
// $is_crop     : 세로 높이가 $height를 넘을 때 crop 할 것인지를 결정
//                0 : crop 하지 않습니다
//                1 : 기본 crop
//                2 : 중간을 기준으로 crop
// $quality     : 썸네일의 quality (jpeg, png에만 해당하며, gif에는 해당 없슴)
// $small_thumb : 1 (true)이면, 이미지가 썸네일의 폭/높이보다 작을 때에도 썸을 생성
//                2이면, 이미지가 썸네일의 폭/높이보다 작을 때 확대된 썸을 생성
// $watermark   : 워터마크 출력에 대한 설정 
//                $watermark[][filename] - 워터마크 파일명
//                $watermark[location] - center, top, top_left, top_right, bottom, bottom_left, bottom_right
//                $watermark[x],$watermark[y] - location에서의 offset
// $filter      : php imagefilter, http://kr.php.net/imagefilter
//                $filter[type], [arg1] ... [arg4]
// $noimg       : $noimg(이미지파일)
?>

<img src="<?=thumbnail('./data/file/btn_go.gif', 200,200,0,0,70,2)?>" >
<BR>20*20을 200*200으로 지정한썸네일 만들기 (확대)<BR>

<img src="<?=thumbnail('./data/file/btn_go.gif', 200,0,1,0,69,2)?>" >
<BR>20*20을 200*0으로 지정한썸네일 만들기 (확대)<BR>

<img src="<?=thumbnail('./data/file/800-600.jpg', 0,100, 0, 0, 56)?>" >
<BR>높이만 100으로 지정한썸네일 만들기 (crop 하지 않으면 높이 기준으로 썸네일 생성)<BR>

<img src="<?=thumbnail('./data/file/800-600.jpg', 1000, 0, 0, 0, 55)?>" >
<BR>폭만 1000으로 지정한썸네일 만들기 (원본 800*600)<BR>

<img src="<?=thumbnail('./data/file/800-600.jpg', 1001, 0, 0, 0, 54)?>" >
<BR>폭만 1000으로 지정한썸네일 만들기 (원본 800*600)<BR>

<img src="<?=thumbnail('./data/file/800-600.jpg', 400, 1000, 0, 0, 54, "", "", "", "", "jpg")?>" >
<BR>폭만 400으로 지정한썸네일 만들어서 jpg로 저장하기 (원본 800*600)<BR>
