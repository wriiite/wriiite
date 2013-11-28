# Contribution Guidelines

For back-end, please use [laravel framework](http://laravel.com/)
For front-end, please join the backbone branch or the angular branch.

## backend

use at least php5.4

### Installing laravel
you can clone this repo and use [composer](http://laravel.com/docs/installation)

### testing
use phpunit

### local dev

you can launch artisan serve to serve the backend on http://localhost:8000
~~~
$ php artisan serve
~~~

### contributing
follow [laravel's coding guidelines](https://github.com/laravel/framework/blob/master/CONTRIBUTING.md) and use tabs.

## frontend

### requirements
#### node & npm
You will need node and npm. On a mac, I recommend using (homebrew)[http://brew.sh/] then `$ brew install nodejs` (and you might install nm separately)
#### grunt & bower
Grunt and bower are very useful. install them with npm
~~~
$ npm install -g grunt-cli
$ npm install -g bower
~~~

### installing the project
cd to the root directory
run `npm install` to install all nodejs dependencies, this wil trigger bower and grunt

### test and code

http://localhost:8000/#/ is the home directory

### contributing
in front-end, we do not have (yet) any coding giudeline, we are lame and dirty
