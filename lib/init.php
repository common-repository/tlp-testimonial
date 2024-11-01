<?php

class TLPtestimonial
{


	function __construct(){

        $this->incPath       = dirname( __FILE__ );
        $this->classesPath		= $this->incPath . '/classes/';
        $this->widgetsPath		= $this->incPath . '/widgets/';
        $this->viewsPath		= $this->incPath . '/views/';

        $this->assetsUrl        = TPL_TESTIMONIAL_PLUGIN_URL  . '/assets/';

        $this->TPLloadClass( $this->classesPath ); 
        $this->options = array(
                'settings' => 'tpl_testimonial_settings'
            );

	}


	function TPLloadClass($dir){
		if (!file_exists($dir)) return;

            $classes = array();

            foreach (scandir($dir) as $item) {
                if( preg_match( "/.php$/i" , $item ) ) {
                    require_once( $dir . $item );
                    $className = str_replace( ".php", "", $item );
                    $classes[] = new $className;
                }      
            }
            
            if($classes){
            	foreach( $classes as $class )
            	    $this->objects[] = $class;
            }
	}

    function loadWidget($dir){
        if (!file_exists($dir)) return;
        foreach (scandir($dir) as $item) {
            if( preg_match( "/.php$/i" , $item ) ) {
                require_once( $dir . $item );
                $class = str_replace( ".php", "", $item );

                if (method_exists($class, 'register_widget')) {
                    $caller = new $class;
                    $caller->register_widget();
                }
                else {
                    register_widget($class);
                }
            }
        }
    }


	 function render( $viewName, $args = array()){
        global $TLPtestimonial;        
        
        $viewPath = $TLPtestimonial->viewsPath . $viewName . '.php';
        if( !file_exists( $viewPath ) ) return;
        
        if( $args ) extract($args);            
        $pageReturn = include $viewPath;
        if( $pageReturn AND $pageReturn <> 1 )
            return $pageReturn;
        if( @$html ) return $html;        
    } 


	/**
     * Dynamicaly call any  method from models class
     * by pluginFramework instance
     */
    function __call( $name, $args ){
        if( !is_array($this->objects) ) return;
        foreach($this->objects as $object){
            if(method_exists($object, $name)){
                $count = count($args);
                if($count == 0)
                    return $object->$name();
                elseif($count == 1)
                    return $object->$name($args[0]);
                elseif($count == 2)
                    return $object->$name($args[0], $args[1]);     
                elseif($count == 3)
                    return $object->$name($args[0], $args[1], $args[2]);      
                elseif($count == 4)
                    return $object->$name($args[0], $args[1], $args[2], $args[3]);  
                elseif($count == 5)
                    return $object->$name($args[0], $args[1], $args[2], $args[3], $args[4]);         
                elseif($count == 6)
                    return $object->$name($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);                                                                                             
            }
        }
    } 

}

global $TLPtestimonial;
if( !is_object( $TLPtestimonial ) )
    $TLPtestimonial = new TLPtestimonial;		
