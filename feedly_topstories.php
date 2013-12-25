<?php
/*
Plugin Name: Feedly Top Stories
Plugin URI: 
Description: 
Version: 1.0
Author: Mohamed Kamagate
Author URI: http://kamagatos.com
License: 
*/

if ( !class_exists( 'FTSPlugin' ) ) {

	class FTSPlugin {
		
		public $pluginPath;
		public $pluginUrl;
		public $pluginName;
		
		//Feedly Top Stories Plugin Constructor
		public function __construct( ) {
			$this->pluginPath 	= plugin_dir_path(__FILE__);
			$this->pluginUrl 	= plugin_dir_url(__FILE__);
			
			require_once( $this->pluginPath . 'controller.php' );

			//Add actions
			add_action('widgets_init', array( $this, 'addWidgets') );
			add_action( 'wp_enqueue_scripts', array( $this, 'prefix_add_css') );
		}

		function prefix_add_css() 
		{
		    wp_register_style( 'prefix-style', plugins_url('fts.css', __FILE__) );
		    wp_enqueue_style( 'prefix-style' );
		}
		public function addWidgets() 
		{
			register_widget('FTSWidget');
		}
	}
}

$GLOBALS["sfplugin"] = new FTSPlugin();

?>