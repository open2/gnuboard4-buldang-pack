<?
require_once 'google-api-php-client/src/Google/autoload.php';

session_start();

$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/plugin/oauth_login/google/oauth2callback.php';

$client = new Google_Client();
$client->setAuthConfigFile('client_secrets.json');
$client->setRedirectUri($redirect_uri);
$client->addScope("https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile");

if (! isset($_GET['code'])) {
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();

    // ������ email ������ �о� ���ϴ�.
    $oauth = new Google_Service_Oauth2($client);
    $mb_email = $oauth->userinfo->get()->email;

    // �α����� ���� �մϴ�.

    // ������ �Ŀ� �̵��ϴ� ������
    $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/bbs/login.php';
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
?>