'use strict';

angular.module('KramyaApp')
	.controller('hospitalDataCtlr', function($scope,$location,$moment, toaster , $window , $http , store , myService , jwtHelper, $sce) {
		console.log("url parameters",$location.search());
		$scope.files=[];
		$scope.isLogin=false;
		$scope.showFilter=false;
		$scope.rData='-review_time';
		$scope.filterText="Time";
		$scope.isHospital=false;
    $scope.rating = 5;
    $scope.comment="";
    $scope.myInterval = 5000;
  	$scope.noWrapSlides = false;
    $scope.commentData=[];
    $scope.userData=[];
    $scope.enquiryData={ 
    	"fromDate": $moment().format("DD-MMMM-YYYY"), 
    	"toDate": $moment().format("DD-MMMM-YYYY"),
    	"userIntrest":"general",
   		"userMessage": ''};
   	$scope.searchData='';
   	$scope.dis={
   		originalObject:{
   			dis:'(Disease Name)'
   		}
   	};
    $scope.userIntrestName='I just started researching and looking for a general information';
    $scope.toggleBounce = function() { if (this.getAnimation() != null) { this.setAnimation(null); } else { this.setAnimation(google.maps.Animation.BOUNCE); } }
    $scope.postComment=function(){
    	var commentJson={
    		'X-API-KEY':'ANSHULVYAS16',
    		user_id:$scope.userData.userId,
    		hospital_id:$scope.searchData.hospital_id,
    		review_time_text:$moment().fromNow(),
    		review_time:$moment(),
    		user_image:$scope.userData.userImage,
    		user_name:$scope.userData.userName,
    		rating:$scope.rating,
    		comment:$scope.comment
    	};
    	var frontcommentData = commentJson;
			$http({
				method: 'POST',
				headers: {'Content-Type': 'application/json'},
				url: 'http://kramya.com/kramyaapi/index.php/reviews',
				data:commentJson
			})
			.then(function successCallback(response) {
				console.log('success comm:' ,response);
				toaster.pop({type: 'success', title: 'Comment Posted',  body:response.data[1]});
				var arre=[];
				// console.log('$scope.rating' , $scope.rating);
		    for (var i = 0; i < $scope.rating; i++) {
		    	arre.push(i);
		    };
				frontcommentData.rating=arre;
				frontcommentData.review_time=$moment().format();
	    	$scope.commentData.push(frontcommentData);
	    	$scope.comment='';
	    	$scope.rating=5;
				// toaster.pop({type: 'success', title: 'Success',  body:response.data[1]});
			}, 
			function errorCallback(response) {
		  	console.log('error comm:' , response);
		  	toaster.pop({type: 'error', title: 'Registration failed',  body:response.data[1]});
			});
			
    };
    $scope.goToHospital=function(id , name){
    	toaster.pop({type: 'info', title: 'Opening '+name});
    	$location.path("/hospital").search({'id':id,'disease':$scope.deseaseId ,'location':$scope.hospitalLocation});
    	$scope.slides=[];
    	$scope.hospitalId=id;
    };
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
		$scope.apiKey='ANSHULVYAS16';
		if(store.get('jwt')){
			$scope.isLogin=true;
			$scope.userData=jwtHelper.decodeToken(store.get('jwt'));
			console.log('userdata',$scope.userData);
			$scope.apiKey=$scope.userData.userKey;
			if($scope.userData.role=='hospital'){
						$scope.isHospital=true;
					}
			// console.log('key' , key);
		}
		$scope.$watch(function($scope) {return store.get('jwt')},
      function(newValue){
      	if(newValue !=null && newValue!=undefined){
	      	$scope.isLogin=true;
					$scope.userData=jwtHelper.decodeToken(newValue);
					console.log('userdata',$scope.userData);
					if($scope.userData.role=='hospital'){
						$scope.isHospital=true;
					}
					$scope.apiKey=$scope.userData.userKey;
				}
				else{
					$scope.isLogin=false;
				}
      });
		
		$scope.hospitalId=$location.search().id;
		$scope.deseaseId=$location.search().disease;
		$scope.hospitalLocation=$location.search().location;
		if(myService.get('hospitalData')){
			$scope.hospitalData=myService.get('hospitalData');
			console.log('$scope.hospitalData:' , $scope.hospitalData)
		}
		else{
			var sendUrl="http://kramya.com/kramyaapi/index.php/hospitals/profile?X-API-KEY="+$scope.apiKey+"&hospital_id="+$scope.hospitalId+"&disease_id="+$scope.deseaseId+"&location="+$scope.hospitalLocation;
			// console.log('url to send:' , sendUrl);
			$http({
			  method: 'GET',
			  url: sendUrl
			})
			.success(function (response, status, headers, config){
				console.log('success hospitals:' ,response);
			  $scope.hospitalData = response;
			  $scope.slides=$scope.hospitalData.hospital_image;
			})
			.error(function (response, status, headers, config) {
	      console.log('error hospitals:' , response);
	    });
	  }       
	  $scope.$watch(function($scope) {return $scope.hospitalId},
      function(newValue){
      	if(newValue){
      		var sendUrl="http://kramya.com/kramyaapi/index.php/hospitals/profile?X-API-KEY="+$scope.apiKey+"&hospital_id="+$scope.hospitalId+"&disease_id="+$scope.deseaseId+"&location="+$scope.hospitalLocation;
				$http({
				  method: 'GET',
				  url: sendUrl
				})
				.success(function (response, status, headers, config){
					console.log('success hospitals:' ,response);
				  $scope.hospitalData = response;
				  $scope.slides=$scope.hospitalData.hospital_image;
				  				  
				  for (var i = 0; i < $scope.hospitalData.reviews.length; i++) {
				  	var arr=[];
				    for (var j = parseInt($scope.hospitalData.reviews[i].rating)-1; j >= 0; j--){
							arr.push(j);
						}
						$scope.hospitalData.reviews[i].review_index=parseInt($scope.hospitalData.reviews[i].review_index);
						$scope.hospitalData.reviews[i].rating=arr;
						// $scope.hospitalData.reviews[i].review_time=$moment($scope.hospitalData.reviews[i]).add(-6, 'hours');
						$scope.hospitalData.reviews[i].review_time_text=$moment($scope.hospitalData.reviews[i].review_time).fromNow();
				  };
				  $scope.commentData=$scope.hospitalData.reviews;
				  console.log('$scope.commentData:' , $scope.commentData);
				})
				.error(function (response, status, headers, config) {
		      console.log('error hospitals:' , response);
		    });
		}		
      });
		$scope.$watch(function($scope) {return $scope.hospitalData},
      function(newValue){
      	// console.log("newValue" , newValue)
      	if(newValue != undefined || newValue != null ){
	        $scope.searchData=newValue;
	      	var arr=[];
	        for (var j = parseInt($scope.searchData.hospital_rating)-1; j >= 0; j--){
						arr.push(j);
					}
					$scope.searchData.hospital_rating=arr;
					$scope.mapMarker=$scope.searchData.latitude+','+$scope.searchData.longitude;
					if($scope.userData)
				   	$scope.userMessageData="<div><b>Hi "+$scope.searchData.hospital_name+",<br/>This is "+ $scope.userData.userName+". I am travelling to India on <mark>"+$scope.enquiryData.fromDate +"</mark> to <mark>"+$scope.enquiryData.toDate +"</mark> and <mark>"+$scope.userIntrestName+"</mark> for <mark>"+$scope.diseaseName+"</mark> I need following details:</b><br/><ol><li>Duration of treatment</li><li>Accommodation support</li></ol><b>Could you please let me know what document and formalities you need from my end to confirm the appointment.I look forward for your quick revert.<br>Regards<br>"+$scope.userData.userName+"</b></div>"; 	
	      }
      });
		$scope.$watch(function($scope) {return $scope.enquiryData.userIntrest},
    function(newValue){
    	// console.log("newValue" , newValue)
    	if(newValue != undefined || newValue != null ){
    		if($scope.enquiryData.userIntrest=='intrested'){
    			$scope.userIntrestName='I am in the process of choosing the right clinic';
    		}
    		else if($scope.enquiryData.userIntrest=='appointment'){
    			$scope.userIntrestName='I am now ready to book an appointment';
    		}
    		$scope.userMessageData="<div><b>Hi "+$scope.searchData.hospital_name+",<br/>This is "+ $scope.userData.userName+". I am travelling to India on <mark>"+$scope.enquiryData.fromDate +"</mark> to <mark>"+$scope.enquiryData.toDate +"</mark> and <mark>"+$scope.userIntrestName+"</mark> for <mark>"+$scope.diseaseName+"</mark> I need following details:</b><br/><ol><li>Duration of treatment</li><li>Accommodation support</li></ol><b>Could you please let me know what document and formalities you need from my end to confirm the appointment.I look forward for your quick revert.<br>Regards<br>"+$scope.userData.userName+"</b></div>";
    	}
    });
		$scope.$watch(function($scope) {return $scope.enquiryData.toDate},
    function(newValue){
    	// console.log("newValue" , newValue)
    	if(newValue != undefined || newValue != null ){
    		$scope.userMessageData="<div><b>Hi "+$scope.searchData.hospital_name+",<br/>This is "+ $scope.userData.userName+". I am travelling to India on <mark>"+$scope.enquiryData.fromDate +"</mark> to <mark>"+$scope.enquiryData.toDate +"</mark> and <mark>"+$scope.userIntrestName+"</mark> for <mark>"+$scope.diseaseName+"</mark> I need following details:</b><br/><ol><li>Duration of treatment</li><li>Accommodation support</li></ol><b>Could you please let me know what document and formalities you need from my end to confirm the appointment.I look forward for your quick revert.<br>Regards<br>"+$scope.userData.userName+"</b></div>";
    	}
    });
		$scope.$watch(function($scope) {return $scope.enquiryData.fromDate},
    function(newValue){
    	// console.log("newValue" , newValue)
    	if(newValue != undefined || newValue != null ){
    		$scope.userMessageData="<div><b>Hi "+$scope.searchData.hospital_name+",<br/>This is "+ $scope.userData.userName+". I am travelling to India on <mark>"+$scope.enquiryData.fromDate +"</mark> to <mark>"+$scope.enquiryData.toDate +"</mark> and <mark>"+$scope.userIntrestName+"</mark> for <mark>"+$scope.diseaseName+"</mark> I need following details:</b><br/><ol><li>Duration of treatment</li><li>Accommodation support</li></ol><b>Could you please let me know what document and formalities you need from my end to confirm the appointment.I look forward for your quick revert.<br>Regards<br>"+$scope.userData.userName+"</b></div>";
    	}
    });
    $scope.$watch(function($scope) {return $scope.dis.originalObject.dis},
    function(newValue){
    	// console.log("newValue" , newValue)
    	if(newValue != undefined || newValue != null ){
    		$scope.diseaseName=newValue
    		$scope.userMessageData="<div><b>Hi "+$scope.searchData.hospital_name+",<br/>This is "+ $scope.userData.userName+". I am travelling to India on <mark>"+$scope.enquiryData.fromDate +"</mark> to <mark>"+$scope.enquiryData.toDate +"</mark> and <mark>"+$scope.userIntrestName+"</mark> for <mark>"+$scope.diseaseName+"</mark> I need following details:</b><br/><ol><li>Duration of treatment</li><li>Accommodation support</li></ol><b>Could you please let me know what document and formalities you need from my end to confirm the appointment.I look forward for your quick revert.<br>Regards<br>"+$scope.userData.userName+"</b></div>";
    	}
    });
		$scope.toTrustedHTML = function( htmlString ){
		  return $sce.trustAsHtml( htmlString );
		}
		$scope.$on("fileSelected", function (event, args) {
      $scope.$apply(function () {
          $scope.files.push(args.file);
      });
    });
		$scope.cut = function(text) {
			for (var i = 0; i < $scope.files.length; i++) {
				if($scope.files[i].name==text){
					var index = $scope.files.indexOf(i);
					$scope.files.splice(index, 1);
				}
			}
		}
		$scope.filterTimeDown=function(){
			$scope.rData='review_time';
			$scope.showFilter=false;
			$scope.filterText="Time";
		}
		$scope.filterTimeUp=function(){
			$scope.rData='-review_time';
			$scope.showFilter=false;
			$scope.filterText="Time";
		}
		$scope.filterRateUp=function(){
			$scope.rData='-rating';
			$scope.showFilter=false;
			$scope.filterText="Rating";
		}
		$scope.filterRateDown=function(){
			$scope.rData='rating';
			$scope.showFilter=false;
			$scope.filterText="Rating";
		}
		$scope.sendEnquiry=function(dis){
			var data = new FormData();
			$scope.enquiryData.userId=$scope.userData.userId;
			$scope.enquiryData.userEmailId=$scope.userData.userEmail;
			$scope.enquiryData.userName=$scope.userData.userName;
			$scope.enquiryData.hospitalEmailId=$scope.searchData.hospitalEmailId;
			$scope.enquiryData.hospitalName=$scope.searchData.hospital_name;
			$scope.enquiryData.hospitalId=$scope.hospitalId;
			$scope.enquiryData.userMessage=$scope.userMessageData;
			if(dis != null && dis!=undefined){
				$scope.enquiryData.procedureId=dis.id;
				$scope.enquiryData.procedureName=dis.dis;
			}

			for (var key in $scope.enquiryData) {
				data.append(key , $scope.enquiryData[key]);
			}
			for (var i = 0; i < $scope.files.length; i++) {
	      data.append('user_report[]', $scope.files[i]);
	    }
	    $.ajax({
		    url: 'http://kramya.com/kramyaapi/index.php/booking',
		    data: data,
		    headers: {
		    'X-API-KEY': $scope.apiKey
		    },
		    cache: false,
		    contentType: false,
		    processData: false,
		    type: 'POST',
		    success: function(data){
		    	toaster.pop({type: 'success', title: 'Enquiry Sent',  body:data.book});
		    	// alert(data.book);
		    	// $location.path("/hospital").search({'id':$scope.formData.hospital_id});
		    	console.log(data);
		      
		    },
		     error: function(jqXHR, textStatus, errorThrown){
		     	toaster.pop({type: 'error', title: 'Enquiry Not Sent',  body:errorThrown});
		     	console.log(errorThrown);
        //if fails     
    		}
			});
			console.log('$scope.enquiryData',$scope.enquiryData);
		}
	})
