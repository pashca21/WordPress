// noconflict mode
var ff = jQuery.noConflict();
var IsplaceChange = false;


// search

// estate overview Form Submit on change
ff(document).ready(function() {
  ff('.ff-autosubmit').on('change', function() {
    var form = ff(this).closest('form');
		ff('.FFestateview-default-overview-search-submit-button').stop().addClass("FFestateview-default-overview-search-submit-ajax");
		ff('.FFestateview-default-overview-search-submit-button').stop().removeClass("FFestateview-default-overview-search-submit-close");
		ff('.FFestateview-default-overview-search-submit-button').stop().removeClass("FFestateview-default-overview-search-submit-button");
		ff('.FFestateview-default-overview-search-submit-ajax').html("<span class='loader'></span>");
		form.submit();
  });
});


// estate details Gallery slider
ff(document).ready(function(){
	ff('.FFestateview-default-details-main').slick({
	  slidesToShow: 1,
	  arrows: true,
	  centerMode: false,
	  fade: true,
	  lazyLoad: 'ondemand',
	  autoplay: false,
	  autoplaySpeed: 3000,
	  dots: false
	});
	
	ff('.FFestateview-default-details-nav').slick({
	  slidesToShow: 10,
	  slidesToScroll: 1,
	  arrows: true,
	  asNavFor: '.FFestateview-default-details-main',
	  dots: false,
	  focusOnSelect: true
	});
	
});





// yes no 
ff('.FFestateview-default-multimedia-controlle div').click(function(e) {
		  
   e.preventDefault();
  
   console.log(ff(this).attr("data-slide"));
   var slideno = ff(this).attr("data-slide");
   ff('.FFestateview-default-details-main').slick('slickGoTo', slideno - 1);
   ff('.FFestateview-default-details-nav').slick('slickGoTo', slideno - 1);
});






// Show contact form
ff(".FFestateview-default-popup-open" ).click(function() {
	ff(".FFestateview-default-popup").toggleClass("ff-hidden");
});
ff(".FFestateview-default-popup-close" ).click(function() {
	ff(".FFestateview-default-popup").toggleClass("ff-hidden");
});




// estate overview paging
ff(".ffgoto" ).click(function() {
  var page = ff(this ).attr("data-page");
  ff("input[name='ffpage']").val(page);
  ff( "#FFestateview-default-overview-search-form" ).submit();
});

// show estate search filter
ff(".FFestateview-default-overview-search-filter-button" ).click(function(e) {
	e.preventDefault();	
	ff(this).toggleClass("active");
	ff(".FFestateview-default-overview-search-secondary").toggleClass("active");
});


function formSubmit() {
	var form	 = ff('#FFestateview-default-overview-search-form');
	var loc		 = ff("#autocomplete");
	var lat      = ff("#lat");
	
	
	// clear lat lng by location clear
	if(typeof(lat.val()) !== 'undefined' && typeof(loc.val()) !== 'undefined' ) {
		if(loc.val().length == 0 && lat.val().length >= 1 )
		{		
			document.getElementById("lat").value = "";
			document.getElementById("lng").value = "";
			
			// update button 
			ff('.FFestateview-default-overview-search-submit-button').html("<span class='loader'></span>");

			// submit form
			form.submit();	
		}
		else if(loc.val().length >= 1 && lat.val().length == 0 )
		{
			// focus location field if not select
			loc.val('Bitte Auswahl treffen');
			loc.css('color','red');
			loc.focus();
		}
		else
		{
			// update button 
			ff('.FFestateview-default-overview-search-submit-button').html("<span class='loader'></span>");

			// submit form
			form.submit();	
		}	
	}
	else
	{
		// update button 
		ff('.FFestateview-default-overview-search-submit-button').html("<span class='loader'></span>");

		// submit form
		form.submit();	
	}	
};



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


