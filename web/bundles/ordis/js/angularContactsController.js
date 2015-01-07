Ordis.controller('ContactsController', ['$scope','$http', function($scope,$http){
	console.log('ContactsController loaded!');
	
	$scope.urlsContactUsNg = URLS.contactUsNg;
	
	$scope.message = {
			topic: "Подяка"
	}
	
	messageSentBox = $("#messageSent");
	
	$scope.sendButtonDisabled = false;
	
	function showAlert()
	{
		messageSentBox.show('slow', function(){
			setTimeout(function(){
					messageSentBox.hide('slow');
					$scope.sendButtonDisabled = false;
					$scope.$apply();
				}, 2000);
		});
	}
	
	$scope.contactUs = function()
	{
		$scope.sendButtonDisabled = true;
		$http({
			method: "POST", 
			url: $scope.urlsContactUsNg,
			data: $scope.message
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				showAlert();
			}
		})
	}
	
}]);