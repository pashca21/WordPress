
// noconflict mode
var ff = jQuery.noConflict();

// calculate given space
ff( window ).bind("resize", function(){
    resize();
});

ff(document).ready(function(){
    resize();
});


// resize column width
function resize(){
	
	//get element
	var element  = ff("div[data-width]");
		element.each(function( index ) {
			
			//set width
			var size = ff(this).width();
			var minWidth = ff(this).attr("data-width");

			if (minWidth !== null &&  size !== null){
				ff(this).attr("data-column", Math.floor(size / minWidth));
			}
		}); 
};



// yes no 
ff('.ff-input').click(function() {
  var mainParent = ff(this).parent('.ff-input-checkbox');
	  if(ff(mainParent).find('input.ff-input').is(':checked')) {
		ff(mainParent).addClass('active');
	  } else {
		ff(mainParent).removeClass('active');
	  }
})

ff(".ff-input-yesno label" ).click(function() {
		var active = ff(this).attr('data-type');

		ff(this).parent("div").attr("id",active);
		ff(this).closest(".ff-input-yesno").find("input[data-type='"+active+"']").prop("checked", true);	
});





// init chosen
ff(".FFvaluation-default-overview .chosen-select").chosen(); 



// Show contact form
ff(".FFvaluation-default-popup-open" ).click(function() {
	ff(".FFvaluation-default-popup").toggleClass("ff-hidden");
});
ff(".FFvaluation-default-popup-close" ).click(function() {
	ff(".FFvaluation-default-popup").toggleClass("ff-hidden");
});



// send popup form
ff(document).on('submit','.validateDontSubmit',function (e) {
    //prevent the form from doing a submit
    e.preventDefault();

	var form					= ff(this).closest(".form");
	var salutation				= form.find("#email-salutation").val();
	var firstName				= form.find("#email-firstName").val();
	var lastName				= form.find("#email-lastName").val();
	var phone					= form.find("#email-phone").val();
	var email					= form.find("#email-mail").val();
	
	var estateType				= form.find("#property-estatetype").val();
	var street					= form.find("#property-street").val();
	var streetNumber			= form.find("#property-street-number").val();
	var zip						= form.find("#property-zip").val();
	var town					= form.find("#property-town").val();
	var livingarea				= form.find("#property-livingarea").val();
	var rooms					= form.find("#property-rooms").val();
	var yearofconstruction		= form.find("#property-yearofconstruction").val();
	
	var growthPurchasePrice		= form.find("#val-growth-purchase-price").val();
	var growthRentPrice			= form.find("#val-growth-rent-price").val();
	var possiblePurchasePrice	= form.find("#val-possible-purchase-price").val();
	var possibleRentPrice		= form.find("#val-possible-rent-price").val();
	
	var legalPrivacy			= form.find("#email-legal-privacy-text").html();
	var legalPhone				= form.find("#email-legal-phone-text").html();

	//send ajax
	ff.ajax({
		type: 'POST',
		url: ffdata.ajaxurl,
		data: {
			action: 'ajaxvaluationcontactfunctiont',
			salutation:salutation,
			firstName:firstName,
			lastName:lastName,
			phone:phone,
			email:email,
			estateType:estateType,
			street:street,
			streetNumber:streetNumber,
			zip:zip,
			town:town,
			livingarea:livingarea,
			rooms:rooms,
			yearofconstruction:yearofconstruction,
			growthPurchasePrice:growthPurchasePrice,
			growthRentPrice:growthRentPrice,
			possiblePurchasePrice:possiblePurchasePrice,
			possibleRentPrice:possibleRentPrice,
			legalPrivacy:legalPrivacy,
			legalPhone:legalPhone
		},
		success: function (data, textStatus, XMLHttpRequest) {

			ff(".FFvaluation-default-popup .form").fadeOut('fast');
			ff(".FFvaluation-default-popup-success").fadeIn('SLOW');
			setTimeout(function () {
				ff(".FFvaluation-default-popup").toggleClass("ff-hidden");
		    }, 3000);
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			alert("Leider konnten wir Ihre E-Mail nicht verarbeiten. Wir bitten um Entschuldigung. Bitte wenden Sie sich Telefonisch an unser Haus.");
		}
	}); 

});