$('#example21').chloroform({
	'lang':'en'
});

$('.langchange').click(function(){
	var language = $(this).val();
	
	$('#example21').chloroform('hideBubble');
	$('#example21').chloroform('setOption','lang',language);
	
});


Chloroform.i18n.en.isprime = {
	'1':'that isn\'t a number',
	'2':'that number is too high to calculate primeness',
	'3':'the number {0} is not prime'
}
Chloroform.i18n.fr.isprime = {
	'1':'ca n\'est pas une nombre',
	'2':'la nombre est trop grande pour calculer',
	'3':'la nombre {0} n\'est pas prime'
}
function isprime(elem,not) {
	var n = elem.val();
	if (isNaN(n)){
		return {'valid':false,'message':'1'};
	}
	if (n>=100000){
		return {'valid':false,'message':'2'};
	}
	var m=Math.sqrt(n);
	for (var i=2;i<=m;i++){
		if (n%i==0) {
			return {'valid':false,'message':['3',n]}
		}
	} 
	return {'valid':true};
}
