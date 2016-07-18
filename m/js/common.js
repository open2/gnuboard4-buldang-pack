/**
 * Copyright 2016 Been Kyung-yoon.
 */
/**
 * 그누보드 common.js 에서 모바일용 함수 재선언
 *   - 모바일에선 새창 대신 페이지 이동으로 처리.
 */

/**
 * 쪽지 창
 *
 * @param url
 * @param mb_id
 * @param domain
 */
function win_memo(url, mb_id, domain) {
    if (!url)
        url = "/bbs/memo.php";

    redirect(url);
}

/**
 * 파일 다운로드
 *
 * @param link
 * @param file
 * @returns {boolean}
 */
function file_download(link, file) {
    if (typeof g4_bo_download_point === "undefined" && g4_bo_download_point > 0) {
        if (!confirm("'" + file + "' 파일을 다운로드 하시면 포인트가 차감(" + g4_bo_download_point + "점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?")) {
            return false;
        }
    }
    var a = document.createElement('a');
    a.href = strip_tags(htmlspecialchars_decode(link));

    fileDownload(a.href, file);
}

/**
 * 자기소개 창
 *
 * @param mb_id
 */
function win_profile(mb_id) {
    redirect(g4_path + "/" + g4_bbs + "/profile.php?mb_id=" + mb_id);
}

/**
 * 포인트 창
 *
 * @param url
 */
function win_point(url) {
    redirect(g4_path + "/" + g4_bbs + "/point.php");
}

/**
 * 스크랩 창
 *
 * @param url
 */
function win_scrap(url) {
    if (!url)
        url = g4_path + "/" + g4_bbs + "/scrap.php";
    redirect(url);
}
