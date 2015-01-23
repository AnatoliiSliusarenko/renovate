Renovate.controller('MainSliderController', function($scope,$http){
	console.log('MainSliderController loaded!');
	
	$scope.urlsResultsGetNg = URLS.resultsGetNg;
	$scope.urlsResultsShowResult = URLS.resultsShowResult;
	$scope.urlsNewsGetNg = URLS.newsGetNg;
	$scope.urlsNewsShowNews = URLS.newsShowNews;
	$scope.urlsArticlesGetNg = URLS.articlesGetNg;
	$scope.urlsArticlesShowArticle = URLS.articlesShowArticle;
	$scope.urlsSharesGetNg = URLS.sharesGetNg;
	$scope.urlsSharesShowShare = URLS.sharesShowShare;
	
	$scope.items = [];
	$scope.dataReady = {
			results: false,
			news: false,
			articles: false,
			shares: false
	}
	
	$scope.$watch('dataReady', function(){
		console.log("dataReady => ", $scope.dataReady);
		if ($scope.dataReady.results 
				&& $scope.dataReady.news 
				&& $scope.dataReady.articles 
				&& $scope.dataReady.shares)
		{
			setTimeout(function(){
				$('.results-for').slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					arrows: false,
					fade:true,
					asNavFor: '.results-nav'
				});
				
				$('.results-nav').slick({
					slidesToShow: 3,
					slidesToScroll: 1,
					asNavFor: '.results-for',
					dots: true,
					centerMode: true,
					focusOnSelect: true,
					variableWidth: true,
					autoplay: true,
					autoPlaySpeed: 3000
				});
			},100);
		}
	}, true);
	
	function getResults()
	{
		$http({
			method: "GET", 
			url: $scope.urlsResultsGetNg,
			params: {onhomepage: 1}
			  })
		.success(function(response){
			console.log("main slider results => ",response);
			if (response.result)
			{
				_.each(response.result, function(result){
					setResultDirectHref(result);
				});
				$scope.items = $scope.items.concat(response.result);
				$scope.dataReady.results = true;
			}
		})
	}
	
	function getNews()
	{
		$http({
			method: "GET", 
			url: $scope.urlsNewsGetNg,
			params: {onhomepage: 1}
			  })
		.success(function(response){
			console.log("main slider news => ",response);
			if (response.result)
			{
				_.each(response.result, function(newsp){
					setNewsDirectHref(newsp);
				});
				$scope.items = $scope.items.concat(response.result);
				$scope.dataReady.news = true;
			}
		})
	}
	
	function getArticles()
	{
		$http({
			method: "GET", 
			url: $scope.urlsArticlesGetNg,
			params: {onhomepage: 1}
			  })
		.success(function(response){
			console.log("main slider articles => ",response);
			if (response.result)
			{
				_.each(response.result, function(article){
					setArticleDirectHref(article);
				});
				$scope.items = $scope.items.concat(response.result);
				$scope.dataReady.articles = true;
			}
		})
	}
	
	function getShares()
	{
		$http({
			method: "GET", 
			url: $scope.urlsSharesGetNg,
			params: {onhomepage: 1}
			  })
		.success(function(response){
			console.log("main slider shares => ",response);
			if (response.result)
			{
				_.each(response.result, function(share){
					setShareDirectHref(share);
				});
				$scope.items = $scope.items.concat(response.result);
				$scope.dataReady.shares = true;
			}
		})
	}
	
	function setResultDirectHref(result){
		var href = $scope.urlsResultsShowResult.replace('0', result.nameTranslit);
		result.href = href;
	}	
	
	function setNewsDirectHref(newsp){
		var href = $scope.urlsNewsShowNews.replace('0', newsp.nameTranslit);
		newsp.href = href;
	}
	
	function setArticleDirectHref(article){
		var href = $scope.urlsArticlesShowArticle.replace('0', article.nameTranslit);
		article.href = href;
	}
	
	function setShareDirectHref(share){
		var href = $scope.urlsSharesShowShare.replace('0', share.nameTranslit);
		share.href = href;
	}
	
	function getItems(){
		getResults();
		getNews();
		getArticles();
		getShares();
	}
	
	getItems();
});