<?php

function itasks_posts_form_handler() {
	    register_post_type('tasks_post',
            array(
                'labels'      => array(
                	'name'          => __( 'Tasks Posts', 'itasks' ),
                	'singular_name' => __( 'Tasks Post', 'itasks' ),
		),
		'public'      => true,
		'has_archive' => true,
		'show_in_menu' => false, //remove menu of CPT
                'rewrite'     => array( 'slug' => 'tasks' ), // my custom slug
            )									        );
}
add_action('init', 'itasks_posts_form_handler');
