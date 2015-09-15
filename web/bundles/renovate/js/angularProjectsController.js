Renovate.controller('ProjectsController', function($scope,$http,$modal){
	console.log('ProjectsController loaded!');
	
	$scope.projects = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 10;
	
	$scope.filterHandler = null;
	
	$scope.filter = {
		offset: null,
		limit: null
	}
	
	$scope.urlsProjectsNg = URLS.projectsNg;
	$scope.urlsProjectsCountNg = URLS.projectsCountNg;
	$scope.urlsProjectsRemoveNg = URLS.projectsRemoveNg;
	
	$scope.$watch('itemsPerPage', function(){
		console.log("itemsPerPage => ", $scope.itemsPerPage);
		getProjectsCount();
	});
	
	$scope.$watch('currentPage', function(){
		console.log("currentPage => ", $scope.currentPage);
		getProjects();
	});
	
	$scope.$watch('filter', function(){
		clearTimeout($scope.filterHandler);
		$scope.filterHandler = setTimeout(function(){
			console.log("filter => ", $scope.filter);
			getProjectsCount();
		}, 500);
	}, true);

	function getProjects() {
		$scope.filter.offset = $scope.itemsPerPage*($scope.currentPage - 1);
		$scope.filter.limit = $scope.itemsPerPage;
		$http({
			method: "GET", 
			url: $scope.urlsProjectsNg,
			params: $scope.filter
			  })
		.success(function(response){
			console.log(" projects => ",response);
			if (response.result)
			{
				$scope.projects = response.result;
			}
		})
	}
	
	function getProjectsCount() {
		$http({
			method: "GET", 
			url: $scope.urlsProjectsCountNg,
			params: $scope.filter
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getProjects();
			}
		})
	}
	getProjectsCount();

	$scope.addProject = function(){
		var modalInstance = $modal.open({
			templateUrl: 'addProject.html',
			controller: 'AddProjectController',
			backdrop: "static",
			size: 'lg'
		});

		modalInstance.result.then(function (added) {
			if (added) getProjectsCount();
		}, function () {
			//bad
		});
	}

	$scope.editProject = function(project){
		var modalInstance = $modal.open({
			templateUrl: 'editProject.html',
			controller: 'EditProjectController',
			backdrop: "static",
			size: 'lg',
			resolve: {
				project: function(){return project;}
			}
		});

		modalInstance.result.then(function (edited) {
			if (edited) getProjectsCount();
		}, function () {
			//bad
		});
	}

	$scope.removeProject = function(project){
		var remove = confirm("Дійсно бажаєте видалити: " + project.name + " ?");
		if (!remove) return;

		var url = $scope.urlsProjectsRemoveNg.replace('0', project.id);

		$http({
			method: "GET",
			url: url
		})
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getProjectsCount();
			}
		});
	}


	$('#calendar').fullCalendar({
		events: [
			{
				title: 'Event1',
				start: '2015-09-15',
				color: 'red'
			},
			{
				title: 'Event2',
				start: '2015-09-16'
			}
		],
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		editable: true,
		dayClick: function(date, jsEvent, view) {

			alert('Clicked on: ' + date.format());

			alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);

			alert('Current view: ' + view.name);

			// change the day's background color just for fun
			//$(this).css('background-color', 'red');

		},
		eventClick: function(calEvent, jsEvent, view) {

			alert('Event: ' + calEvent.title);
			alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
			alert('View: ' + view.name);

			// change the border color just for fun
			//$(this).css('border-color', 'red');

		}
	});

})
.controller('AddProjectController', function($scope,$http,$modalInstance){
	console.log('AddProjectController loaded!');
	$scope.urlsProjectsAddNg = URLS.projectsAddNg;

	function addProject(){
		$http({
			method: "POST",
			url: $scope.urlsProjectsAddNg,
			data: $scope.project
		})
		.success(function(response){
			console.log("added project => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}

	$scope.ok = function () {
		if (!$scope.projectForm.$valid) return;
		addProject();
	};

	$scope.cancel = function () {
		$modalInstance.dismiss('cancel');
	};
})
.controller('EditProjectController', function($scope,$http,$modalInstance,project){
	console.log('EditProjectController loaded!');
	$scope.urlsProjectsEditNg = URLS.projectsEditNg;
	$scope.project = project;

	function editProject(){
		var url = $scope.urlsProjectsEditNg.replace('0', $scope.project.id);

		$http({
			method: "POST",
			url: url,
			data: $scope.project
		})
		.success(function(response){
			console.log("edited project => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}

	$scope.ok = function () {
		if (!$scope.projectForm.$valid) return;
		editProject();
	};

	$scope.cancel = function () {
		$modalInstance.dismiss('cancel');
	};
});