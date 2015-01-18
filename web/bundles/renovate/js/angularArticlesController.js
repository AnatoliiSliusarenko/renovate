Renovate.controller('ArticlesController', function($scope,$http,$modal){
	console.log('ArticlesController loaded!');
	
	$scope.articles = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 5;
	
	$scope.urlsArticlesGetNg = URLS.articlesGetNg;
	$scope.urlsArticlesShowArticle = URLS.articlesShowArticle;
	$scope.urlsArticlesCountNg = URLS.articlesCountNg;
	$scope.urlsArticlesRemoveNg = URLS.articlesRemoveNg;
	
	$scope.$watch('itemsPerPage', function(){
		console.log("itemsPerPage => ", $scope.itemsPerPage);
		getArticlesCount();
	});
	
	$scope.$watch('currentPage', function(){
		console.log("currentPage => ", $scope.currentPage);
		getArticles();
	});
	
	function getArticles()
	{
		var offset = $scope.itemsPerPage*($scope.currentPage - 1);
		var limit = $scope.itemsPerPage;
		$http({
			method: "GET", 
			url: $scope.urlsArticlesGetNg,
			params: {offset: offset, limit: limit}
			  })
		.success(function(response){
			console.log("articles => ",response);
			if (response.result)
			{
				$scope.articles = response.result;
			}
		})
	}
	
	function getArticlesCount()
	{
		$http({
			method: "GET", 
			url: $scope.urlsArticlesCountNg
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getArticles();
			}
		})
	}
	getArticlesCount();
	
	$scope.addArticle = function(){
		var modalInstance = $modal.open({
		      templateUrl: 'addArticle.html',
		      controller: 'AddArticleController',
		      backdrop: "static"
		});
		
		modalInstance.result.then(function (added) {
		      if (added) getArticlesCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.editArticle = function(article){
		var modalInstance = $modal.open({
		      templateUrl: 'editArticle.html',
		      controller: 'EditArticleController',
		      backdrop: "static",
		      resolve: {
		    	  article: function(){return article;}
		      }
		});
		
		modalInstance.result.then(function (edited) {
		      if (edited) getArticlesCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.removeArticle = function(article){
		var remove = confirm("Дійсно бажаєте видалити: " + article.name + " ?");
		if (!remove) return;
		
		var url = $scope.urlsArticlesRemoveNg.replace('0', article.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getArticlesCount();
			}
		});
	}
	
	$scope.setItemDirectHref = function(article){
		var href = $scope.urlsArticlesShowArticle.replace('0', article.id);
		article.href = href;
	}
})
.controller('AddArticleController', function($scope,$http,$modalInstance){
	console.log('AddArticleController loaded!');
	$scope.urlsDocumentsGetNg = URLS.documentsGetNg;
	$scope.urlsArticlesAddNg = URLS.articlesAddNg;
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
	
	function addArticle(){
		$http({
			method: "POST", 
			url: $scope.urlsArticlesAddNg,
			data: $scope.article
			  })
		.success(function(response){
			console.log("added article => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.articleForm.$valid) return;
		addArticle();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
})
.controller('EditArticleController', function($scope,$http,$modalInstance,article){
	console.log('EditArticleController loaded!');
	$scope.urlsDocumentsGetNg = URLS.documentsGetNg;
	$scope.urlsArticlesEditNg = URLS.articlesEditNg;
	$scope.documents = [];
	$scope.article = article;
	
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
	
	function editArticle(){
		var url = $scope.urlsArticlesEditNg.replace('0', $scope.article.id);
		
		$http({
			method: "POST", 
			url: url,
			data: $scope.article
			  })
		.success(function(response){
			console.log("edited article => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.articleForm.$valid) return;
		editArticle();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
});