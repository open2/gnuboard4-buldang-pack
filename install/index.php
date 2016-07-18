<?
$g4['path'] = "..";
include_once ("../config.php");

// 퍼미션을 다음과 같은 형식으로 얻는다. drwxrwxrwx
function get_perms($mode)
{
    /* Determine Type */
    if( $mode & 0x1000 )
        $perms["type"] = 'p'; /* FIFO pipe */
    else if( $mode & 0x2000 )
        $perms["type"] = 'c'; /* Character special */
    else if( $mode & 0x4000 )
        $perms["type"] = 'd'; /* Directory */
    else if( $mode & 0x6000 )
        $perms["type"] = 'b'; /* Block special */
    else if( $mode & 0x8000 )
        $perms["type"] = '-'; /* Regular */
    else if( $mode & 0xA000 )
        $perms["type"] = 'l'; /* Symbolic Link */
    else if( $mode & 0xC000 )
        $perms["type"] = 's'; /* Socket */
    else
        $perms["type"] = 'u'; /* UNKNOWN */

    /* Determine permissions */
    $perms["owner_read"]    = ($mode & 00400) ? 'r' : '-';
    $perms["owner_write"]   = ($mode & 00200) ? 'w' : '-';
    $perms["owner_execute"] = ($mode & 00100) ? 'x' : '-';
    $perms["group_read"]    = ($mode & 00040) ? 'r' : '-';
    $perms["group_write"]   = ($mode & 00020) ? 'w' : '-';
    $perms["group_execute"] = ($mode & 00010) ? 'x' : '-';
    $perms["world_read"]    = ($mode & 00004) ? 'r' : '-';
    $perms["world_write"]   = ($mode & 00002) ? 'w' : '-';
    $perms["world_execute"] = ($mode & 00001) ? 'x' : '-';

    /* Adjust for SUID, SGID and sticky bit */
    if( $mode & 0x800 )
        $perms["owner_execute"] = ($perms["owner_execute"]=='x') ? 's' : 'S';
    if( $mode & 0x400 )
        $perms["group_execute"] = ($perms["group_execute"]=='x') ? 's' : 'S';
    if( $mode & 0x200 )
        $perms["world_execute"] = ($perms["world_execute"]=='x') ? 't' : 'T';

    return $perms;
}

// 파일이 존재한다면 설치할 수 없다.
if (file_exists("../dbconfig.php")) {
    echo "<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'>";    
    echo <<<HEREDOC
    <script type="text/javascript">
    alert("dbconfig.php가 있어서 설치하실 수 없습니다.\n설치하시려면 dbconfig.php를 지우세요.\n새로 설치를 하면 db는 모두 삭제될 수 있습니다.");
    location.href="../";
    </script>
HEREDOC;
    exit;
}

// 루트 디렉토리에 파일 생성 가능한지 검사.
if (!is_writeable("..")) 
{
    echo "<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'>";
    echo "<script language='JavaScript'>alert('루트 디렉토리의 퍼미션을 707로 변경하여 주십시오.\\n\\ncommon.php 파일이 있는곳이 루트 디렉토리 입니다.\\n\\n$> chmod 707 . \\n\\n그 다음 설치하여 주십시오.');</script>"; 
    exit;
}
?>
<!DOCTYPE HTML>
<html lang="ko">
<head>
<meta http-equiv="content-type" content="text/html; charset=<?=$g4['charset']?>">
<title>그누보드4 설치 (1/3) - 라이센스(License)</title>

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

<body background="img/all_bg.gif" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div class="container" style="width:621px;margin-top:100px;">

<div class="panel panel-primary">
<div class="panel-heading">
    <strong>그누보드4 설치 (1/3)</strong>
</div>
<div class="panel-body">
    <p>
    라이센스(License) 내용을 반드시 확인하십시오.
    </p>
    <textarea name="textarea" style='width:99%;margin-bottom:10px;' rows="9" class="box" readonly><?=implode("", file("../LICENSE"));?></textarea>
    <textarea name="textarea" style='width:99%' rows="9" class="box" readonly><?=implode("", file("../LICENSE_BD"));?></textarea>
    
    <p style="margin-top:5px;" class="text-danger">
    설치를 원하시면 위 내용에 동의하셔야 합니다.<br>
    동의를 원하시면 &lt;예, 동의합니다&gt; 버튼을 클릭해 주세요.
    </p>

    <div class="pull-right">
        <form name=frm method=post onsubmit="return frm_submit(document.frm);" autocomplete="off" role="form" class="form-inline">
        <input type="hidden" name="agree" value="동의함">
        <input type="submit" name="btn_submit" class="btn btn-default" value="예, 동의합니다 ">
        </form>
    </div>

</div>
<div class="panel-footer">
</div>

</div>

</div><!-- end of container -->

<script  type="text/javascript">
function frm_submit(f)
{
    f.action = "./install_config.php";
    f.submit();
}

document.frm.btn_submit.focus();
</script>

</body>
</html>
