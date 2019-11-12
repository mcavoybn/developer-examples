# A script to get user authentication tokens for Forsta

source ./forsta_library.sh

create_user() {
  printf "\nPlease enter your new user details.\n"
  printf "New User Name: "
  read new_username
  
  printf "Pretty User Name: "
  read new_prettyname
  
  data="{\"first_name\":\"$new_prettyname\",\"tag_slug\":\"$new_username\"}"
  api_post $FORSTA_NEW_USER "$data"

  if [[ $response != *"\"id\":"* ]]; then
    echo "Failed to create user: $response"
    exit -1
  fi

  userid=`get_json_value id $response`
  printf "Created $new_username with id $userid\n\n"
}

get_auth_token() {
  data="{\"userid\":\"$userid\",\"description\":\"Script generated\"}"
  api_post $FORSTA_NEW_AUTH_TOKEN "$data"

  if [[ $response != *"\"token\":"* ]]; then
    echo "Failed to create user: $response"
    exit -1
  fi

  token=`get_json_value token $response`

  echo "--------------------------------------------------------------------------"
  printf "Save the following token for API authentication. IT CAN NOT BE RECOVERED.\n"
  echo "--------------------------------------------------------------------------"
  printf "\n"

  echo $token
  
  echo ""
}

echo "-----------------------------------------------------------"
echo "This script generates bot users for the forsta.io platform."
echo "-----------------------------------------------------------"

printf "\nPlease login as an adminstrator.\n"
authenticate

create_user
get_auth_token
