
// noconflict mode
var ff = jQuery.noConflict();

// estate overview Form Submit on change
ff(document).ready(function() {
  ff('.ff-autosubmit').on('change', function() {
    var form = ff(this).closest('form');
		form.submit();
  });
});

// estate overview paging
ff(".ffgoto" ).click(function() {
  var page = ff(this ).attr("data-page");
  ff("input[name='ffpage']").val(page);
  ff( "#FFrecommendation-default-overview-search-form" ).submit();
});



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