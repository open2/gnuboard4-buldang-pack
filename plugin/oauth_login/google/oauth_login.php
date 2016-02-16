<?php
require_once 'google-api-php-client/src/Google/autoload.php';

$client = new Google_Client();
$client->setAuthConfigFile('client_secrets.json');
$client->addScope("https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile");

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
    $drive_service = new Google_Service_Drive($client);
    $files_list = $drive_service->files->listFiles(array())->getItems();
    echo json_encode($files_list);
} else {
    $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/plugin/oauth_login/google/oauth2callback.php';
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
?>