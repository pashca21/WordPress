
// noconflict mode
var ew = jQuery.noConflict();

// estate overview Form Submit on change
ew(document).ready(function() {
  ew('.ew-autosubmit').on('change', function() {
    var form = ew(this).closest('form');
		form.submit();
  });
});

// estate overview pading
ew(".ewgoto" ).click(function() {
  var page = ew(this ).attr("data-page");
  ew("input[name='ewpage']").val(page);
  ew( "#EWestateReference-default-overview-search-form" ).submit();
});


// calculate given space
ew( window ).bind("resize", function(){
	resize();
});

ew(document).ready(function(){
	resize();
});


// resize column width
function resize(){
	
	//get element
	var element  = ew("div[data-width]");
		element.each(function( index ) {
			
			//set width
			var size = ew(this).width();
			var minWidth = ew(this).attr("data-width");

			if (minWidth !== null &&  size !== null){
				ew(this).attr("data-column", Math.floor(size / minWidth));
			}
		}); 
};
