Renovate.controller('TariffsController', function($scope,$http,$modal){
	console.log('TariffsController loaded!');
	$scope.urlsRolesGetClientRolesNg = URLS.rolesGetClientRolesNg;
	$scope.clientRoles = [];
	
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
				if (!isClient())
					$scope.tooltipMessage = "Нажаль Ви не є клієнтом компанії RENOVATE. Будь-ласка зв`яжіться з нашим офісом.";
				else
					$scope.tooltipMessage = "Ми раді вітати Вас!";
			}
		})
	}
	
	function isClient(){
		$scope.isClient = false;
		var clientRolesIds = _.map($scope.clientRoles, function(role){return role.id});
		_.each($scope.user.roles, function(role){
			if (_.contains(clientRolesIds, role.id))
			{
				$scope.isClient = true;
			}
		});
		
		return $scope.isClient;
	}
	
	function initializeUser(){
		if (USER == 'undefined')
		{
			$scope.user = null;
			$scope.tooltipMessage = "Нажаль Ви не є зареєстровані в системі компанії RENOVATE. Будь-ласка зв`яжіться з нашим офісом.";
		}else
		{
			$scope.user = JSON.parse(USER);
			getClientRoles();
		}
		
		console.log("current user => ", $scope.user);
	}
	initializeUser();
	
})
.controller('TariffsPanelController', function($scope,$http,$modal){
	console.log('TariffsPanelController loaded!');
	
	$scope.services = [];
	$scope.tariffs = [];
	$scope.clientRoles = [];
	
	$scope.urlsServicesCalculatorGetNg = URLS.servicesCalculatorGetNg;
	$scope.urlsRolesGetClientRolesNg = URLS.rolesGetClientRolesNg;
	$scope.urlsTariffsPanelGetPublicNg = URLS.tariffsPanelGetPublicNg;
	$scope.urlsTariffsPanelRemovePublicNg = URLS.tariffsPanelRemovePublicNg;
	
	$scope.publicTariffsColapsed = true;
	$scope.activatingTariffsColapsed = true;
	
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
	
	(function getClientRoles()
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
	})();
	
	function getTariffs()
	{
		$http({
			method: "GET", 
			url: $scope.urlsTariffsPanelGetPublicNg
			  })
		.success(function(response){
			console.log("tariffs => ",response);
			if (response.result)
			{
				$scope.tariffs = response.result;
			}
		})
	};
	getTariffs();
	
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
		      if (added) getTariffs();
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
		      if (edited) getTariffs();
		    }, function () {
		      //bad
		});
	}
	
	$scope.removeTariff = function(tariff){
		var remove = confirm("Дійсно бажаєте видалити: " + tariff.name + " ?");
		if (!remove) return;
		
		var url = $scope.urlsTariffsPanelRemovePublicNg.replace('0', tariff.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getTariffs();
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