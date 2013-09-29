# Coin Algorithm

This is the source code for the http://coins.fedecarg.com webapp.

## Requirements

Zend Framework 2 requires PHP 5.3.3 or later.

## Main classes

* [Currency controller](https://github.com/fedecarg/zf-coin-algorithm/tree/master/src/module/Application/src/Application/Controller)
* [Currency library](https://github.com/fedecarg/zf-coin-algorithm/tree/master/src/module/Application/src/Application/Library/Currency) 

## Installation

### Using Composer

The recommended way to get a working copy of this project is to clone the repository and manually invoke composer.phar.

Clone the git repository

    cd /path/to/workspace
    git clone git://github.com/fedecarg/zf-coin-algorithm.git
    cd zf-coin-algorithm

Use composer to install dependencies

    curl -s https://getcomposer.org/installer | php --
    php composer.phar self-update
    php composer.phar install

Set up a virtual host to point to the public/ directory of the project and you should be ready to go:

    <VirtualHost *:80>
        ServerName mydomain.com
        DocumentRoot /path/to/webapp/src/public"
        SetEnv APPLICATION_ENV "development"
        <Directory /path/to/webapp/src/public/>
            DirectoryIndex index.php
            AllowOverride all
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>

## Quality Assurance

All the tools listed in the build.xml file can be installed using the PEAR Installer:

    sudo pear config-set auto_discover 1
    sudo pear install pear.phpqatools.org/phpqatools

To execute the unit tests:

    cd /path/to/workspace/zf-coin-algorithm
    ant phpunit

    [exec] PHPUnit 3.7.27 by Sebastian Bergmann.
    [exec] 
    [exec] Configuration read from src/module/Application/test/phpunit.xml
    [exec] 
    [exec] ..................
    [exec] 
    [exec] Time: 81 ms, Memory: 9.00Mb
    [exec] 
    [exec] OK (18 tests, 18 assertions)

To execute all the QA tests:

    cd /path/to/workspace/zf-coin-algorithm
    ant

## Author

Federico Cargnelutti @fedecarg


