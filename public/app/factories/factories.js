app.constant('baseURL', '/api/v1/');

app.factory('UsersFactory', function ($resource, baseURL) {

    return $resource(baseURL + 'users/:id/:rel', {
        id: '@id'
    }, {
        pagesByAuthor : {
            method: 'GET',
            params: {
                rel : 'pages'
            }

        },
        booksByAuthor : {
            method: 'GET',
            params: {
                rel : 'books'
            }

        },
    });

});

app.factory('BooksFactory', function ($resource, baseURL) {

    return $resource(baseURL + 'books/:id/:rel', {
        id: '@id'
    }, {
        authors: {
            method: 'GET',
            params: {
                rel : 'users'
            }
        },
        update : {
            method : 'PUT'
        }

    });
   
});



app.factory('PagesFactory', function ($resource, baseURL) {

    return $resource(baseURL + 'pages/:id', {
        id: '@id'
    }, {
        
    })

});


app.factory('Auth', function($http, baseURL){
  return {
        load: function() {
            return $http.get(baseURL + 'auth');
        },
        logout: function() {
            return $http.get(baseURL + 'auth/logout');
        },
        login: function(inputs) {
            return $http.post(baseURL + 'auth/login', inputs);
        },
        check: function() {
            return $http.get(baseURL + 'auth/check');
        }
    }
});