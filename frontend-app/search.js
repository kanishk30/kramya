'use strict';

angular.module('KramyaApp')
	.controller('searchResultCtlr', function($scope,$location,$http, $window , myService) {
		
		$scope.selected = undefined;
		$scope.repeatData='-hospital_rating';
		console.log("url parameters",$location.search())
		var dis=$location.search().dis;
		var loc=$location.search().loc;
		var sendUrl='http://192.168.69.15:8080/kramya-development/kramyaapi/index.php/filter/search?X-API-KEY=ANSHULVYAS16&disease='+dis+'&location='+loc;
		$http({
			method: 'GET',
			url: sendUrl
		})
		.then(function successCallback(response) {
			console.log('success:' ,response);
			$scope.searchResult=response.data;
		}, 
		function errorCallback(response) {
	  	console.log('error:' , response);
	  	// alert(response.data);
	});	   
		$scope.$watch(function($scope) {return $scope.searchResult},
		  function(newValue){
		    $scope.searchData=newValue;
        if(newValue!=undefined ){
        	if(newValue.error){
			  		$scope.searchData='';
			  		$scope.countResult=0;
			  		alert(newValue.error);
			  	}
			  	else{
			  		$scope.countResult=$scope.searchData.length;
			      for (var i = 0; i < $scope.countResult; i++){
			      	if($scope.searchData[i].hospital_image[0][$scope.searchData[i].hospital_image[0].length-1]=='b'){

			      		$scope.searchData[i].hospital_image[0]=$scope.searchData[i].hospital_image[1];
			      	}
					    myService.set('hospitalImageArray' , $scope.searchData[i].hospital_image);
			      	var arr=[];
			        for (var j = parseInt($scope.searchData[i].hospital_rating)-1; j >= 0; j--){
								arr.push(j);
							}
							var hospital_name = $scope.searchData[i].hospital_name.replace(/[&]/g, "and");
							hospital_name=hospital_name.replace(/[" "]/g, "+");
							$scope.searchData[i].mapUrl="https://www.google.com/maps/embed/v1/place?q="+hospital_name+","+$scope.searchData[i].city+","+$scope.searchData[i].state+","+$scope.searchData[i].country+","+"&key=AIzaSyAN0om9mFmy1QN6Wf54tXAowK4eT0ZUPrU";
							$scope.searchData[i].hospital_rating=arr;
						}
					}
				}
      });	
			$scope.searchHospital=function(id){
				$location.path("/hospital").search({'id':id});
			}
		});
