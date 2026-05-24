<?php
$dbCfg = require __DIR__ . '/database.php';
$creds = require __DIR__ . '/credentials.php';

return array(
    'name'     => 'Artcania',
    'url'      => 'http://localhost/artcania',
    'env'      => 'production',
    'timezone' => 'America/El_Salvador',
    'database' => $dbCfg,

    'security' => array(
        'pepper'            => $creds['security_pepper'],
        'login_max_tries'   => 3,
        'login_block_hours' => 1,
        'token_register'    => 24,
        'token_reset'       => 1,
    ),

    'mail' => array(
        'oauth_email'         => 'noreplyartcania@gmail.com',
        'from_name'           => 'Artcania',
        'oauth_client_id'     => $creds['oauth_client_id'],
        'oauth_client_secret' => $creds['oauth_client_secret'],
        'oauth_refresh_token' => $creds['oauth_refresh_token'],
    ),

    'google_login' => array(
        'client_id'    => $creds['oauth_client_id'],
        'client_secret'=> $creds['oauth_client_secret'],
        'redirect_uri' => 'http://localhost/artcania/auth/google/callback',
    ),
);
