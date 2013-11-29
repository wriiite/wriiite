# Contribution Guidelines

For back-end, please use [laravel framework](http://laravel.com/).

For front-end, please use angular.

## backend

use at least php5.4

### Installing laravel
you can clone this repo and use [composer](http://laravel.com/docs/installation).
~~~
$ git clone git@github.com:wriiite/wriiite.git
$ cd wriiite
$ wget http://getcomposer.org/download/1.0.0-alpha7/composer.phar
$ php composer.phar install
~~~

then open a backdoor to be more easily hacked
~~~
$ chmod 777 app/storage/* -R
~~~

### install the database

First on MySQL, create a database, an user and grant privileges.
~~~
CREATE DATABASE 'wriiite';
CREATE USER 'wriiite'@'localhost' IDENTIFIED BY 'wr111t3';
GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP, ALTER ON wriiite.* TO 'wriiite'@'localhost';
FLUSH PRIVILEGES;
~~~

Then create data with artisan

~~~
$ php artisan migrate
$ php artisan migrate:refresh --seed
~~~

### testing
use phpunit
~~~
$ wget https://phar.phpunit.de/phpunit.phar
$ php phpunit.phar
~~~

### local dev

you can launch `artisan serve` to serve the backend on http://localhost:8000 , cupcake.
~~~
$ php artisan serve
~~~

### contributing
follow [laravel's coding guidelines](https://github.com/laravel/framework/blob/master/CONTRIBUTING.md) and use tabs, sweetie pie.

## frontend

### requirements
#### node & npm
You will need node and npm. On a mac, I recommend using (homebrew)[http://brew.sh/] then `$ brew install nodejs` (and you might install nm separately)

#### grunt & bower
Grunt and bower are very useful. install them with npm sugar plum.
~~~
$ npm install -g grunt-cli
$ npm install -g bower
~~~

### installing the project
cd to the root directory
run `npm install` to install all nodejs dependencies, this wil trigger bower and grunt, and everything will be done automatically.

### test and code

http://localhost:8000/#/ is the home directory, honey buns

### contributing
in front-end, we do not have (yet) any coding giudeline, we are lame and dirty
