<?php
function itasks_admin_menu(){
	add_menu_page(
		'iTasks',
		'iTasks',
		'edit_posts', //role: editor, author, admin, contributor
		'itasks',
		'itasks_tasks_list_handler'
	);
	$link_page_CPT = 'edit.php?post_type=tasks_page';
	add_submenu_page(
		'itasks',
		'Tasks Page',
		'Tasks Page',
		'edit_posts',
		$link_page_CPT
	);
	$link_post_CPT = 'edit.php?post_type=tasks_post';
	add_submenu_page(
		'itasks',
		'Tasks Post',
		'Tasks Post',
		'edit_posts',
		$link_post_CPT
	);
	add_submenu_page(
		'itasks',
		'Tasks',
		'Tasks',
		'edit_posts',
		'itasks-list',
		'itasks_tasks_list_handler'
	);
	add_submenu_page(
		'itasks',
		'Add Tasks',
		'Add Tasks',
		'upload_files',
		'itasks-form',
		'itasks_add_form_handler'
	);
	add_submenu_page(
		'itasks',
		'Settings',
		'Settings',
		'edit_posts',
		'itasks-settings',
		'itasks_settings_form_handler'
	);
	remove_submenu_page('itasks','itasks'); //Remove first submenu 
}
add_action('admin_menu', 'itasks_admin_menu' );



?>
