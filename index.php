<?
include_once("./_common.php");

$g4['title'] = "";
include_once("./_head.php");
?>
<a class="btn btn-default sideview" data-toggle="popover" title="" data-content="1111">popover</a>
<a class="btn btn-default sideview" data-toggle="popover" title="" data-content="2222">popover</a>

<script>
$(function (){
   $(".sideview").popover("hide");
});
</script>

<?
switch ($mnb) {
    case ''     : include_once("$g4[path]/index.main.php"); break;  // $mnb==""은 메인일때라는거죠.
    default     : include_once("$g4[path]/index.mnb.php"); break;
}

include_once("./_tail.php");
?>