// block estate form search
ff(".ff-hide-estate" ).click(function() {
	var id   =  ff(this).attr("data-path");
	var	card =  ff(this).closest( ".FFestateview-default-overview-estate" );

	//deactivate Estate
	ff.ajax({
		type: 'POST',
		url: ffdata.ajaxurl,
		data: {
			action: 'ajaxblockestatefunctiont',
			id: id
		},
		success: function (data, textStatus, XMLHttpRequest) {
			//alert(data);
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			//alert(errorThrown);
		}
	});

	// disable
	ff(card).children('.back').fadeIn(1000);

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
	var street					= form.find("#email-street").val();
	var zip						= form.find("#email-zip").val();
	var town					= form.find("#email-town").val();
	
	var message					= form.find("#email-message").val();
	
	var estateLeadUrl			= form.find("#estateLeadUrl").val();
	var estateHeadline			= form.find("#estateHeadline").val();
	var estateIdentifier		= form.find("#estateIdentifier").val();
	var estateId				= form.find("#estateId").val();
	var ffreply					= form.find("#ffreply").val();
	
	var legalPrivacy			= form.find("#email-legal-privacy-text").html();
	var legalPhone				= form.find("#email-legal-phone-text").html();
	
	ff('.validateDontSubmit').hide();
	ff('.FFestateview-default-popup-preloader').show();
	
	//send ajax
	ff.ajax({
		type: 'POST',
		url: ffdata.ajaxurl,
		data: {
			action: 'ajaxcontactfunctiont',
			salutation:salutation,
			firstName:firstName,
			lastName:lastName,
			phone:phone,
			email:email,
			street:street,
			zip:zip,
			town:town,
			message:message,	
			estateIdentifier:estateIdentifier,	
			estateId:estateId,	
			estateLeadUrl:estateLeadUrl,		
			ffreply:ffreply,		
			estateHeadline:estateHeadline,
			legalPrivacy:legalPrivacy,
			legalPhone:legalPhone
		},
		success: function (data, textStatus, XMLHttpRequest) {

			ff(".FFestateview-default-popup .form").fadeOut('fast');
			ff(".FFestateview-default-popup-success").fadeIn('SLOW');
			setTimeout(function () {
				ff(".FFestateview-default-popup").toggleClass("ff-hidden");
		    }, 3000);
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			alert("Leider konnten wir Ihre E-Mail nicht verarbeiten. Wir bitten um Entschuldigung. Bitte wenden Sie sich telefonisch an unser Haus.");
		}
	}); 
		
});

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



// number
ff('<div class="ff-input-number-nav"><div class="ff-input-number-button ff-input-number-up"><img src="data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDQ1NSA0NTUiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDQ1NSA0NTU7IiB4bWw6c3BhY2U9InByZXNlcnZlIiB3aWR0aD0iNTEyIiBoZWlnaHQ9IjUxMiI+PGc+PHBvbHlnb24gcG9pbnRzPSI0NTUsMjEyLjUgMjQyLjUsMjEyLjUgMjQyLjUsMCAyMTIuNSwwIDIxMi41LDIxMi41IDAsMjEyLjUgMCwyNDIuNSAyMTIuNSwyNDIuNSAyMTIuNSw0NTUgMjQyLjUsNDU1IDI0Mi41LDI0Mi41ICAgNDU1LDI0Mi41ICIgZGF0YS1vcmlnaW5hbD0iIzAwMDAwMCIgY2xhc3M9ImFjdGl2ZS1wYXRoIiBzdHlsZT0iZmlsbDojQUFBQUFBIiBkYXRhLW9sZF9jb2xvcj0iI2FhYWFhYSI+PC9wb2x5Z29uPjwvZz4gPC9zdmc+" /></div><div class="ff-input-number-button ff-input-number-down"><img src="data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDQ1NSA0NTUiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDQ1NSA0NTU7IiB4bWw6c3BhY2U9InByZXNlcnZlIiB3aWR0aD0iNTEyIiBoZWlnaHQ9IjUxMiI+PGc+PHJlY3QgeT0iMjEyLjUiIHdpZHRoPSI0NTUiIGhlaWdodD0iMzAiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIGNsYXNzPSJhY3RpdmUtcGF0aCIgc3R5bGU9ImZpbGw6I0FBQUFBQSIgZGF0YS1vbGRfY29sb3I9IiNhYWFhYWEiPjwvcmVjdD48L2c+IDwvc3ZnPg==" /></div></div>').insertAfter('.ff-input-number input');
ff('.ff-input-number').each(function() {
  var spinner = ff(this),
	input = spinner.find('input[type="number"]'),
	btnUp = spinner.find('.ff-input-number-up'),
	btnDown = spinner.find('.ff-input-number-down'),
	min = input.attr('min'),
	max = input.attr('max');

  btnUp.click(function() {
	var oldValue = parseFloat(input.val());
	if (oldValue >= max) {
	  var newVal = oldValue;
	} else {
	  var newVal = oldValue + 1;
	}
	spinner.find("input").val(newVal);
	spinner.find("input").trigger("change");
  });

  btnDown.click(function() {
	var oldValue = parseFloat(input.val());
	if (oldValue <= min) {
	  var newVal = oldValue;
	} else {
	  var newVal = oldValue - 1;
	}
	spinner.find("input").val(newVal);
	spinner.find("input").trigger("change");
  });
});

// estate overview Form Submit on change
ff(document).ready(function() {
	ff(".ff-autosubmit").on("change paste keyup", function() {
		formSubmit();
	});
	
	ff(".ff-autoclick").click(function(e) {
		e.preventDefault();
		formSubmit();
	});
});

// init chosen
ff(".FFestateview-default-overview .chosen-select").chosen(); 

// FF Estate View single Estate new slider modal script
ff(".estate-pictures-slider-modal-trigger").click(function() {
	ff(".estate-pictures-slider-modal").addClass('active')
	ff(".FFestateview-default-details-agent").css('z-index', '-1' )
	ff('#estate-slider').sliderPro('resize')
	ff('html, body').css({
    overflow: 'hidden',
    height: '100%'
	})
})

