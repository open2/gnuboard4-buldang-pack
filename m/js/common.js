/**
 * Copyright 2016 Been Kyung-yoon.
 */
/**
 * �״����� common.js ���� ����Ͽ� �Լ� �缱��
 *   - ����Ͽ��� ��â ��� ������ �̵����� ó��.
 */

/**
 * ���� â
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
 * ���� �ٿ�ε�
 *
 * @param link
 * @param file
 * @returns {boolean}
 */
function file_download(link, file) {
    if (typeof g4_bo_download_point === "undefined" && g4_bo_download_point > 0) {
        if (!confirm("'" + file + "' ������ �ٿ�ε� �Ͻø� ����Ʈ�� ����(" + g4_bo_download_point + "��)�˴ϴ�.\n\n����Ʈ�� �Խù��� �ѹ��� �����Ǹ� ������ �ٽ� �ٿ�ε� �ϼŵ� �ߺ��Ͽ� �������� �ʽ��ϴ�.\n\n�׷��� �ٿ�ε� �Ͻðڽ��ϱ�?")) {
            return false;
        }
    }
    var a = document.createElement('a');
    a.href = strip_tags(htmlspecialchars_decode(link));

    fileDownload(a.href, file);
}

/**
 * �ڱ�Ұ� â
 *
 * @param mb_id
 */
function win_profile(mb_id) {
    redirect(g4_path + "/" + g4_bbs + "/profile.php?mb_id=" + mb_id);
}

/**
 * ����Ʈ â
 *
 * @param url
 */
function win_point(url) {
    redirect(g4_path + "/" + g4_bbs + "/point.php");
}

/**
 * ��ũ�� â
 *
 * @param url
 */
function win_scrap(url) {
    if (!url)
        url = g4_path + "/" + g4_bbs + "/scrap.php";
    redirect(url);
}
