# API Template
> Startup for API building with api platform

## Built With

* [Symfony](https://github.com/symfony/symfony) - Symfony is a PHP framework for web and console applications and a set of reusable PHP components
* [Api platform](https://github.com/api-platform/api-platform) - REST and GraphQL framework to build modern API-driven projects

## Installation

```bash
# install dependencies
$ composer install

# serve with at localhost:8000
$ php -S 127.0.0.1:8000 -t public

# if you have some trouble try :
$ php bin/console cache:clear
```

## Development setup

How to set up the development environment :
- how to create db ?
- how to fill it ?
- how to run test-suite ?

```bash
# First of all creat a .env file in the root folder
# copy/paste the content of .env.example inside you're .env file.
# Then you just have to update every variables
## For example the database url : you just have to change text that inside <...> to fit you're dev environment
DATABASE_URL="mysql://<user>:<password>@<127.0.0.1>:<3306>/<db_name>?serverVersion=5.7"
DATABASE_URL="postgresql://<user>:<password>@<127.0.0.1>:<5432>/<db_name>?serverVersion=13&charset=utf8"
```

```bash

# JWT configuration
$ mkdir -p config/jwt
$ php bin/console lexik:jwt:generate-keypair

# Database setup
$ bin/console doctrine:database:create
$ bin/console doctrine:migrations:migrate

# Fill database with fake data
$ bin/console doctrine:fixtures:load

# run test
## Be careful the test suite is executed directly in the Database !
$ bin/phpunit
$ bin/phpunit --group <groupName>

# lint the code
##Tchech line to update
$ vendor/bin/phpcs -n
## Fix those lines
$ vendor/bin/php-cs-fixer fix <directory>

# Reset de la base de donnees
# DO NOT DO IN PRODUCTION !
$ bin/console d:d:d --force && bin/console d:d:c && bin/console d:m:m && bin/console d:f:l

# Update all dependencies
## See : https://symfony.com/doc/current/setup/unstable_versions.html
## See : https://symfony.com/releases
## See : https://symfony.com/doc/current/setup/upgrade_major.html#upgrade-major-symfony-deprecations
$ composer update
```

Some useful command if you want to create or update db scheme and then migrate them.
```bash
# Create an entity
$ bin/console make:entity <NameYourEntity>

# Create a migration
$ bin/console make:migration

# Apply all migrations
$ bin/console doctrine:migrations:migrate

# CRONs
## See : https://github.com/Cron/Symfony-Bundle
$ bin/console cron:list
$ bin/console cron:create
$ bin/console cron:delete _jobName_

$ bin/console cron:start [--blocking]
$ bin/console cron:strop
```

Custom command. Used for CRONs
```bash
# Publish awaiting animations
$ bin/console app:display-awaiting-animations
```

## Organisation
```bash
.
├─ _postman                     -> This folder contain all the configuration require for testing all API EndPoint with postman app
├─ migrations                   -> History of all db update create with the command : $ bin/console make:migration
│   ├─ VersionX.php
│   └─ VersionX.php
├─ src
│   ├─ Controller               -> The folder controller contain all the logic for custom endPoint declare in entities 
│   ├─ CustomCommand            -> All custom command, used in particular for CRON 
│   ├─ DataFixtures             -> All fake data you thats add into db when u execute : $ bin/console doctrine:fixtures:load
│   ├─ Entity                   -> All the database entity and object definition are store here
│   ├─ EventListener            -> 
│   ├─ Events                   -> Events are usefull for trigger automatic things (automaticly create log after each API call) and change output sometimes ( send error message when there is a php exception)
│   ├─ OpenApi                  -> OpenApi contain the documentation for the swagger element in this folder override auto generated api platform documantation
│   ├─ Repository               -> Repository is attatch to entity ( 1 entity = 1 repository ) repositories are here to allow us to create custom database queries
│   └─ Services                 -> Services contain logic element thats can be use every were. For example we have the reponse builder servire or other example the mailer service 
└─ tests                        -> In this folder you have all tests executed with the command : $ bin/phpunit
    ├─ Func                     -> Functional tests
    └─ Unit                     -> Unit tests
```

## Release History

* 0.0.0
  * MVP

## Authors

Alban PIERSON – pro.pierson.alban@gmail.com

## License

This project is licensed under the GNU GPL v3 License - see the [LICENSE.md](LICENSE.md) file for details
