<?php
/*
Plugin Name: Custom TT-Tech
Plugin URI: http://www.tt-tech.de/
Description: Import API By Command
Text Domain: custom-tt-tech
Domain Path: /languages
Version: 1.0.1
Author: LeTin
*/
/*
 * Version: 1.0.1 Change Logs
 * Create Element with Visual Composer
 */
define( 'CUSTOM_TT_TECH_PLUGIN_DIR', plugin_dir_path( __FILE__ ) ); //Dinh nghia duong dan cho plugin;

require_once ( CUSTOM_TT_TECH_PLUGIN_DIR . 'inc/custom-function.php');
require_once ( CUSTOM_TT_TECH_PLUGIN_DIR . 'inc/widgets/my-recent-posts.php');
require_once ( CUSTOM_TT_TECH_PLUGIN_DIR . 'inc/widgets/my-desc-category.php');

function register_custom_widget() {
	register_widget( 'MY_PostWidgetCustomTTTech' );
	register_widget( 'MY_DescCategoryWidgetCustomTTTech' );
}

function myplugin_init() {
 $plugin_dir = basename(dirname(__FILE__)) ."/languages";
 load_plugin_textdomain( 'custom-tt-tech', false, $plugin_dir );
 add_action( 'widgets_init', 'register_custom_widget' );
}

add_action('plugins_loaded', 'myplugin_init');


