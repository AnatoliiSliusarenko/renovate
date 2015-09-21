Renovate.controller('ContactsController', function($scope,$http){
	console.log('ContactsController loaded!');

	$scope.urlsContactUsNg = URLS.contactUsNg;
	$scope.alerts = [];
	$scope.message = {
		topic: "Подяка"
	};

	$scope.sendButtonDisabled = false;


	$scope.urlsJobsShowJob = URLS.jobsShowJob;

	$scope.modelJob = [];

	$scope.leftCounter = 0;
	$scope.centerCounter = 0;
	$scope.rightCounter = 1;

	$scope.jobLength = 0;

	$scope.initJobs = function (jobName, jobDescription, jobSrc, jobNameTranslate) {
		var temp = {};

		temp.jobName = jobName;
		temp.jobDescription = jobDescription;
		temp.jobSrc = jobSrc;
		temp.jobNameTranslate = jobNameTranslate;

		temp.jobHref = $scope.urlsJobsShowJob.replace('0', jobNameTranslate);

		$scope.modelJob.push(temp);
	};

	$scope.clickLeftBlock = function () {
		$scope.leftCounter = $scope.centerCounter;
		$scope.centerCounter = $scope.rightCounter;
		($scope.rightCounter < $scope.jobLength) ? $scope.rightCounter++ : $scope.rightCounter = 0;
	};

	$scope.clickRightBlock = function () {
		$scope.rightCounter = $scope.centerCounter;
		$scope.centerCounter = $scope.leftCounter;
		($scope.leftCounter > 0) ? $scope.leftCounter-- : $scope.leftCounter = $scope.jobLength;
	};

	function showAlert()
	{
		$scope.alerts.push({msg: 'Ваш лист успішно надіслано!', type: 'success'});
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
	};

	$scope.closeAlert = function(index) {
		$scope.alerts.splice(index, 1);
		$scope.sendButtonDisabled = false;
	};

});