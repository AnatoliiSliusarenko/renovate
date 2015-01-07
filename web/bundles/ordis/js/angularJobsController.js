Ordis.controller('JobsController', ['$scope','$http', function($scope,$http){
	console.log('JobsController loaded!');
	
	$scope.jobs = [];
	$scope.urlsJobsGetNg = URLS.jobsGetNg;
	
	function getJobs()
	{
		$http({
			method: "GET", 
			url: $scope.urlsJobsGetNg
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