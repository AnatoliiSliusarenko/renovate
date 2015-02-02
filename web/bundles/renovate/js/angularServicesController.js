Renovate.controller('ServicesController', function($scope,$http,$modal){
	console.log('ServicesController loaded!');
	
	$scope.services = [];
	$scope.clientRoles = [];
	$scope.serviceCategories = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 5;
	
	$scope.filter = {
			role: null,
			category: null,
			type: null
	}
	
	$scope.urlsServicesGetNg = URLS.servicesGetNg;
	$scope.urlsServicesCountNg = URLS.servicesCountNg;
	$scope.urlsRolesGetClientRolesNg = URLS.rolesGetClientRolesNg;
	$scope.urlsServiceCategoriesGetAllNg = URLS.serviceCategoriesGetAllNg;
	
	$scope.$watch('itemsPerPage', function(){
		console.log("itemsPerPage => ", $scope.itemsPerPage);
		getServicesCount();
	});
	
	$scope.$watch('currentPage', function(){
		console.log("currentPage => ", $scope.currentPage);
		getServices();
	});
	
	$scope.$watch('filter', function(){
		console.log("filter => ", $scope.filter);
		getServicesCount();
	}, true);

	
	function getServiceCategories()
	{
		$http({
			method: "GET", 
			url: $scope.urlsServiceCategoriesGetAllNg
			  })
		.success(function(response){
			console.log(" serviceCategories => ",response);
			if (response.result)
			{
				$scope.serviceCategories = response.result;
			}
		})
	}
	getServiceCategories();
	
	function getClientRoles()
	{
		$http({
			method: "GET", 
			url: $scope.urlsRolesGetClientRolesNg
			  })
		.success(function(response){
			console.log(" clientRoles => ",response);
			if (response.result)
			{
				$scope.clientRoles = response.result;
			}
		})
	}
	getClientRoles();
	
	function getServices()
	{
		$scope.filter.offset = $scope.itemsPerPage*($scope.currentPage - 1);
		$scope.filter.limit = $scope.itemsPerPage;
		$http({
			method: "GET", 
			url: $scope.urlsServicesGetNg,
			params: $scope.filter
			  })
		.success(function(response){
			console.log("services => ",response);
			if (response.result)
			{
				$scope.services = response.result;
			}
		})
	}
	
	function getServicesCount()
	{
		$http({
			method: "GET", 
			url: $scope.urlsServicesCountNg
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getServices();
			}
		})
	}
	getServicesCount();
	
	$scope.addService = function(){
		var modalInstance = $modal.open({
		      templateUrl: 'addService.html',
		      controller: 'AddServiceController',
		      backdrop: "static"
		});
		
		modalInstance.result.then(function (added) {
		      if (added) getServicesCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.editJob = function(job){
		var modalInstance = $modal.open({
		      templateUrl: 'editJob.html',
		      controller: 'EditJobController',
		      backdrop: "static",
		      resolve: {
		    	  job: function(){return job;}
		      }
		});
		
		modalInstance.result.then(function (edited) {
		      if (edited) getJobsCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.removeJob = function(job){
		var remove = confirm("Дійсно бажаєте видалити: " + job.name + " ?");
		if (!remove) return;
		
		var url = $scope.urlsJobsRemoveNg.replace('0', job.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getJobsCount();
			}
		});
	}
})
.controller('AddServiceController', function($scope,$http,$modalInstance){
	console.log('AddServiceController loaded!');
	$scope.urlsDocumentsGetNg = URLS.documentsGetNg;
	$scope.urlsJobsAddNg = URLS.jobsAddNg;
	$scope.documents = [];
	
	function getDocuments(){
		$http({
			method: "GET", 
			url: $scope.urlsDocumentsGetNg
			  })
		.success(function(response){
			console.log("documents => ",response);
			if (response.result)
			{
				$scope.documents = response.result;
				$scope.documents.splice(0,0,{
					id: null,
					name: "--> не обрано <--"
				});
			}
		})
	}
	getDocuments();
	
	setTimeout(function() {
	    $('#file_upload').uploadify({
	    	'fileSizeLimit': 0,
	    	'progressData' : 'speed',
	    	'formData'     : {
				'timestamp' : TIMESTAMP,
				'token'     : TOKEN
			},
	    	'buttonText' : 'Завантажити...',
	        'swf'      : URLS.uploadifySWF,
	        'uploader' : URLS.documentsUpload,
	        'onUploadSuccess' : function(file, data, response) {
	            console.log('The file ' + file.name + ' was successfully uploaded with a response: ' + response + ' : ' + data);
	            getDocuments();
	        }
	    });
	}, 1000);
	
	function addJob(){
		$http({
			method: "POST", 
			url: $scope.urlsJobsAddNg,
			data: $scope.job
			  })
		.success(function(response){
			console.log("added job => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.jobForm.$valid) return;
		addJob();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
})
.controller('EditJobController', function($scope,$http,$modalInstance,job){
	console.log('EditJobController loaded!');
	$scope.urlsDocumentsGetNg = URLS.documentsGetNg;
	$scope.urlsJobsEditNg = URLS.jobsEditNg;
	$scope.documents = [];
	$scope.job = job;
	
	function getDocuments(){
		$http({
			method: "GET", 
			url: $scope.urlsDocumentsGetNg
			  })
		.success(function(response){
			console.log("documents => ",response);
			if (response.result)
			{
				$scope.documents = response.result;
				$scope.documents.splice(0,0,{
					id: null,
					name: "--> не обрано <--"
				});
			}
		})
	}
	getDocuments();
	
	setTimeout(function() {
	    $('#file_upload').uploadify({
	    	'fileSizeLimit': 0,
	    	'progressData' : 'speed',
	    	'formData'     : {
				'timestamp' : TIMESTAMP,
				'token'     : TOKEN
			},
	    	'buttonText' : 'Завантажити...',
	        'swf'      : URLS.uploadifySWF,
	        'uploader' : URLS.documentsUpload,
	        'onUploadSuccess' : function(file, data, response) {
	            console.log('The file ' + file.name + ' was successfully uploaded with a response: ' + response + ' : ' + data);
	            getDocuments();
	        }
	    });
	}, 1000);
	
	function editJob(){
		var url = $scope.urlsJobsEditNg.replace('0', $scope.job.id);
		
		$http({
			method: "POST", 
			url: url,
			data: $scope.job
			  })
		.success(function(response){
			console.log("edited job => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.jobForm.$valid) return;
		editJob();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
})
.controller('BlockJobsController', function($scope,$http){
	console.log('BlockJobsController loaded!');
	
	$scope.urlsJobsGetNg = URLS.jobsGetNg;
	$scope.urlsJobsShowJob = URLS.jobsShowJob;
	$scope.jobs = [];
	
	function getJobs()
	{
		$http({
			method: "GET", 
			url: $scope.urlsJobsGetNg,
			params: {onhomepage: 1}
			  })
		.success(function(response){
			console.log("block jobs => ",response);
			if (response.result)
			{
				$scope.jobs = response.result;
				setTimeout(function(){
					$('.jobs-slider').slick({
						slidesToShow: 2,
						slidesToScroll: 1,
						centerMode: true,
						dots: false,
						focusOnSelect: true,
						variableWidth: true,
						autoplay: true,
						autoPlaySpeed: 2000
						});
				},100);
			}
		})
	}
	getJobs();
	
	$scope.setItemDirectHref = function(job){
		var href = $scope.urlsJobsShowJob.replace('0', job.nameTranslit);
		job.href = href;
	}	
});