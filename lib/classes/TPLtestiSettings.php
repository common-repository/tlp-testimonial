<?php
if(!class_exists('TPLtestiSettings')):
/**
*
*/
class TPLtestiSettings
{

	function __construct()
	{
		add_action( 'init', array($this, 'tlp_pluginInit') );
		add_action( 'add_meta_boxes', array($this, 'testimonial_meta' ));
		add_action( 'admin_menu' , array($this, 'tlp_menu_register'));
		add_action(	'wp_ajax_tlptestiSettings', array($this, 'tlptestiSettings'));
		add_action( 'admin_print_scripts-post-new.php', array($this, 'tlp_testimonial_script'), 11 );
		add_action( 'admin_print_scripts-post.php', array($this, 'tlp_testimonial_script'), 11 );
		add_action( 'save_post', array($this, 'save_tlp_data' ),10, 3);
		add_filter( 'manage_edit-testimonial_columns', array($this, 'arrange_testimonial_columns'));
		add_action( 'manage_testimonial_posts_custom_column', array($this,'manage_testimonial_columns'), 10, 2);
		add_filter("manage_edit-testimonial_sortable_columns", array($this,'testimonial_column_sort'));
	}

	function arrange_testimonial_columns($concerts_columns){
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['title'] = _x('Name', 'tlp_testimonial');
		$new_columns['designation'] = _x('Designation', 'tlp_testimonial');
		$new_columns['company'] = _x('Company', 'tlp_testimonial');
		$new_columns['location'] = _x('Location', 'tlp_testimonial');
		$new_columns['date'] = __('date');
		return $new_columns;
	}

	function testimonial_column_sort($columns){
		$custom = array(
			'designation' 	=> 'designation',
			'company' 		=> 'company',
			'location'		=> 'location'
		);
		return wp_parse_args($custom, $columns);
	}

	function manage_testimonial_columns($column_name, $id){
		global $post;
		switch ($column_name) {
			case 'designation':
				echo get_post_meta( $post->ID , 'designation' , true );
				break;
			case 'company':
				echo get_post_meta( $post->ID , 'company' , true );
				break;
			case 'location':
				echo get_post_meta( $post->ID , 'location' , true );
				break;
			default:
				break;
		} // end switch
	}

	function tlp_pluginInit(){
		$this->load_plugin_textdomain();
		global $TLPtestimonial;
		$settings = get_option($TLPtestimonial->options['settings']);
		@$width = ($settings['img']['width'] ? (int) $settings['img']['width'] : 150);
		@$height = ($settings['img']['height'] ? (int) $settings['img']['height'] : 150);

		add_image_size( 'testimonial-thumb', $width, $height );

		$testimonial_labels = array(
				'name'                => _x( 'Testimonial', TPL_TESTIMONIAL_SLUG ),
				'singular_name'       => _x( 'Testimonial', TPL_TESTIMONIAL_SLUG ),
				'menu_name'           => __( 'Testimonial', TPL_TESTIMONIAL_SLUG ),
				'name_admin_bar'      => __( 'Testimonial', TPL_TESTIMONIAL_SLUG ),
				'parent_item_colon'   => __( 'Parent Testimonial:', TPL_TESTIMONIAL_SLUG ),
				'all_items'           => __( 'All Testimonials', TPL_TESTIMONIAL_SLUG ),
				'add_new_item'        => __( 'Add New Testimonial', TPL_TESTIMONIAL_SLUG ),
				'add_new'             => __( 'Add Testimonial', TPL_TESTIMONIAL_SLUG ),
				'new_item'            => __( 'New Testimonial', TPL_TESTIMONIAL_SLUG ),
				'edit_item'           => __( 'Edit Testimonial', TPL_TESTIMONIAL_SLUG ),
				'update_item'         => __( 'Update Testimonial', TPL_TESTIMONIAL_SLUG ),
				'view_item'           => __( 'View Testimonial', TPL_TESTIMONIAL_SLUG ),
				'search_items'        => __( 'Search Testimonial', TPL_TESTIMONIAL_SLUG ),
				'not_found'           => __( 'Not found', TPL_TESTIMONIAL_SLUG ),
				'not_found_in_trash'  => __( 'Not found in Trash', TPL_TESTIMONIAL_SLUG ),
			);
			$testimonial_args = array(
				'label'               => __( 'Testimonial', TPL_TESTIMONIAL_SLUG ),
				'description'         => __( 'Testimonial', TPL_TESTIMONIAL_SLUG ),
				'labels'              => $testimonial_labels,
				'supports'            => array( 'title', 'editor','thumbnail' ),
				'taxonomies'          => array(),
				'hierarchical'        => false,
				'public'              => true,
				'rewrite'			  => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 20,
				'menu_icon'			  => 'dashicons-format-quote',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
			);

			register_post_type( 'testimonial', $testimonial_args );
			flush_rewrite_rules();
	}

