# FORSTA_API_PATH=${FORSTA_API_PATH:-"http://0.0.0.0:8000"}
FORSTA_API_PATH=${FORSTA_API_PATH:-"https://atlas.forsta.io"}

FORSTA_LOGIN_TEST="/v1/login/send"
FORSTA_LOGIN="/v1/login"
FORSTA_NEW_USER="/v1/user/"
FORSTA_NEW_AUTH_TOKEN="/v1/userauthtoken/"

get_json_value() {
  value=$1
  json_string=$2
  echo $2 | grep "\"$1\":\"[^\"]*\"" -o | cut -d : -f 2 | cut -d "\"" -f 2 | head -n 1
}

api_post_no_auth() {
  api_path=$1
  data=$2
  response=`curl -s -d "$data" -H "Content-Type: application/json" -X POST "$FORSTA_API_PATH$api_path"`
}

api_get_no_auth() {
  api_path=$1
  response=`curl -s -H "Content-Type: application/json" "$FORSTA_API_PATH$api_path"`  
}

api_post() {
  api_path=$1
  data=$2
  response=`curl -s -d "$data" -H "Content-Type: application/json" -H "Authorization: JWT $auth_token" -X POST "$FORSTA_API_PATH$api_path"`
}

authenticate_password() {
  fq_tag="$username:$orgname"

  printf "Would you like to see your password as you type it? [y/n]"
  read clear_text
  
  if [ $clear_text != "y" ]; then
    stty -echo
  fi

  printf "Password: "
  read password

  if [ $clear_text != "y" ]; then
    stty echo
    printf "\n"
  fi

  data="{\"password\":\"$password\",\"fq_tag\":\"$fq_tag\"}"
  api_post_no_auth $FORSTA_LOGIN "$data"
}

# Note: Still need to test/debug this
authenticate_sms() {  
  printf "Code sent via text (Not yet tested): "
  read code
  
  authtoken="$orgname:$username:$code"
  
  data="{\"authtoken\":\"$authtoken\"}"
  api_post_no_auth $FORSTA_LOGIN "$data"
}

authenticate() {
  
  printf "Forsta Org Name: "
  read orgname

  printf "Forsta User Name: "
  read username

  # Login without auth to check the errors for the auth type
  api_get_no_auth "$FORSTA_LOGIN_TEST/$orgname/$username"
  #response=`curl -s -H "Content-Type: application/json" "$FORSTA_API_PATH$FORSTA_LOGIN_TEST/$orgname/$username"`

  if [[ $response =~ "password auth required" ]]
  then
    authenticate_password
  elif [[ $response =~ "totp auth required" ]]
  then
    authenticate_sms
  else
    echo "Error testing auth: $response"
    exit
  fi

  if [[ $response != *"\"token\":"* ]]; then
    echo "Login failure"
    exit -1
  fi

  #auth_token=`echo $response | grep "\"token\":\"[^\"]*\"" -o | cut -d : -f 2`
  auth_token=`get_json_value token $response`
}
