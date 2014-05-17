var testServer = function(){
	$.ajax({
		url: 'http://localhost:8080/red',
		type: 'GET',
		dataType: 'jsonp'

	})
	.success(function(data){
		console.log(data);
	});
};