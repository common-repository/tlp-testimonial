<?php

if(!class_exists('TLPtestiOwlCarosuel')):


	/**
	*
	*/
	class TLPtestiOwlCarosuel extends WP_Widget
	{

		/**
		 * TLP TEAM widget setup
		 */
		function TLPtestiOwlCarosuel() {

		    $widget_ops = array( 'classname' => 'widget_tlp_testi_owl_carosuel', 'description' => __('Display the testimonial as carosuel.', 'tlp_testimonial') );
		    parent::__construct( 'widget_tlp_testi_owl_carosuel', __('TPL Testimonial', 'tlp_testimonial'), $widget_ops);

		    add_action( 'wp_enqueue_scripts', array($this,'carosuel_script' ));

		}

		function carosuel_script(){
			global $TLPtestimonial;
			wp_enqueue_style( 'tlp_testi_owl_carosuel_css', $TLPtestimonial->assetsUrl . 'vendor/owl-carousel/owl.carousel.css');
			wp_enqueue_style( 'tlp_testi_owl_carosuel_front_css', $TLPtestimonial->assetsUrl . 'css/front-end.css');
			wp_enqueue_script( 'tlp_testi_owl_carosuel_js',  $TLPtestimonial->assetsUrl. 'vendor/owl-carousel/owl.carousel.js', array('jquery'));
		}

		/**
		 * display the widgets on the screen.
		 */
		function widget( $args, $instance ) {

			$caroID = $args['widget_id'].'-testi-carosuel';

			global $TLPtestimonial;

		    extract( $args );


		    @$title = ($instance['title'] ? $instance['title'] : "TLP Testimonial");
		    @$number = ($instance['number'] ? (int)$instance['number'] : 1);
		    @$total = ($instance['total'] ? (int)$instance['total'] : 8);
		    @$speed = ($instance['speed'] ? (int)$instance['speed'] : 2000);
		    @$auto_play = ($instance['auto_play'] ? 'true' : 'false');
		    @$nav_button = ($instance['nav_button'] ? 'true' : 'false');
		    @$stop_hover = ($instance['stop_hover'] ? 'true' : 'false');
		    @$responsive = ($instance['responsive'] ? 'true' : 'false');
		    @$auto_height = ($instance['auto_height'] ? 'true' : 'false');
		    @$lazy_load = ($instance['lazy_load'] ? 'true' : 'false');


		    echo $before_widget;
            if ( ! empty( $instance['title'] ) ) {
                echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
            }
		    ?>
		    <div class="tlp-widget-holder">
		    <?php
					$args_q = array(
						'post_type' => 'testimonial',
						'post_status'=> 'publish',
						'posts_per_page' => $total,
						'orderby' => 'date',
						'order'   => 'DESC',
					);

					$teamQuery = new WP_Query( $args_q );
					$html = null;
					if ( $teamQuery->have_posts() ) {

						$html .= "<div id='$caroID'>";
							while ($teamQuery->have_posts()) : $teamQuery->the_post();

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

						    	$html .= '<div class="item testimonial_content">';
			    			    	$html .= '<span class="testimonial_pic">
											     <img src="'.$t->img.'" alt="testimonials" />
											   </span>';
		    						$html .="<span class='content'>";
			    						$html .="<p>{$t->testimonial}</p>";
			    						$html .= "<sapn class='down-triangle'></sapn>";
			    						$html .="</span>";
		    						$html .= '<span class="testimonial_designation">';
		    							$html .= "<h2>{$t->title}</h2>";
		    							$html .= "<p>{$t->designation}</p>";
		    						$html .= '</span>';
								$html .='</div>';

								$t = null;
								endwhile;

						wp_reset_postdata();

						$html .='</div>';
					}else{
						$html .= "<p>".__('No post found', TPL_TESTIMONIAL_SLUG)."</p>";
					}
					echo $html;

			?>
		    </div>

		    <?php
		    echo $after_widget;


		    $caro = null;
		    $caro .= "<script>";
		    $caro .= '(function($){
							$("#'.$caroID.'").owlCarousel({

							    // Most important owl features
							    items : '.$number.',
							    itemsCustom : false,
							    itemsDesktop : [1199,4],
							    itemsDesktopSmall : [980,3],
							    itemsTablet: [768,2],
							    itemsTabletSmall: false,
							    itemsMobile : [479,1],
							    singleItem : false,
							    itemsScaleUp : false,

							    //Basic Speeds
							    slideSpeed : '.$speed.',
							    paginationSpeed : 800,
							    rewindSpeed : 1000,

							    //Autoplay
							    autoPlay : '.$auto_play.',
							    stopOnHover : '.$stop_hover.',

							    // Navigation
							    navigation : false,
							    navigationText : ["prev","next"],
							    rewindNav : true,
							    scrollPerPage : false,

							    //Pagination
							    pagination : '.$nav_button.',
							    paginationNumbers: false,

							    // Responsive
							    responsive: '.$responsive.',
							    responsiveRefreshRate : 200,
							    responsiveBaseWidth: window,

							    // CSS Styles
							    baseClass : "owl-carousel",
							    theme : "owl-theme",

							    //Lazy load
							    lazyLoad : '.$lazy_load.',
							    lazyFollow : true,
							    lazyEffect : "fade",

							    //Auto height
							    autoHeight : '.$auto_height.',

							    //JSON
							    jsonPath : false,
							    jsonSuccess : false,

							    //Mouse Events
							    dragBeforeAnimFinish : true,
							    mouseDrag : true,
							    touchDrag : true,

							    //Transitions
							    transitionStyle : false,

							    // Other
							    addClassActive : false,

							    //Callbacks
							    beforeUpdate : false,
							    afterUpdate : false,
							    beforeInit: false,
							    afterInit: false,
							    beforeMove: false,
							    afterMove: false,
							    afterAction: false,
							    startDragging : false,
							    afterLazyLoad : false

							});
		    			})(jQuery)';
		    $caro .= "</script>";

		    echo $caro;


		}


		function form( $instance ) {

		    $defaults = array(
		        'number'		=> 1,
		        'total'			=> 8,
		        'speed'			=> 2000,
		        'auto_play'		=> 1,
		    );
		    $instance = wp_parse_args( (array) $instance, $defaults ); ?>

			<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', TPL_TESTIMONIAL_SLUG); ?></label>
		        <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" /></p>

		    <p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Number of member per slide:', TPL_TESTIMONIAL_SLUG); ?></label>
		        <input type="text" size="2" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo $instance['number']; ?>" /></p>

		    <p><label for="<?php echo $this->get_field_id( 'total' ); ?>"><?php _e('Total Number of member:' , TPL_TESTIMONIAL_SLUG); ?></label>
		        <input type="text" size="2" id="<?php echo $this->get_field_id('total'); ?>" name="<?php echo $this->get_field_name('total'); ?>" value="<?php echo $instance['total']; ?>" /></p>

		    <p><label for="<?php echo $this->get_field_id( 'speed' ); ?>"><?php _e('Slide Speed:' , TPL_TESTIMONIAL_SLUG); ?></label>
		        <input type="text" size="4" id="<?php echo $this->get_field_id('speed'); ?>" name="<?php echo $this->get_field_name('speed'); ?>" value="<?php echo $instance['speed']; ?>" /></p>

			<?php

			global $TLPtestimonial;
			$options = $TLPtestimonial->owl_property();

			if(!empty($options)){
				echo "<p>";
				foreach ($options as $key => $value) {
					$checked = (@$instance[$key] ? "checked" : null);
					$html = null;
					$html .=  '<input type="checkbox" '.$checked.' value="1" class="checkbox" id="'.$this->get_field_id($key).'" name="'.$this->get_field_name($key).'">
							<label for="'.$this->get_field_id($key).'">'.$value.'</label><br>';

					echo $html;
				}
				echo "</p>";
			}
		}

		public function update( $new_instance, $old_instance ) {

			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? (int)( $new_instance['number'] ) : '';
			$instance['total'] = ( ! empty( $new_instance['total'] ) ) ? (int)( $new_instance['total'] ) : '';
			$instance['speed'] = ( ! empty( $new_instance['speed'] ) ) ? (int)( $new_instance['speed'] ) : '';

			global $TLPtestimonial;
			$options = $TLPtestimonial->owl_property();
			if(!empty($options)){
				foreach ($options as $key => $value) {
					$instance[$key] = ( ! empty( $new_instance[$key] ) ) ? (int)( $new_instance[$key] ) : '';
				}
			}

			return $instance;
		}
	}
endif;
