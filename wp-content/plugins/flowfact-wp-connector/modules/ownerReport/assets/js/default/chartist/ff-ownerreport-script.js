var ff = jQuery.noConflict();
var estate_key_min_length= 0;
var error_html= '<p class="ff-key-invalid">Please enter a key</p>';
var estate_input = ff('.FFownerReport-default-overview-login-objecTracking input');

ff(document).ready(function() {
	ff('.FFownerReport-default-moreprospects-button').click(function(e){
		e.preventDefault();

		ff('.FFownerReport-estate-report-prospect:hidden:lt(3)').show();
		console.log(ff('.FFownerReport-estate-report-prospect:hidden').length)
	if(ff('.FFownerReport-estate-report-prospect:hidden').length==0 ){
		ff('.FF-ownerReport-moreprospects').hide()
	}
	})


	ff(estate_input).change(function(e) {
		ff('.ff-key-invalid').remove();
	})

	ff(".ff-autoclick").click(function(e) {
		input_length = estate_input.val().length;

		e.preventDefault();
		if(input_length < estate_key_min_length || input_length==0){
			if(ff('.ff-key-invalid').length ==0){
					jQuery(error_html).insertAfter(estate_input);
			}	
		
		}else{
			jQuery('.FFownerReport-login-form').submit();
		}
		e.preventDefault();
		
	});
	

});


ff( window ).bind("resize", function(){
	report_resize();
});

ff(document).ready(function(){
	report_resize();
});
function report_resize(){
	
	//get element
	var element  = ff(".FFownerReport-estate-report");
		element.each(function( index ) {
			
			//set width
			var size = ff(this).width();
			var minWidth =900;
					
			if (minWidth !== null &&  size !== null){
				ff(this).attr("data-column", Math.ceil(size / minWidth));
			}
		}); 
};




var activities_chart = jQuery('.FFownerReport-estate-report-activities-chart')

var data = {
  labels:JSON.parse(ff(activities_chart).attr('data-labels')),
  series: JSON.parse(ff(activities_chart).attr('data-values'))
};

var options = {
	 showLabel: false,
  plugins: [
        Chartist.plugins.legend({
        legendNames: data.labels,
        position: 'bottom'
    })
    ]
   
};

var responsiveOptions = [
  ['screen and (min-width: 100px)', {
    chartPadding: 5,
    labelOffset: 0,
   
   
  }],
  ['screen and (min-width: 1024px)', {
    labelOffset: 5,
    chartPadding: 20
  }]
];

new Chartist.Pie('.FFownerReport-estate-report-activities-chart', data, options, responsiveOptions);






var activities_stream = jQuery('.FFownerReport-estate-report-activitiesstream-chart')

var data = {
  labels:JSON.parse(jQuery(activities_stream).attr('data-labels')),
  series: [JSON.parse(jQuery(activities_stream).attr('data-values'))]
};
console.log(data);


var options = {
	
	 
	   axisY: {

	   	onlyInteger: true,
            type: Chartist.FixedScaleAxis,
             divisor: 10,
              labelInterpolationFnc: function skipLabels(value, index) {
      console.log(value);
    }

    },
  axisX: {
    labelInterpolationFnc: function skipLabels(value, index) {
      return index % 3  === 0 ? value : null;
    }
  
},

	 showLabel: false,
 
   
};

var responsiveOptions = [
  ['screen and (min-width: 100px)', {
    chartPadding: 5,
    labelOffset: 30,
    labelDirection: 'explode',
   
  }],
  ['screen and (min-width: 1024px)', {
    labelOffset: 5,
    chartPadding: 20
  }]
];

new Chartist.Line('.FFownerReport-estate-report-activitiesstream-chart', data, options, responsiveOptions);