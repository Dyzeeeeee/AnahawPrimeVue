<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

class FacebookAuth extends BaseController
{
    protected $facebook;
    protected $helper;

    public function __construct()
    {
        $this->facebook = new Facebook([
            'app_id' => '790378076376898',
            'app_secret' => '672a17ba1a68870e341b027bc5eed760',
            'default_graph_version' => 'v3.2',
        ]);
        $this->helper = $this->facebook->getRedirectLoginHelper();
    }

    public function login()
    {
        $permissions = ['email']; // Optional permissions
        $loginUrl = $this->helper->getLoginUrl('http://localhost:5173/facebook/callback', $permissions);
        return $this->response->setJSON(['url' => $loginUrl]);
    }


    public function callback()
    {
        try {
            $accessToken = $this->helper->getAccessToken();
            if (isset($accessToken)) {
                // User authenticated, get user details
                $response = $this->facebook->get('/me?fields=name,email', $accessToken->getValue());
                $user = $response->getGraphUser();
                // Log user in or create user in your system
            }
        } catch (FacebookResponseException $e) {
            // Handle error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }
}
