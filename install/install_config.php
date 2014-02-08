<?
$g4['path'] = "..";
include_once ("../config.php");

// 파일이 존재한다면 설치할 수 없다.
if (file_exists("../dbconfig.php")) {
    echo "<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'>";
    echo <<<HEREDOC
    <script language="JavaScript">
    alert("설치하실 수 없습니다.");
    location.href="../";
    </script>
HEREDOC;
    exit;
}

$gmnow = gmdate("D, d M Y H:i:s") . " GMT";
header("Expires: 0"); // rfc2616 - Section 14.21
header("Last-Modified: " . $gmnow);
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: pre-check=0, post-check=0, max-age=0"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

if ($_POST["agree"] != "동의함") {
    echo "<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'>";
    echo <<<HEREDOC
    <script language="JavaScript">
    alert("라이센스(License) 내용에 동의하셔야 설치를 계속하실 수 있습니다.");
    history.back();
    </script>
HEREDOC;
    exit;
}
?>
<!DOCTYPE HTML>
<html lang="ko">
<head>
<meta http-equiv="content-type" content="text/html; charset=<?=$g4['charset']?>">
<title>그누보드4 설치 (2/3) - 라이센스(License)</title>

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

<form name=frm method=post action="javascript:frm_submit(document.frm)" autocomplete="off">

<div class="container" style="width:621px;margin-top:100px;">

<div class="panel panel-primary">
<div class="panel-heading">
    <strong>그누보드4 설치 (2/3)</strong>
</div>
<div class="panel-body">

    <table width=100% class="table table-condensed table-hover table-borderless" style="word-wrap:break-word;">
    <tr>
        <td width=45%>
            <!-- TAB 순서 때문에 테이블을 2개로 -->
            <table width=100% class="table table-condensed table-hover table-borderless" style="word-wrap:break-word;">
            <tr>
                <td colspan=2><strong>MySQL 정보입력</strong></td>
            </tr>
            <tr>
                <td width=80>Host :</td>
                <td>
                    <input name="mysql_host" type="text" class="form-control" value="localhost">
                </td>
            </tr>
            <tr>
                <td>User :</td>
                <td>
                    <input name="mysql_user" type="text" class="form-control" placeholder="MySQL 사용자명">
                </td>
            </tr>
            <tr>
                <td>Password :</td>
                <td>
                    <input name="mysql_pass" type="text" class="form-control" placeholder="MySQL 접속 패스워드">
                </td>
            </tr>
            <tr>
                <td>DB :</td>
                <td>
                    <input name="mysql_db" type="text" class="form-control" placeholder="MySQL DB 이름">
                </td>
            </tr>
            </table>

        </td>
        <td width=5%></td>
        <td width=45%>

            <table width=100% class="table table-condensed table-hover table-borderless" style="word-wrap:break-word;">
            <tr>
                <td colspan=2><strong>최고관리자 정보입력</strong></td>
            </tr>
            <tr>
                <td width=80>ID :</td>
                <td>
                    <input name="admin_id" type="text" class="form-control" value="admin" onkeypress="only_alpha_numeric();">
                </td>
            </tr>
            <tr>
                <td>Password :</td>
                <td>
                    <input name="admin_pass" type="text" class="form-control" placeholder="관리자 패스워드">
                </td>
            </tr>
            <tr>
                <td>Name :</td>
                <td>
                    <input name="admin_name" type="text" class="form-control" value="최고관리자" placeholder="최고관리자 이름">
                </td>
            </tr>
            <tr>
                <td>E-mail :</td>
                <td>
                    <input name="admin_email" type="text" class="form-control" value="admin@domain.domain">
                </td>
            </tr>
            </table>

        </td>
        <td width=5%></td>
    </tr>
    </table>

    <p class="text-danger">
    이미 그누보드4가 존재한다면 DB 자료가 망실되므로 주의하십시오.
    </p>

    <div class="pull-right" style="margin-bottom:10px;">
        <input type="submit" name="submit2" class="btn btn-default" value=" 다   음 ">
    </div>
</div>
<div class="panel-footer">
</div>
</div>
</form>

</div><!-- end of container -->

<script type="text/javascript">
<!--
function frm_submit(f)
{
    if (f.mysql_host.value == "")
    {   
        alert("MySQL Host 를 입력하십시오."); f.mysql_host.focus(); return; 
    }
    else if (f.mysql_user.value == "")
    {
        alert("MySQL User 를 입력하십시오."); f.mysql_user.focus(); return; 
    }
    else if (f.mysql_db.value == "")
    {
        alert("MySQL DB 를 입력하십시오."); f.mysql_db.focus(); return; 
    }
    else if (f.admin_id.value == "")
    {
        alert("최고관리자 ID 를 입력하십시오."); f.admin_id.focus(); return; 
    }
    else if (f.admin_pass.value == "")
    {
        alert("최고관리자 패스워드를 입력하십시오."); f.admin_pass.focus(); return; 
    }
    else if (f.admin_name.value == "")
    {
        alert("최고관리자 이름을 입력하십시오."); f.admin_name.focus(); return; 
    }
    else if (f.admin_email.value == "")
    {
        alert("최고관리자 E-mail 을 입력하십시오."); f.admin_email.focus(); return; 
    }

    if(/[^a-zA-Z0-9]/g.test(f.admin_id.value)) {
        alert("최고관리자 ID 가 영문자 및 숫자가 아닙니다.");
        f.admin_id.focus();
    }

    f.action = "./install_db.php";
    f.submit();

    return true;
}

// 영문자만 입력 가능   
function only_alpha() 
{
    var c = event.keyCode;
    if (!(c >= 65 && c <= 90 || c >= 97 && c <= 122)) {
        event.returnValue = false;
    }
}

// 영문자와 숫자만 입력 가능   
function only_alpha_numeric() 
{
    var c = event.keyCode;
    if (!(c >= 65 && c <= 90 || c >= 97 && c <= 122 || c >= 48 && c <= 57)) {
        event.returnValue = false;
    }
}

document.frm.mysql_user.focus();
//-->
</script>

</body>
</html>
