<?php


class AuthGoogle
{
    public $client_id;
    public $client_secret;
    public $redirect_url;
    public $login_url;
    public $url_access_token = 'https://www.googleapis.com/oauth2/v4/token';
    public $Curl_Connection;

    public function __construct($client_id, $client_secret, $redirect_url)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_url = $redirect_url;
        $this->login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email') . '&redirect_uri=' . urlencode($this->getRedirectUrl()) . '&response_type=code&client_id=' . $this->getClientId() . '&access_type=online';
    }

    public function getClientId()
    {
        return $this->client_id;
    }


    public function getClientSecret()
    {
        return $this->client_secret;
    }


    public function getRedirectUrl()
    {
        return $this->redirect_url;
    }


    public function getLoginUrl()
    {
        return $this->login_url;
    }


    public function getAccessToken($code)
    {
        $curlPost = 'client_id=' . $this->client_id . '&redirect_uri=' . $this->redirect_url . '&client_secret=' . $this->client_secret . '&code=' . $code . '&grant_type=authorization_code';
        $this->Curl_Connection = curl_init();
        curl_setopt($this->Curl_Connection, CURLOPT_URL, $this->url_access_token);
        curl_setopt($this->Curl_Connection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->Curl_Connection, CURLOPT_POST, true);
        curl_setopt($this->Curl_Connection, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->Curl_Connection, CURLOPT_POSTFIELDS, $curlPost);
        $data = json_decode(curl_exec($this->Curl_Connection), true);
        $http_code = curl_getinfo($this->Curl_Connection, CURLINFO_HTTP_CODE);

        if ($http_code != 200)
            throw new Exception('Error : Failed to receieve access token');

        return $data;
    }


    public function GetUserProfileInfo($access_token)
    {
        $url = 'https://www.googleapis.com/oauth2/v2/userinfo?fields=name,email,gender,id,picture,verified_email';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 200)
            throw new Exception('Error : Failed to get user information');

        return $data;
    }

}