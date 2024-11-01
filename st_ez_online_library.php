<?php

/**
  Plugin Name: Easy Online Library
  Plugin URI: http://www.sanskrutitech.in
  Description: A Plugin that lets you create an online library of files like pdf, doc for your visitors to browse through. 
  Author: Dhara Shah
  Version: 1.0
  Author URI: http://www.sanskrutitech.in
 */

define('ST_EZ_ONLINE_LIBRARY_FOLDER', dirname(plugin_basename(__FILE__)));
define('ST_EZ_ONLINE_LIBRARY_URL', plugins_url('',__FILE__));

register_activation_hook(__FILE__,'st_ez_online_library_install');

register_deactivation_hook( __FILE__, 'st_ez_online_library_uninstall' );

function st_ez_online_library_install(){
    global $wpdb;
    global $table_suffix;
    global $st_ez_online_library_db_ver;

    $st_ez_online_library_db_ver = 1.0;
    $table_suffix = "st_ez_online_library";
    $table_name = $wpdb->prefix . $table_suffix;
    
    $sql = "CREATE TABLE " . $table_name . " (
		st_ez_online_library_id bigint(20) NOT NULL AUTO_INCREMENT,
		file_path varchar(255) NOT NULL,
        thumbnail_path varchar(255),
		PRIMARY KEY (st_ez_online_library_id)
		);";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    
    add_option("st_ez_online_library_db_ver",$st_ez_online_library_db_ver);
}

function st_ez_online_library_uninstall(){
    /** Do Nothing **/
}

function st_ez_online_library_display ($atts, $content = null) {
    global $wpdb;
    
    $table_suffix = "st_ez_online_library";
    $table_name = $wpdb->prefix . $table_suffix;
    
    $table_result = $wpdb->get_results("Select * FROM $table_name ORDER BY st_ez_online_library_id DESC");
    
    foreach ($table_result as $table_row) { ; ?>
        
	<div style="float: left;width: 150px; margin: 10px 10px;border: 1px solid #cccccc;">
	     <a href="<?php echo $table_row->file_path; ?>"><img src="<?php echo $table_row->thumbnail_path; ?>" width="150" height="150"/></a>
	</div>
        
    <?php } 
}
/* Action Hook For Shortcode of St Online Library Plugin*/
add_shortcode("ez_online_library", "st_ez_online_library_display");

if ( is_admin() ){
    require_once dirname( __FILE__ ) . '/st_ez_online_library_admin.php';
    
    add_action('admin_print_scripts', 'st_ez_online_library_admin_scripts');
}

function st_ez_online_library_admin_scripts()
{
	wp_register_style('style.css',ST_EZ_ONLINE_LIBRARY_URL.'/css/style.css');
	wp_enqueue_style('style.css');
        
    wp_register_style('demo_table_jui.css',ST_EZ_ONLINE_LIBRARY_URL.'/css/demo_table_jui.css');
	wp_enqueue_style('demo_table_jui.css');
}

?>
