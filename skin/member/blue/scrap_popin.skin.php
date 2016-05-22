<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

// 스크랩 메모별로 정렬하기
$sql = " select distinct ms_memo from $g4[scrap_table] where mb_id = '$member[mb_id]' and ms_memo != '' ";
$result = sql_query($sql);
$memo_str = "<select class='form-control' name='ms_memo' onchange=\"javascript:document.getElementById('wr_content').value=this.value;\">";
$memo_str .= "<option value=''>내가 사용하는 참고메모 선택하기</option>";
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $memo_str .= "<option value='$row[ms_memo]'";
        $memo_str .= ">" . cut_str($row[ms_memo],60,'') . "</option>";
    }
    $memo_str .= "</select>";
?>
<div class="container">
<div class="panel panel-default">
    <div class="panel-heading">
    <strong>스크랩하기</strong>
    </div>
    <div class="panel-body">
        <form name=f_scrap_popin method=post action="./scrap_popin_update.php" role="form" class="form-inline">
        <input type="hidden" name="bo_table" value="<?=$bo_table?>">
        <input type="hidden" name="wr_id"    value="<?=$wr_id?>">
        <input type="hidden" name="wr_mb_id" value="<?=$write[mb_id]?>">
        <input type="hidden" name="wr_subject" value="<?=$write[wr_subject]?>">

        <table class="table table-hover" width=100%>
        <tr class="success">
            <td class="col-sm-2">제목</td>
            <td><?=get_text(cut_str($write[wr_subject], 255))?></td>
        </tr>
        <tr>
            <td>참고메모</td>
            <td>
                <?=$memo_str?>
                <textarea class="form-control" name="wr_content" id="wr_content" rows="3" style="width:90%;"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan=2>
            <ul>
                <li>참고메모는 스크랩을 분류 및 검색할 때 쓰는 키워드 입니다 (예: 스크랩).</li>
                <li>새로운 참고메모는 직접 입력해야 합니다.</li>
                <li>참고메모를 입력하지 않아도 되지만, 편한 사용을 위해서 입력하면 좋습니다.</li>
            </ul>
            </td>
        </tr>
        </table>

        <div class="container"  style="display: inline-block;text-align: center;">
            <button type="submit" class="btn btn-default">확인</button>
            &nbsp;&nbsp;
            <a class="btn btn-default" href="javascript:window.close();" >닫기</a>
        </div>
        </form>
    </div>
</div>
</div>