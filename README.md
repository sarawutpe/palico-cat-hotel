# LARAVEL 8 | PALICO CAT HOTEL

## PHP DB

PHP Version: 7.4
Laravel: 8
MariaDB

## Web

-   CSS: Bootstrap 4
-   Theme: CoreUI https://coreui.io
-   Fonts: DB Heavent
-   sweetalert2 https://sweetalert2.github.io/
-   toastr https://codeseven.github.io/toastr/demo.html
-   dayjs https://day.js.org/en/

-   composer require mailjet/mailjet-apiv3-php

## Laravel Commands

$ run
php artisan serve

# palico-cat-hotel

# model

php artisan make:model Member -m

## Database

-   dbname palico_cat_hotel_db

php artisan make:controller MemberController

# create table
php artisan make:migration [CREATE_TABLE_NAME]

# migrate
php artisan migrate

R all cache
php artisan cache:clear


# Route cache cleared!

php artisan route:clear

# make middleware

$ php artisan make:middleware [NAME_HERE]

# Knowledge
Rent Status:
PENDING
RESERVED
CHECKED_IN
CHECKED_OUT
CANCELED

Pay Status:
PENDING
PAYING
CANCELED


