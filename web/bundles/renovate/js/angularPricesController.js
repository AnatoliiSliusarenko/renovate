Renovate.controller('PricesController', function($scope,$http,$modal){
	console.log('PricesController loaded!');
	
	$scope.prices = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 10;
	
	$scope.urlsPricesGetNg = URLS.pricesGetNg;
	$scope.urlsPricesCountNg = URLS.pricesCountNg;
	$scope.urlsPricesRemoveNg = URLS.pricesRemoveNg;
	
	$scope.$watch('itemsPerPage', function(){
		console.log("itemsPerPage => ", $scope.itemsPerPage);
		getPricesCount();
	});
	
	$scope.$watch('currentPage', function(){
		console.log("currentPage => ", $scope.currentPage);
		getPrices();
	});
	
	function getPrices()
	{
		var offset = $scope.itemsPerPage*($scope.currentPage - 1);
		var limit = $scope.itemsPerPage;
		$http({
			method: "GET", 
			url: $scope.urlsPricesGetNg,
			params: {offset: offset, limit: limit}
			  })
		.success(function(response){
			console.log("prices => ",response);
			if (response.result)
			{
				$scope.prices = response.result;
			}
		})
	}
	
	function getPricesCount()
	{
		$http({
			method: "GET", 
			url: $scope.urlsPricesCountNg
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getPrices();
			}
		})
	}
	getPricesCount();
	
	$scope.addPrice = function(){
		var modalInstance = $modal.open({
		      templateUrl: 'addPrice.html',
		      controller: 'AddPriceController',
		      backdrop: "static"
		});
		
		modalInstance.result.then(function (added) {
		      if (added) getPricesCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.editPrice = function(price){
		var modalInstance = $modal.open({
		      templateUrl: 'editPrice.html',
		      controller: 'EditPriceController',
		      backdrop: "static",
		      resolve: {
		    	  price: function(){return price;}
		      }
		});
		
		modalInstance.result.then(function (edited) {
		      if (edited) getPricesCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.removePrice = function(price){
		var remove = confirm("Дійсно бажаєте видалити: " + price.name + " ?");
		if (!remove) return;
		
		var url = $scope.urlsPricesRemoveNg.replace('0', price.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getPricesCount();
			}
		});
	}
})
.controller('AddPriceController', function($scope,$http,$modalInstance){
	console.log('AddPriceController loaded!');
	$scope.urlsPricesAddNg = URLS.pricesAddNg;
	
	function addPrice(){
		$http({
			method: "POST", 
			url: $scope.urlsPricesAddNg,
			data: $scope.price
			  })
		.success(function(response){
			console.log("added price => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.priceForm.$valid) return;
		addPrice();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
})
.controller('EditPriceController', function($scope,$http,$modalInstance,price){
	console.log('EditPriceController loaded!');
	$scope.urlsPricesEditNg = URLS.pricesEditNg;
	$scope.price = price;
	
	function editPrice(){
		var url = $scope.urlsPricesEditNg.replace('0', $scope.price.id);
		
		$http({
			method: "POST", 
			url: url,
			data: $scope.price
			  })
		.success(function(response){
			console.log("edited price => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.priceForm.$valid) return;
		editPrice();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
});