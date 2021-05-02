<?php
/*
 * @package iTasks
 * @version 1.0
 */
/*
Plugin Name: iTasks
Plugin URI: 
Description: iTasks let you add tasks list.
Author: John Mark
Version: 1.0
Author URI:https://pollyolly.github.io/jmr/ 
*/
global $itasks_db_version;
$itasks_db_version = '1.0';
function itasks_install(){
    global $wpdb;
    global $itasks_db_version;
    $tbl_itasks_tasks = "{$wpdb->prefix}itasks_tasks";
    $sql = "CREATE TABLE " . $tbl_itasks_tasks  . " (
      id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
      tasks varchar(500) NULL,
      tasks_status varchar(20) NULL,
      tasks_priority varchar(20) NULL,
      tasks_start_date varchar(20) NULL,
      tasks_end_date varchar(20) NULL,
      tasks_remarks varchar(500) NULL,
      tasks_file_link varchar(500) NULL
    );";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    add_option('itasks_db_version', $itasks_db_version);
    $installed_ver = get_option('icb_db_version');
    if ($installed_ver != $itasks_db_version) { //NEW VERSION OF DATABASE
        $sql = "CREATE TABLE " . $tbl_itasks_tasks  . " (
	    id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
	    tasks varchar(500) NULL,
	    tasks_status varchar(20) NULL,
	    tasks_priority varchar(20) NULL,
	    tasks_start_date varchar(20) NULL,
	    tasks_end_date varchar(20) NULL,
	    tasks_remarks varchar(500) NULL,
	    tasks_file_link varchar(500)
	  );";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        // notice that we are updating option, rather than adding it
        update_option('itasks_db_version', $itasks_db_version);
    }
}
register_activation_hook(__FILE__, 'itasks_install');
function itasks_install_data(){
	global $wpdb;
	$tbl_itasks_tasks  = "{$wpdb->prefix}itasks_tasks";
	$check_empty = (int)$wpdb->get_var("SELECT COUNT(*) FROM ".$tbl_itasks_tasks);
	if($check_empty < 1){
		$tasks_data = array(
	    		array('tasks' => 'Tasks Demo1','tasks_status'=>'Ongoing','tasks_priority' => 'High',
	    	  		'tasks_start_date'=>'2021/03/01', 'tasks_end_date'=>'2021/03/07', 'tasks_remarks'=>'Remarks 1'),
	    		array('tasks' => 'Tasks Demo2','tasks_status'=>'Done','tasks_priority' => 'Least', 
	    	  		'tasks_start_date'=>'2021/03/14', 'tasks_end_date'=>'2021/03/20', 'tasks_remarks'=>'Remarks 2'),
	    		array('tasks' => 'Tasks Demo3','tasks_status'=>'Pending','tasks_priority' => 'Medium', 
	    	  		'tasks_start_date'=>'2021/02/06', 'tasks_end_date'=>'2021/02/12', 'tasks_remarks'=>'Remarks 3'),
	    		array('tasks' => 'Tasks Demo4','tasks_status'=>'Overdue','tasks_priority' => 'Least', 
	    	  		'tasks_start_date'=>'2021/01/01', 'tasks_end_date'=>'2021/01/01', 'tasks_remarks'=>'Remarks 4'),
	    		array('tasks' => 'Tasks Demo5','tasks_status'=>'Pending','tasks_priority' => 'High', 
	    	  		'tasks_start_date'=>'2021/04/29', 'tasks_end_date'=>'2021/04/30', 'tasks_remarks'=>'Remarks 5'),
	    		array('tasks' => 'Tasks Demo6','tasks_status'=>'Done','tasks_priority' => 'Medium', 
	    	  		'tasks_start_date'=>'2021/04/23', 'tasks_end_date'=>'2021/04/25', 'tasks_remarks'=>'Remarks 6')
		);
		$insertQuery = " INSERT INTO {$tbl_itasks_tasks} (".implode(",",array_keys($tasks_data[0])) .") VALUES ";
		foreach($tasks_data as $dataArray){
			$values = array();
			foreach($dataArray as $column=>$value){
				$values [] = "'".$value."'";	
			}
			$insertQuery .= " (". implode(",",$values) ."),";
		}
		$wpdb->query(substr($insertQuery,0,-1)); //Remove last string
	}
}
register_activation_hook(__FILE__, 'itasks_install_data');

function itasks_update_db_check(){
    global $itasks_db_version;
    if (get_site_option('itasks_db_version') != $itasks_db_version) {
        itasks_install();
    }
}
add_action('plugins_loaded', 'itasks_update_db_check');
/*include_once (plugin_dir_path(__FILE__) . '/assets/inc_script_style.php');
include_once (plugin_dir_path(__FILE__) . '/icb_shortcodes.php');
include_once (plugin_dir_path(__FILE__) . '/icb_mailer.php');
// //Tables
include_once (plugin_dir_path(__FILE__)  . '/tables/Topic_table_class.php');
include_once (plugin_dir_path(__FILE__)  . '/tables/Message_table_class.php');
include_once (plugin_dir_path(__FILE__)  . '/tables/Settings_table_class.php');
 */
//Forms
include_once (plugin_dir_path(__FILE__)  . '/forms/Add_form.php');
include_once (plugin_dir_path(__FILE__)  . '/forms/Settings_form.php');

//Tables
include_once (plugin_dir_path(__FILE__)  . '/tables/Tasks_table_class.php');
// //Subpage
include_once (plugin_dir_path(__FILE__) . '/admin/admin_sub_menu.php');
//Dashboard
include_once (plugin_dir_path(__FILE__) . '/dashboard/Report_widget.php');
//Posts
include_once (plugin_dir_path(__FILE__) . '/post_types/Task_posts.php');
include_once (plugin_dir_path(__FILE__) . '/post_types/Task_pages.php');

/*
//AJAX
include_once (plugin_dir_path(__FILE__) . '/ajax/ajax-calls.php');
function icb_startSession(){
	if(!session_id()){
		session_start();
    }
}
add_action("init","icb_startSession", 1);
 */
