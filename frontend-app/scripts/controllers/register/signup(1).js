angular.module('KramyaApp')
	.controller('SignupCtlr', function($scope,$location, $window ,toaster, $http , myService) {
		$scope.files=[];
		$scope.is_agreed=true;
		$scope.cut = function(index) {
			$scope.files.splice(index, 1);
		}
		$scope.formData={};
		$scope.$on("fileSelected", function (event, args) {
      $scope.$apply(function () {
          $scope.files.push(args.file);
      });
    });
    $scope.formSubmit=function(){
    	console.log("data" , $scope.formData);
    	console.log(" file" , $scope.files)
    	$scope.formData.user_signup_source='KramyaWeb';
			var data = new FormData();
			for (var key in $scope.formData) {
				data.append(key , $scope.formData[key]);
			}
			if($scope.files != [])
	    	data.append('user_img', $scope.files[0]);
      $.ajax({
			    url: 'http://kramya.com/kramyaapi/index.php/users',
			    data: data,
			    headers: {
			    'X-API-KEY': 'ANSHULVYAS16'
			    },
			    cache: false,
			    contentType: false,
			    processData: false,
			    type: 'POST',
			    success: function(data){
			    	console.log(data);
			    	toaster.pop({type: 'success', title: 'Signup successful', body:"Check Your inbox for activation link."});
			    	// alert('Check Your inbox for activation link.');
			    	 $location.path("/login");
			    }
			});
  	}
  });