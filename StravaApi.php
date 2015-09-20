<?php

namespace WscStravaApi;

class StravaOAuth
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

    protected function oauthResponse()
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


    protected function getApiResponse($url, $userId = null)
    {
        if ($_SESSION['wscOAuthResponse']->access_token) {
            $theArgs = array(
                'body' => array(
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'access_token' => $_SESSION['wscOAuthResponse']->access_token,
                    'id' => ($userId) ? $userId : null,
                    'per_page' => 20
                )
            );
            $response = wp_remote_get($url, $theArgs);
            $jsonDecode2 = json_decode($response['body']);

            return $jsonDecode2;
        }

        return false;
    }

    protected function isAuthenticated()
    {
        $this->oauthResponse();

        if (isset($_SESSION['wscOAuthResponse']) && $_SESSION['wscOAuthResponse']->message != 'Bad Request') {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Todo: use this for later when we want to pass more arguments to "getApiResponse"

    protected function apiRequestParameters($paramValues)
    {
        $asdf = $paramValues;
        return $asdf;
    }
    */
}

class StravaUser extends StravaOAuth
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getUserActivity($rideId)
    {
        if ($this->isAuthenticated()) {
            $activityStream = $this->getApiResponse($this->apiUrl . '/activities/'.$rideId);

            return $activityStream;
        } else {
            $this->stravaConnectLogin();

            return false;
        }
    }

    public function getUserActivityStream()
    {
        if ($this->isAuthenticated()) {
            if (!$_SESSION['wscUserActivities']) {
                $activityStream = $this->getApiResponse($this->apiUrl . '/activities', $_SESSION['wscOAuthResponse']->athlete->id);
                $_SESSION['wscUserActivities'] = $activityStream;
            } else {
                $activityStream = $_SESSION['wscUserActivities'];
            }

            return $activityStream;
        } else {
            $this->stravaConnectLogin();

            return false;
        }
    }

    public function getUserDetails()
    {
        if ($this->isAuthenticated()) {

            if (!$_SESSION['wscUserDetails']) {
                $userDetails = $this->getApiResponse($this->apiUrl . '/athlete', $_SESSION['wscOAuthResponse']->athlete->id);
                $_SESSION['wscUserDetails'] = $userDetails;
            } else {
                $userDetails = $_SESSION['wscUserDetails'];
            }

            return $userDetails;
        } else {
            $this->stravaConnectLogin();

            return false;
        }
    }

    public function stravaConnectLogin()
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

    public function stravaConnectLogout()
    {
        ?>
        <a href="https://www.strava.com/oauth/deauthorize">Log out</a>
    <?php
    }
}