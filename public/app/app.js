var app = angular.module('bookApp', ['ngResource', 'ngAnimate', 'ngRoute']);

//This configures the routes and associates each route with a view and a controller
app.config(function ($routeProvider) {
    $routeProvider
        .when('/',
            {
                controller  : 'BookController',
                templateUrl : 'app/partials/books.html'
            })
        //Define a route that has a route parameter in it (:customerID)
        .when('/book/:bookID',
            {
                controller  : 'BookController',
                templateUrl : 'app/partials/bookInfo.html'
            })
        //Define a route that has a route parameter in it (:customerID)
        .when('/user/:userID',
            {
                controller  : 'UserController',
                templateUrl : 'app/partials/userInfo.html'
            })
        .when('/users', 
            {
                controller  : 'UserController',
                templateUrl : 'app/partials/users.html'
            })
        .otherwise({ redirectTo: '/' });
});
