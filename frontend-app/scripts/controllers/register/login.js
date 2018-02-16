angular.module('KramyaApp')
	.controller('loginController', function($scope, $location, $state ,toaster, $window , $http ,store , myService , jwtHelper) {
		$scope.endpoint='users';
		$scope.endpointForgot='user';
		$scope.forgot=false;
		$scope.loginData={'X-API-KEY': 'ANSHULVYAS16'};
		$scope.forgotData={'X-API-KEY': 'ANSHULVYAS16'};
		$scope.sendLoginData=function(){
			// console.log('url' , 'http://kramya.com/kramyaapi/index.php/login/'+$scope.endpoint);
			console.log('$scope.loginData:' , $scope.loginData);
			$http({
			  method: 'POST',
			  headers: {'Content-Type': 'application/json'},
			  url: 'http://kramya.com/kramyaapi/index.php/login/'+$scope.endpoint,
			  data: $scope.loginData
			}).then(function successCallback(response) {
				store.set('jwt', response.data.jwt);
				// var role=jwtHelper.decodeToken(response.data.jwt).role;
				toaster.pop({type: 'success', title: 'Login Successful',  body:"You have been successfully logged in."});
			  // if($scope.endpoint==''){

			  // }
			    // console.log(response);
			    // console.log('to show data',jwtHelper.decodeToken(response.data.jwt));
			    // $location.path("/");
			  }, function errorCallback(response) {
			  	console.log('errorData:',response);
			  	toaster.pop({type: 'error', title: 'Login failed',  body:response.data[1]});
			  	// console.log(response.data);
			    // called asynchronously if an error occurs
			    // or server returns response with an error status.
			  });			
		}
		$scope.sendForgotData=function(){
			console.log('fdata' , $scope.forgotData);
			$http({
			  method: 'POST',
			  headers: {'Content-Type': 'application/json'},
			  url: 'http://kramya.com/kramyaapi/index.php/forgot/change'+$scope.endpointForgot+'password',
			  data: $scope.forgotData
			}).then(function successCallback(response) {
					toaster.pop({type: 'success', title: 'Password Changed',  body:response.data.changes});
			    console.log(response);
			    // $location.path("/");
			  }, function errorCallback(response) {
			  	console.log('errorData:',response);
			  	// alert(response.data.changes);
			  	toaster.pop({type: 'error', title: 'Password not changed',  body:response.data.changes});
			    // called asynchronously if an error occurs
			    // or server returns response with an error status.
			  });
		}
	});