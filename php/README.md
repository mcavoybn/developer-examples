# Installation

[Install via composer.](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos)

Composer can usually be installed via package manager, i.e. `sudo apt install composer`

# Run

Open user_create.php and fill out the missing values. You will need an org admin's login credentials and details for a new mock user you create. Here is an example:
```
'json' =>  [
    'email' => 'your@email.com',
    'first_name' => 'new',
    'last_name' => 'user',
    'tag_slug' => 'new.user', // a username
]
```

Once you have input the values from above run the script using:
```
cd developer-examples/php
composer install
php -f user_create.php
```

