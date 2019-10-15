<?php 
require 'vendor/autoload.php';
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;


# initialize http client
use GuzzleHttp\Client;
$client = new GuzzleHttp\Client();

# login as admin to retrieve jwt
# Either use auth_token OR username and password
$admin_user_auth_token = '<admin authtoken>';
$admin_username = '@<user>:<org>';
$admin_password = '<password>';

# User to create
$new_user_email = 'rose@forsta.io';
$new_user_first_name = 'Test';
$new_user_last_name = 'User';
$new_user_tag_slug = 'test1234';

try {
  $loginResponse = $client->post('https://atlas.forsta.io/v1/login/', [
      'json' =>  [
          'userauthtoken' => $admin_user_auth_token
#          'fq_tag' => $admin_username,
#          'password' => $admin_password
      ]
  ]);
}
catch (Exception $e) {
  exit("Failed to login: " . $e->getMessage());
}

echo("Successfully connected\n");
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

# create a new auth token for the user we just created
$tokenDescription = 'Auth Token Description';
try{
  $postAuthTokenResponse = $client->post('https://atlas.forsta.io/v1/userauthtoken/', [
      'headers' => [
          'Authorization' => $jwt
      ],
      'json' =>  [
          'userid' => '',
          'description' => $tokenDescription
      ]
  ]);  
}
catch (Exception $e) {
  exit("Failed to create user authtoken: " . $e->getMessage());
}

$createUserResponseJson = json_decode($postAuthTokenResponse->getBody()->getContents());

echo("User authtoken created. Please save the following:\n");
echo($createUserResponseJson->token . "\n");

?>
