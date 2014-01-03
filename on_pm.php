<!DOCTYPE HTML>
<html lang="ko">
<head>
<?
// 이곳에서 설정을 합니다.
$g4['title'] = "On PM - 시스템 점검중입니다";
$g4['path'] = ".";
$g4['charset'] = "euc-kr";
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="content-type" content="text/html;charset=<?=$g4['charset']?>">
<title><?=$g4['title']?></title>

<link rel="stylesheet" href="<?=$g4['path']?>/js/bootstrap/css/bootstrap.min.css?bver=<?=$g4[bver]?>" type="text/css" media="screen" title="no title" charset="<?=$g4[charset]?>">
<link rel="stylesheet" href="<?=$g4['path']?>/js/font-awesome/css/font-awesome.min.css?aver=<?=$g4[aver]?>" type="text/css" media="screen" title="no title" charset="<?=$g4[charset]?>">
<!--[if lt IE 7]>
    <script src="<?=$g4['path']?>/js/font-awesome/css/font-awesome-ie7.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?=$g4['path']?>/style.css?sver=<?=$g4[sver]?>" type="text/css">

<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="<?=$g4['path']?>/js/bootstrap/js/bootstrap.min.js"></script>
<!--[if lt IE 9]>
    <script src="<?=$g4['path']?>/js/html5shiv/html5shiv.js"></script>
    <script src="<?=$g4['path']?>/js/respond/respond.min.js"></script>
<![endif]-->
</head>

<body>
<a class="sr-only" href="#content"><?=$g4['title']?></a>

<div class="panel panel-info">
    <div class="panel-heading">
        <h1><span class="glyphicon glyphicon-info-sign"></span> 정기점검중입니다 :)</h1>
        <p>안정적이고 더 나은 서비스를 위하여 정기점검을 실시합니다.<br>
        불편을 드려 죄송합니다.
        </p>
    </div>
    <div class="panel-body">
        <div><h4><span class="glyphicon glyphicon-calendar"></span> <strong>점검 일정</strong></h4>
         2013/12월1일 01시00분~2013/12월 2일 01시00분
        </div>
        <div><h4><span class="glyphicon glyphicon-phone-alt"></span> <strong>긴급 연락</strong></h4>
         opencode@opencode.co.kr
        </div>
    </div>
</div>

</body>
</html>