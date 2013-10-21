app.constant('baseURL', '/api/v1/');

app.factory('UsersFactory', function ($resource, baseURL) {

    return $resource(baseURL + 'users/:id/:rel', {
        id: '@id'
    }, {
        get: {
            method: 'GET',
            params: {
                id  : '@id'
            }
        },
        pagesByAuthor : {
            method: 'GET',
            params: {
                id  : '@id',
                rel : 'pages'
            }

        },
        booksByAuthor : {
            method: 'GET',
            params: {
                id  : '@id',
                rel : 'books'
            }

        },
    });

});

app.factory('BooksFactory', function ($resource, baseURL) {

    return $resource(baseURL + 'books/:id/:rel', {
        id: '@id'
    }, {
        get: {
            method: 'GET',
            params: {
                id  : '@id'
            }
        },
        authors: {
            method: 'GET',
            params: {
                id  : '@id',
                rel : 'users'
            }
        },

    });
   
});



app.factory('PagesFactory', function ($resource, baseURL) {

    return $resource(baseURL + 'pages/:id', {
        id: '@id'
    }, {
        get: {
            method: 'GET',
            params: {
                id  : '@id'
            },
        }
    })

});