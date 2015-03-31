Renovate.controller('EstimationsController', function($scope,$http,$modal){
	console.log('EstimationsController loaded!');
	
	$scope.estimations = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 10;
	
	$scope.filterHandler = null;
	
	$scope.filter = {
			from: null,
			to: null
	}
	
	$scope.urlsEstimationsGetNg = URLS.estimationsGetNg;
	$scope.urlsEstimationsCountNg = URLS.estimationsCountNg;
	
	
	
	$scope.$watch('itemsPerPage', function(){
		console.log("itemsPerPage => ", $scope.itemsPerPage);
		getEstimationsCount();
	});
	
	$scope.$watch('currentPage', function(){
		console.log("currentPage => ", $scope.currentPage);
		getEstimations();
	});
	
	$scope.$watch('filter', function(){
		clearTimeout($scope.filterHandler);
		$scope.filterHandler = setTimeout(function(){
			console.log("filter => ", $scope.filter);
			getEstimationsCount();
		}, 500);
	}, true);
	
	$scope.openFrom = function($event) {
	    $event.preventDefault();
	    $event.stopPropagation();

	    $scope.openedFrom = true;
	};
	
	$scope.openTo = function($event) {
	    $event.preventDefault();
	    $event.stopPropagation();

	    $scope.openedTo = true;
	};
	
	function getEstimations()
	{
		$scope.filter.offset = $scope.itemsPerPage*($scope.currentPage - 1);
		$scope.filter.limit = $scope.itemsPerPage;
		$http({
			method: "GET", 
			url: $scope.urlsEstimationsGetNg,
			params: $scope.filter
			  })
		.success(function(response){
			console.log(" estimations => ",response);
			if (response.result)
			{
				$scope.estimations = response.result;
			}
		})
	}
	
	function getEstimationsCount()
	{
		$http({
			method: "GET", 
			url: $scope.urlsEstimationsCountNg,
			params: $scope.filter
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getEstimations();
			}
		})
	}
	getEstimationsCount();
	
	
	///-----------------Cost Categories-------------------------
	$scope.urlsCostCategoriesGetNg = URLS.costCategoriesGetNg;
	$scope.costCategoriesWorks = [];
	$scope.costCategoriesMaterials = [];
	
	function getCostCategoriesWorks(){
		$http({
			method: "GET", 
			url: $scope.urlsCostCategoriesGetNg,
			params: {type: 'works'}
			  })
		.success(function(response){
			console.log('costCategoriesWorks => ', response.result);
			if (response.result)
			{
				$scope.costCategoriesWorks = response.result;
			}
		})
	};
	getCostCategoriesWorks();
	
	function getCostCategoriesMaterials(){
		$http({
			method: "GET", 
			url: $scope.urlsCostCategoriesGetNg,
			params: {type: 'materials'}
			  })
		.success(function(response){
			console.log('costCategoriesMaterials => ', response.result);
			if (response.result)
			{
				$scope.costCategoriesMaterials = response.result;
			}
		})
	};
	getCostCategoriesMaterials();
	///---------------------------------------------------------
});