ff(".estate-pictures-slider-modal").click(function(e) {
	if (e.target !== this)
    return;
	ff(".estate-pictures-slider-modal").removeClass('active')
	ff(".FFestateview-default-details-agent").css('z-index', '0' )
	ff('html, body').css({
    overflow: 'auto',
    height: 'auto'
	})
})

ff(".slider-modal-close-button").click(function() {
	ff(".estate-pictures-slider-modal").removeClass('active')
	ff(".FFestateview-default-details-agent").css('z-index', '0' )
	ff('html, body').css({
    overflow: 'auto',
    height: 'auto'
	})
})

ff(document).ready(function() {
	var img = document.getElementById("main-image");

	if(img && img.naturalHeight > img.naturalWidth) {
		ff(".slider-wrapper .left").addClass('portrait')
	}
});

// Slider Pro Init
ff( document ).ready(function( $ ) {
	ff( '#estate-slider' ).sliderPro({
		autoplay: "false",
		imageScaleMode: "contain",
	  width: "100%",
		aspectRatio: 1.33,
		slideAnimationDuration: 0,
		orientation: 'horizontal',
		thumbnailPosition: 'right',
		fullScreen: false,
		fadeArrows: false,
		fadeThumbnailArrows: false,
		arrows: true,
		buttons: false,
		thumbnailArrows: true,
		breakpoints: {
			800: {
				thumbnailsPosition: 'bottom',
				thumbnailWidth: 270,
				thumbnailHeight: 100
			},
			500: {
				orientation: 'vertical',
				thumbnailsPosition: 'bottom',
				thumbnailWidth: 120,
				thumbnailHeight: 50
			}
		}
	});
});

/* OSM Scripts */
ff(document).ready(function(){
	if(document.getElementById('osm-single-property')) {

		const singleProperty = document.getElementById('osm-single-property'); 
		console.log(singleProperty)

		jQuery( "#map-switch" ).on("change", function() {
			if (this.checked) {
				jQuery('.ff-consent').hide()
				jQuery('.ff-consent-box').css('background-color', 'transparent')
				jQuery( "#osm-single-property" ).css('display', 'block');

				console.log('switch')

				// OSM marker
				if(singleProperty.hasAttribute('data-lng') && singleProperty.hasAttribute('data-lat')) {
					console.log('marker')
					console.log(singleProperty.dataset.lat)
					console.log(singleProperty.dataset.lng)

					var map = L.map('osm-single-property').setView([singleProperty.dataset.lat, singleProperty.dataset.lng], 13);

					L.tileLayer('https://tile.geofabrik.de/7d2102584fc0e8f4f66d6e0c60a4fd94/{z}/{x}/{y}.png', {
							attribution: 'Daten von <a href="https://www.openstreetmap.org/">OpenStreetMap</a> - Veröffentlicht unter <a href="https://opendatacommons.org/licenses/odbl/">ODbL</a>'
					}).addTo(map);

					L.marker([singleProperty.dataset.lat, singleProperty.dataset.lng]).addTo(map)
				} else if (singleProperty.dataset.zip != '' || singleProperty.dataset.town != '') {
					console.log('highlight')
					const estateCoords = document.getElementById('estate-coords')

					console.log(estateCoords.dataset.lat + ' ' + estateCoords.dataset.lng)

					// create OSM and center it on zip code coords
					var map = L.map('osm-single-property').setView([estateCoords.dataset.lat, estateCoords.dataset.lng], 12);

					//L.marker([estateCoords.dataset.lat, estateCoords.dataset.lng]).addTo(map)

					// set copyright tile layer
					L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
							attribution: 'Daten von <a href="https://www.openstreetmap.org/">OpenStreetMap</a> - Veröffentlicht unter <a href="https://opendatacommons.org/licenses/odbl/">ODbL</a>'
					}).addTo(map);

					// get zipcode shapes ID based on at & lon
					ff.get('https://box-shp.alias.s24cloud.net/shape?latitude='+estateCoords.dataset.lat+'&longitude='+estateCoords.dataset.lng+'&types=region,city,district,borough,town', 
					function(shapeID) {
						console.log(shapeID)
						console.log('shape no: ')
						console.log(shapeID.length - 1)
						var shapeData = shapeID[shapeID.length - 1].id

						ff.get('https://box-shp.alias.s24cloud.net/shape/'+shapeData, function(geoShape) {
							console.log('geo shape')
							console.log(geoShape)

							var districtHighlight = [
								geoShape.geometry
							];

							console.log('district high ')
							console.log(districtHighlight)

							var myStyle1 = {
								"color": "#ff7800",
								"weight": 5,
								"opacity": 0.65
							};

							L.geoJSON(districtHighlight, {
								style: myStyle1
							}).addTo(map);
						})
					})
				} else {
					console.log('nothing')
				}


			} else {
				jQuery('.ff-consent').fadeIn();
				jQuery('.ff-consent-box').css('background-color', '#fafafa');
				jQuery( "#osm-single-property" ).css('display', 'none');
			}
		});
	}
});
