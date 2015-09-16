Renovate.controller('ProjectsController', function($scope,$http,$modal){
	console.log('ProjectsController loaded!');
	
	$scope.projects = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 10;

	var $calendar = $('#calendar');
	
	$scope.filterHandler = null;
	
	$scope.filter = {
		offset: null,
		limit: null
	}
	
	$scope.urlsProjectsNg = URLS.projectsNg;
	$scope.urlsProjectsCountNg = URLS.projectsCountNg;
	$scope.urlsProjectsRemoveNg = URLS.projectsRemoveNg;
	$scope.urlsEventsNg = URLS.eventsNg;
	$scope.urlsEventsEditNg = URLS.eventsEditNg;
	$scope.urlsEventsRemoveNg = URLS.eventsRemoveNg;
	
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

	//---Events
	function getEvents(from, to) {
		$http({
			method: "GET",
			url: $scope.urlsEventsNg,
			params: {from: from, to: to}
		})
		.success(function(response){
			console.log(" events => ",response);
			if (response.result)
			{
				$calendar.fullCalendar('removeEvents');
				$calendar.fullCalendar('addEventSource', response.result);
			}
		})
	}

	function editEvent(eventFC){
		var event = {
			id: eventFC.id,
			start: eventFC.start.format(),
			end: eventFC.end.format()
		}

		var url = $scope.urlsEventsEditNg.replace('0', event.id);

		$http({
			method: "POST",
			url: url,
			data: event
		})
		.success(function(response){
			console.log("edited event => ", response);
			if (response.result)
			{
				showAlert();
			}
		})
	}

	function showAlert(){
		$("#infoAlert").fadeIn(2000, function(){$("#infoAlert").fadeOut(2000);});
	};

	$calendar.fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		editable: true,
		allDaySlot: false,
		dayClick: function(date, jsEvent, view) {
			if (!confirm("Дійсно бажаєте додати подію ?")) return false;

			var start = date.format("YYYY-MM-DD HH:mm:ss");
			var end = date.add(30, 'm').format("YYYY-MM-DD HH:mm:ss");

			var modalInstance = $modal.open({
				templateUrl: 'addEvent.html',
				controller: 'AddEventController',
				backdrop: "static",
				size: 'lg',
				resolve: {
					start: function(){return start;},
					end: function(){return end;}
				}
			});

			modalInstance.result.then(function (added) {
				if (added) getEvents(view.start.format(),view.end.format());
			}, function () {
				//bad
			});
		},
		eventClick: function(calEvent, jsEvent, view) {
			if (!confirm("Дійсно бажаєте видалити подію: " + calEvent.title + " ?")) return false;

			var url = $scope.urlsEventsRemoveNg.replace('0', calEvent.id);

			$http({
				method: "GET",
				url: url
			})
			.success(function(response){
				console.log(response);
				if (response.result)
				{
					getEvents(view.start.format(),view.end.format());
				}
			});

		},
		eventDrop: function(event){
			editEvent(event);
		},
		eventResize: function(event){
			editEvent(event);
		},
		viewRender: function(view, element){
			getEvents(view.start.format(),view.end.format());
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
})
.controller('AddEventController', function($scope,$http,$modalInstance, start, end){
	console.log('AddEventController loaded!');
	$scope.urlsEventsAddNg = URLS.eventsAddNg;
	$scope.urlsProjectsNg = URLS.projectsNg;
	$scope.urlsUsersGetWorkersNg = URLS.usersGetWorkersNg;

	$scope.projects = [];
	$scope.workers = [];

	(function getProjects(){
		$http({
			method: "GET",
			url: $scope.urlsProjectsNg
		})
		.success(function(response){
			console.log(" projects => ",response);
			if (response.result)
			{
				$scope.projects = response.result;
			}
		})
	})();

	(function getWorkers(){
		$http({
			method: "GET",
			url: $scope.urlsUsersGetWorkersNg
		})
		.success(function(response){
			console.log(" workers => ",response);
			if (response.result)
			{
				$scope.workers = response.result;
			}
		})
	})();

	function addEvent(){
		$scope.event.start = start;
		$scope.event.end = end;

		$http({
			method: "POST",
			url: $scope.urlsEventsAddNg,
			data: $scope.event
		})
		.success(function(response){
			console.log("added event => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}

	$scope.ok = function () {
		if (!$scope.eventForm.$valid) return;
		addEvent();
	};

	$scope.cancel = function () {
		$modalInstance.dismiss('cancel');
	};
});