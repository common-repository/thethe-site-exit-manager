<?php
if (!function_exists('TheThe_makeAdminPage')) {
    function TheThe_makeAdminPage(){
		include 'inc/view-about-us.php';
    }
}
function TheTheSEM_Style(){
	$x = $GLOBALS['TheTheSEM']['wp_plugin_dir_url'];
	wp_admin_css( 'nav-menu' );			
	wp_deregister_style( 'thethefly-plugin-panel-interface');
	wp_register_style( 'thethefly-plugin-panel-interface', $x.'style/admin/interface.css' );
	wp_enqueue_style( 'thethefly-plugin-panel-interface' );

	wp_deregister_script( 'jquery-ui' );
	wp_register_script( 'jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js');
	wp_enqueue_script( 'jquery-ui' );	
	
	wp_deregister_script( 'jquery-ui-ttsem' );
	wp_register_script( 'jquery-ui-ttsem', $x.'style/js/jquery.ttsem-min.js');
	wp_enqueue_script( 'jquery-ui-ttsem' );
	
	wp_deregister_script( 'wp-media' );
	wp_register_script( 'wp-media', $x.'style/js/media.js');
	wp_enqueue_script( 'wp-media' );
	
	wp_deregister_script( 'wp-ajax-response' );
	wp_register_script( 'wp-ajax-response', $x.'style/js/wp-ajax-response.js');
	wp_enqueue_script( 'wp-ajax-response');
		
	wp_deregister_script( 'jquery-ui-ttsem-box' );
	wp_register_script( 'jquery-ui-ttsem-box', $x.'style/js/jquery.ttsem.box-min.js');
	wp_enqueue_script( 'jquery-ui-ttsem-box' );
	
	wp_enqueue_script( 'postbox');
	wp_enqueue_script( 'post');
}
/**
 * Add menu to admin options page
 */
function TheTheSEM_Menu()
{
    global $menu;

    $flag['makebox'] = true;
    if (is_array($menu)) foreach ($menu as $e) {
        if (isset($e[0]) && (in_array($e[0], array('TheThe Fly','TheTheFly')))) {
            $flag['makebox'] = false;
            break;
        }
    }

    if ($flag['makebox']) {
		$icon_url = $GLOBALS['TheTheSEM']['wp_plugin_dir_url'].'style/admin/images/favicon.ico';
		// Add a new top-level menu:
		add_menu_page('TheThe Fly', 'TheThe Fly', 8, 'thethefly', 'TheThe_makeAdminPage',$icon_url, 63);
        // Add a submenu to the top-level menu:
         $panelHookAboutUs = add_submenu_page('thethefly', 'TheThe Fly: About the Club', 'About the Club', 8, 'thethefly', 'TheThe_makeAdminPage');
    }

    // Add Tabs-n-slides
    $panelHook = add_submenu_page('thethefly', 'TheThe Site Exit Manager','Site Exit Manager',8,'site-exit-manager','TheTheSEM_options');
	add_filter( 'admin_print_styles-' . $panelHookAboutUs, 'TheTheSEM_Style');
	add_filter( 'admin_print_styles-' . $panelHook, 'TheTheSEM_Style');
} // end func wpSEM_menu

/**
 * 
 */
function TheTheSEM_options()
{
	$message = '';
	$options = $newoptions = get_option('ttsem_options');
	if(isset($_POST['submit'])) {
		$newoptions['is_active'] = $_POST['is_active'];
		$newoptions['redirect_type'] = $_POST['type'];
		$newoptions['redirect_value'] = $_POST['value'];
		$newoptions['redirect_body'] = $_POST['body'];
		if (isset($_REQUEST['found_post_id']) && $_REQUEST['found_post_id']) {
			$newoptions['redirect_value'] = $_REQUEST['found_post_id'];
		}
		if($options != $newoptions) {
			update_option('ttsem_options',$newoptions);
			$message = "Options Saved";
		}
	}
	if(isset($_POST['reset'])) {
		$newoptions = $GLOBALS['ttsem_def'];
		if($options != $newoptions) {
			update_option('ttsem_options',$newoptions);
			$message = "Options Saved";
		}
	}		
	$options = get_option('ttsem_options');
	include 'inc/view-tabs.php';	
}// end func