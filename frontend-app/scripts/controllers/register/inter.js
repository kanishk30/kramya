angular.module('KramyaApp')
 .controller('interCtlr', function($scope,$location,$moment, toaster , $window , $http , store , myService , jwtHelper, $sce){
  	$scope.apiKey='ANSHULVYAS16';
  	if(store.get('jwt')){
		$scope.showSend=false;
		$scope.isLogin=true;
		$scope.userData=jwtHelper.decodeToken(store.get('jwt'));
		console.log('userdata in inbox',$scope.userData);
		$scope.apiKey=$scope.userData.userKey;
	}
	$scope.hospitalList=[];
	$scope.isAgreed=true;
	// $scope.hospitalList=[
	// 	{"hospital_id":"KRMHSPTL064396","hospital_name":"Fortis Healthcare","hospital_image":["http://kramya.com/kramyaapi/uploads/hospitals/KRMHSPTL064396/KRMHSPTL064396_21.png","http://kramya.com/kramyaapi/uploads/hospitals/KRMHSPTL064396/KRMHSPTL064396_5.jpg","http://kramya.com/kramyaapi/uploads/hospitals/KRMHSPTL064396/KRMHSPTL064396_1.png","http://kramya.com/kramyaapi/uploads/hospitals/KRMHSPTL064396/KRMHSPTL064396_4.png","http://kramya.com/kramyaapi/uploads/hospitals/KRMHSPTL064396/KRMHSPTL064396_6.jpg","http://kramya.com/kramyaapi/uploads/hospitals/KRMHSPTL064396/KRMHSPTL064396_2.png"],"city":"Gurgaon","state":"Haryana","country":"India","since":"2001","rating":[0,1,2,3,4]},
	// 	{"hospital_id":"KRMHSPTL893612","hospital_name":"Narayana Health","hospital_image":["http://kramya.com/kramyaapi/uploads/hospitals/KRMHSPTL893612/KRMHSPTL893612_3.jpg","http://kramya.com/kramyaapi/uploads/hospitals/KRMHSPTL893612/KRMHSPTL893612_1.jpg","http://kramya.com/kramyaapi/uploads/hospitals/KRMHSPTL893612/KRMHSPTL893612_0.jpg","http://kramya.com/kramyaapi/uploads/hospitals/KRMHSPTL893612/KRMHSPTL893612_2.jpg"],"city":"Bengaluru","state":"Karnataka","country":"India","since":"2001","rating":[0,1,2,3,4]},
	// 	{"hospital_id":"KRMHSPTL4021313","hospital_name":"Grewal Eye Institute Pvt Ltd","hospital_image":["http://kramya.com/kramyaapi/uploads/hospitals/KRMHSPTL4021313/KRMHSPTL4021313_10.jpg","http://kramya.com/kramyaapi/uploads/hospitals/KRMHSPTL4021313/KRMHSPTL4021313_4.jpg","http://kramya.com/kramyaapi/uploads/hospitals/KRMHSPTL4021313/KRMHSPTL4021313_6.jpg","http://kramya.com/kramyaapi/uploads/hospitals/KRMHSPTL4021313/KRMHSPTL4021313_7.jpg","http://kramya.com/kramyaapi/uploads/hospitals/KRMHSPTL4021313/KRMHSPTL4021313_5.jpg","http://kramya.com/kramyaapi/uploads/hospitals/KRMHSPTL4021313/KRMHSPTL4021313_2.jpg","http://kramya.com/kramyaapi/uploads/hospitals/KRMHSPTL4021313/KRMHSPTL4021313_1.jpg","http://kramya.com/kramyaapi/uploads/hospitals/KRMHSPTL4021313/KRMHSPTL4021313_9.jpg","http://kramya.com/kramyaapi/uploads/hospitals/KRMHSPTL4021313/KRMHSPTL4021313_0.jpg","http://kramya.com/kramyaapi/uploads/hospitals/KRMHSPTL4021313/KRMHSPTL4021313_3.jpg","http://kramya.com/kramyaapi/uploads/hospitals/KRMHSPTL4021313/KRMHSPTL4021313_8.jpg"],"city":"Chandigarh","state":"Chandigarh","country":"India","since":"1993","rating":[0,1,2,3]},
	// ];
	$scope.editHospital=function(id){
		$location.path("/register").search({'id':id});
	}
	$scope.viewHospital=function(id){
		$location.path("/hospital").search({'id':id});
	}
	var makeArray=function(num){
		var arr=[]
		for(var i=0 ; i<num ; i++){
			arr.push(i);
		}
		return arr;
	}
	var sendUrl="http://kramya.com/kramyaapi/index.php/hospitallist?X-API-KEY="+$scope.apiKey+"&id="+$scope.userData.userId;
	$http({
		method: 'GET',
		headers: {'Content-Type': 'application/json'},
		url: sendUrl
	})
	.success(function(res){
		console.log('res' , res);
		$scope.hospitalList=res.list.map(function(value){
			value.rating=makeArray(value.rating);
			return value;
		});
	})
});	