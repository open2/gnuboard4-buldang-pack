<?
// ����� : echo me2do("http://buspang.kr/lib/me2do.lib.php");
// �������� : http://opencode.co.kr/bbs/board.php?bo_table=gnu4_tips&wr_id=928
// key����? : config.2.php �־��ָ� �˴ϴ�.

function me2do($url) {
    global $g4;

    // ���̹� openapi ������� + urlencode�� �ּ�(urlencode���ϸ� �ּҰ� ���ư�)
  	$in = "http://openapi.naver.com/shorturl.xml?key=$g4[me2do_key]&url=" . urlencode($url);
    $xml = simplexml_load_file($in);
    return $xml->result->url;
}
?>