<?php

namespace WscStravaApi;

class StravaConnectApi
{
    const BASE_URL = 'https://www.strava.com';

    protected $apiUrl;
    protected $authUrl;
    protected $authTokenUrl;
    protected $clientId;
    protected $clientSecret;
    protected $clientAccessToken;

    public function __construct()
    {
        $this->clientId = get_theme_mod('wsc_api_client_id');
        $this->clientSecret = get_theme_mod('wsc_api_client_secret');
        $this->clientAccessToken = get_theme_mod('wsc_api_access_token');
        $this->apiUrl = self::BASE_URL . '/api/v3';
        $this->authUrl = self::BASE_URL . '/oauth';
        $this->authTokenUrl = self::BASE_URL . '/oauth/token';
    }

    private function oauthResponse()
    {
        if (isset($_GET['code']) && isset($_GET['state'])) {
            $authTokenResponse = wp_remote_post(
                $this->authTokenUrl,
                array(
                    'method' => 'POST',
                    'timeout' => 45,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => array(),
                    'body' => array(
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                        'code' => $_GET['code']
                    ),
                    'cookies' => array()
                )
            );

            if (is_wp_error($authTokenResponse)) {
                $error_message = $authTokenResponse->get_error_message();
                echo "Something went wrong: $error_message";

                return false;
            } else {
                $responseBody = json_decode($authTokenResponse['body']);
                $_SESSION['wscOAuthResponse'] = $responseBody;
                return $responseBody;
            }
        } else {
            return $_SESSION['wscOAuthResponse'];
        }
    }


    private function getApiResponse($url)
    {
        if($_SESSION['wscOAuthResponse']->access_token) {
            $args = array(
                'headers' => array(),
                'body' => array('access_token' => $_SESSION['wscOAuthResponse']->access_token),
            );
            $response = wp_remote_get($url, $args);
            $jsonDecode2 = json_decode($response['body']);

            return $jsonDecode2;
        }

        return false;
    }

    public function getUserActivityStream()
    {
        if ($this->isAuthenticated()) {
            $activityStream = $this->getApiResponse($this->apiUrl . '/activities/?id=' . $_SESSION['wscOAuthResponse']->athlete->id);

            return $activityStream[0];
        }

        return false;
    }

    private function tokenExchange()
    {
        $authTokenResponse = wp_remote_post(
            'https://www.strava.com/oauth/token',
            array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'body' => array(
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'code' => $_SESSION['wscOAuthResponse']->access_token
                ),
                'cookies' => array()
            )
        );

        if (is_wp_error($authTokenResponse)) {
            $error_message = $authTokenResponse->get_error_message();
            echo "Something went wrong: $error_message";

            return false;
        } else {
            $responseBody = json_decode($authTokenResponse['body']);

            return $responseBody;
        }
    }

    public function getUserDetails()
    {
        if ($this->isAuthenticated()) {

            if (!$_SESSION['wscUserDetails']) {
                $activityStream = $this->getApiResponse($this->apiUrl . '/athlete');
                $_SESSION['wscUserDetails'] = $activityStream;
            } else {
                $activityStream = $_SESSION['wscUserDetails'];
            }

            return $activityStream;
        }

        return false;
    }

    public function isAuthenticated()
    {
        $this->oauthResponse();

        if (isset($_SESSION['wscOAuthResponse']) && $_SESSION['wscOAuthResponse']->message != 'Bad Request') {
            return true;
        } else {
            $this->stravaApiLoginButton();

            return false;
        }
    }

    public function stravaApiLoginButton()
    {
        $actual_link = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}/{$_SERVER['REQUEST_URI']}";

        $loginHref = 'https://www.strava.com/oauth/authorize?' .
            'client_id=8004&' .
            'response_type=code' .
            '&redirect_uri=' . $actual_link .
            '&scope=write&' .
            'state=loggedInWithWSC&' .
            'approval_prompt=force';
        ?>
        <p>You need to login first.</p>
        <br/>
        <a href="<?php echo $loginHref; ?>">
            <img src="<?php echo plugins_url('/images/LogInWithStrava.png', __FILE__); ?>"/>
        </a>
    <?php
    }
}