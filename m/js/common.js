/**
 * Copyright 2016 Been Kyung-yoon.
 */
/**
 * �״����� common.js ���� ����Ͽ� �Լ� �缱��
 *   - ����Ͽ��� ��â ��� ������ �̵����� ó��.
 *   - TODO: ����ó�� ���� common.js �� ���� �Լ��� �缱���� ��, �Ҵ��ѿ� �����Ͽ� common.js ������ ����� �б����� ���
 */

// ���� â
function win_memo(url, mb_id, domain)
{
    if (!url)
        url = g4_path + "/" + g4_bbs + "/memo.php";

    redirect(url);
}
