<?
include_once("./_common.php");

$g4['title'] = "";
include_once("./_head.php");
?>
<a class="btn btn-default sideview">popover</a>
<a class="btn btn-default sideview" alt="test12">popover</a>

<script>
$(document).ready(function() {

	$('.sideview').popover({
		html: true,
		trigger: 'manual',
		title: 'Sideview&nbsp;<a onclick="$(this).parent().parent().hide();"  style="cursor:pointer"><i class="fa fa-times-circle"></i></a>'
	}).click(function(e) {
		if($(".popover").css("display") == "block"){
			$(this).popover('hide');
		}else{
			$(this).popover('show');

    var el = $(this);
    var _data = "mb_id="+el.attr('alt');
    
			$.ajax({url: "<?=$g4[bbs_path]?>/ajax_sideview.php", type: "POST", data: _data, success: function(response) {
					$(".popover-content").html(response);
				}
			});

		}

    // 한개의 sideview popover만을 원할때
    $('.sideview').not(this).popover('hide');

	})
	;
});
</script>

<?
switch ($mnb) {
    case ''     : include_once("$g4[path]/index.main.php"); break;  // $mnb==""은 메인일때라는거죠.
    default     : include_once("$g4[path]/index.mnb.php"); break;
}

include_once("./_tail.php");
?>