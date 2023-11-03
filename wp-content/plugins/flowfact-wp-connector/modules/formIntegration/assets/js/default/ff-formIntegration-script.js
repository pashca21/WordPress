// noconflict mode
var ff = jQuery.noConflict();
var IsplaceChange = false;


// send popup form
ff(document).on('submit','.ffdefaultForm',function (e) {
    //prevent the form from doing a submit
    e.preventDefault();

	var form					= ff(this).closest(".form");
	var salutation		= form.find("#email-salutation").val();
	var lastName			= form.find("#email-lastName").val();
	var phone					= form.find("#email-phone").val();
	var email					= form.find("#email-mail").val();
	var street				= form.find("#email-street").val();
	var zip						= form.find("#email-zip").val();
	var town					= form.find("#email-town").val();
	var message				= form.find("#email-message").val();
	var legalStore		= form.find("#email-legal-store-text").html();
	var legalPrivacy	= form.find("#email-legal-privacy-text").html();
	var legalPhone		= form.find("#email-legal-phone-text").html();
	
	//send ajax
	ff.ajax({
		type: 'POST',
		url: ffdata.ajaxurl,
		data: {
			action: 'ajaxdefaultformfunctiont',
			salutation:salutation,
			lastName:lastName,
			phone:phone,
			email:email,
			street:street,
			zip:zip,
			town:town,
			message:message,		
			legalStore:legalStore,
			legalPrivacy:legalPrivacy,
			legalPhone:legalPhone
		},
		success: function (data, textStatus, XMLHttpRequest) {
			ff(".FFformIntegration .form").fadeOut('fast');
			ff(".FFformIntegration-default-success").fadeIn('SLOW');
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			alert("Leider konnten wir Ihre E-Mail nicht verarbeiten. Wir bitten um Entschuldigung. Bitte wenden Sie sich telefonisch an unser Haus.");
		}
	});
});
