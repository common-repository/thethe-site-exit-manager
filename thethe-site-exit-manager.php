<?php
/*
Plugin Name: TheThe Site Exit Manager
Plugin URI: http://thethefly.com/wp-plugins/thethe-site-exit-manager/
Description: TheThe Site Exit Manager pops up a special window on the event of website exit, trying to keep the visitor's attention. While popping up this window, the plugin is basically loading a specified URL or WP page/post in the background (usually with a special offer to motivate the visitor to keep surfing the site). Supported browsers: Firefox, Chrome, IE, Safari (no Opera!).
Version: 1.0.1
Author: TheThe Fly
Author URI: http://thethefly.com/
*/

$TheTheSEM = array(
    'wp_plugin_dir' => dirname(__FILE__),
	'wp_plugin_dir_url' => WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)),
    'wp_plugin_name' => 'TheThe Site Exit Manager',
    'wp_plugin_version' => '1.0.1'
);
$ttsem_def = array();
$ttsem_def['is_active'] = 1;
$ttsem_def['redirect_type'] = "url";
$ttsem_def['redirect_value'] = "http://thethefly.com";
$ttsem_def['redirect_body'] = "Are you sure you want to quit this page?";

function TheTheSEM_Activate(){
    update_option('ttsem_options', $GLOBALS['ttsem_def']);
}
function TheTheSEM_deActivate(){
    delete_option('ttsem_options');
}

register_activation_hook( __FILE__, 'TheTheSEM_Activate' );
register_deactivation_hook( __FILE__, 'TheTheSEM_deActivate' );

if (is_admin()) {
    require_once $TheTheSEM['wp_plugin_dir'] . '/thethe-admin.php';
	add_filter('admin_menu','TheTheSEM_Menu');
} else {	
	/**
	 * hook it!
	 */
	add_filter('wp_head','ttsem_head');
	add_filter('wp_footer','ttsem_footer');
	add_filter('init','ttsem_init');
	add_shortcode('on-site-exit', 'ttsem_handler');
}
/**
 * Global config setup
 */
$GLOBALS['ttsem'] = get_option('ttsem_options');

/**
 * Custom wordpress header
 */
function ttsem_head()
{
	/**
	 * Custom head
	 */
} // end func ttsem_head

/**
 * Custom wordpress footer
 */
function ttsem_footer()
{
	global $wpdb,$post;

	if ($GLOBALS['ttsem']['redirect_value']) {
		if ($GLOBALS['ttsem']['redirect_type'] == 'url') {

		} elseif (($GLOBALS['ttsem']['redirect_type'] == 'page') || ($GLOBALS['ttsem']['redirect_type'] == 'post')) {
			$GLOBALS['ttsem']['redirect_value'] = intval($GLOBALS['ttsem']['redirect_value']);
			$GLOBALS['ttsem']['redirect_value'] = get_permalink($GLOBALS['ttsem']['redirect_value']);
		}
		$url = str_replace("'","\'",$GLOBALS['ttsem']['redirect_value']);
		$message = str_replace("'","&#039;",strip_tags($GLOBALS['ttsem']['redirect_body']));
		$message = str_replace("\n",'\n',$message);
		$message = str_replace("\r",'',$message);

        $scriptInner = "<script type='text/javascript'>\n";
        $scriptInner .= "jQuery(document).ready(function($) {\n";

		$scriptInner .= "$.tsem({ message : '{$message}', url : '{$url}'});";

		$scriptInner .= "});\n";
		$scriptInner .= '</script>';

		if ($url) echo (($post->ID == @$GLOBALS['ttsem']['post_id']) ||(@$GLOBALS['ttsem']['is_active'])) ? $scriptInner : '';
	}	
}

/**
 * Init action
 */
function ttsem_init()
{
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js');
	wp_enqueue_script( 'jquery' );
	
	
	wp_deregister_script( 'wp-media' );
	wp_register_script( 'wp-media', $GLOBALS['TheTheSEM']['wp_plugin_dir_url'].'style/js/media.js');
	wp_enqueue_script( 'wp-media' );

	wp_deregister_script( 'jquery-ui-ttsem' );
	wp_register_script( 'jquery-ui-ttsem', $GLOBALS['TheTheSEM']['wp_plugin_dir_url'].'style/js/jquery.ttsem-min.js');
	wp_enqueue_script( 'jquery-ui-ttsem' );

} // end func ttsem_init


/**
 * Shortcode process, override global config
 * 
 * @param array $atts
 * @param string $content
 * @param string $code
 */
function ttsem_handler($atts, $content=null, $code='')
{
	global $wpdb,$post;
	if (is_single() || is_page()) {
		if (isset($atts['type'])) {
			if ($atts['type'] == 'url') {
				$GLOBALS['ttsem']['redirect_value'] = $atts['redirect'];
			} elseif (($atts['type'] == 'page') || ($atts['type'] == 'post')) {
				$GLOBALS['ttsem']['redirect_value'] = get_permalink($atts['redirect']);
			}
			$GLOBALS['ttsem']['redirect_type'] = 'url';
			$GLOBALS['ttsem']['redirect_body'] = str_replace("'","&#039;",$content);
			$GLOBALS['ttsem']['post_id'] = get_the_ID(); 
		}
	}
}