angular.module('KramyaApp')
  .controller('suggCtlr', function($scope,$location,$moment, toaster , $window , $http , store , myService , jwtHelper, $sce){
  	$scope.apiKey='ANSHULVYAS16';
  	if(store.get('jwt')){
			$scope.showSend=false;
			$scope.isLogin=true;
			$scope.userData=jwtHelper.decodeToken(store.get('jwt'));
			console.log('userdata in inbox',$scope.userData);
			$scope.apiKey=$scope.userData.userKey;
		}
		var sendUrl="http://kramya.com/kramyaapi/index.php/reachus/suggestions";
		$scope.sugData={
			'X-API-KEY':$scope.apiKey
		}
		$scope.submitSugg=function(){
			console.log("$scope.sugData" , $scope.sugData);
			$http({
				method: 'POST',
				headers: {'Content-Type': 'application/json'},
				url: sendUrl,
				data:$scope.sugData
			})
			.success(function(res){
				$scope.messageContent='';
				toaster.pop({type: 'success', title: 'Sent',  body:res.messages})
				console.log(res);
			})
		};
	});	