<?php

if(!class_exists('TLPinitTestiWidget')):

	/**
	* 
	*/
	class TLPinitTestiWidget
	{
		
		function __construct()
		{
			add_action( 'widgets_init', array($this, 'initWidget'));
		}


		function initWidget(){
			global $TLPtestimonial;

			$TLPtestimonial->loadWidget( $TLPtestimonial->widgetsPath );
		}
	}


endif;