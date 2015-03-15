Renovate.controller('PaymentsController', function($scope,$http,$modal){
	console.log('PaymentsController loaded!');
	
	$scope.payments = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 10;
	
	(function Initialization(){
		var userid = USERID;
		if (userid){
			$scope.urlsPaymentsGetNg = URLS.paymentsUserGetNg.replace('0', userid);
			$scope.urlsPaymentsCountNg = URLS.paymentsUserGetCountNg.replace('0', userid);
		}else{
			$scope.urlsPaymentsGetNg = URLS.paymentsMyGetNg;
			$scope.urlsPaymentsCountNg = URLS.paymentsMyGetCountNg;
		}
		
		$scope.$watch('itemsPerPage', function(){
			console.log("itemsPerPage => ", $scope.itemsPerPage);
			getPaymentsCount();
		});
		
		$scope.$watch('currentPage', function(){
			console.log("currentPage => ", $scope.currentPage);
			getPayments();
		});
		
		getPaymentsCount();
	})();
	
	function getPayments()
	{
		var offset = $scope.itemsPerPage*($scope.currentPage - 1);
		var limit = $scope.itemsPerPage;
		$http({
			method: "GET", 
			url: $scope.urlsPaymentsGetNg,
			params: {offset: offset, limit: limit}
			  })
		.success(function(response){
			console.log("payments => ",response);
			if (response.result)
			{
				$scope.payments = response.result;
			}
		})
	}
	
	function getPaymentsCount()
	{
		$http({
			method: "GET", 
			url: $scope.urlsPaymentsCountNg
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getPayments();
			}
		})
	}
});