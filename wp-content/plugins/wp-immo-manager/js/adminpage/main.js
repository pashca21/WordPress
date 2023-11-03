"use strict";

(jQuery)(function ($) {
	// Validate User
	var d1 = $.Deferred();
	var validOption = Cookies.get("wpi_pro");
	var licence = Cookies.get("wpi_licence");
	var email = Cookies.get("wpi_admin");
	var validated = Cookies.get("wpi_validated");
	var url = 'https://media-store.net/wp-json/wp/v2/wpmi/validateUser_v2';
	var domain = window.location.protocol + '//' + window.location.host + window.location.pathname;
	domain = domain.replace('/wp-admin/admin.php', '');
	var wpurl = ajaxurl + '?action=wpi_valid_status';
	//console.log(domain);

	if (validated !== 'true') {
		$.ajax({
			"type": "GET",
			"url": url,
			"data": {"licence": licence, "email": email, "domain": domain},
			"cache": false,
			complete: function (xhr) {
				//console.log(xhr);
				d1.resolve(xhr, validOption);

				return d1.promise;
			},
			success: function (data, status, xhr) {
				console.log(data);
			},
			error: function (xhr, status, errorThrown) {
				//console.dir(errorThrown);
				//console.log(status);
			}

		});
		d1.promise().then(function (jqXHR, validOption) {
			//console.log('Promise geladen...');
			//console.log(jqXHR.responseJSON);
			var response = jqXHR.responseJSON;
			var valid = 'false';

			$.ajax({
				"action": "wpi_valid_status",
				"type": "POST",
				"url": wpurl,
				"data": response,
				"cache": false,
				complete: function (xhr) {
					//console.log(xhr.responseText);
					if (xhr.responseText == 'reload0') {
						window.location.reload();
					}
				},
			});

			/*if (response.valid === true && validOption !== 'true') {
			 console.log(response);
			 Cookies.set('wpi_pro', 'true', {path: ''});
			 Cookies.set('wpi_validated', 'true', {path: ''});
			 */
			/*setTimeout(function () {*/
			/*window.location.reload(true);*/
			/*}, 2000);*/
			/*}
			 else if (true !== response.valid && validOption == 'true') {
			 console.log(response.text);
			 //Cookies.set('wpi_pro', 'false', {path: ''});
			 Cookies.remove('wpi_licence');
			 Cookies.remove('wpi_validated');
			 }

			*/
		});
	}


	/**
	 * Single Page Settings
	 */
		// Activen Radio Button ermitteln
	var activeRadio = '.' + $('.single-radio input:checked').parent().parent().attr('id');
	// Settings einblenden
	$(activeRadio).removeClass('hidden');
	// Klick-Action überwachen
	$('.single-radio input').on('click', function (cb) {
		var newID = cb.target;
		var newRadio = '.' + $(newID).parent().parent().attr('id');
		console.log(newRadio);
		$('.radio-div').addClass('hidden');
		$(newRadio).removeClass('hidden').css('opacity', '0').animate({opacity: 1}, 600);
	});
	// Input Feld als Selector hinzufügen
	//$('#preise tr:first-child').after('<input type="checkbox" class="selector" />  <label>Alles Auswählen</label>');

	// Bei Klick auf Selector alle Inputs auswählen
	$('input.selector').click(function () {
		var se_self = this;
		return (this.tog = !this.tog) ?
			function () {
				$(se_self).attr('checked', 'checked')
					.parents('fieldset')
					.find("input:checkbox")
					.attr('checked', 'checked');
			}() :
			function () {
				$(se_self).removeAttr('checked')
					.parents('fieldset')
					.find("input:checkbox")
					.removeAttr('checked');
			}();
	});
});
