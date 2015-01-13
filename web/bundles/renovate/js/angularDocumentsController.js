Renovate.controller('DocumentsController', function($scope, $http){
	console.log('DocumentsController loaded!');
	
	$scope.documents = [];
	
	$scope.urlsDocumentsGetNg = URLS.documentsGetNg;
	
	setTimeout($(function() {
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
	}), 1000);
	
	function getDocuments()
	{	
		$http({
			method: "POST", 
			url: $scope.urlsDocumentsGetNg
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.documents = response.result;
			}
		})
	}
	
	getDocuments();
	
});