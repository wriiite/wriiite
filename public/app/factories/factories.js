app.factory('BooksFactory', function ($resource) {

    var factory = {};
    
    factory.getBooks = function () {

        return $resource('/api/v1/books/:id', {
            id: '@id'
        }, {
            get: {
                method: 'GET',
                params: {
                    id: '@id'
                }
            }
        })

    };

    factory.getAuthors = function () {
        return $resource('/api/v1/books/:id/users', {
            id : '@id'
        }, {
            get : {
                method : 'GET',
                params : {
                    id      : '@id'
                }
            }
        });
    };

    return factory;

   
});


app.factory('UsersFactory', function ($resource) {

    factory = {};

    factory.getUsers = function () {
        return $resource('/api/v1/users/:id', {
            id: '@id'
        }, {
            get: {
                method: 'GET',
                params: {
                    id: '@id'
                },
            }
        })
        
    };

    factory.getPagesByAuthor = function () {
        return $resource('/api/v1/users/:id/pages', {
            id : '@id'
        }, {
            get : {
                method : 'GET',
                params : {
                    id      : '@id'
                }
            }
        });
    };

    factory.getBooksByAuthor = function () {
        return $resource('/api/v1/users/:id/books', {
            id : '@id'
        }, {
            get : {
                method : 'GET',
                params : {
                    id      : '@id'
                }
            }
        });
    };
    
    return factory;
});


app.factory('PagesFactory', function ($resource) {

    factory = {};

    factory.getPages = function () {
        return $resource('/api/v1/pages/:id', {
            id: '@id'
        }, {
            get: {
                method: 'GET',
                params: {
                    id: '@id'
                },
            }
        })
        
    };
    
    return factory;
});