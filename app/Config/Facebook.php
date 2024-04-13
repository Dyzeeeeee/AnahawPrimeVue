<?php

// app/Config/Facebook.php
namespace Config;

use CodeIgniter\Config\BaseConfig;

class Facebook extends BaseConfig
{
    public $appId = '790378076376898';
    public $appSecret = '672a17ba1a68870e341b027bc5eed760';
    public $redirectUrl = 'http://localhost:5173/login/facebook/callback';
}
