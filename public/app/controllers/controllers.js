app.controller('AuthController', function($scope, $rootScope, $http, $location, Auth, UsersFactory) {

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


app.controller('BookController', function ($scope, BooksFactory, PagesFactory, $routeParams, $rootScope) {

	init();
	function init() {
		var bookID = ($routeParams.bookID) ? parseInt($routeParams.bookID) : 0;

		if (bookID > 0) {
			$scope.book			= BooksFactory.get({id : bookID});
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
						$scope.success.creation = 'La page a été créée avec succés !';
					},
					function(response) {
						$scope.errors.creation 	= response.data.metadata.message;
					}
				);
			}
		}
		else {
			$scope.books = BooksFactory.get();
		}
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
