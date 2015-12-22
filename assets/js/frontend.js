jQuery(document).ready( function ($) {

	function initialize_age_gate() {
		$(".container").addClass('nona-age-gate');
		$("#nona-overlay-wrap").addClass('nona-age-gate-show');


		$('#agegate-form').mobiscroll().date({
			theme: 'default',
			display: 'inline',
			startYear: 1900,
			endYear: 2020,
			dateOrder: "ddMyy",
			defaultValue: new Date("1989/04/02"),
			rows: 5,
			mode: "mixed",
			height: "30"
		});

		jQuery.ajax({
				url : nona.ajax_url,
				type : 'post',
					data : {
						action : 'nona_ajax'
					},
				});

		$("#nona_verify").on("click", function(e) {
		e.preventDefault();
					// This is what we are sending the server
			var old_enough = false;

			if (is_visitor_old_enough()) {
				old_enough = true;
			}

			if (old_enough) {
				if ( cookie.enabled() ) {
				   cookie.set( 'age-verified', 'verified', {
					   expires: 7,
					   path: '/',
					   secure: false
					});
				}
				$("#nona-overlay-wrap").removeClass('nona-age-gate-show');
				$("#nona-overlay-wrap").addClass('nona-age-gate-hide');
			}

			$("#nona_verify_form").submit();

		});

		jQuery("#agegate-wrap").fadeIn();
	}

	function is_visitor_old_enough() {
			var instance = $('#agegate-form').mobiscroll('getInst');
			var values = instance.getValue();

			var day   = values[0]; //day
			var month = parseInt(values[1])+1; //caters for months (starting at Jan as index 0)
			var year  = values[2]; //year

			var today = new Date(); //existing JS function
			var birthDate = new Date(year + "/" + month + "/" + day);
			var age = today.getFullYear() - birthDate.getFullYear();
			var m = today.getMonth() - birthDate.getMonth();

			if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
				age--;
			}

			return (age >= nona.age_to_restrict); //returns boolean
	}

	// INIT AGE GATE
	if(!cookie.get('age-verified')) {
		initialize_age_gate();
	} else {
		// console.log(cookie.get('age-verified'));
		// console.log('['+nona.ajaxurl+']');
		$("#nona-overlay-wrap").addClass('nona-age-gate-hide');
		jQuery.ajax( {
				url: nona.ajaxurl, //key variable that is set in the php enqueue
				type: 'POST',
				data: {
			            'action':'nona_ajax_age', //action needs to match the function in the php file as well as the add_action for it
			            'cookie_verified' : cookie.get('age-verified')
			        },
			    success: function(res){
			    	console.log(res);
			    },
			    error: function(res){
			    	console.log('error');
			    	console.log(res);
			    }
			})

			// .done(function(data, status) {
			// //status is the http request status - 200 for all good, 404 not found e.t.c

			// 	console.log('There is a cookie with value '+cookie.get('age-verified'));
			// 	console.log("success");

			// })
			// .fail(function(data, status) {
			// 	console.log("error");
			// 	console.log(data);
			// })
			// .always(function() {
			// 	console.log("complete");
			// });

	}


});




