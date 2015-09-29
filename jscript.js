$(document).ready( function() {
	$('#drawers').find('h5').click(function(){
	$(this).next().slideToggle();
	$("#drawers div").not($(this).next()).slideUp();
	});

	$('#drawers').find('h5').first().next().slideToggle();

	$('.weiter').click(function(){
		$('h5').next().slideToggle();
	});
});