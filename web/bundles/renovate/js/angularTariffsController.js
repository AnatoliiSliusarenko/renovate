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
		      //if (added) getServicesCount();
		    }, function () {
		      //bad
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
});