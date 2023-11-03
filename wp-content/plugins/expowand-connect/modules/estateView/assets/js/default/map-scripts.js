// noconflict mode
var ff = jQuery.noConflict();

// google places
function init() {
	var input = document.getElementById('autocomplete');
	var autocomplete = new google.maps.places.Autocomplete(input);
	
	google.maps.event.addListener(autocomplete, 'place_changed', function() {
      var place = autocomplete.getPlace();
      var lat = place.geometry.location.lat();
      var lng = place.geometry.location.lng();
      var placeId = place.place_id;
	
	  if(place.address_components !== undefined && place.address_components.length > 0 ) {
		 document.getElementById("lat").value = lat;
		 document.getElementById("lng").value = lng;
		 IsplaceChange = true;	
		  
		 // submit form
		 formSubmit();
	  }
		 
    });

}
google.maps.event.addDomListener(window, 'load', init);
