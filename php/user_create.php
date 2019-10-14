<?php 
require 'vendor/autoload.php';

# initialize http client
use GuzzleHttp\Client;
$client = new GuzzleHttp\Client();

# login as admin to retrieve jwt
$admin_user_auth_token = '';
$loginResponse = $client->post('https://atlas.forsta.io/v1/login/', [
    'json' =>  [
        'userauthtoken' => $admin_user_auth_token    
    ]
]);
$loginResponseJson = json_decode($loginResponse->getBody()->getContents());
$jwt = 'JWT ' . $loginResponseJson->token;

# use admin jwt to create a new user
$createUserResponse = $client->post('https://atlas.forsta.io/v1/user/', [
    'headers' => [
        'Authorization' => $jwt
    ],
    'json' =>  [
        'email' => '',
        'first_name' => '',
        'last_name' => '',
        'tag_slug' => '',
    ]
]);
$createUserResponseJson = json_decode($createUserResponse->getBody()->getContents());
$newUserId = $createUserResponseJson->id;

# create a new auth token for the user we just created
$tokenDescription = 'Auth Token Description';
$postAuthTokenResponse = $client->post('https://atlas.forsta.io/v1/userauthtoken/', [
    'headers' => [
        'Authorization' => $jwt
    ],
    'json' =>  [
        'userid' => $newUserId,
        'description' => $tokenDescription
    ]
]);
?>