	function save_tlp_data($post_id, $post, $update){

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		global $TLPtestimonial;

		if ( !wp_verify_nonce( @$_REQUEST['tlp_nonce'], $TLPtestimonial->nonceText() ) )return;

		// Check permissions
		if ( @$_GET['post_type'] )
		{
			if ( !current_user_can( 'edit_'.$_GET['post_type'], $post_id ) ) return;
		}

		if ( 'testimonial' != $post->post_type ) return;

	    if ( isset( $_REQUEST['designation'] ) ) {
	        update_post_meta( $post_id, 'designation', sanitize_text_field( $_REQUEST['designation'] ) );
	    }

	    if ( isset( $_REQUEST['company'] ) ) {
	        update_post_meta( $post_id, 'company', sanitize_text_field( $_REQUEST['company'] ) );
	    }

	    if ( isset( $_REQUEST['location'] ) ) {
	        update_post_meta( $post_id, 'location', sanitize_text_field( $_REQUEST['location'] ) );
	    }

	}

	function tlptestiSettings(){
		global $TLPtestimonial;

		$error = true;
		if($TLPtestimonial->verifyNonce()){
			unset($_REQUEST['action']);

			update_option( $TLPtestimonial->options['settings'], $_REQUEST);

			$response = array(
					'error'=> $error,
					'msg' => __('Settings successsully updated',TPL_TESTIMONIAL_SLUG)
				);
		}else{
			$response = array(
					'error'=> true,
					'msg' => __('Security Error !!',TPL_TESTIMONIAL_SLUG)
				);
		}
		wp_send_json( $response );
		die();

	}

	function testimonial_meta(){
		add_meta_box(
			'tlp_testimonial_meta',
			'Testimonial data',
			array($this,'tlp_testimonial_meta'),
			'testimonial',
			'normal',
			'high');
	}

	function tlp_testimonial_meta($post){
			global $TLPtestimonial;
			wp_nonce_field( $TLPtestimonial->nonceText(), 'tlp_nonce' );
			$meta = get_post_meta( $post->ID );
		?>
		<div class="testimonial-field-holder">

			<div class="tlp-field-holder">
				<div class="tplp-label">
					<label for="designation"><?php _e('Designations',TPL_TESTIMONIAL_SLUG); ?></label>
				</div>
				<div class="tlp-field">
				<?php $deg = get_post_meta($post->ID, 'designation' , true); ?>
					<input type="text" class="tlpfield" name="designation" value="<?php echo (@$deg ? @$deg : null) ?>" >
					<span class="desc"></span>
				</div>
			</div>


			<div class="tlp-field-holder">
				<div class="tplp-label">
					<label for="company"><?php _e('Company',TPL_TESTIMONIAL_SLUG); ?></label>
				</div>
				<div class="tlp-field">
					<?php $company = get_post_meta($post->ID, 'company' , true); ?>
					<input type="text" name="company" class="tlpfield" value="<?php echo (@$company ? @$company : null) ?>">
					<span class="desc"></span>
				</div>
			</div>
			<div class="tlp-field-holder">
				<div class="tplp-label">
					<label for="location"><?php _e('Location',TPL_TESTIMONIAL_SLUG); ?></label>
				</div>
				<div class="tlp-field">
					<?php $location = get_post_meta($post->ID, 'location' , true); ?>
					<input type="text" name="location" class="tlpfield" value="<?php echo (@$location ? @$location : null) ?>">
					<span class="desc"></span>
				</div>
			</div>
	</div>
<?php
	}

	function tlp_testimonial_script(){
		global $post_type;
		if($post_type == 'testimonial'){
			$this->tlp_style();
			$this->tlp_script();
		}
	}

	function tlp_menu_register() {
		$page_s = add_submenu_page( 'edit.php?post_type=testimonial', __('Testimonial Settings', TPL_TESTIMONIAL_SLUG), __('Settings',TPL_TESTIMONIAL_SLUG), 'administrator', 'tlp_testimonial_settings', array($this, 'tlp_testimonial_settings') );

		add_action('admin_print_styles-' . $page_s, array( $this,'tlp_style'));
		add_action('admin_print_scripts-'. $page_s, array( $this,'tlp_script'));

	}

	function tlp_style(){
		global $TLPtestimonial;
		wp_enqueue_style( 'tpl_testimonial_css_settings', $TLPtestimonial->assetsUrl . 'css/settings.css');
	}

	function tlp_script(){
		global $TLPtestimonial;
		wp_enqueue_script( 'tpl_testimonial_css_settings_js_settings',  $TLPtestimonial->assetsUrl. 'js/settings.js', array('jquery','wp-color-picker'), '', true );
		$nonce = wp_create_nonce( $TLPtestimonial->nonceText() );
		wp_localize_script( 'tpl_testimonial_css_settings_js_settings', 'tpl_var', array('tlp_nonce' => $nonce) );
	}

	function tlp_testimonial_settings(){

		global $TLPtestimonial;
		$TLPtestimonial->render('settings');
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 0.1.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain( TPL_TESTIMONIAL_SLUG, FALSE,  TPL_TESTIMONIAL_LENGUAGE_PATH );

	}

}
endif;
