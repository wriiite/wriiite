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
                templateUrl : 'app/partials/books/index.html'
            })
        .when('/book/new',
            {
                controller  : 'NewBookController',
                templateUrl : 'app/partials/books/create.html'
            }
        )
        //Define a route that has a route parameter in it (:customerID)
        .when('/book/:bookID',
            {
                controller  : 'BookController',
                templateUrl : 'app/partials/books/read.html',
                resolve : {
                    book : function(BooksFactory) {
                        BooksFactory.get({id : $routeProvider.bookID})
                            .$promise.then(
                                function(error) { console.log(error)}
                            )
                    }
                }
            })
        //Define a route that has a route parameter in it (:customerID)
        .when('/user/:userID',
            {
                controller  : 'UserController',
                templateUrl : 'app/partials/users/read.html'
            })
        .when('/users', 
            {
                controller  : 'UserController',
                templateUrl : 'app/partials/users/index.html'
            })
        .when('/auth/login', 
            {
                controller  : 'AuthController',
                templateUrl : 'app/partials/auth/login.html'
            })
        .when('/404',
            {
                controller  : 'errorController',
                templateUrl : 'app/partials/errors/404.html'
            })
        .otherwise({ redirectTo: '/404' });
});
