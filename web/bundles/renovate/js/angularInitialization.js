var Renovate = angular.module('Renovate',['ui.bootstrap'])
.config(['$interpolateProvider', '$httpProvider', function ($interpolateProvider, $httpProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
    $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
}])
.directive("repeatPassword", function(){
	return {
        require: "ngModel",
        link: function(scope, elem, attrs, ctrl) {
        	if (elem === undefined) return;
            var otherInput = elem.inheritedData("$formController")[attrs.repeatPassword];

            ctrl.$parsers.push(function(value) {
                if(value === otherInput.$viewValue) {
                    ctrl.$setValidity("repeat", true);
                    return value;
                }
                ctrl.$setValidity("repeat", false);
            });

            otherInput.$parsers.push(function(value) {
                ctrl.$setValidity("repeat", value === ctrl.$viewValue);
                return value;
            });
        }
    };
});

console.log('Angular Initialization finished...');