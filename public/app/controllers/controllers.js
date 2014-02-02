app.controller('AuthController', function($scope, $rootScope, $location, Auth, UsersFactory) {

	$scope.loadAuth = function() {
		Auth.load().success(function(data) {
			$rootScope.main.user = data.user;
			$location.path("/user/" + $rootScope.main.user.id);
		});
	}

	$scope.logoutUser = function() {
		Auth.logout().success(function(data) {
			$rootScope.main.user = {};
		});
	}
 
	$scope.loginUser = function() {
		Auth.login({
			email: $scope.credentials.email,
			password: $scope.credentials.password
		}).success(function(data) {
			$scope.loadAuth();
			$scope.main.credentials = {};

		}).error(function(data) {
			$scope.authError = data.error;
		});
	}
});


app.controller('NewBookController', function ($scope, BooksFactory, $location, PagesFactory, $rootScope) {

    // we will create a book and its first page
	$scope.create = function() {

		var postData = {
			'title' : $scope.title,
			'description' : $scope.description
		};

		var createBook = function() {
			BooksFactory.save(postData, 
				function(response) {
					console.log("book created");
					postData.book_id = response.id;
					$location.path('/book/'+postData.book_id);
				},
				function(response) {
					$scope.alert = {
						message: response.data.metadata.message,
						error: true
					};
					console.log($scope.alert);
				}
			);
		}

		createBook();
	}
})

app.controller('BookController', function ($scope, BooksFactory, PagesFactory, $routeParams, $rootScope, $location) {

	init();
	function init() {

		var bookID = ($routeParams.bookID) ? parseInt($routeParams.bookID) : 0;

		if (bookID > 0) {
			$scope.book			= BooksFactory.get({id : bookID}, function () {
			displayBook(bookID);
			}, function(response) {
				$scope.error = {};
				var status = response.status;
				$scope.error['status' + status] = response.data.metadata.message;
				console.log(response.data.metadata.message);
			});
		}
		else {
			$scope.books = BooksFactory.get();
		}
	}


	var displayBook = function (bookID) {
		$scope.authors		= BooksFactory.authors({id : bookID});
		$scope.errors 		= {};
		$scope.success 		= {};
		$scope.newPage = function() {

			postData = {
				'book_id' 	: bookID,
				'content'	: $scope.nextPage
			};

			PagesFactory.save(postData, 
				function(response) {
					BooksFactory.update({id:bookID}, {'publish' : true, 'book_id' : bookID});
					$scope.book.status = 1;
					$scope.book.pages.push(appendNewPage(response));
					$scope.success.creation = 'La page a été créée avec succés !';
				},
				function(response) {
					$scope.errors.creation 	= response.data.metadata.message;
				}
			);
		}

		var appendNewPage = function (response) {
			var newPage = {};
			newPage.content = response.content;
			newPage.id 		= response.id;
			newPage.number  = response.number;
			newPage.status  = response.status;
			newPage.user 	= {username : $rootScope.main.user.username , id :$rootScope.main.user.id }
			return newPage;
		}

		$scope.sortValid	= false;
		$scope.validAuthorBtn = function (value) {
			if (value == 1) {
				$scope.sortValid	= false;
			}	
			if (value == 2) {
				$scope.sortValid	= true;
			}
			$scope.$digest();
		};
	}


});

app.controller('UserController', function ($scope, UsersFactory, $routeParams, $location, $rootScope) {


	init();
	function init() {
		var userID = ($routeParams.userID) ? parseInt($routeParams.userID) : 0;

		if (userID > 0) {
			$scope.user		= UsersFactory.get({id : userID}, function() {
					$scope.pages	= UsersFactory.pagesByAuthor({id: userID});
					$scope.books	= UsersFactory.booksByAuthor({id: userID});
				},
				function(response) {
					//404 Or bad
					if(response.status == 404) {
						$location.path('/books');
					}
				}
			);
		}
		else {
			$scope.users = UsersFactory.get();
		}

		$scope.sortValid	= 1;
		$scope.validPages = function (value) {
			if (value == 0) {
				$scope.sortValid	= 0;
			}
			if (value == 1) {
				$scope.sortValid	= 1;
			}
			$scope.$digest()
		};
	}
});
