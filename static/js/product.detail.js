$(document).ready(function(){
	resizeProductAreaImage();
});

function resizeProductAreaImage()
{
	var obj = $('#product_detail_area');
	var maxWidth = obj.width() - 18;
	$.each($(obj).find('img'), function(i, n){
		var opImg = this;
		var nwImg = new Image();
		nwImg.src = opImg.src;
		$(nwImg).bind('load', function(){
			var iWidth = this.width;
			if (iWidth > maxWidth)
			{
				opImg.width = maxWidth;
				$(opImg).wrap('<a href="'+opImg.src+'" target="_blank" title="点击查看大图片"></a>');
			}
		});
	});
}
