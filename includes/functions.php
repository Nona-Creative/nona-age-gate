<?php

	/**
	 * Echoes the actual form
	 *
	 * @since 0.1
	 * @echo string
	 */
	function nona_verify_form() {

		echo nona_get_verify_form();
	}

	/**
	 * Returns the all-important verification form.
	 * You can filter this if you like.
	 *
	 * @since 0.1
	 * @return string
	 */
	function nona_get_verify_form() {

		$submit_button_label = apply_filters( 'nona_form_submit_label', get_option( 'nona_enter_button_text', __( 'I am old enough!', 'nona' ) ) );
		$form = '';
		$form .= '<form id="nona_verify_form" action="' . esc_url( home_url( '/' ) ) . '" method="post">';
			// Add a sweet nonce. So sweet.
		$form .= wp_nonce_field( 'verify-age', 'nona-nonce' );
		$form .= '
			<div class="agegate-wrap" id="agegate-wrap">
				<div class="agegate">
					<div id="agegate-form">
						<input type="hidden" id="agegate-result" value="" />
					</div>
				</div>
				<div class="tjgcustom-fields">
				<input type="hidden" name="nona_verify_d_hidden" id="nona_verify_d_hidden" value="" />
				<input type="hidden" name="nona_verify_m_hidden" id="nona_verify_m_hidden" value="" />
				<input type="hidden" name="nona_verify_y_hidden" id="nona_verify_y_hidden" value="" />
				</div>
					<input type="submit" name="nona_verify" id="nona_verify" value="' . esc_attr( $submit_button_label ) . '" />
					<small>You must be over 18 to Enter</small>

			</div>';
		$form .= '</form>';
		return apply_filters( 'nona_verify_form', $form );
	}

	/**
	 * This is the very important function that determines if a given visitor
	 * needs to be verified before viewing the site. You can filter this if you like.
	 *
	 * @since 0.1
	 * @return bool
	 */
	function nona_needs_verification() {

		// Assume the visitor needs to be verified
		$return = true;

		// Check that the form was at least submitted. This lets visitors through that have cookies disabled.
		$nonce = ( isset( $_REQUEST['age-verified'] ) ) ? $_REQUEST['age-verified'] : '';

		if ( wp_verify_nonce( $nonce, 'age-verified' ) )
			$return = false;

		// Or, if there is a valid cookie let 'em through
		if ( isset( $_COOKIE['age-verified'] ) ) {
				$return = false;
		}

		return (bool) $return;
	}

	// Call Ajax
	add_action( 'wp_ajax_nona_needs_verification', 'nona_needs_verification'); // ajax for logged in users
	add_action( 'wp_ajax_nopriv_nona_needs_verification', 'nona_needs_verification' ); // ajax for not logged in users
