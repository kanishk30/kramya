'use strict';

angular.module('KramyaApp')
	.controller('hospitalDataCtlr', function($scope,$location, $window , $http , myService , $sce) {
		console.log("url parameters",$location.search().id);
		var hospitalId=$location.search().id;
		var sendUrl="http://192.168.69.15:8080/kramya-development/kramyaapi/index.php/hospitals?X-API-KEY=ANSHULVYAS16&hospital_id="+hospitalId;
				$http({
				  method: 'GET',
				  url: sendUrl
				}).then(function successCallback(response) {
					console.log('success hospitals:' ,response.data);
				    $scope.hospitalData = response.data;
				    myService.set('hospitalImageArray',$scope.hospitalData.hospital_image )
				  }, function errorCallback(response) {
				  	console.log('error:' , response);
				  });


		$scope.$watch(function($scope) {return $scope.hospitalData},
		      function(newValue){
		      	console.log("newValue" , newValue)
		      	if(newValue != undefined || newValue != null ){
			        $scope.searchData=newValue;
			      	var arr=[];
			        for (var j = parseInt($scope.searchData.hospital_rating)-1; j >= 0; j--){
								arr.push(j);
							}
							$scope.searchData.hospital_rating=arr;
			        console.log("this is what you need:",$scope.searchData);
			      }
		      });
		$scope.toTrustedHTML = function( htmlString ){
		  return $sce.trustAsHtml( htmlString );
		}

	});