/**
 * Copyright 2016 Been Kyung-yoon.
 */
/**
 * 그누보드 common.js 에서 모바일용 함수 재선언
 *   - 모바일에선 새창 대신 페이지 이동으로 처리.
 *   - TODO: 현재처럼 별도 common.js 로 기존 함수를 재선언할 지, 불당팩에 내장하여 common.js 내에서 모바일 분기할지 고민
 */

// 쪽지 창
function win_memo(url, mb_id, domain)
{
    if (!url)
        url = g4_path + "/" + g4_bbs + "/memo.php";

    redirect(url);
}
