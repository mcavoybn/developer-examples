<?php 
require 'vendor/autoload.php';
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;


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
try {
  $createUserResponse = $client->post('https://atlas.forsta.io/v1/user/', [
      'headers' => [
          'Authorization' => $jwt
      ],
      'json' =>  [
          'email' => $new_user_email,
          'first_name' => $new_user_first_name,
          'last_name' => $new_user_last_name,
          'tag_slug' => $new_user_tag_slug,
      ]
  ]);
}
catch (Exception $e) {
  exit("Failed to create user: " . $e->getMessage());
}

echo("User created\n");
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
