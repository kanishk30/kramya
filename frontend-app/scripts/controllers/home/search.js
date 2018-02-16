'use strict';

angular.module('KramyaApp')
	.controller('searchResultCtlr', function($scope,$location,$http, $interval, toaster, $timeout, $window , store , myService , jwtHelper) {
		$scope.apiKey='ANSHULVYAS16';
		$scope.showMapHere=true;
		$scope.favData={};
		$scope.myInterval = 100000;
		// $scope.m=false;
		// $scope.mh=function(){
		// 	$scope.m=!$scope.m;
		// }
  	$scope.noWrapSlides = false;
		if(store.get('jwt')){
			$scope.isLogin=true;
			$scope.userData=jwtHelper.decodeToken(store.get('jwt'));
			$scope.apiKey=$scope.userData.userKey;
		}
		$scope.$watch(function($scope) {return store.get('jwt')},
      function(newValue){
      	if(newValue !=null && newValue!=undefined){
	      	$scope.isLogin=true;
					$scope.userData=jwtHelper.decodeToken(newValue);
					$scope.apiKey=$scope.userData.userKey;
				}
				else{
					$scope.isLogin=false;
				}
      });
		$scope.toggleBounce = function() { if (this.getAnimation() != null) { this.setAnimation(null); } else { this.setAnimation(google.maps.Animation.BOUNCE); } }
		//map data
		$scope.showMap=function(){
			$scope.showMapHere=!$scope.showMapHere;
			// $interval($scope.reRednerMap , 1000);
			// $timeout($scope.reRednerMap,1001);
		}
		// $timeout($scope.showMap,3000);
		$scope.reRednerMap = function() {
         angular.forEach($scope.maps, function(index) {
            google.maps.event.trigger(index, 'resize');
          });
         // console.log('p:');
    }
    // $timeout($scope.reRednerMap,1001);
    $interval($scope.reRednerMap , 1000);
    $scope.maps = [];
    $scope.$on('mapInitialized', function(evt, evtMap) {
          $scope.maps.push(evtMap);
    });
		// $scope.mapCentre="nagpur";
		// $scope.mapZoom=5;
		$scope.c=true;

		// $scope.charp=function(){
		// 	$scope.c=false;
		// }
		// $timeout($scope.charp,1000);
		$scope.searchKramya=function(dis,loc){
			$scope.repeatData='-hospital_rating';
			if(loc == undefined && dis == undefined){
				toaster.pop({type: 'error', title: "Empty Search!", body:"Did you miss something?"});
				// alert("Select anything from box dude!");
			}
			else {
				if(loc == undefined ){
					loc='';
				}
				else if(dis== undefined){
					dis='';
				}
				$location.path("/search-result").search({'dis':dis, 'loc':loc});
				sendUrl='http://kramya.com/kramyaapi/index.php/filter/search?X-API-KEY='+$scope.apiKey+'&disease='+dis+'&location='+loc;
					$http({
						method: 'GET',
						url: sendUrl
					})
					.then(function successCallback(response) {
						console.log('success:' ,response);
						$scope.searchResult=response.data;
						window.scrollTo(0,0);
					}, 
					function errorCallback(response) {
				  	console.log('error:' , response);
				  	// alert(response.data);
				});	
			}
		}
		$scope.selected = undefined;
		$scope.showSearch=false;
		$scope.setShowSearch=function(){
			$scope.showSearch=!$scope.showSearch;
			$scope.showDrop=false;
		}
		$scope.repeatData='-hospital_rating';
		$scope.ratingUp=function(){
			$scope.repeatData='-hospital_rating';
			$scope.showDrop=false;
		}
		$scope.ratingDown=function(){
			$scope.repeatData='hospital_rating';
			$scope.showDrop=false;
		}
		$scope.doctorsUp=function(){
			$scope.repeatData='-doctors';
			$scope.showDrop=false;
		}
		$scope.doctorsDown=function(){
			$scope.repeatData='doctors';
			$scope.showDrop=false;
		}
		$scope.bedsUp=function(){
			$scope.repeatData='-beds';
			$scope.showDrop=false;
		}
		$scope.bedsDown=function(){
			$scope.repeatData='beds';
			$scope.showDrop=false;
		}
		$scope.sinceUp=function(){
			$scope.repeatData='-since';
			$scope.showDrop=false;
		}
		$scope.sinceDown=function(){
			$scope.repeatData='since';
			$scope.showDrop=false;
		}
		console.log("url parameters",$location.search())
		var dis=$location.search().dis;
		var loc=$location.search().loc;
		var sendUrl='http://kramya.com/kramyaapi/index.php/filter/search?X-API-KEY='+$scope.apiKey+'&disease='+dis+'&location='+loc;
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
        	$scope.showSearch=false;
        	if(newValue.error){
			  		$scope.searchData='';
			  		$scope.countResult=0;
			  		$scope.showMapHere=false;
			  		$scope.showSearch=true;
			  		// alert(newValue.error);
			  		toaster.pop({type: 'error', title: "No Result", body:newValue.error});
			  	}
			  	else{
			  		$scope.countResult=$scope.searchData.length;
			  		toaster.pop({type: 'success', title: "Search successful!", body:$scope.countResult+" Result Found!"});
			  		var bounds = new google.maps.LatLngBounds();
			  		var polygonCoords = [];
			  		$scope.marker=[];
			      for (var i = 0; i < $scope.countResult; i++){
			      	$scope.searchData[i].showHeart=true;
			      	if($scope.searchData[i].latitude!=0 && $scope.searchData[i].longitude != 0){
			      		polygonCoords.push(new google.maps.LatLng($scope.searchData[i].latitude, $scope.searchData[i].longitude));
			      		$scope.marker.push({'position':$scope.searchData[i].latitude +","+$scope.searchData[i].longitude ,'title':$scope.searchData[i].hospital_name });
			      	}
			      	// console.log("$scope.marker" , $scope.marker);
			      	// if($scope.searchData[i].hospital_image[0][$scope.searchData[i].hospital_image[0].length-1]=='b'){
			      	// 	$scope.searchData[i].hospital_image[0]=$scope.searchData[i].hospital_image[1];
			      	// }
					    // myService.set('hospitalImageArray' , $scope.searchData[i].hospital_image);
						}
						for (i = 0; i < polygonCoords.length; i++) {
						  bounds.extend(polygonCoords[i]);
						}
						$scope.mapCentre=bounds.getCenter().lat()+','+bounds.getCenter().lng();
						console.log('$scope.mapCentre:' , $scope.mapCentre);
						var GLOBE_WIDTH = 256; // a constant in Google's map projection
						var west = bounds.getSouthWest().lng();
						var east = bounds.getNorthEast().lng();
						var angle = east - west;
						if (angle < 0) {
						  angle += 360;
						}
						console.log('$window.innerWidth' , $window.innerWidth);
						console.log('angle' , angle);
						if(angle==0){
							$scope.mapZoom=11;
						}
						else{
							$scope.mapZoom = Math.round(Math.log($window.innerWidth*0.1* 360 / angle / GLOBE_WIDTH) / Math.LN2);
						}
						console.log("$scope.mapZoom:" , $scope.mapZoom);
						// console.log('bound north east:' , bounds.getNorthEast().lat()+','+bounds.getNorthEast().lng());
					}
				}
      });
      $scope.addFavorite=function(item){
      	item.showHeart = !item.showHeart;
      	$scope.favData.hospitalId=item.hospital_id;
      	$scope.favData.hospitalName=item.hospital_name;
      	$scope.favData.userEmail=$scope.userData.userEmail;
      	$scope.favData.userId=$scope.userData.userId;
      	console.log('hos id fav:' , $scope.favData);
      	sendUrl='http://kramya.com/kramyaapi/index.php/favourites';
      	$http({
      		headers: {
      			'X-API-KEY':$scope.apiKey,
				   'Content-Type': 'application/json'
				 },
					method: 'POST',
					url: sendUrl,
					data:$scope.favData
				})
				.then(function successCallback(response) {
					toaster.pop({type: 'success', title: "Added successfully!", body:response.data.favourites});
					// alert(response.data.favourites);
					console.log('success:' ,response);
				}, 
				function errorCallback(response) {
			  	console.log('error:' , response);
			  	// alert(response.data);
			});	
      }	
			$scope.searchHospital=function(id){
				$location.path("/hospital").search({'id':id,'disease':dis ,'location':loc });
			}
		});
