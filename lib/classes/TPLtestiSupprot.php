<?php
if( !class_exists( 'TPLtestiSupprot' ) ) :

	class TPLtestiSupprot {

		function verifyNonce( ){
            global $TLPtestimonial;
            $nonce      = @$_REQUEST['tlp_nonce'];
            $nonceText  = $TLPtestimonial->nonceText();
            if( !wp_verify_nonce( $nonce, $nonceText ) ) return false;
            return true;
        }

        function nonceText(){
        	return "tlp_testimonial_nonce";
        }

        function owl_property(){
            return array(
                    'auto_play' => __('Auto Play',TPL_TESTIMONIAL_SLUG),
                    'nav_button'   => __('Nav Button',TPL_TESTIMONIAL_SLUG),
                    'stop_hover'    => __('Stop Hover',TPL_TESTIMONIAL_SLUG),
                    'responsive'    => __('Responsive',TPL_TESTIMONIAL_SLUG),
                    'auto_height'   => __('Auto Height',TPL_TESTIMONIAL_SLUG),
                    'lazy_load'     => __('Lazy Load',TPL_TESTIMONIAL_SLUG)
                );
        }

	}
endif;
