<? if ($is_member) { ?>

<a class="btn btn-default" href="javascript:my_menu_add()" style="margin:0 10px 0 0;"><i class="fa fa-thumb-tack"></i></a>

<script language=javascript>
function my_menu_add() { 
if (confirm("'<?=$board[bo_subject]?>' 게시판 바로가기를 등록하시겠습니까?")) { 
   hiddenframe.location.href = "../bbs/my_menu_add.php?bo_table=<?=$bo_table?>";
}}
</script>

<? } ?>
