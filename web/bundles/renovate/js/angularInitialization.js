var Renovate = angular.module('Renovate',['ui.bootstrap','ngSanitize'])
.config(['$interpolateProvider', '$httpProvider', '$tooltipProvider', function ($interpolateProvider, $httpProvider, $tooltipProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
    $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
}])
.filter('ellipsis', function () {
    return function (text, length) {
        if (text.length > length) {
            return text.substr(0, length) + "...";
        }
        return text;
    }
})
.controller('SearchController', function($scope){
	console.log('SearchController loaded!');
	$scope.search = '';
	$scope.canSearch = false;
	
	$scope.isColapsedArticles = false;
	$scope.isColapsedJobs = false;
	$scope.isColapsedNews = false;
	$scope.isColapsedResults = false;
	$scope.isColapsedShares = false;
	
	String.prototype.isEmpty = function() {
	    return (this.length === 0 || !this.trim());
	};
	
	$scope.checkCanSeacrh = function(){
		$scope.canSearch = !$scope.search.isEmpty();
	}
});

console.log('Angular Initialization finished...');