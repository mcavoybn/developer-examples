<?php

$first_name = $_REQUEST["first_name"];
$last_name = $_REQUEST["last_name"];
$email = $_REQUEST["email"];
$tag_slug = $_REQUEST["tag_slug"];

print_r($last_name);
print_r($email);
print_r($tag_slug);

$client = new GuzzleHttp\Client();

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

// response
print_r("success");

?>