<?php
function itasks_admin_menu(){
	add_menu_page(
		'iTasks',
		'iTasks',
		'edit_posts', //role: editor, author, admin, contributor
		'itasks',
		'itasks_tasks_list_handler'
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
		'edit_posts',
		'itasks-form',
		'itasks_add_form_handler'
	);
	remove_submenu_page('itasks','itasks'); //Remove first submenu 
}
add_action('admin_menu', 'itasks_admin_menu' );



?>
