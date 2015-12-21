jQuery(document).ready( function ($) {

	function initialize_age_gate() {
		$(".container").addClass('nona-age-gate');

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

		$("#nona_verify").on("click", function(e) {
		e.preventDefault();

			var old_enough = false;

			if (is_visitor_old_enough()) {
				old_enough = true;
			}

			if (old_enough) {
				if ( cookie.enabled() ) {
				   cookie.set( 'age-verified', 'verified', {
					   expires: 7,
					   secure: false
					});
				}
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
	if(cookie.get('age-verified') !== 'verified') {
		initialize_age_gate();
	}

});




