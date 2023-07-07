$(document).ready(function() {

$('form').on('submit', function(){ 
	$(this)
	 .find("[type=submit]")
	 .prop("disabled", true) 
});

});
