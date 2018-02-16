angular.module('KramyaApp')
	.controller('RegisterCtlr', function($scope,$location, $window , $http , toaster,$timeout, $interval,anchorSmoothScroll,store , myService , jwtHelper) {
		$scope.formData={'since':'2015'};
		$scope.firstformData={};
		$scope.files=[];
		$scope.is_agreed=true;
		$scope.apiKey='ANSHULVYAS16';
		$scope.progressDiv=1;
		$scope.test=function(){
			alert('test');
			// google.maps.event.trigger($scope.map,'resize');
		}
		$scope.renderMap=function(){
			google.maps.event.trigger($scope.map,'resize');
		}
		// $interval($scope.renderMap,3000);
  	$scope.$watch(function($scope) {return $scope.progressBar},
      function(newValue){
      	if(newValue==100){
      		$scope.progressDiv=9;
      	}
      	if(newValue<100){
      		$scope.progressDiv=8;
      	}
      	if(newValue<80){
      		$scope.progressDiv=7;
      		$timeout($scope.renderMap,1000);

      	}
      	if(newValue<70){
      		$scope.progressDiv=6;
      	}
      	if(newValue<65){
      		$scope.progressDiv=5;
      	}
      	if(newValue<55){
      		$scope.progressDiv=4;
      	}
      	if(newValue<35){
      		$scope.progressDiv=3;
      	}
      	if(newValue<25){
      		$scope.progressDiv=2;
      	}
      	if(newValue<15){
      		$scope.progressDiv=1;
      	}
      	
      });
		$scope.myProgress=function(){
			if($scope.progressDiv==1){
				$scope.progressBar+=15;
			}
			if($scope.progressDiv==2){
				$scope.progressBar+=10;
			}
			if($scope.progressDiv==3){
				$scope.progressBar+=10;
			}
			if($scope.progressDiv==4){
				$scope.progressBar+=20;
			}
			if($scope.progressDiv==5){
				$scope.progressBar+=10;
				$timeout($scope.renderMap,1000);
			}
			if($scope.progressDiv==6){
				$scope.progressBar+=5;
			}
			if($scope.progressDiv==7){
				$scope.progressBar+=10;
			}
			if($scope.progressDiv==8){
				$scope.progressBar+=20;
			}
			$scope.progressDiv++;
			anchorSmoothScroll.scrollTo('top-s');
		};
		$scope.myProgressBack=function(){
			if($scope.progressDiv==2){
				$scope.progressBar-=15;
			}
			if($scope.progressDiv==3){
				$scope.progressBar-=10;
			}
			if($scope.progressDiv==4){
				$scope.progressBar-=10;
			}
			if($scope.progressDiv==5){
				$scope.progressBar-=20;
			}
			if($scope.progressDiv==6){
				$scope.progressBar-=10;
			}
			if($scope.progressDiv==7){
				$scope.progressBar-=5;
			}
			if($scope.progressDiv==8){
				$scope.progressBar-=10;
			}
			if($scope.progressDiv==9){
				$scope.progressBar-=20;
			}
			$scope.progressDiv--;
			anchorSmoothScroll.scrollTo('top-s');
		}
		if(store.get('jwt')){
			$scope.storeData=jwtHelper.decodeToken(store.get('jwt'));
			$scope.apiKey=$scope.storeData.userKey;
			console.log('decoded',$scope.storeData);
			if($scope.storeData.role=='hospital'){
				var urlId=$location.search().id;

				var id=$scope.storeData.userId
				var sendUrl="http://kramya.com/kramyaapi/index.php/hospitals?X-API-KEY="+$scope.apiKey+"&hospital_id="+urlId;
				$http({
					method: 'GET',
					url: sendUrl
				})
				.success(function(response) {
					console.log("hospital data:" , response);
					$scope.formData=response;
					$scope.mapCentre=$scope.formData.latitude+","+$scope.formData.longitude;
					$scope.mapLocation=$scope.formData.city+', '+$scope.formData.state+', '+$scope.formData.country;
					// console.log("$scope.mapCentre" , $scope.mapCentre); 
					$scope.progressBar=parseInt($scope.formData.profile_complete_percentage);
					for (key in $scope.formData) {
						if($scope.formData[key]=='false'){
							$scope.formData[key]=false;
						}
						else if($scope.formData[key]=='true'){
							$scope.formData[key]=true;
						}
						if($scope.formData[key]=='null'){
							$scope.formData[key]='';
						}
					}
					if($scope.formData.procedure_supported)
						$scope.disToShowPro=$scope.formData.procedure_supported;
					if($scope.formData.specialisation_area)
					$scope.disToShowSpe=$scope.formData.specialisation_area;
				})
				.error(function(response) {
			  	console.log('hospital error:' , response);
			  	// alert(response.data);
				});
				// console.log('key' , key);
			}
		}
		$http({
			method: 'GET',
			url: "http://kramya.com/kramyaapi/index.php/autocomplete/disease?X-API-KEY="+$scope.apiKey
		})
		.success(function(response) {
			console.log("dis  data:" , response); 
			$scope.disData=response;
		})
		.error(function(response) {
	  	console.log('dis error:' , response);
	  	// alert(response.data);
		});
		$scope.disToShowPro=[];
		$scope.disToShowSpe=[];
		var inArray =function(element , array){
			for (var i = 0; i < array.length; i++) {
				if(array[i]==element){
					return true;
				}
			};
			return false;
		}
		$scope.addProDisease=function(dis){
			if(!inArray(dis.dis,$scope.disToShowPro))
				$scope.disToShowPro.push(dis.dis);
				
			else{
				toaster.pop({type: 'error',  title: 'Already Present'});
			}
		}
		$scope.addSpeDisease=function(dis){
			if(!inArray(dis.dis,$scope.disToShowSpe))
				$scope.disToShowSpe.push(dis.dis);
			else{
				toaster.pop({type: 'error', title: 'Already Present'});
			}
		}
		$scope.removePro=function(index){
			$scope.disToShowPro.splice(index, 1);
		}
		$scope.removeSpe=function(index){
			$scope.disToShowSpe.splice(index, 1);
		}
		$scope.cut = function(index) {
					$scope.files.splice(index, 1);
		}
		$scope.cutImage = function(index){
			$scope.formData.hospital_image.splice(index, 1);
		}
		$scope.$watch(function($scope) {return $scope.mapLocation},
      function(newValue){
      	if(newValue!=undefined && newValue!=null && newValue!=''){
      		// $interval($scope.renderMap,3000);
      		// $scope.mapLocation=newValue;
					var geocoder = new google.maps.Geocoder();
					geocoder.geocode({ 'address':$scope.mapLocation}, function(results, status) {
						if(status == google.maps.GeocoderStatus.OK) {
							if(results[0].geometry.location.lat()!=undefined){
								$scope.formData.latitude = results[0].geometry.location.lat();
								$scope.formData.longitude = results[0].geometry.location.lng();
								$scope.mapCentre=results[0].geometry.location.lat()+","+results[0].geometry.location.lng();
							}
							var address_component=results[0].address_components;
							for (var i = 0; i < address_component.length; i++) {
								if(address_component[i].types[0]=='postal_code'){
									$scope.formData.pincode=address_component[i].long_name;
									console.log('$scope.formData.pincode' , $scope.formData.pincode);
								}
								if(address_component[i].types[0]=='country'){
									$scope.formData.country=address_component[i].long_name;
									console.log('$scope.formData.country' , $scope.formData.country);
								}
								if(address_component[i].types[0]=='administrative_area_level_1'){
									$scope.formData.state=address_component[i].long_name;
									console.log('$scope.formData.state' , $scope.formData.state);
								}
								if(address_component[i].types[0]=='locality'){
									$scope.formData.city=address_component[i].long_name;
									console.log('$scope.formData.city' , $scope.formData.city);
								}
							}
						} 
					});
				}
			}); 
		$scope.$on("fileSelected", function (event, args) {
      $scope.$apply(function () {
          $scope.files.push(args.file);
      });
    });
    $scope.firstFormSubmit=function(fromPage){
    	$scope.firstformData['X-API-KEY']= $scope.apiKey;
    	if(fromPage==="out"){
    		var sendUrl='http://kramya.com/kramyaapi/index.php/hospitals/register_basic';
    	}
    	else{
    		sendUrl='http://kramya.com/kramyaapi/index.php/hospitalsnew/register_basic';
    		$scope.firstformData['hospital_emailid']=$scope.storeData.userEmail;
    	}
    	console.log('$scope.firstformData',$scope.firstformData);
    	console.log('sendUrl' , sendUrl);
			$http({
				method: 'POST',
				headers: {'Content-Type': 'application/json'},
				url: sendUrl,
				data:$scope.firstformData
			})
			.success(function (response) {
				console.log('success:' ,response);
				toaster.pop({type: 'success', title: 'Success',  body:response});
			})
			.error(function(response) {
	  		console.log('error:' , response);
		  	toaster.pop({type: 'error', title: 'Registration failed',  body:response});

	  	// alert(response.data);
			}); 
			
    }
		$scope.formSubmit=function(){
			$scope.formData.profile_complete_percentage=$scope.progressBar;
			var data = new FormData();
			$scope.formData.procedure_supported='';
			$scope.formData.specialisation_area='';
			for (var i=0; i<$scope.disToShowPro.length; i++) {
				if(i==$scope.disToShowPro.length-1){
					$scope.formData.procedure_supported+=$scope.disToShowPro[i];
				}
				else{
					$scope.formData.procedure_supported+=$scope.disToShowPro[i]+',';
				}
			}
			for (var i = 0; i < $scope.disToShowSpe.length; i++) {
				if(i==$scope.disToShowSpe.length-1){
					$scope.formData.specialisation_area+=$scope.disToShowSpe[i];
				}
				else{
					$scope.formData.specialisation_area+=$scope.disToShowSpe[i]+',';
				}
			}
			for (var key in $scope.formData) {
				data.append(key , $scope.formData[key]);
			}
			for (var i = 0; i < $scope.files.length; i++) {
	      data.append('hospital_img[]', $scope.files[i]);
	    }
	    console.log("my data:" ,$scope.formData)
	    if($scope.is_agreed){
	    	toaster.pop({type: 'wait', title: 'Profile Update Processing', body:"Hold On! We are processing your request."});
	      $.ajax({
				    url: 'http://kramya.com/kramyaapi/index.php/hospitals/register_complete',
				    data: data,
				    headers: {
				    'X-API-KEY': $scope.apiKey
				    },
				    cache: false,
				    contentType: false,
				    processData: false,
				    type: 'POST',
				    success: function(data){
				    	console.log(data);
				    	toaster.pop({type: 'success', title: 'Updated successfully',  body:'Your profile has been updated successfully.'});
				    	// location.replace("/");
				    },
				    error: function (xhr, ajaxOptions, thrownError) {
			        // alert(xhr.status);
			        // alert(thrownError);
			        console.log(thrownError);
			        // location.replace("/");
			      }
				});
			}
			else{
				toaster.pop({type: 'error', title: 'Registration failed',  body:'You must agree to our terms and conditions in order to register.'});
			}
  	}
	})
	.directive('fileUpload', function () {
    return {
        scope: true,
        link: function (scope, el, attrs) {
            el.bind('change', function (event) {
                var files = event.target.files;
                for (var i = 0;i<files.length;i++) {
                    scope.$emit("fileSelected", { file: files[i] });
                }                                       
            });
        }
    };
});