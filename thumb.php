<?
include_once("./lib/thumb.lib.php");

echo "source: 800x600 image file의 썸네일 예제 입니다</BR></BR>";

// $file_name   : 파일명
// $width       : 썸네일의 폭
// $height      : 썸네일의 높이 (지정하지 않으면 썸네일의 넓이를 사용)
//                * $width, $height에 모두 값이 없으면, 이미지 사이즈 그대로 thumb을 생성
// $is_create   : 썸네일이 이미 있을 때, 새로 생성할지 여부를 결정
// $is_crop     : 세로 높이가 $height를 넘을 때 crop 할 것인지를 결정
//                1 : 기본 crop
//                2 : 중간을 기준으로 crop
// $quality     : 썸네일의 quality (jpeg, png에만 해당하며, gif에는 해당 없슴)
// $small_thumb : true(1)이면, 이미지가 썸네일의 폭/높이보다 작을 때에도 썸을 생성
// $watermark   : 워터마크 출력에 대한 설정 
//                $watermark[][filename] - 워터마크 파일명
//                $watermark[location] - center, top, top_left, top_right, bottom, bottom_left, bottom_right
//                $watermark[x],$watermark[y] - location에서의 offset
// $filter      : php imagefilter, http://kr.php.net/imagefilter
//                $filter[type], [arg1] ... [arg4]
// $noimg       : noimage, $noimg[type] = text(텍스트출력), img(이미지파일), nothing(아무것도 안함)
//
//function thumbnail($file_name, $width=0, $height=0, $is_create=false, $is_crop=2, $quality=70, $small_thumb=true, $watermark="", $filter="", $noimg="") 
?>

<?
$noimg = "/img/search_top.gif";
?>
<img src="<?=thumbnail('/data/file/nothing.jpg', 600, 700, 0, 0, 75, 1, "", "", $noimg)?>" >
<BR>600*700 no image, image file<BR>

<img src="<?=thumbnail('/data/file/800-600.jpg', 600, 700, 0, 0, 75, 1)?>" >
<BR>600*700 Quality 75, no filter<BR>

<?
// case  IMG_FILTER_UBSHARPMASK: UnsharpMask($target, $filter[arg1], $filter[arg2], $filter[arg3]);
// function UnsharpMask($img, $amount, $radius, $threshold)
$filter[type] = 99;
$filter[arg1] = 80;
$filter[arg2] = 0.5;
$filter[arg3] = 3;
?>
<img src="<?=thumbnail('/data/file/800-600.jpg', 600, 700, 0, 0, 74, 1,"", $filter)?>" >
<BR>600*700 Quality 74, unsharp-mask filter<BR>

<img src="<?=thumbnail('/data/file/', 600, 700, 12)?>" >
<BR>600*700 크기 그대로 no-image<BR>

<?
$watermark[0][filename] = "./data/file/watermark/watermark.png";
$watermark[0][location] = "bottom_right";
$watermark[0][x] = 20;
$watermark[0][y] = 20;
$watermark[1][filename] = "./data/file/watermark/watermark.png";
$watermark[1][location] = "center";
$watermark[1][x] = 0;
$watermark[1][y] = 0;

$filter[type] = IMG_FILTER_GRAYSCALE;
?>
<img src="<?=thumbnail('/data/file/800-600.jpg', 600, 700, 0, 0, 50, 1, $watermark, $filter)?>" >
<BR>600*700 크기 그대로 썸네일 만들기 (50% quality, watermark - bottom_right + center, IMG_FILTER_GRAYSCALE filter)<BR>

<?
$watermark[0][filename] = "./data/file/watermark/watermark.png";
$watermark[0][location] = "bottom_right";
$watermark[0][x] = 20;
$watermark[0][y] = 20;
$watermark[1][filename] = "./data/file/watermark/watermark.png";
$watermark[1][location] = "center";
$watermark[1][x] = 0;
$watermark[1][y] = 0;

$filter[type] = IMG_FILTER_GAUSSIAN_BLUR;
?>
<img src="<?=thumbnail('/data/file/800-600.jpg', 600, 700, 1, 0, 60, 1, $watermark, $filter)?>" >
<BR>600*700 크기 그대로 썸네일 만들기 (60% quality, watermark - bottom_right + center, IMG_FILTER_GAUSSIAN_BLUR filter)<BR>
밑의 필터가 없는 것과 비교를 해보세요.<BR>

<?
$watermark[0][filename] = "./data/file/watermark/watermark.png";
$watermark[0][location] = "bottom_right";
$watermark[0][x] = 20;
$watermark[0][y] = 20;
$watermark[1][filename] = "./data/file/watermark/watermark.png";
$watermark[1][location] = "center";
$watermark[1][x] = 0;
$watermark[1][y] = 0;

?>
<img src="<?=thumbnail('/data/file/800-600.jpg', 600, 700, 0, 0, 69,1,$watermark)?>" >
<BR>600*700 크기 그대로 썸네일 만들기 (watermark - bottom_right + center)<BR>

