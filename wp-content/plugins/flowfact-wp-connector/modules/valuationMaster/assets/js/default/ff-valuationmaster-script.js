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

	const nextButton = document.getElementById("nextBtn")
	var currentTab = 0; // Current tab is set to be the first tab (0)
	if(document.getElementsByClassName('ff-valuation-tab')[0]){
		showTab(currentTab); // Display the current tab
	}


	
	function showTab(n) {
	  // This function will display the specified tab of the form ...
	  var x = document.getElementsByClassName("ff-valuation-tab");

		if(!x[n].classList.contains('ff-valuation-category')) {
			var startpage = true
		}

	  x[n].style.display = "block";
	  // ... and fix the Previous/Next buttons:
	  if (n == 0 && startpage == false) {
			if(!x[n].classList.contains('ff-valuation-category')) {
				document.getElementById("prevBtn").style.display = "none";
				document.getElementById("goBackToSelection").style.display = "inline";
			}
	  } else {
			if(!x[n].classList.contains('ff-valuation-category')) {
				document.getElementById("prevBtn").style.display = "inline";
				document.getElementById("goBackToSelection").style.display = "none";
			}
	  }
	  if (n == (x.length - 2)) {
			nextButton.innerHTML = "Weiter";
			nextButton.disabled = false;

			if(document.getElementById('map-property') && typeof map == 'undefined') {
				var map = L.map('map-property').setView([52.5170365, 13.3888599], 13);

				L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
						maxZoom: 19,
						attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors.'
				}).addTo(map)
			
				var marker = null;

				var timeout = null
				
				// Display dropdown suggestions
				ff('input[name=street').keyup(function() {
					var queryAll = '&q=' + ff(this).val()

					clearTimeout(timeout)
					timeout = setTimeout(function() {
						ff.get('https://nominatim.openstreetmap.org/search?format=json&accept-language=de&countrycodes=de&addressdetails=1&country=Deutschland&limit=50'+queryAll, function(data){
							console.log(data)	
							if(data.length > 0) {
								validAddress()
								ff('.auto-address').html('')
								ff('.auto-address').fadeIn(100)
								for (const prop in data) {
									if (prop >= 50) {
										return false
									} else {
										var place = ''
										if (data[prop].address.village) {
											place = data[prop].address.village
										}
										if (data[prop].address.town) {
											place = data[prop].address.town
										}
										if(data[prop].address.city) {
											place = data[prop].address.city
										}

										ff('.auto-address').append(`
										<li data-lat='${data[prop].lat}' data-lon='${data[prop].lon}'>${data[prop].address.road}, ${data[prop].address.postcode} ${place}</li>
										`);
									}
								}
							} else {
								wrongAddress()
							}
						});					
					}, 500)
				})

				// Pupulate input fields based on street suggestions & move market to suggested geoLocation
				ff('ul.auto-address').on('click', 'li', function(){

					newAddress = '&q=' + ff(this).text()
					var lat = ff(this).data('lat')
					var lon = ff(this).data('lon')
					
					ff('.auto-address').fadeOut()
					ff('.auto-address').html('')
					
					ff.get('https://nominatim.openstreetmap.org/search?format=json&accept-language=de&countrycodes=de&addressdetails=1&country=Deutschland&limit=50'+newAddress, function(data){
						console.log(data)	

						var streetName = data[0].address.road
						var postCode = data[0].address.postcode
						var ort
						if(data[0].address.city) {
							var ort = data[0].address.city
						}
						if(data[0].address.town) {
							var ort = data[0].address.town
						}
						if(data[0].address.village) {
							var ort = data[0].address.village
						}
						
						ff('input[name=street]').val(streetName)
						ff('input[name=zip]').val(postCode)
						ff('input[name=town]').val(ort)

						validAddress()
					});
					moveMap(lat, lon)
				});

				// Limit Postcode character number to 5
				ff('input[name=zip]').keypress(function() {
					if (this.value.length > 4) {
						this.value = this.value.slice(0, 4);
					}
				});

				// Adjust the marker on town, zip or house number change
				ff('input[name=town], input[name=zip], input[name=house_number]').keyup(function() {

					var houseNumber = ff('input[name=house_number]').val()
					var street = '&street=' + ff('input[name=street]').val() + ' ' + houseNumber
					var zip = '&spostcode=' + ff('input[name=zip]').val()
					var town = '&city=' + ff('input[name=town]').val()

					if(validateForm()) {
						var address = street + zip + town

						clearTimeout(timeout)
						timeout = setTimeout(function() {
							// get address
	
							ff.get('https://nominatim.openstreetmap.org/search?format=json&accept-language=de&countrycodes=de&addressdetails=1&country=Deutschland'+address, function(data){
									console.log(data)
									if(data.length > 0) {
										validAddress()
										moveMap(data[0].lat, data[0].lon)
									} else {
										console.log('error')
										wrongAddress()
									}
							});
						}, 500)
					} else {
						validateForm()
					}
				})

				/** Move map to the location */
				function moveMap(lat, lon) {
					if (marker !== null) {
						map.removeLayer(marker);
					}

					marker = L.marker([lat, lon]).addTo(map);
					map.flyTo(new L.LatLng(lat, lon), 13);
				}

				function wrongAddress() {
					ff('#map-response').html('Keine gültige deutsche Adresse')
				}
				function validAddress() {
					ff('#map-response').html('')
				}
			}

	  } if (n == (x.length - 1)) {
			if(!x[n].classList.contains('ff-valuation-category')) {
				nextButton.innerHTML = "Vollständigen Report anfordern";

				var captchaEnabled = x[n].dataset.captcha;

				if ( captchaEnabled == 'true' ) {
					nextButton.disabled = true;
				}
			}
	  } 
		
		else {
			document.getElementById("nextBtn").innerHTML = "Weiter";
	  }
	}

	function nextPrev(n) {
	  // This function will figure out which tab to display
	  var x = document.getElementsByClassName("ff-valuation-tab");
	  var u = document.getElementsByClassName("ff-valuation-button");
	  var z = document.getElementsByClassName("ff-valuation-loading");
	  var i = document.getElementsByClassName("ff-requiert-label");
		var trustIcons = document.getElementsByClassName("ff-valuation-button");
	
	  // Exit the function if any field in the current tab is invalid:
	  if (n == 1 && !validateForm()) {
			return false;
	  }	
	  // Hide the current tab:
	  x[currentTab].style.display = "none";

	  // Increase or decrease the current tab by 1:
	  currentTab = currentTab + n;
	  // if you have reached the end of the form... :
	  if (currentTab >= x.length) {
		//...the form gets submitted:
		u[0].style.display = "none";
		i[0].style.display = "none";
		trustIcons[0].display = "none";
		z[0].style.display = "block";
		document.getElementById("regForm").submit();
	  }
	  // Otherwise, display the correct tab:
	  showTab(currentTab);
	}

	// Captcha functions for Contact tab
	function createCaptcha() {
		//clear the contents of captcha div first 
		document.getElementById('captcha').innerHTML = "";
		const charsStr =
		"0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@!#$%^&*";
		var lengthOtp = 6;
		var captcha = [];
		for (var i = 0; i < lengthOtp; i++) {
			//below code will not allow Repetition of Characters
			var index = Math.floor(Math.random() * charsStr.length + 1); //get the next character from the array
			if (captcha.indexOf(charsStr[index]) == -1) {
				captcha.push(charsStr[index]);
			}	else {
				i--;
			}
		}
		var canv = document.createElement("canvas");
		canv.id = "captcha";
		canv.width = 110;
		canv.height = 50;
		var ctx = canv.getContext("2d");
		ctx.font = "25px Georgia";
		ctx.strokeText(captcha.join(""), 0, 30);
		//storing captcha so that can validate you can save it somewhere else according to your specific requirements
		code = captcha.join("");
		document.getElementById("captcha").appendChild(canv); // adds the canvas to the body element
	}

	if(document.getElementById('captcha')){
		createCaptcha();
	}

	function validateCaptcha() {
		event.preventDefault();
		if (document.getElementById("cpatchaTextBox").value == code) {
			nextButton.style.backgroundColor="#4CAF50"
			nextButton.style.color="#fff"
			nextButton.disabled = false;
		} else {
			document.getElementById("cpatchaTextBox").value = ""
			document.getElementById("cpatchaTextBox").placeholder = "Ungültiges Captcha.";
		}
	}

	function validateForm() {
	  // This function deals with validation of the form fields
	  var x, y, i, valid = "valid";
	  x = document.getElementsByClassName("ff-valuation-tab");
	  y = x[currentTab].getElementsByTagName("input");
	  // A loop that checks every input field in the current tab:
	  for (i = 0; i < y.length; i++) {
		// If a field is empty...

			if (  y[i].hasAttribute('required') ) 

			{
				if ((y[i].value != "" && y[i].value <= parseFloat(y[i].max) && y[i].value >= y[i].min) && y[i].type != "checkbox" ) {
				  // add an "valid" class to the field:
				  y[i].className = " valid";
				}

				else if (y[i].value != "" && y[i].max == "" && y[i].min == "" && y[i].type != "checkbox") {
				  // add an "valid" class to the field:
				  y[i].className = " valid";
				}

				else if (y[i].checked == true  && y[i].type == "checkbox") 
				{
				  // add an "invalid" class to the field:
				  y[i].className = " valid";
				}

				else
				{
				  // add an "invalid" class to the field:
				  y[i].className = "invalid";
				  y[i].parentNode.id = "invalid";
				  // and set the current valid status to false:
				  valid = false;
				}		
			}

	  }
	  return valid; // return the valid status
	}

	(function ($) {
	$.fn.countTo = function (options) {
		options = options || {};
		
		return $(this).each(function () {
			// set options for current element
			var settings = $.extend({}, $.fn.countTo.defaults, {
				from:            $(this).data('from'),
				to:              $(this).data('to'),
				speed:           $(this).data('speed'),
				refreshInterval: $(this).data('refresh-interval'),
				decimals:        $(this).data('decimals')
			}, options);
			
			// how many times to update the value, and how much to increment the value on each update
			var loops = Math.ceil(settings.speed / settings.refreshInterval),
				increment = (settings.to - settings.from) / loops;
			
			// references & variables that will change with each update
			var self = this,
				$self = $(this),
				loopCount = 0,
				value = settings.from,
				data = $self.data('countTo') || {};
			
			$self.data('countTo', data);
			
			// if an existing interval can be found, clear it first
			if (data.interval) {
				clearInterval(data.interval);
			}
			data.interval = setInterval(updateTimer, settings.refreshInterval);
			
			// initialize the element with the starting value
			render(value);
			
			function updateTimer() {
				value += increment;
				loopCount++;
				
				render(value);
				
				if (typeof(settings.onUpdate) == 'function') {
					settings.onUpdate.call(self, value);
				}
				
				if (loopCount >= loops) {
					// remove the interval
					$self.removeData('countTo');
					clearInterval(data.interval);
					value = settings.to;
					
					if (typeof(settings.onComplete) == 'function') {
						settings.onComplete.call(self, value);
					}
				}
			}
			
			function render(value) {
				var formattedValue = settings.formatter.call(self, value, settings);
				$self.html(formattedValue);
			}
		});
	};
	
	$.fn.countTo.defaults = {
		from: 0,               // the number the element should start at
		to: 0,                 // the number the element should end at
		speed: 1000,           // how long it should take to count between the target numbers
		refreshInterval: 100,  // how often the element should be updated
		decimals: 0,           // the number of decimal places to show
		formatter: formatter,  // handler for formatting the value before rendering
		onUpdate: null,        // callback method for every time the element is updated
		onComplete: null       // callback method for when the element finishes updating
	};
	
	function formatter(value, settings) {
		return value.toFixed(settings.decimals);
	}
}(ff));

