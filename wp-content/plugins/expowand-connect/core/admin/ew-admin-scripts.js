// noconflict mode
var ew = jQuery.noConflict();

jQuery( document ).ready(function() {
	jQuery( ".ew-settings-opener" ).click(function() {
		jQuery(this).closest(".ew-setting-module").children(".ew-setting-close").toggle();
	});
});

jQuery( document ).ready(function() {

	jQuery("#next-step").closest(".ew-setting-module").children(".ew-setting-close").toggle();
	
	if(jQuery("#next-step") && jQuery("#next-step").offset() &&  jQuery("#next-step").offset().top)
	{
		var position = jQuery("#next-step").offset().top;
		console.log(position);
		jQuery([document.documentElement, document.body]).animate({
			scrollTop: position
		}, 2000);
	}

});