.directive('dateFrom', function() {
		return function (scope, element, attrs) {
				var doDate = $('#EndDate');
				element.datepicker({
					dateFormat: 'dd-MM-yy', showOtherMonths: true, selectOtherMonths: true, minDate: '0',
          beforeShowDay: function (date) {
              var day = date.getDay();
              return [true, ''];
          },
          onSelect: function (selectedDate) {
            var toDate = new Date(element.datepicker("getDate"));
            toDate.setDate(toDate.getDate() + 1);
              doDate.datepicker('option', 'minDate', toDate);
              scope.enquiryData.fromDate = selectedDate;
              scope.enquiryData.toDate = doDate.val();
          }
				});
		}
	})
.directive('dateTo', function() {
	return function (scope, element, attrs) {
		element.datepicker({
			dateFormat: 'dd-MM-yy', showOtherMonths: true, selectOtherMonths: true, minDate: '1d',
      beforeShowDay: function (date) {
        var day = date.getDay();
        return [true, ''];
			},
			onSelect: function (selectedDate) {
				scope.enquiryData.toDate = selectedDate;
			}
    });
	}
})
.directive('starRating',
	function() {
		return {
			restrict : 'A',
			template : '<ul class="rating">'
					 + '	<li ng-repeat="star in stars" ng-class="star" ng-click="toggle($index)">'
					 + '\u2605'
					 + '</li>'
					 + '</ul>',
			scope : {
				ratingValue : '=',
				max : '=',
				onRatingSelected : '&'
			},
			link : function(scope, elem, attrs) {
				var updateStars = function() {
					scope.stars = [];
					for ( var i = 0; i < scope.max; i++) {
						scope.stars.push({
							filled : i < scope.ratingValue
						});
					}
				};
				
				scope.toggle = function(index) {
					scope.ratingValue = index + 1;
					scope.onRatingSelected({
						rating : index + 1
					});
				};
				
				scope.$watch('ratingValue',
					function(oldVal, newVal) {
						if (newVal) {
							updateStars();
						}
					}
				);
			}
		};
	}
);
