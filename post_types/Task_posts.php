<?php

function itasks_posts_form_handler() {
	    register_post_type('tasks_post',
            array(
                'labels' => array(
                	'name'          => __( 'Tasks Posts', 'itasks' ),
                	'singular_name' => __( 'Tasks Post', 'itasks' ),
		),
		'public'      	     => true,
		'publicly_queryable' => true,
		'query_var'          => true,
		'capability_type'    => 'post',
		'has_archive' 	     => true,
		'hierarchical'       => false,
		'show_in_menu' => false, //remove menu of CPT
		'rewrite'     => array( 'slug' => 'tasks_post' ), // my custom slug
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail','comments'),
		'taxonomies'         => array( 'category', 'post_tag' ),
		//'show_in_rest'       => true //To support Guttenberg
            ));
}
add_action('init', 'itasks_posts_form_handler');
