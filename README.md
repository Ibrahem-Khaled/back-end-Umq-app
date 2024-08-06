# Developer Documentation : Abdallah 

## Chat

### How To Create GroupKey in chat

* Group Key created to create new converstaiton id by this formul:
* see method "createNewUser()" at class "ChatUserController.php"
```
               /**
                 * Rule of generate "group_key" :
                 * set the small user_id in person "a"
                 */
```

## Subscribe Package Setting Show/Hide 

* The Setting of "prevent" means hide or "allow" means show in mobile save in database table
   "SettingAdmin" in key called "PSS"
* the "PSS" is symoble for "payment_subscribe_status"
* Change the setting from database phpmyadmin "payment_subscribe_status" will reflect in mobile
* The Resone of make this action is the apple store publish app to apple store, need us to implement "in-app-paurchase"
  so we hide feature subscribe package from support apple while the review complete, we show subscribe package dynamically.

* this response of api get setting payment :
```
{
    "status": "success",
    "code": 1,
    "PSS": "prevent"
}
```
see "prevent" will hide subscribe plans in mobile application

## Subscribe User To Package
 

* get last subscribe by user, then check   
   >> case  downgrade - means need to decrease number of period days - not allowed to downgrade untill your current subscibe expired.
   >> check the avalaible days before expire more than the period days need to subsribe
 
 ```
     public function is_allow_to_subscibe_or_upgrade(Request $request, Int $new_period)  {
        $current_subscribe = $this->get_model_current_user_subscibe($request);

        //case not current subscribe
        if($current_subscribe == null ) {
            return true;
        } 
        
        if( $this->avaliable_day_before_expire_subscribtion() < $new_period ) {
            return true;
        } 

        return false;
    }

 ```

## Payment method Paypal

* There is Another link of project upload to VPS Server Hostinger langauge "NodeJS" Express.
* This Project handle all code of payment
* To Change the url of payment in mobile, go to PHPMyAdmin at table "SettingAdmin" will found key "paypal_domain"
  this url is redirect user to any paypal or payment method you want in the future.
  
----

----

# PHP Version And jwt Docuemntation

##  PHP version

```
C:\Users\Abdo>php -v
PHP 8.1.6 (cli) (built: May 11 2022 08:55:59) (ZTS Visual C++ 2019 x64)
Copyright (c) The PHP Group
Zend Engine v4.1.6, Copyright (c) Zend Technologies
```

## XAMPP Version 
XAMPP Controller v3.3.0


# LaravelAPIBoilerplateJWT

[![Build Status](https://travis-ci.org/Tony133/laravel-api-boilerplate-jwt.svg?branch=master)](https://travis-ci.org/Tony133/laravel-api-boilerplate-jwt)

An API Boilerplate to create a ready-to-use REST API in seconds with Laravel 8.x

## Install with Composer

```
    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar install or composer install
```

## Set Environment

```
    $ cp .env.example .env
```

## Set the application key

```
   $ php artisan key:generate
```

## Generate jwt secret key

```
    $ php artisan jwt:secret
```

## User Registration with Curl

```
    $ curl  -H 'content-type: application/json' -H 'Accept: application/json' -v -X POST -d '{"name":"tony","email":"tony_admin@laravel.com","password":"secret"}' http://127.0.0.1:8000/api/auth/register
```

## User Authentication with Curl

```
    $ curl  -H 'content-type: application/json' -H 'Accept: application/json' -v -X POST -d '{"email":"tony_admin@laravel.com","password":"secret"}' http://127.0.0.1:8000/api/auth/login
```

## Get the logged in user with Curl

```
    $ curl  -H 'content-type: application/json' -H 'Accept: application/json'  -v -X GET http://127.0.0.1:8000/api/auth/me?token=[:token]
```

## User Logout with curl

```
    $ curl  -H 'content-type: application/json' -H 'Accept: application/json' -v -X GET http://127.0.0.1:8000/api/auth/logout?token=[:token]

```

## Refresh token with curl

```
    $ curl  -H 'content-type: application/json' -H 'Accept: application/json' -v -X GET http://127.0.0.1:8000/api/auth/refresh?token=[:token]

```

## User Forgot Password with Curl

```
    $ curl -H 'content-type: application/json' -H 'Accept: application/json' -v POST -d '{"email": "tony_admin@laravel.com"}' http://127.0.0.1:8000/api/auth/forgot
```

## User Change Password with Curl

```
    $ curl -H 'content-type: application/json' -H 'Accept: application/json' -v POST -d '{"email": "tony_admin@laravel.com", "password": "secret"}' http://127.0.0.1:8000/api/auth/change?token=[:token]
```

