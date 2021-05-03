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

add_action( 'admin_head', 'itasks_page_help_tab' );
function itasks_page_help_tab() {
        $screen = get_current_screen();
            // Return early if we're not on the book post type.
        if ( 'tasks_page' != $screen->post_type ) {
                return;
        }
        // Setup help tab args.
        $args = array(
               'id'      => 'help_itasks', // Unique id for the tab.
               'title'   => __( 'Contact', 'itasks' ), // Unique visible title for the tab.
               'content' => '<h3>Email Me</h3><p>johnmarkroco05@gmail.com</p>', // Actual help text.
        );
       $args2 = array(
               'id'      => 'help2_itasks', // Unique id for the tab.
               'title'   => __( 'More Info', 'itasks' ), // Unique visible title for the tab.
               'content' => '<h3>My Portfolio</h3><p><a href="https://pollyolly.github.io/jmr/" target="_blank">https://pollyolly.github.io/jmr/</a></p>', // Actual help text.
        );

        // Add the help tab.
        $screen->add_help_tab( $args );
        $screen->add_help_tab( $args2 );

}
