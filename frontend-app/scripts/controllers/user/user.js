'use strict';
angular.module('KramyaApp')
	.controller('userController', function($scope,$location, $window , $http , myService , $sce , store , jwtHelper) {
		console.log("url parameters",$location.search().id);
		$scope.showHome=true;
		var userId=$location.search().id;
		var jwt = store.get('jwt');
		$scope.show=function(id){
			if(id==1){
				$scope.showHome=true;
				$scope.showInbox=false;
				$scope.showFav=false;
				$scope.showProfile=false;
			}
			else if(id==2){
				$scope.showHome=false;
				$scope.showInbox=true;
				$scope.showFav=false;
				$scope.showProfile=false;
			}
			else if(id==3){
				$scope.showHome=false;
				$scope.showInbox=false;
				$scope.showFav=true;
				$scope.showProfile=false;
			}
			else if(id==4){
				$scope.showHome=false;
				$scope.showInbox=false;
				$scope.showFav=false;
				$scope.showProfile=true;
			}
		}
		if(jwt){
			var apiKey=jwtHelper.decodeToken(jwt).userKey;
			// console.log('role' , role);
			var sendUrl="http://kramya.com/kramyaapi/index.php/users?username="+userId+"&X-API-KEY="+apiKey;
			$http({
			method: 'GET',

			url: sendUrl
		})
		.success(function(response) {
			console.log("http data:" , response);
			$scope.userD=response[0]; 
		})
		.error(function(response) {
	  	console.log('http error:' , response);
	  	// alert(response.data);
		});
		$scope.$watch(function($scope) {return $scope.userD },
		      function(newValue){
		      	console.log("userdata nayivalue" , newValue);
		      	
		      	if(newValue != undefined || newValue != null ){
		      		$scope.userData=newValue;
		      		console.log('after valid:' , $scope.userData );
		      		
		      	}
		      });  
		}
	})
	.controller('hospitalProfileController', function($scope,$location, $window , $http , myService , $sce , store , jwtHelper) {
		console.log("url parameters",$location.search().id);
		$scope.showProfile=false;
		var hospitalId=$location.search().id;
		var jwt = store.get('jwt');
		if(jwt){
			var apiKey=jwtHelper.decodeToken(jwt).userKey;
			// console.log('role' , role);
			var sendUrl="http://kramya.com/kramyaapi/index.php/hospitals?hospital_id="+hospitalId+"&X-API-KEY="+apiKey;
			$http({
			method: 'GET',

			url: sendUrl
		})
		.success(function(response) {
			console.log("http data:" , response);
			$scope.userD=response[0]; 
		})
		.error(function(response) {
	  	console.log('http error:' , response);
	  	// alert(response.data);
		});
		$scope.$watch(function($scope) {return $scope.userD },
		      function(newValue){
		      	console.log("hospitaldata nayivalue" , newValue);
		      	
		      	if(newValue != undefined || newValue != null ){
		      		$scope.hospitalData=newValue;
		      		console.log('after valid:' , $scope.hospitalData );
		      		
		      	}
		      });  
		}
	});