<?
// 판매자 등록을 하면, 추천/비추천을 할 수 없게 합니다.
// 어뷰징을 막기위한 최소한의 조치 입니다.
if ($member[mb_id] && !$is_admin)
    if ($member['mb_seller_datetime'] !== "0000-00-00 00:00:00") {
        $board[bo_use_good] = false;
        $board[bo_use_nogood] = false;
}
?>
