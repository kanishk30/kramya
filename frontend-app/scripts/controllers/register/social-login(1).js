angular.module('KramyaApp')
	.controller('SocialLoginCtlr', function($scope, $location, $state , toaster, $window , $http ,store , myService , jwtHelper) {
		$scope.fbLogin=function(){
  		toaster.pop({type: 'wait', title: 'Login request processing', body:"Hold On! We are processing your request."});
			FB.login(function(response) {
		    if (response.authResponse) {
		     FB.api('/me', 'get', {fields: 'id,name,location,gender,email,age_range,locale,link,verified,timezone,updated_time' }, function(response) {
				   	console.log(response);
				   	myService.set('fbLogin' , true);
				   	var fbLocation='';
				   	if(response.location){
				   		fbLocation=response.location.name;
				   	}
				   	if(!response.verified){
				   		toaster.pop({type: 'error', title: 'Not verified', body:"You are not a verified facebook user."});
				   	}
				   	else{
					   	$scope.toSendData={
					   		'user_img':"//graph.facebook.com/"+response.id+"/picture?type=large",
					   		'user_signup_source':'facebook',
					   		'user_name':response.name,
					   		'user_email_id':response.email,
					   		'user_password':response.id,
					   		'user_location': fbLocation,
					   		'user_gender':response.gender,
					   		'user_age':response.age_range.min,
					   		'user_facebook_profile':response.link,
				    		'X-API-KEY': 'ANSHULVYAS16'
					   	}
					   	// console.log('toSend Data:' , $scope.toSendData);
					  	$http({
							  method: 'POST',
							  headers: {'Content-Type': 'application/json'},
							  url: 'http://kramya.com/kramyaapi/index.php/users',
							  data: $scope.toSendData
							})
							.success(function(data){
								// console.log('success' , data);
								store.set('jwt' , data.jwt);
								toaster.pop({type: 'success', title: $scope.toSendData.user_name,  body:"You have been successfully logged in using facebook."});
							})
							.error(function(data){
								console.log('error',data);
							});
						}
				});
		    } else {
		     // alert('User cancelled login or did not fully authorize.');
		     toaster.pop({type: 'error', title: 'Login Failed',  body:"You have cancelled login or did not fully authorize."});
						
		    }
			}, 
			{
    		scope: 'email , public_profile,user_birthday, user_location', 
    		return_scopes: true
			});
		};
  	$scope.googleLogin=function(){
  		toaster.pop({type: 'wait', title: 'Login request processing', body:"Hold On! We are processing your request."});  	
  		gapi.client.load('plus', 'v1', apiClientLoaded);
  	}
  	function apiClientLoaded() {
        gapi.client.plus.people.get({userId: 'me'}).execute(handleResponse);
    }
    function handleResponse(response) {
			myService.set('googleLogin',true);
	    console.log('googleLogin' ,response);
	    $scope.toSendData={
			   		'user_img':response.image.url.slice(0, -6),
			   		'user_signup_source':'google',
			   		'user_name':response.displayName,
			   		'user_email_id':response.emails[0].value,
			   		'user_password':response.id,
			   		'user_location': response.placesLived[0].value,
			   		'user_gender':response.gender,
			   		'user_age':response.ageRange.min,
			   		'user_google_profile':response.url,
		    		'X-API-KEY': 'ANSHULVYAS16'
			};
			$http({
			  method: 'POST',
			  headers: {'Content-Type': 'application/json'},
			  url: 'http://kramya.com/kramyaapi/index.php/users',
			  data: $scope.toSendData
			})
			.success(function(data){
				// console.log('success' , data);
				store.set('jwt' , data.jwt);
				toaster.pop({type: 'success', title: $scope.toSendData.user_name, body:"You Have been Successfully Logged In using Google Plus."});  	
			})
			.error(function(data){
				console.log('error',data);
			});
			// console.log('toSend Data:' , $scope.toSendData);
    }
	});