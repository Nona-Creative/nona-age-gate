<?php

/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */

if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/CMB2/init.php';
}

/**
 * CMB2 Theme Options
 * @version 0.1.0
 */
class Nona_Admin {

	/**
 	 * Option key, and option page slug
 	 * @var string
 	 */
	private $key = 'nona_site_block_options';

	/**
 	 * Options page metabox id
 	 * @var string
 	 */
	private $metabox_id = 'nona_option_metabox';

	/**
	 * Options page prefix
	 * @var string
	 */
	private $prefix = '_nona_';

	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = '';

	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Holds an instance of the object
	 *
	 * @var Nona_Admin
	 **/
	private static $instance = null;

	/**
	 * Constructor
	 * @since 0.1.0
	 */
	private function __construct() {
		// Set our title
		$this->title = __( 'Site Block', 'nona' );
	}

	/**
	 * Returns the running object
	 *
	 * @return Nona_Admin
	 **/
	public static function get_instance() {
		if( is_null( self::$instance ) ) {
			self::$instance = new Nona_Admin();
			self::$instance->hooks();
		}
		return self::$instance;
	}

	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_admin_init', array( $this, 'add_options_page_metabox' ) );
	}


	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_options_page() {
		$this->options_page = add_menu_page(
			$this->title,
			$this->title,
			'manage_options',
			$this->key,
			array( $this, 'admin_page_display' ),
			'dashicons-lock'
		);

		// Include CMB CSS in the head to avoid FOUC
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
		</div>
		<?php
	}

	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	function add_options_page_metabox() {

		// hook in our save notices
		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 );

		$cmb = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );

		// Set our CMB2 fields

		/**
		 * Age field.
		 */
		$cmb->add_field( array(
			'name' => __( 'Age to Restrict', 'nona' ),
			'desc' => __( 'years.', 'nona' ),
			'id'   => $this->prefix . 'age',
			'type' => 'text_small',
			'default' => '18',
			// 'repeatable' => true,
		) );

		$cmb->add_field( array(
			'name' => __( 'Days to remember', 'nona' ),
			'desc' => __( 'days.', 'nona' ),
			'id'   => $this->prefix . 'to_remember',
			'type' => 'text_small',
			'default' => '7',
			// 'repeatable' => true,
		) );

		$cmb->add_field( array(
			'name' => __( 'Overlay Options', 'cmb2' ),
			'desc' => __( 'Display options for the Site Block Overlay.', 'cmb2' ),
			'id'   => $this->prefix . 'section_title_overlay',
			'type' => 'title',
		) );

		$cmb->add_field( array(
			'name'       => __( 'Title', 'nona' ),
			'desc'       => __( 'Title for overlay', 'cmb2' ),
			'id'         => $this->prefix . 'title',
			'type'       => 'text',
			'default' => 'Welcome',
		) );

		$cmb->add_field( array(
			'name'    => __( 'Description', 'cmb2' ),
			'desc'    => __( 'Describe the site and the reason for the site block.', 'cmb2' ),
			'id'      => $this->prefix . 'description',
			'type'    => 'wysiwyg',
			'options' => array( 'textarea_rows' => 5, ),
		) );

		$cmb->add_field( array(
			'name'       => __( 'Instruction', 'nona' ),
			'desc'       => __( 'e.g. You need to be 18 years and older', 'cmb2' ),
			'id'         => $this->prefix . 'instruction',
			'type'       => 'text',
			'default' => 'You need to be 18 years and older',
		) );

		$cmb->add_field( array(
			'name' => __( 'Button text', 'nona' ),
			'desc' => __( 'e.g. Enter', 'nona' ),
			'id'   => $this->prefix . 'button',
			'type' => 'text_medium',
			'default' => 'Enter',
			// 'repeatable' => true,
		) );

		$cmb->add_field( array(
			'name' => __( 'Header Image', 'cmb2' ),
			'desc' => __( 'Upload header image or logo (optional)', 'cmb2' ),
			'id'   => $this->prefix . 'header_image',
			'type' => 'file',
		) );

		$cmb->add_field( array(
			'name' => __( 'Background Image', 'cmb2' ),
			'desc' => __( 'Upload background image (optional)', 'cmb2' ),
			'id'   => $this->prefix . 'bg_image',
			'type' => 'file',
		) );

		$cmb->add_field( array(
			'name'    => __( 'Background color', 'nona' ),
			'desc'    => __( 'for the section or background (optional)', 'nona' ),
			'id'      => $this->prefix . 'background_color',
			'type'    => 'colorpicker',
			'default' => '#bada55',
		) );

		$cmb->add_field( array(
			'name' => __( 'ARA Options', 'cmb2' ),
			'desc' => __( 'Display options for the ARA notice overlay.', 'cmb2' ),
			'id'   => $this->prefix . 'section_title_ara',
			'type' => 'title',
		) );

		$cmb->add_field( array(
			'name'       => __( 'ARA notice text', 'nona' ),
			'desc'       => __( 'e.g. Not for sale to persons under the age of 18. Please Drink Responsibly.', 'cmb2' ),
			'id'         => $this->prefix . 'ara_text',
			'type'       => 'text',
		) );

	}

	/**
	 * Register settings notices for display
	 *
	 * @since  0.1.0
	 * @param  int   $object_id Option key
	 * @param  array $updated   Array of updated fields
	 * @return void
	 */
	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== $this->key || empty( $updated ) ) {
			return;
		}

		add_settings_error( $this->key . '-notices', '', __( 'Settings updated.', 'nona' ), 'updated' );
		settings_errors( $this->key . '-notices' );
	}

	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}

		throw new Exception( 'Invalid property: ' . $field );
	}

}

/**
 * Helper function to get/return the Nona_Admin object
 * @since  0.1.0
 * @return Nona_Admin object
 */
function nona_admin() {
	return Nona_Admin::get_instance();
}

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
function get_nona_option( $key = '' ) {
	return cmb2_get_option( nona_admin()->key, $key );
}

// Get it started
nona_admin();
