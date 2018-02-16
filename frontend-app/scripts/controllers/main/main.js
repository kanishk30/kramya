'use strict';

angular.module('KramyaApp')
	.controller('mainController', function($scope,$location, $window , $http , store , toaster , myService , jwtHelper , anchorSmoothScroll) {
		$scope.apiKey='ANSHULVYAS16';
		$scope.imgDataLoad=false;
		if(store.get('jwt')){
			$scope.apiKey=jwtHelper.decodeToken(store.get('jwt')).userKey;
			// console.log('key' , key);
		}
		$scope.scrollTo = function(id) {
      // $location.hash(id);
      anchorSmoothScroll.scrollTo(id);
   }
   $http({
			method: 'GET',
			url: "http://kramya.com/kramyaapi/index.php/data/frontdata?X-API-KEY="+$scope.apiKey
		})
		.success(function(response) {
			
			$scope.frontdata=response;
			for (var i = 0; i < $scope.frontdata.most_popular_hospitals.length; i++) {
		  	var arr=[];
		    for (var j = parseInt($scope.frontdata.most_popular_hospitals[i].rating)-1; j >= 0; j--){
					arr.push(j);
				}
				$scope.frontdata.most_popular_hospitals[i].rating=arr;
			};
			console.log("frontdata  data:" , $scope.frontdata); 
			$scope.imgDataLoad=true;

		})
		.error(function(response) {
	  	console.log('frontdata error:' , response);
	  	// alert(response.data);
		});
		$scope.imageSearch=function(city){
			// alert(city);
			$location.path("/search-result").search({'dis':'', 'loc':city});
		};
		$http({
			method: 'GET',
			url: "http://kramya.com/kramyaapi/index.php/autocomplete/disease?X-API-KEY="+$scope.apiKey
		})
		.success(function(response) {
			// console.log("dis  data:" , response); 
			$scope.disData=response;
		})
		.error(function(response) {
	  	console.log('dis error:' , response);
	  	// alert(response.data);
		});
		$http({
			method: 'GET',
			url: "http://kramya.com/kramyaapi/index.php/autocomplete/location?X-API-KEY=ANSHULVYAS16"
		})
		.then(function successCallback(response) {
			// console.log("city data:" , response.data); 
			$scope.cities=response.data;
		}, 
		function errorCallback(response) {
	  	console.log('city error:' , response);
	  	// alert(response.data);
		});
		// $scope.$watch(function($scope) {return $scope.autoresult},
		//   function(newValues){
  //       if(newValues!=undefined ){
  //       	console.log("city data:" , newValues)
  //       	$scope.cities=newValues;
  //       }
  //     });
		
		$scope.showService=false;
		$scope.searchKramya=function(dis,loc){
			if(loc == undefined && dis == undefined){
				toaster.pop({type: 'error', title: "Empty Search!", body:"Did you miss something?"});
			}
			else {
				if(loc == undefined ){
					loc='';
				}
				else if(dis== undefined){
					dis='';
				}
				$location.path("/search-result").search({'dis':dis, 'loc':loc});
			}
		};
		$scope.searchHospital=function(id){
			$location.path("/hospital").search({'id':id});
		}
	});