<?
$watermark[0][filename] = "./data/file/watermark/watermark.png";
$watermark[0][location] = "bottom_right";
$watermark[0][x] = 20;
$watermark[0][y] = 20;
?>
<img src="<?=thumbnail('/data/file/800-600.jpg', 600, 700, 0, 0, 70,1,$watermark)?>" >
<BR>600*700 크기 그대로 썸네일 만들기 (watermark - bottom_right)<BR>

<?
$watermark = array();
$watermark[0][filename] = "./data/file/watermark/watermark.png";
$watermark[0][location] = "bottom_left";
$watermark[0][x] = 20;
$watermark[0][y] = 20;
?>
<img src="<?=thumbnail('/data/file/800-600.jpg', 600, 700, 0, 0, 71,1,$watermark)?>" >
<BR>600*700 크기 그대로 썸네일 만들기 (watermark - bottom_left)<BR>

<?
$watermark[0][filename] = "./data/file/watermark/watermark.png";
$watermark[0][location] = "bottom";
$watermark[0][x] = 0;
$watermark[0][y] = 20;
?>
<img src="<?=thumbnail('/data/file/800-600.jpg', 600, 700, 0, 0, 72,1,$watermark)?>" >
<BR>600*700 크기 그대로 썸네일 만들기 (watermark - bottom)<BR>

<?
$watermark[0][filename] = "./data/file/watermark/watermark.png";
$watermark[0][location] = "center";
$watermark[0][x] = 0;
$watermark[0][y] = 0;
?>
<img src="<?=thumbnail('/data/file/800-600.jpg', 600, 700, 0, 0, 73,1,$watermark)?>" >
<BR>600*700 크기 그대로 썸네일 만들기 (watermark - center)<BR>

<?
$watermark[0][filename] = "./data/file/watermark/watermark.png";
$watermark[0][location] = "top";
$watermark[0][x] = 0;
$watermark[0][y] = 0;
?>
<img src="<?=thumbnail('/data/file/800-600.jpg', 600, 700, 0, 0, 74,1,$watermark)?>" >
<BR>600*700 크기 그대로 썸네일 만들기 (watermark - top)<BR>

<?
$watermark[0][filename] = "./data/file/watermark/watermark.png";
$watermark[0][location] = "top_left";
$watermark[0][x] = 20;
$watermark[0][y] = 20;
?>
<img src="<?=thumbnail('/data/file/800-600.jpg', 600, 700, 0, 0, 75,1,$watermark)?>" >
<BR>600*700 크기 그대로 썸네일 만들기 (watermark - top_left)<BR>

<?
$watermark[0][filename] = "./data/file/watermark/watermark.png";
$watermark[0][location] = "top_right";
$watermark[0][x] = 20;
$watermark[0][y] = 20;
?>
<img src="<?=thumbnail('/data/file/800-600.jpg', 600, 700, 0, 0, 76,1,$watermark)?>" >
<BR>600*700 크기 그대로 썸네일 만들기 (watermark - top_right)<BR>

<img src="<?=thumbnail('/data/file/800-600.jpg', 400, 300, 0, 0, 100)?>" >
<BR>400*300 크기 그대로 썸네일 만들기 (quality 100)<BR>

<img src="<?=thumbnail('/data/file/800-600.jpg', 401, 301, 0, 0, 20)?>" >
<BR>401*301 크기 그대로 썸네일 만들기 (quality 20)<BR>

<img src="<?=thumbnail('./data/file/800-600.jpg')?>" >
<BR>800*600 크기 그대로 썸네일 만들기<BR>

<img src="<?=thumbnail('./data/file/800-600.jpg', 1000,1000)?>" >
<BR>1000*1000으로 지정한썸네일 만들기<BR>

<img src="<?=thumbnail('./data/file/800-600.jpg', 300)?>" >
<BR>폭만 300으로 지정한썸네일 만들기<BR>

<img src="<?=thumbnail('./data/file/800-600.jpg', 0,100)?>" >
<BR>높이만 100으로 지정한썸네일 만들기<BR>

<img src="<?=thumbnail('./data/file/800-600.jpg',0,120,0,1)?>" >
<BR>높이만 120으로 지정한썸네일 만들기(crop)<BR>

<img src="<?=thumbnail('./data/file/800-600.jpg', 200,200,0,1)?>" >
<BR>200*200으로 지정한썸네일 만들기(crop)<BR>

<img src="<?=thumbnail('./data/file/800-600.jpg', 300,100)?>" >
<BR>300*100으로 지정한썸네일 만들기<BR>

<img src="<?=thumbnail('./data/file/800-600.jpg', 300,300)?>" >
<BR>300*300으로 지정한썸네일 만들기<BR>

<img src="<?=thumbnail('./data/file/800-600.jpg', 1000,300)?>" >
<BR>1000*300으로 지정한썸네일 만들기<BR>

<img src="<?=thumbnail('./data/file/800-600.jpg', 100,300)?>" >
<BR>100*300으로 지정한썸네일 만들기<BR>

<img src="<?=thumbnail('./data/file/800-600.jpg', 1000,200,0,1)?>" >
<BR>1000*200으로 지정한썸네일 만들기 (crop)<BR>
