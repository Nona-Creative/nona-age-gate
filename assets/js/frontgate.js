jQuery(document).ready( function ($) {

	var time_to_remember 	= parseInt(nona_block.time_to_remember);
	var age_to_restrict 	= parseInt(nona_block.restrict_age);
	var backstretch_image = nona_block.bg_image;


	function initialize_age_gate() {
		$("body").addClass("overlay-active");
		$("#nona-overlay-wrap").fadeIn(500);
		$("#nona-overlay-wrap").backstretch(backstretch_image);

		$('#nona_verify_d').autotab({
			format: 'alphanumeric',
			target: '#nona_verify_m'
		});
		$('#nona_verify_m').autotab({
			format: 'alphanumeric',
			target: '#nona_verify_y',
			previous: '#nona_verify_d'
		});
		$('#nona_verify_y').autotab({
			format: 'alphanumeric',
			previous: '#nona_verify_m'
		});

		$("#nona_verify").on("click", function(e) {

			e.preventDefault();

			if ( is_old_enough() ) {
			  //  cookie.set( 'nona-age-verified', 'verified', {
				//    expires: time_to_remember,
				//    domain: document.location.hostname,
				//    path: '/',
				//    secure: false
				// });
				$("body").removeClass("overlay-active");
				$("#nona-overlay-wrap").fadeOut(500);

			} else {
				$("#error-too-young").slideDown().delay(3000).slideUp("slow");
			}

		});

	}

	function is_old_enough() {

		var day 	= document.getElementById('nona_verify_d').value;
		var month = document.getElementById('nona_verify_m').value;
		var year 	= document.getElementById('nona_verify_y').value;

		var today = new Date(); // existing JS function
		var birthDate = new Date(year + "/" + month + "/" + day);
		var age = today.getFullYear() - birthDate.getFullYear();
		var m = today.getMonth() - birthDate.getMonth();

		if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
			age--;
		}
		return (age >= age_to_restrict); //returns boolean
	}

		// INIT AGE GATE
	if( ! cookie.get('nona-age-verified') ) {
		initialize_age_gate();
	}

});
