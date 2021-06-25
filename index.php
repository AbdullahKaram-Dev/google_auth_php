<?php

require 'AuthGoogle.php';

define('CLIENT_ID', '636978555444-i0qbb2pu4to5t5umpf0ee8lrnbt85cq8.apps.googleusercontent.com');
define('CLIENT_SECRET', 'ggQdZopyI6U5buHAHMBxh3z5');
define('CLIENT_REDIRECT_URL', 'http://localhost/google-auth/index.php');

$google_auth = new AuthGoogle(CLIENT_ID,CLIENT_SECRET,CLIENT_REDIRECT_URL);

if(isset($_GET['code'])) {
    try {

        $access_token = $google_auth->GetAccessToken($_GET['code'])['access_token'];
        $user_info = $google_auth->GetUserProfileInfo($access_token);

        echo "<pre>";
        print_r($user_info);


        echo $user_info['id'] . "<br>";
        echo $user_info['email'] . "<br>";
        echo $user_info['name'] . "<br>";
        echo "<img src=" . $user_info['picture'] . ">";
        die;

    } catch (Exception $e) {

        echo $e->getMessage();
        exit();
    }
}






?>



<a href="<?= $google_auth->getLoginUrl() ?>"  target="_blank">Google Login</a>





