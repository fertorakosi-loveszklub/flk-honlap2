# flk-honlap2 [![Build Status](https://travis-ci.org/fertorakosi-loveszklub/flk-honlap2.svg?branch=master)](https://travis-ci.org/fertorakosi-loveszklub/flk-honlap2) [![GPL v3 License](https://img.shields.io/badge/license-GPL-blue.svg)](https://github.com/fertorakosi-loveszklub/flk-honlap2/blob/master/LICENSE) ![Version](https://img.shields.io/badge/version-2.2.2-yellow.svg)

## About
This is the source code of the official website of the FLK (Fertőrákosi Lövészklub). The site is built with Laravel 5 and is unit-tested (functional tests are a work in progress).
Personal data of the members stored in the database is encrypted, they are decrypted on-the-fly while using the site.

## Requirements
 - A web server (live site uses nginx)
 - PHP 5.4+
    - Apache module or php5-fpm for nginx
    - Extensions:
      - PDO for database access
      - MCrypt 
      - mbstring
    - php5-cli for artisan
 - A database (live site uses MySQL)
 - Git
 - Composer

## Installation
1. Clone this repo
  - `$ git clone https://github.com/fertorakosi-loveszklub/flk-honlap2`
2. Install required composer packages
  - `$ composer install`
3. Configure environment variables in `.env.example`. Names are self-explanatory.
4. Rename `.env.example` to `.env`
  - `$ mv .env.example .env`
5. Migrate database
  - `$ php artisan migrate`
6. Seed database
  - `$ php artisan db:seed`
7. Configure web server rewrites (.htaccess or nginx server block). See Laravel 5 installation notes for details.

## Testing
To run the unit tests, install the `require-dev` packages with composer:
`composer install --dev`

Then run test by executing `phpunit`.

## Changelog
See changelog.txt

## License
The license is GPLv3. See LICENSE for details.
