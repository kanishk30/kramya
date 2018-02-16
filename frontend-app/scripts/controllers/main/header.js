'use strict';
angular.module('KramyaApp')
	.controller('headerController', function($scope ,$location, $moment, $cookies, toaster,$cookieStore, myService, $window , store , jwtHelper) {

		$scope.showHeader=false;
		$scope.islogin=false;
		$scope.userImage="http://www.clostridia.net/clospore/images/Blank_profile_male.jpg";
		$scope.user=function(id){
			if($scope.role=='user'){
				$location.path("/user-profile").search({'id':id});
			}
			else if($scope.role=='hospital'){
				$location.path("/hospital-list");
			}
		}
		// $scope.timeDiff='';
		$scope.isHospital=false;
		$scope.$watch(function($scope) {return store.get('jwt')},
		  function(newValue){
        if(newValue!==undefined & newValue!==null){
        	console.log('newValue' , newValue);
        	$scope.userData=jwtHelper.decodeToken(newValue);
        	$scope.role=$scope.userData.role;
        	if(!$scope.profileComplete)
        		$scope.profileComplete=parseInt($scope.userData.profileComplete);
        	if($scope.role=='hospital'){
        		$scope.isHospital=true;
        		// if($scope.profileComplete<50){
        		// 	$location.path("/register");
        		// }
        	}
        	// $scope.timeDiff=$moment()-$moment($scope.userData.issuedAt);
        	// if(Math.round($scope.timeDiff/1000)+93>$scope.userData.ttl){
        	// 	toaster.pop({type: 'error', title: "Sesson Expired", body:"Your session has expired, login again to continue."});
        	// 	// alert('Sesson Has Expired');
        	// 	$scope.logout();
        	// }
        	// console.log('time',Math.round($scope.timeDiff/1000));
        	if($scope.userData.userImage!='NULL'){
        		$scope.userImage=$scope.userData.userImage;
        	}
        	console.log('$scope.userData' , $scope.userData);
        	$scope.islogin=true;
        }
      });
		
		// $scope.img="http://www.clostridia.net/clospore/images/Blank_profile_male.jpg";
		
		$scope.logout=function(){
			store.remove('jwt');
			toaster.pop({type: 'warning', title: "Successfully Signed Out", body:"You Have been Successfully Signed out"});
			// if(myService.get('fbLogin')== true){
			// 	FB.logout(function(response) {
			// 	  // user is now logged out
			// 	});
			// }
			// if(myService.get('googleLogin')==true){
			// 		gapi.auth.signOut();
		 //    }
			// alert('You Have been Successfully Signed out');
			$scope.islogin=false;
			if($location.path()=='/user-profile' || $location.path()=='/register' || $location.path()=='/inbox' ||$location.path()=='/hospital-list'){
				$location.path('/');
			}
			// $window.location.reload();
		};
		$scope.go = function ( path ) {
		  $location.path( path );
		};
	});