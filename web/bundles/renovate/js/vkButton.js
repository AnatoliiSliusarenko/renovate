VK.init({apiId: 4763375, onlyWidgets: true});
vkHandler = null;
function initVK(){
	clearTimeout(vkHandler);
	vkHandler = setTimeout(function(){
		var elements = $('.vk').each(function(i, v){
			var options = {
					type: "button"
			}
			var href = $(v).attr('href');
			
			if (href != 'undefined') {
				options.pageUrl = href;
			}
			
			VK.Widgets.Like($(v).attr('id'), options);
		});
	}, 1000);
}
initVK();