var app = angular.module('bookApp', ['ngResource', 'ngAnimate', 'ngRoute', 'ui.bootstrap'])
.run(function($rootScope, Auth) {
    $rootScope.main = {};
    authCheck = Auth.check();
    
    if(authCheck){
        Auth.load().success(function(data) {
            $rootScope.main.user = data.user;
        });
    }

    $rootScope.logoutUser = function() {
        Auth.logout().success(function(data) {
            console.log("You have been logged out.");
            $rootScope.main = {};
        });
    }
    

    

});

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
        .when('/auth/login', 
            {
                controller  : 'AuthController',
                templateUrl : 'app/partials/login.html'
            })
        .otherwise({ redirectTo: '/' });
});
