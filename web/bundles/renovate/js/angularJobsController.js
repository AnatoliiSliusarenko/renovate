Renovate.controller('JobsController', function($scope,$http,$modal){
	console.log('JobsController loaded!');
	
	$scope.jobs = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 5;
	
	$scope.urlsJobsGetNg = URLS.jobsGetNg;
	$scope.urlsJobsCountNg = URLS.jobsCountNg;
	
	$scope.$watch('itemsPerPage', function(){
		console.log("itemsPerPage => ", $scope.itemsPerPage);
		getJobsCount();
	});
	
	$scope.$watch('currentPage', function(){
		console.log("currentPage => ", $scope.currentPage);
		getJobs();
	});
	
	function getJobs()
	{
		var offset = $scope.itemsPerPage*($scope.currentPage - 1);
		var limit = $scope.itemsPerPage;
		$http({
			method: "GET", 
			url: $scope.urlsJobsGetNg,
			params: {offset: offset, limit: limit}
			  })
		.success(function(response){
			console.log("jobs => ",response);
			if (response.result)
			{
				$scope.jobs = response.result;
			}
		})
	}
	
	function getJobsCount()
	{
		$http({
			method: "GET", 
			url: $scope.urlsJobsCountNg
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getJobs();
			}
		})
	}
	getJobsCount();
	
	$scope.addJob = function(){
		var modalInstance = $modal.open({
		      templateUrl: 'myModalContent.html',
		      controller: 'AddJobController',
		      backdrop: "static"
		});
		
		modalInstance.result.then(function () {
		      //ok
		    }, function () {
		      //bad
		});
	}
	
	$scope.editJob = function(id){
		console.log('will edit: ', id);
	}
	
	$scope.removeJob = function(id){
		console.log('will remove: ', id);
	}
})
.controller('AddJobController', function($scope,$http,$modalInstance){
	$scope.name="ку ку";
	
	$scope.ok = function () {
	    $modalInstance.close();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
});