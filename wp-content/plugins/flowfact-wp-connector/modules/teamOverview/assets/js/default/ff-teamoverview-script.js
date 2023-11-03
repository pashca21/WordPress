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

