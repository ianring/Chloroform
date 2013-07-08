$('#example20').chloroform({
	'lang':'en'
});

$('.langchange').click(function(){
	var language = $(this).val();
	
	$('#example20').chloroform('hideBubble');
	$('#example20').chloroform('setOption','lang',language);
	
});