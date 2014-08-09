psysh-laravel
=============

[![Build Status](https://travis-ci.org/y-yamagata/psysh-laravel.svg?branch=master)](https://travis-ci.org/y-yamagata/psysh-laravel)

psysh-laravel

Installation
------------

Add the package to your `composer.json` and run `composer update`.

    {
        "require": {
            "y-yamagata/psysh-laravel": "0.1.*"
        }
    }

Add the service provider in `app/config/app.php`

    'providers' => array(

        'YYamagata\PsyshLaravel\PsyshLaravelServiceProvider'

    )

Publish config using artisan CLI.

    $ php artisan config:publish y-yamagata/psysh-laravel

Usage
-----

    $ php artisan psysh

