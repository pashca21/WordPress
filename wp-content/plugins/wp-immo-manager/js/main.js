jQuery(document).ready(function ($) {

	var hardfacts = $('.hardfacts');

	function setIcons(DomElement) {
		var icons = {
			'Kaufpreis': '<i class="flaticon-148-money-icons"></i>',
			'Kaltmiete': '<i class="flaticon-052-savings"></i>',
			'Warmmiete': '<i class="flaticon-052-savings"></i>',
			'Nebenkosten': '<i class="flaticon-134-plumbering-1"></i>',
			'Wohnfläche': '<i class="flaticon-053-interior-design"></i>',
			'Nutzfläche': '<i class="flaticon-088-cleaned"></i>',
			'Grundstücksfläche': '<i class="flaticon-095-measuring"></i>',
			'Anzahl Zimmer': '<i class="flaticon-117-beds"></i>',
			'Anzahl Gewerbeeinheiten': '<i class="flaticon-044-industrial-park"></i>',
			'Lagerfläche': '<i class="flaticon-017-plans"></i>',
			'Bürofläche': '<i class="flaticon-007-property"></i>',
			'Kellerfläche': '<i class="flaticon-093-packaging"></i>'
		};

		$.each(DomElement, function (i, v) {
			var label = $(v.children[1]).html().trim();
			var elem = $(this);

			if (icons.hasOwnProperty(label)) {
				elem.prepend(icons[label]);
			}

		});

	};

	if($('.single-wpi_immobilie') && $(hardfacts).length) {
		setIcons( hardfacts);
	}
	// Filter the h3 text on Content
	if($('#dreizeiler h3').length){
		$('#dreizeiler h3').html('Kurzbeschreibung');
	};
	if ($('#ausstatt_beschr h3').length) {
		$('#ausstatt_beschr h3').html('Ausstattung');
	}
	;
	if ($('#sonstige_angaben h3').length) {
		$('#sonstige_angaben h3').html('Sonstiges');
	}
	;
	if ($('#user_defined_simplefield h3').length) {
		$('#user_defined_simplefield h3').html('Weitere Angaben');
	}
	;

	// Fancybox initialisation
	if (jQuery.fn.fancybox) {
		$("[data-fancybox]").fancybox({
			// Options
		});
	}
});
