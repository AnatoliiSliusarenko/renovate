Renovate.controller('PageDescriptionsController', function($scope,$http,$modal){
	console.log('PageDescriptionsController loaded!');
	
	$scope.pageDescriptions = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 5;
	
	$scope.urlsPageDescriptionsGetNg = URLS.pageDescriptionsGetNg;
	$scope.urlsPageDescriptionsCountNg = URLS.pageDescriptionsCountNg;
	$scope.urlsPageDescriptionsRemoveNg = URLS.pageDescriptionsRemoveNg;
	
	$scope.$watch('itemsPerPage', function(){
		console.log("itemsPerPage => ", $scope.itemsPerPage);
		getPageDescriptionsCount();
	});
	
	$scope.$watch('currentPage', function(){
		console.log("currentPage => ", $scope.currentPage);
		getPageDescriptions();
	});
	
	function getPageDescriptions()
	{
		var offset = $scope.itemsPerPage*($scope.currentPage - 1);
		var limit = $scope.itemsPerPage;
		$http({
			method: "GET", 
			url: $scope.urlsPageDescriptionsGetNg,
			params: {offset: offset, limit: limit}
			  })
		.success(function(response){
			console.log(" page descriptions => ",response);
			if (response.result)
			{
				$scope.pageDescriptions = response.result;
			}
		})
	}
	
	function getPageDescriptionsCount()
	{
		$http({
			method: "GET", 
			url: $scope.urlsPageDescriptionsCountNg
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getPageDescriptions();
			}
		})
	}
	getPageDescriptionsCount();
	
	$scope.addPageDescription = function(){
		var modalInstance = $modal.open({
		      templateUrl: 'addPageDescription.html',
		      controller: 'AddPageDescriptionController',
		      backdrop: "static"
		});
		
		modalInstance.result.then(function (added) {
		      if (added) getPageDescriptionsCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.editPageDescription = function(pageDescription){
		var modalInstance = $modal.open({
		      templateUrl: 'editPageDescription.html',
		      controller: 'EditPageDescriptionController',
		      backdrop: "static",
		      resolve: {
		    	  pageDescription: function(){return pageDescription;}
		      }
		});
		
		modalInstance.result.then(function (edited) {
		      if (edited) getPageDescriptionsCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.removePageDescription = function(pageDescription){
		var remove = confirm("Дійсно бажаєте видалити: " + pageDescription.url + " ?");
		if (!remove) return;
		
		var url = $scope.urlsPageDescriptionsRemoveNg.replace('0', pageDescription.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getPageDescriptionsCount();
			}
		});
	}
})
.controller('AddPageDescriptionController', function($scope,$http,$modalInstance){
	console.log('AddPageDescriptionController loaded!');
	$scope.urlsPageDescriptionsAddNg = URLS.pageDescriptionsAddNg;
	
	function addPageDescription(){
		$http({
			method: "POST", 
			url: $scope.urlsPageDescriptionsAddNg,
			data: $scope.pageDescription
			  })
		.success(function(response){
			console.log("added page description => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.pageDescriptionForm.$valid) return;
		addPageDescription();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
})
.controller('EditPageDescriptionController', function($scope,$http,$modalInstance,pageDescription){
	console.log('EditPageDescriptionController loaded!');
	$scope.urlsPageDescriptionsEditNg = URLS.pageDescriptionsEditNg;
	$scope.pageDescription = pageDescription;
	
	function editPageDescription(){
		var url = $scope.urlsPageDescriptionsEditNg.replace('0', $scope.pageDescription.id);
		
		$http({
			method: "POST", 
			url: url,
			data: $scope.pageDescription
			  })
		.success(function(response){
			console.log("edited page description => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.pageDescriptionForm.$valid) return;
		editPageDescription();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
});