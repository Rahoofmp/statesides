var Charts = function() {
	"use strict";
	var runChart1 = function() {
		if( typeof (Chart) === 'undefined'){ return; }
		var labels = [], data = [], backgroundColor = []; 
		var package_sales = jQuery.parseJSON($('#grph_package_sales').val());


		$.each( package_sales, function( key, value ) {
			labels.push(value['name']);
			backgroundColor.push(value['color']);
			data.push(value['total']); 
		});

		if ($('.canvasDoughnut').length){

			var chart_doughnut_settings = {
				type: 'doughnut',
				tooltipFillColor: "rgba(51, 51, 51, 0.55)",
				data: {
					labels: labels,
					datasets: [{
						data: data,
						backgroundColor: backgroundColor,
						hoverBackgroundColor: [
						"#CFD4D8",
						"#B370CF",
						"#E95E4F",
						"#36CAAB",
						"#49A9EA"
						]
					}]
				},
				options: { 
					legend: false, 
					responsive: false 
				}
			}

			$('.canvasDoughnut').each(function(){

				var chart_element = $(this);
				var chart_doughnut = new Chart( chart_element, chart_doughnut_settings);

			});			

		}  

	};
 
	// function to copy the refferal url
	var runCopyRefferralUrl = function() {
		$("#copy-refferal-url").click(function() {
			var $temp = $("<input>");
			$("body").append($temp);
			$temp.val($(this).data("link")).select();
			document.execCommand("copy");
			$temp.remove();
			return false;
		});
	};

	return {
		//main function to initiate template pages
		init : function() {
			runChart1(); 
			runCopyRefferralUrl();
		}
	};
}();
