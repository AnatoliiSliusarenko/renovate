Renovate.controller('TariffsController', function($scope,$http,$modal){
	console.log('TariffsController loaded!');
	
	$scope.urlsRolesGetClientRolesNg = URLS.rolesGetClientRolesNg;
	$scope.urlsTariffsNg = URLS.tariffsNg;
	$scope.urlsServicesCalculatorGetNg = URLS.servicesCalculatorGetNg;
	$scope.urlsUsersCheckTariffNg = URLS.usersCheckTariffNg;
	
	$scope.clientRoles = [];
	$scope.tariffs = [];
	$scope.services = [];
	$scope.currentClientRole = null;
	$scope.test = false;
		
	function checkUserTariff(){
		$http({
			method: "GET", 
			url: $scope.urlsUsersCheckTariffNg
			  })
		.success(function(response){
			if (response.result)
			{
				$scope.user.waitActivation = true;
			}
		})
	}
	
	function getServices()
	{
		$http({
			method: "GET", 
			url: $scope.urlsServicesCalculatorGetNg
			  })
		.success(function(response){
			console.log("services => ",response);
			if (response.result)
			{
				$scope.services = response.result;
				getTariffs();
			}
		})
	};
	
	function calculateTariffsPrices(){
		_.map($scope.tariffs, function(tariff, i){
			$scope.tariffs[i].price = 0;
			
			var price = _.find(tariff.tariffPrices, function(price){
				return price.roleid == $scope.currentClientRole.id;
			});
			
			if (price != undefined)
				$scope.tariffs[i].price = price.value;
		});
	};
	
	function initTariffServices(tariff){
		tariff.services = {};
		_.map($scope.services, function(sc){
			_.map(sc.services, function(service){
				
				var tariffService = _.find(tariff.tariffServices, function(ts){
					return ts.serviceid == service.id;
				});
				
				var optionid = null;
				if (tariffService != undefined){
					optionid = tariffService.optionid;
					var fixed = true;
					_.map(service.options, function(option){
						if (option.id == optionid) fixed = false;
						option.fixed = fixed;
					});
					
				}else if(service.options.length>0) optionid = service.options[0].id;
								
				tariff.services[service.id] = {
						logical: service.logical, 
						checked: (tariffService != undefined) ? true : false, 
						optionid: optionid, 
						prices: service.prices,
						fixed: (tariffService != undefined) ? true : false
				};
			});
		});
	}
	
	function getTariffs()
	{
		$http({
			method: "GET", 
			url: $scope.urlsTariffsNg,
			params: {parentid: false}
			  })
		.success(function(response){
			console.log("tariffs => ",response);
			if (response.result)
			{
				$scope.tariffs = response.result;
				
				$scope.$watch('currentClientRole', function(){
					calculateTariffsPrices();
					_.map($scope.tariffs, function(tariff){
						tariff.edited = false;
						initTariffServices(tariff);
					});
				});
			}
		})
	};
	
	function getClientRoles()
	{
		$http({
			method: "GET", 
			url: $scope.urlsRolesGetClientRolesNg
			  })
		.success(function(response){
			console.log(" client roles => ",response);
			if (response.result)
			{
				$scope.clientRoles = response.result;
				$scope.currentClientRole = $scope.clientRoles[0];
				initialize();
			}
		})
	};
	getClientRoles();
	
	function initialize(){
		if (USER == 'undefined') $scope.user = null;
		else
		{
			$scope.user = JSON.parse(USER);
			$scope.user.clientRole = null;
			$scope.user.waitActivation = false;
			checkUserTariff();
			_.map($scope.clientRoles, function(role){
				var clientRole = _.find($scope.user.roles, function(userRole){
					return userRole.id == role.id;
				});
				
				if (clientRole != undefined){
					$scope.user.clientRole = clientRole;
					$scope.currentClientRole = clientRole;
				}
			});
		}
		
		getServices();
		console.log("current user => ", $scope.user);
	}
	
	$scope.editTariff = function(tariff){
		var modalInstance = $modal.open({
		      templateUrl: 'editTariff.html',
		      controller: 'EditTariffPrivateController',
		      backdrop: "static",
		      size: 'lg',
		      resolve: {
		    	  currentClientRole: function(){return $scope.currentClientRole;},
		    	  tariff: function(){return tariff;},
		    	  services: function(){return $scope.services;}
		      }
		});
		
		modalInstance.result.then(function (edited) {
		    }, function () {
		      //bad
		});
	}
	
	$scope.activateTariff = function(tariff){
		var modalInstance = $modal.open({
		      templateUrl: 'activateTariff.html',
		      controller: 'ActivateTariffPrivateController',
		      backdrop: "static",
		      resolve: {
		    	  tariff: function(){return tariff;},
		    	  user: function(){return $scope.user;},
		    	  currentClientRole: function(){return $scope.currentClientRole;}
		      }
		});
		
		modalInstance.result.then(function (activated) {
		      if (activated) $scope.user.waitActivation = true;
		    }, function () {
		      //bad
		});
	}
})
.controller('ActivateTariffPrivateController', function($scope,$http,$modalInstance,tariff,user,currentClientRole){
	console.log('ActivateTariffPrivateController loaded!');
	$scope.tariff = tariff;
	$scope.user = user;
	$scope.currentClientRole = currentClientRole;
	$scope.infoMessage = null;   
	
	$scope.urlsTariffsAddPrivateNg = URLS.tariffsAddPrivateNg;
	
	(function initialize(){
		if ($scope.user == null)
			$scope.infoMessage = 1;
		else if ($scope.user.clientRole == null)
			$scope.infoMessage = 2;
		else if ($scope.user.clientRole.id != $scope.currentClientRole.id)
			$scope.infoMessage = 3;
		else if ($scope.user.waitActivation)
			$scope.infoMessage = 4;
	})();
	
	$scope.activate = function () {
		$scope.tariff.clientRole = $scope.currentClientRole;
		$http({
			method: "POST", 
			url: $scope.urlsTariffsAddPrivateNg,
			data: $scope.tariff
			  })
		.success(function(response){
			console.log("activated tariff => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	};
	
	$scope.ok = function () {
		$modalInstance.dismiss('cancel');
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
})
.controller('EditTariffPrivateController', function($scope,$http,$modalInstance,currentClientRole,tariff,services){
	console.log('EditTariffPrivateController loaded!');
	$scope.tariff = tariff;
	$scope.services = services;
	$scope.tariff.edited = false;
	$scope.currentClientRole = currentClientRole;
	
	function initTariffServices(tariff){
		tariff.services = {};
		_.map($scope.services, function(sc){
			_.map(sc.services, function(service){
				
				var tariffService = _.find(tariff.tariffServices, function(ts){
					return ts.serviceid == service.id;
				});
				
				var optionid = null;
				if (tariffService != undefined){
					optionid = tariffService.optionid;
					var fixed = true;
					_.map(service.options, function(option){
						if (option.id == optionid) fixed = false;
						option.fixed = fixed;
					});
					
				}else if(service.options.length>0) optionid = service.options[0].id;
								
				tariff.services[service.id] = {
						logical: service.logical, 
						checked: (tariffService != undefined) ? true : false, 
						optionid: optionid, 
						prices: service.prices,
						fixed: (tariffService != undefined) ? true : false
				};
			});
		});
	}
	
	function checkEdited(){
		$scope.tariff.edited = false;
		_.map($scope.tariff.services, function(service, serviceid){
			if (!service.fixed && service.checked){
				$scope.tariff.edited = true;
			}else
			if (service.fixed && !service.logical){
				var tariffService = _.find($scope.tariff.tariffServices, function(tariffService){return tariffService.serviceid == serviceid});
				if (tariffService != undefined && tariffService.optionid != service.optionid) $scope.tariff.edited = true;
			}			
		});
	}
	
	function calculatePrice(){
			var suma = 0;
			_.map($scope.tariff.services, function(service){
				if (service.checked){
					var p;
					if (service.logical){
						p = _.find(service.prices, function(p){
							 return p.roleid == $scope.currentClientRole.id;
						});
					}else{
						p = _.find(service.prices, function(p){
							 return p.roleid == $scope.currentClientRole.id && p.optionid == service.optionid;
						});
					}
					
					if (p != undefined){
						suma+=p.value;
					}
				}
			});
			
			$scope.tariff.price = suma.toFixed(2); 
			$scope.tariff.payment = ($scope.tariff.price*$scope.tariff.squaring).toFixed(2);
			$scope.tariff.saving = ((($scope.tariff.price*$scope.tariff.squaring)*12)*$scope.tariff.discount).toFixed(2);
	}
	
	(function initialization(){
		initTariffServices($scope.tariff);
		
		$scope.$watch('tariff.services', function(){
			calculatePrice();
			checkEdited();
		}, true);
	})();
	
	$scope.ok = function () {
		$modalInstance.close($scope.tariff);
	};

	$scope.cancel = function () {
		initTariffServices($scope.tariff)
	    $modalInstance.dismiss('cancel');
	};
})
.controller('TariffsPanelController', function($scope,$http,$modal){
	console.log('TariffsPanelController loaded!');
	
	$scope.services = [];
	$scope.tariffs = [];
	$scope.clientRoles = [];
	$scope.clients = [];
	
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 5;
	$scope.filter = {
			parentid: true,
			from: null,
			to:null,
			userid: null,
			active: null
	}
	
	$scope.urlsServicesCalculatorGetNg = URLS.servicesCalculatorGetNg;
	$scope.urlsRolesGetClientRolesNg = URLS.rolesGetClientRolesNg;
	$scope.urlsTariffsPanelGetPublicNg = URLS.tariffsPanelGetPublicNg;
	$scope.urlsTariffsPanelActivatePrivateNg = URLS.tariffsPanelActivatePrivateNg;
	$scope.urlsTariffsNg = URLS.tariffsNg;
	$scope.urlsTariffsCountNg = URLS.tariffsCountNg;
	$scope.urlsTariffsRemoveNg = URLS.tariffsRemoveNg;
	$scope.urlsUsersGetClientsNg = URLS.usersGetClientsNg;
	
	$scope.publicTariffsColapsed = true;
	$scope.activatingTariffsColapsed = true;
	
	$scope.$watch('itemsPerPage', function(){
		console.log("itemsPerPage => ", $scope.itemsPerPage);
		getTariffsPrivateCount();
	});
	
	$scope.$watch('currentPage', function(){
		console.log("currentPage => ", $scope.currentPage);
		getTariffsPrivate();
	});
	
	$scope.$watch('filter', function(){
		console.log("filter => ", $scope.filter);
		getTariffsPrivateCount();
	}, true);
	
	(function getServices()
	{
		$http({
			method: "GET", 
			url: $scope.urlsServicesCalculatorGetNg
			  })
		.success(function(response){
			console.log("services => ",response);
			if (response.result)
			{
				$scope.services = response.result;
			}
		})
	})();
	
	(function getClients(){
		$http({
			method: "GET", 
			url: $scope.urlsUsersGetClientsNg
			  })
		.success(function(response){
			console.log(" clients => ",response);
			if (response.result)
			{
				$scope.clients = response.result;
			}
		})
	})();
	
	(function getClientRoles(){
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
	})();
	
	function getTariffsPublic()
	{
		$http({
			method: "GET", 
			url: $scope.urlsTariffsNg,
			params: {parentid: false}
			  })
		.success(function(response){
			console.log("tariffs public => ",response);
			if (response.result)
			{
				$scope.tariffs = response.result;
			}
		})
	};
	getTariffsPublic();
	
    function getTariffsPrivate(){
    	$scope.filter.offset = $scope.itemsPerPage*($scope.currentPage - 1);
		$scope.filter.limit = $scope.itemsPerPage;
    	$http({
			method: "GET", 
			url: $scope.urlsTariffsNg,
			params: $scope.filter
			  })
		.success(function(response){
			console.log("tariffs private => ",response);
			if (response.result)
			{
				$scope.tariffsPrivate = response.result;
			}
		})
    };
    
    function getTariffsPrivateCount(){
    	$http({
			method: "GET", 
			url: $scope.urlsTariffsCountNg,
			params: $scope.filter
			  })
		.success(function(response){
			console.log("tariffs private count => ",response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getTariffsPrivate();
			}
		})
    }
    getTariffsPrivateCount();
	
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
	
	$scope.addTariff = function(){
		var modalInstance = $modal.open({
		      templateUrl: 'addTariff.html',
		      controller: 'AddTariffPublicController',
		      backdrop: "static",
		      size: 'lg',
		      resolve: {
		    	  services: function(){return $scope.services;},
		    	  clientRoles: function(){return $scope.clientRoles;}
		      }
		});
		
		modalInstance.result.then(function (added) {
		      if (added) getTariffsPublic();
		    }, function () {
		      //bad
		});
	}
	
	$scope.editTariff = function(tariff){
		var modalInstance = $modal.open({
		      templateUrl: 'editTariff.html',
		      controller: 'EditTariffPublicController',
		      backdrop: "static",
		      size: 'lg',
		      resolve: {
		    	  tariff: function(){return tariff;},
		    	  services: function(){return $scope.services;},
		    	  clientRoles: function(){return $scope.clientRoles;}
		      }
		});
		
		modalInstance.result.then(function (edited) {
		      if (edited) getTariffsPublic();
		    }, function () {
		      //bad
		});
	}
	
	$scope.removeTariffPublic = function(tariff){
		var remove = confirm("Дійсно бажаєте видалити: " + tariff.name + " ?");
		if (!remove) return;
		
		var url = $scope.urlsTariffsRemoveNg.replace('0', tariff.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getTariffsPublic();
			}
		});
	}
	
	$scope.removeTariffPrivate = function(tariff){
		var remove = confirm("Дійсно бажаєте видалити: " + tariff.name + " ?");
		if (!remove) return;
		
		var url = $scope.urlsTariffsRemoveNg.replace('0', tariff.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getTariffsPrivateCount();
			}
		});
	}
	
	$scope.activateTariffPrivate = function(tariff){
		var activate = confirm("Дійсно бажаєте активувати: " + tariff.name + " ?");
		if (!activate) return;
		
		var url = $scope.urlsTariffsPanelActivatePrivateNg.replace('0', tariff.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getTariffsPrivateCount();
			}
		});
	}
})
.controller('AddTariffPublicController', function($scope,$http,$modalInstance,services,clientRoles){
	console.log('AddTariffPublicController loaded!');
	
	$scope.urlsTariffsPanelAddPublicNg = URLS.tariffsPanelAddPublicNg;
	
	$scope.services = services;
	$scope.clientRoles = clientRoles;
	$scope.tariff = {
			services: {}
	};
	
	$scope.prices = [];
	
	function calculatePrices(){
		_.map($scope.prices, function(price, i){
			var suma = 0;
			_.map($scope.tariff.services, function(service){
				if (service.checked){
					var p;
					if (service.logical){
						p = _.find(service.prices, function(p){
							 return p.roleid == price.roleid;
						});
					}else{
						p = _.find(service.prices, function(p){
							 return p.roleid == price.roleid && p.optionid == service.optionid;
						});
					}
					
					if (p != undefined){
						suma+=p.value;
					}
				}
			});
			
			$scope.prices[i].price = suma.toFixed(2); 
		});
	}
	
	(function initialization(){
		_.map($scope.services, function(sc){
			_.map(sc.services, function(service){
				$scope.tariff.services[service.id] = {
						logical: service.logical, 
						checked: false, 
						optionid: (service.options.length>0) ? service.options[0].id : null, 
						prices: service.prices
				};
			});
		});
		
		_.map($scope.clientRoles, function(role){
			$scope.prices.push({roleid: role.id, name: role.name, price: 0});
		});
		
		$scope.$watch('tariff.services', function(){
			calculatePrices();
		}, true);
	})();
	
	function addTariff(){
		console.log($scope.tariff);
		
		$http({
			method: "POST", 
			url: $scope.urlsTariffsPanelAddPublicNg,
			data: $scope.tariff
			  })
		.success(function(response){
			console.log("added tariff => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.tariffForm.$valid) return;
		
		addTariff();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
})
.controller('EditTariffPublicController', function($scope,$http,$modalInstance,tariff,services,clientRoles){
	console.log('EditTariffPublicController loaded!');
	
	$scope.urlsTariffsPanelEditPublicNg = URLS.tariffsPanelEditPublicNg;
	
	$scope.services = services;
	$scope.clientRoles = clientRoles;
	$scope.tariff = tariff; 
	$scope.tariff.services = {};
	$scope.prices = [];
	
	function calculatePrices(){
		_.map($scope.prices, function(price, i){
			var suma = 0;
			_.map($scope.tariff.services, function(service){
				if (service.checked){
					var p;
					if (service.logical){
						p = _.find(service.prices, function(p){
							 return p.roleid == price.roleid;
						});
					}else{
						p = _.find(service.prices, function(p){
							 return p.roleid == price.roleid && p.optionid == service.optionid;
						});
					}
					
					if (p != undefined){
						suma+=p.value;
					}
				}
			});
			
			$scope.prices[i].price = suma.toFixed(2); 
		});
	}
	
	(function initialization(){
		_.map($scope.services, function(sc){
			_.map(sc.services, function(service){
				
				var tariffService = _.find($scope.tariff.tariffServices, function(ts){
					return ts.serviceid == service.id;
				});
				
				var optionid = null;
				if (tariffService != undefined){
					optionid = tariffService.optionid;
				}else if(service.options.length>0) optionid = service.options[0].id;
				
				$scope.tariff.services[service.id] = {
						logical: service.logical, 
						checked: (tariffService != undefined) ? true : false, 
						optionid: optionid, 
						prices: service.prices
				};
			});
		});
		
		_.map($scope.clientRoles, function(role){
			$scope.prices.push({roleid: role.id, name: role.name, price: 0});
		});
		
		$scope.$watch('tariff.services', function(){
			calculatePrices();
		}, true);
	})();
	
	function editTariff(){
		console.log($scope.tariff);
		
		var url = $scope.urlsTariffsPanelEditPublicNg.replace('0', $scope.tariff.id);
		
		$http({
			method: "POST", 
			url: url,
			data: $scope.tariff
			  })
		.success(function(response){
			console.log("edited tariff => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.tariffForm.$valid) return;
		
		editTariff();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
});