ff(document).mouseup(function(e) {
	var container = ff('.auto-address');

	// if the target of the click isn't the container nor a descendant of the container
	if (!container.is(e.target) && container.has(e.target).length === 0) 
	{
			container.hide();
			container.html('');
	}
});

/** Scroll to agent container */
ff('.scroll-to-callback').on('click', function() {
	var agentContainer = ff('.FFvaluation-default-agent-callback')
	ff([document.documentElement, document.body]).animate({
		scrollTop: agentContainer.offset().top
	}, 2000);
})

ff(function ($) {
  // custom formatting example
  $('.count-number').data('countToOptions', {
	formatter: function (value, options) {
	  return value.toFixed(options.decimals).replace(/\B(?=(?:\d{3})+(?!\d))/g, ',');
	}
  });
  
  // start all the timers
  $('.timer').each(count);  
  
  function count(options) {
	var $this = $(this);
	options = $.extend({}, options || {}, $this.data('countToOptions') || {});
	$this.countTo(options);
  }

	// Input type range
	$('input[type="range"]').on('input', function() {
		var rangeVal = $(this).val();
		var inputName = $(this).attr('id');
		$('#' + inputName).val(rangeVal);
	});

	$('input[type="number"]').on('input', function() {
		var rangeVal = $(this).val();
		var inputName = $(this).attr('id');
		$('input[type="range"][id$="' + inputName + '"]').val(rangeVal);
	});

	/** Results Page Popouts */
	$('[data-popup]').click(function() {
		event.preventDefault();
		var dataPopup = $(this).data('popup')
		if (dataPopup == 'agent-callback') {
			var leadId = $('#leadId').val()
			var btnCallback = $(this)
			var successDiv = $('.agent-callback-success')
			var popupLoading = $('.agent-callback .popup-loading')
			ff.ajax({
				type: 'POST',
				url: ffdata.ajaxurl,
				dataType: 'json',
				data: {
					action: 'ajaxcallback',
					leadId: leadId,
				},
				beforeSend:function(){
					$('.popup.' + dataPopup).css('display', 'flex').fadeIn()
					popupLoading.css('display', 'flex')
				},
				success: function(response) {
					if(response.type == "success") {
					popupLoading.fadeOut(0)
					successDiv.css('display', 'flex')
					btnCallback.text('Rückruf angefragt')
					btnCallback.addClass('btn-requested')
					btnCallback.data('popup', '')
					} else {
						console.log('callback error')
					}
				},
				error: function (response) {
					if(response.type == "error") {
						console.log('callback error')
					}
				}
			});
		}
		if (dataPopup == 'agent-message') {
			$('.popup.' + dataPopup).css('display', 'flex').fadeIn()

			var submitButton = $('input[type="submit"]')
			var btnCallback = $(this)
			submitButton.click(function() {
				var clientName = $('#client_name').val()
				var clientEmail = $('#client_email').val()
				var clientPhone = $('#client_phone').val()
				var clientPath = $('#client_path').val()
				var clientMsg = $('#client_message').val()

				var leadId = $('#leadId').val()

				// Form Data
				$.ajax({
					type: 'POST',
					url: ffdata.ajaxurl,
					dataType: 'json',
					data: {
						action: 'ajaxsubmitagentform',
						name: clientName,
						email: clientEmail,
						phone: clientPhone,
						path:  clientPath,
						msg: clientMsg,
						leadId: leadId,
					},
					beforeSend:function(){
						ff('.agent-message-form').css('display', 'none')
						ff('.agent-message .popup-loading').css('display', 'flex').fadeIn(0)
					},
					success: function(response) {
            if(response.type == "success") {
							ff('.agent-message .popup-loading').fadeOut(0)
							ff('.agent-message-success').css('display', 'flex')
							btnCallback.text('Nachricht gesendet')
							btnCallback.addClass('btn-requested')
							btnCallback.data('popup', '')
            }
            else {
							console.log(response.type + ' error')
            }
         	}
				});
			})
		}
	});

	$('.popup .form-close, .popup .btn-primary.close-popup').click(function() {
		$(this).closest('.popup').fadeOut()
	});

	$('.callback-button.btn-requested').click(function() {
		event.preventDefault();
	});
});
