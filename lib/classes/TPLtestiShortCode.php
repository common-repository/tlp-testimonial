<?php

if(!class_exists('TPLtestiShortCode')):

	/**
	*
	*/
	class TPLtestiShortCode
	{

		function __construct()
		{
			add_shortcode( 'tlptestimonial', array( $this, 'testimonial_shortcode' ) );
		}
		function testimonial_shortcode($atts , $content = ""){

			global $TLPtestimonial;
			wp_enqueue_style( 'tlp_testimonial_owl_carosuel_front_css', $TLPtestimonial->assetsUrl . 'css/front-end.css');

			$atts = shortcode_atts( array(
					'number' => 4
				), $atts, 'tlptestimonial' );


			$html = null;

			$testiArgs = array(
					'post_type' => 'testimonial',
					'post_status'=> 'publish',
					'posts_per_page' => $atts['number'],
					'orderby' => 'date',
					'order'   => 'DESC',
				);

			$testiQuery = new WP_Query( $testiArgs );

			   if ( $testiQuery->have_posts() ) {
			   		$html .= '<div class="tlp-testi-container">';
			   		$i = 1;
				    while ($testiQuery->have_posts()) : $testiQuery->the_post();

				      		$t = new stdClass();
				      		$t->title = get_the_title();
				      		$t->testimonial = get_the_content();
				      		$t->designation = get_post_meta( get_the_ID(), 'designation', true );
				      		$t->company = get_post_meta( get_the_ID(), 'company', true );
				      		$t->location = get_post_meta( get_the_ID(), 'location', true );

				      		$status = $t->designation .", " . $t->company . " " . $t->location;

				      		if(has_post_thumbnail()){
				      			$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'testimonial-thumb' );
				      			$t->img = $image[0];
				      		}else{
								$t->img = $TLPtestimonial->assetsUrl .'images/demo.jpg';
				      		}

					    	$html .= '<div class="tlp-testi-content">';
					    	if($i % 2 == 0){
		    			    	$thum_p = "left";
		    			    	$detail_p = "right";
					    	}else{
					    		$thum_p = "right";
		    			    	$detail_p = "left";
					    	}
					    	$html .= "<div class='tlp-testi-thum $thum_p'>";
	    						$html .= '<div class="thum"><img src="'.$t->img.'"></div>';
	    						$html .= "<h4>{$t->title}</h4>";
	    						$html .= "<p>$status</p>";
	    					$html .='</div>';
	    					$html .="<div class='tlp-testi-details $detail_p blockquote'>";
	    					$html .="<p>{$t->testimonial}</p>";
	    					$html .='</div>';

							$html .='</div>';

							$t = null;
							$i++;
				      endwhile;

				      wp_reset_postdata();
				     // end row
				   $html .= '</div>'; // end container
			   }else{

			   	$html .= "<p>". __('No testimonial found', TPL_TESTIMONIAL_SLUG)."</p>";

			   }

			return $html;
		}

	}


endif;
