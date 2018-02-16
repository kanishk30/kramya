
angular.module('KramyaApp')
    .controller('inboxDataCtlr', function($scope,$location,$moment, toaster , $window , $http , store , myService , jwtHelper, $sce){
    	$scope.apiKey='ANSHULVYAS16';
    	$scope.toTrustedHTML = function( htmlString ){
		  return $sce.trustAsHtml( htmlString );
		}
    	$scope.userdata={};
			if(store.get('jwt')){
				$scope.showSend=false;
				$scope.isLogin=true;
				$scope.userData=jwtHelper.decodeToken(store.get('jwt'));
				console.log('userdata in inbox',$scope.userData);
				$scope.apiKey=$scope.userData.userKey;
				var getSenderMsgurl="http://kramya.com/kramyaapi/index.php/messages?X-API-KEY="+$scope.apiKey+"&id="+$scope.userData.userId+"&role="+$scope.userData.role;
				console.log('url:' , getSenderMsgurl)
				$http({
					method: 'GET',
					url: getSenderMsgurl
				})
				.success(function(response) {
					console.log("senderList  data:" , response);
					$scope.inboxData=response.messages;
					$scope.inboxData.lastMsgFrom=$moment($scope.inboxData.last_message_time).fromNow(); 
					$scope.senderList=$scope.inboxData.list_messages;
					$scope.senderList=$scope.senderList.map(function(value){
						value.fromNow=$moment(value.message_date).fromNow();
						return value;
					});
					var lastValue=$scope.senderList[$scope.senderList.length-1];
					$scope.showMessage(lastValue.user_id  ,lastValue.user_name ,lastValue.user_image , $scope.senderList.length-1);
				})
				.error(function(response) {
			  	console.log('senderList error:' , response);
			  	// alert(response.data);
				});

				$scope.showMessage=function(id,name,img,ind){
					$scope.thisSelect=ind;
					console.log(id);
					$scope.showSend=true;
					$scope.hospitalName=name;
					$scope.hospitalId=id;
					var getMsgurl="http://kramya.com/kramyaapi/index.php/messages/list?X-API-KEY="+$scope.apiKey+"&to="+$scope.userData.userId+"&from="+id;
					$http({
						method: 'GET',
						url: getMsgurl
					})
					.success(function(response) {

						console.log("msg of users:" , response);
						$scope.allMessageData=response;
						$scope.messages=$scope.allMessageData.list;
						$scope.messages=$scope.messages.map(function(value){
							var final=value;
							if(value.type=='to'){
								final['name']=name;
								final['img']=img;
							}
							else{
								final['name']=$scope.userData.userName;
								if(!$scope.userData.userImage){
									$scope.userData.userImage="http://www.drodd.com/images12/facebook-profile-picture22.jpg"
								}
								final['img']=$scope.userData.userImage;
							}
							final['fromNow']=$moment(final.time).fromNow()
							return final;
						}); 
						
					})
					.error(function(response) {
				  	console.log('senderList error:' , response);
				  	// alert(response.data);
					});
				};
				$scope.sendMsg=function(){
					var toAdd={};
					toAdd['name']=$scope.userData.userName;
					if(!$scope.userData.userImage){
						$scope.userData.userImage="http://www.drodd.com/images12/facebook-profile-picture22.jpg"
					}
					toAdd['img']=$scope.userData.userImage;
					toAdd['fromNow']=$moment().fromNow();
					toAdd['message']=$scope.messageContent;
					toAdd['type']='from';
					$scope.messages.push(toAdd);

					var sendUrl="http://kramya.com/kramyaapi/index.php/messages/index";
					inboxJson={
						'from':$scope.userData.userId,
						'to': $scope.hospitalId,
						'message':$scope.messageContent,
						'location':'Not Available',
						'X-API-KEY':$scope.apiKey
					}
					$http({
						method: 'POST',
						headers: {'Content-Type': 'application/json'},
						url: sendUrl,
						data:inboxJson
					})
					.success(function(res){
						$scope.messageContent='';
						toaster.pop({type: 'success', title: 'Sent',  body:res.messages})
						console.log(res);
					})

				}

			}
			
    });
