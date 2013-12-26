<?
include_once("./_common.php");

$g4['title'] = "";
include_once("./_head.php");
?>
<a class="btn btn-default sideview">popover</a>
<a class="btn btn-default sideview" alt="test12">popover</a>

<script>
$(document).ready(function() {
  $(".sideview").click(function() {
    var el = $(this);
    var _data = "mb_id="+el.attr('alt');
    $.ajax({url: "<?=$g4[bbs_path]?>/ajax_sideview.php", type: "POST", data: _data, success: function(response) {
      el.unbind('click').popover({
        content: response,
        title: 'Sideview&nbsp;<a onclick="$(this).parent().parent().hide();" style="cursor:pointer"><i class="fa fa-times-circle"></i></a>',
        html: true,
      }).popover('show');
      }
    });
  });
});

</script>

<?
switch ($mnb) {
    case ''     : include_once("$g4[path]/index.main.php"); break;  // $mnb==""은 메인일때라는거죠.
    default     : include_once("$g4[path]/index.mnb.php"); break;
}

include_once("./_tail.php");
?>