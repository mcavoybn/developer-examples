<?php 
require 'vendor/autoload.php';

$client = new GuzzleHttp\Client();

# login as admin to retrieve jwt
$adminUserAuthToken = '';
$loginResponse = $client->post('https://atlas.forsta.io/v1/login/', [
    'json' =>  [
        'userauthtoken' => $adminUserAuthToken    
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

# add the user to the tag
$tagId = 'Auth Token Description';
$postAuthTokenResponse = $client->post('https://atlas.forsta.io/v1/tag/', [
    'headers' => [
        'Authorization' => $jwt
    ],
    'json' =>  [
        'userid' => $newUserId,
        'description' => $tokenDescription
    ]
]);
?>
