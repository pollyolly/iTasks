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
		'has_archive' 	     => false,
		'hierarchical'       => false,
		'show_in_menu' => false, //remove menu of CPT
		'rewrite'     => array( 'slug' => 'tasks_post' ), // my custom slug
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail','comments'),//custom-fields
		'taxonomies'         => array( 'category', 'post_tag' ),
		//'show_in_rest'       => true //To support Guttenberg
            ));
}
add_action('init', 'itasks_posts_form_handler');
/**
  Add Help Tab to Book post type.
**/
add_action( 'admin_head', 'itasks_posts_help_tab' );
function itasks_posts_help_tab() {
	$screen = get_current_screen();
            // Return early if we're not on the book post type.
	if ( 'tasks_post' != $screen->post_type ) {
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

add_action( 'load-post.php', 'itasks_post_meta_boxes_setup' );//Load only in post
add_action( 'load-post-new.php', 'itasks_post_meta_boxes_setup' );//load only in post new
function itasks_post_meta_boxes_setup() {
	/* Add meta boxes on the 'add_meta_boxes' hook. */
        add_action( 'add_meta_boxes', 'itasks_add_post_meta_boxes' );
        /* Save post meta on the 'save_post' hook. */
	add_action( 'save_post', 'itasks_save_post_class_meta', 10, 2 );
	add_action( 'new_to_publish', 'itasks_save_post_class_meta', 10, 2 );
}

function itasks_add_post_meta_boxes() {
	add_meta_box(
        'itasks-meta-post-class',      // Unique ID
	        esc_html__( 'Additional Information', 'itasks' ),    // Title
	       'itasks_post_class_meta_box',   // Callback function
	       'tasks_post',         // Admin page (or Post Type) //post, post-type //This will show the meta in specified post type
	       'side',         // Context
	       'default'         // Priority
	);
}

function itasks_post_class_meta_box( $post ) { ?>
  <?php wp_nonce_field( basename( __FILE__ ), 'itasks_post_class_nonce' ); ?>
  <p>
    <input class="widefat" type="text" name="itasks-post-input-class" id="itasks-post-input-class" value="<?php echo esc_attr( get_post_meta( $post->ID, 'itasks_post_key_class', true ) ); ?>" size="30" />
       </p>
<?php }

/* Save the meta box’s post metadata. */
function itasks_save_post_class_meta( $post_id, $post ) {
	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['itasks_post_class_nonce'] ) || !wp_verify_nonce( $_POST['itasks_post_class_nonce'], basename( __FILE__ ) ) )
	      return $post_id;
	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );
	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;
	/* Get the posted data and sanitize it for use as an HTML class. */
	$new_meta_value = ( isset( $_POST['itasks-post-input-class'] ) ? sanitize_html_class( $_POST['itasks-post-input-class'] ) : '' );
	/* Get the meta key. */
	$meta_key = 'itasks_post_key_class';
	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );
	/* If a new meta value was added and there was no previous value, add it. */
        if ( $new_meta_value && '' == $meta_value ){
		    add_post_meta( $post_id, $meta_key, $new_meta_value, true );
	/* If the new meta value does not match the old value, update it. */
	} elseif ( $new_meta_value && $new_meta_value != $meta_value ) {
		      update_post_meta( $post_id, $meta_key, $new_meta_value );
	/* If there is no new meta value but an old value exists, delete it. */
	}elseif ( '' == $new_meta_value && $meta_value ){
		delete_post_meta( $post_id, $meta_key, $meta_value );
	}
}
