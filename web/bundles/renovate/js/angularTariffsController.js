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
			$scope.tooltipMessage = "Нажалт Ви не є зареєстровані в системі компанії RENOVATE. Будь-ласка зв`яжіться з нашим офісом.";
		}else
		{
			$scope.user = JSON.parse(USER);
			getClientRoles();
		}
		
		console.log("current user => ", $scope.user);
	}
	initializeUser();
	
});