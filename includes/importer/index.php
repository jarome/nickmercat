<?php
if( !defined('ABSPATH') ) exit;

if( !class_exists( 'Radium_Theme_Importer' ) ){
	require_once 'radium-importer.php';
}

if( !class_exists( 'Neat_Importer' ) ){
	
	class Neat_Importer extends Radium_Theme_Importer{
	    /**
	     * Holds a copy of the object for easy reference.
	     *
	     * @since 0.0.1
	     *
	     * @var object
	     */
	    private static $instance;
	    
	    /**
	     * Set the key to be used to store theme options
	     *
	     * @since 0.0.2
	     *
	     * @var object
	     */
	    public $theme_option_name = 'neat'; //set theme options name here
			
		public $theme_options_file_name = 'theme_options.txt';
		
		public $widgets_file_name 		=  'widgets.json';
		
		public $content_demo_file_name  =  'content.xml';
		
		/**
		 * Holds a copy of the widget settings 
		 *
		 * @since 0.0.2
		 *
		 * @var object
		 */
		public $widget_import_results;
		
	    /**
	     * Constructor. Hooks all interactions to initialize the class.
	     *
	     * @since 0.0.1
	     */
	    public function __construct() {
	    
			$this->demo_files_path = dirname(__FILE__) . '/data/';
	
	        self::$instance = $this;
			parent::__construct();
	
	    }
		
		/**
		 * Add menus
		 *
		 * @since 0.0.1
		 */
		public function set_demo_menus(){
	
			// Menus to Import and assign - you can remove or add as many as you want
			$homepage_menu = get_term_by('name', 'Homepage Menu', 'nav_menu');
			$page_menu = get_term_by('name', 'Page Menu', 'nav_menu');
	
			set_theme_mod( 'nav_menu_locations', array(
	                'homepage_navigation' => $homepage_menu->term_id,
	                'page_navigation' => $page_menu->term_id
	            )
	        );
		}
	}
	new Neat_Importer();
}