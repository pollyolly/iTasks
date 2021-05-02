<?php

function itasks_pages_form_handler() {
	    register_post_type('tasks_page',
            array(
                'labels' => array(
                	'name'          => __( 'Tasks Pages', 'itasks' ),
                	'singular_name' => __( 'Tasks Page', 'itasks' ),
		),
		'public'      	     => true,
		'publicly_queryable' => true,
		'query_var'          => true,
		'capability_type'    => 'page',
		'has_archive' 	     => true,
		'hierarchical'       => false,
		'show_in_menu' => false, //remove menu of CPT
		'rewrite'     => array( 'slug' => 'tasks_page' ), // my custom slug
		'supports'           => array( 'title','editor', 'author','comments'),
		//'taxonomies'         => array( 'category', 'post_tag' ),
		//'show_in_rest'       => true //To support Guttenberg
            ));
}
add_action('init', 'itasks_pages_form_handler');
