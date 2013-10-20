app.controller('BookController', function ($scope, BooksFactory, $routeParams) {

    init();
    function init() {
    	var bookID = ($routeParams.bookID) ? parseInt($routeParams.bookID) : 0;
        if (bookID > 0) {
            $scope.book 		= BooksFactory.getBooks().get({id : bookID});
            $scope.authors 		= BooksFactory.getAuthors().get({id : bookID});
        }
        else 
        {

        	$scope.books = BooksFactory.getBooks().get();
        }


        $scope.sortValid	= false;
		$scope.validAuthorBtn = function (value) {
			if (value == 1){
				$scope.sortValid	= false;
			}   
			if (value == 2){
				$scope.sortValid 	= true;
			}
			$scope.$digest()

		};
    }



    
});

app.controller('UserController', function ($scope, UsersFactory, $routeParams, $location) {


    init();
    function init() {
    	var userID = ($routeParams.userID) ? parseInt($routeParams.userID) : 0;
        if (userID > 0) {
            $scope.user     = UsersFactory.getUsers().get({id : userID}, function() 
                {
                    $scope.pages    = UsersFactory.getPagesByAuthor().get({id: userID});
                    $scope.books    = UsersFactory.getBooksByAuthor().get({id: userID});
                },
                function(response) {
                //404 Or bad
                if(response.status == 404)
                {
                    $location.path('/books');
                }   
            });
            
        }
        else 
        {
        	$scope.users = UsersFactory.getUsers().get();
        }

        $scope.sortValid    = 1;
        $scope.validPages = function (value) {
            if (value == 0){
                $scope.sortValid    = 0;
            }   
            if (value == 1){
                $scope.sortValid    = 1;
            }
            $scope.$digest()

        };
    }

    
});


