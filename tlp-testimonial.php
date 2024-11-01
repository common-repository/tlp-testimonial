<?php

/**
 * Plugin Name: Testimonial
 * Plugin URI: http://demo.techlabpro.com/wp/tlptestimonial/
 * Description: TLP Testimonial plugin is a fully Responsive to manage your company testimonial. Enough functionality to be helpful and also stays out of your way. It has shortcode and widget to display the output.
 * Author: techlabpro1
 * Version: 1.0.1
 * Text Domain: tpl-testimonial
 * Domain Path: /languages
 * Author URI: http://techlabpro.com
 */

define( 'TPL_TESTIMONIAL_VERSION', '1.0.1' );
define( 'TPL_TESTIMONIAL_TITLE', 'Testimonial' );
define( 'TPL_TESTIMONIAL_SLUG', 'tpl-testimonial' );
define( 'TPL_TESTIMONIAL_PLUGIN_PATH', dirname( __FILE__ ) );
define( 'TPL_TESTIMONIAL_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'TPL_TESTIMONIAL_LENGUAGE_PATH', dirname( plugin_basename( __FILE__ ) ) . '/languages' );

require( 'lib/init.php' );
