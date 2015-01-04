Ordis.controller('JobsController', ['$scope','$http', function($scope,$http){
	console.log('JobsController loaded!');
	
	$scope.jobs = [];
	$scope.urlsNgJobsGet = URLS.ngJobsGet;
	
	function getJobs()
	{
		$http({
			method: "POST", 
			url: $scope.urlsNgJobsGet
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.jobs = response.result;
			}
		})
	}
	
	getJobs();
	
}]);