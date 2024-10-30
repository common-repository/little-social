<?php

class CustomPostTypeCreator {

	public function __construct(){
		
	}


	public function create_social_post_type(){
		$labels = array(
		    'name'               => _x( 'Social Post', 'post type general name' ),
		    'singular_name'      => _x( 'Social Post', 'post type singular name' ),
		    'edit_item'          => __( 'Edit Social Post' ),
		    'new_item'           => __( 'New Social Post' ),
		    'all_items'          => __( 'All Social Posts' ),
		    'view_item'          => __( 'View Social Post' ),
		    'search_items'       => __( 'Search Social Posts' ),
		    'not_found'          => __( 'No Social Posts found' ),
		    'not_found_in_trash' => __( 'No Social Posts found in the Trash' ),
		  );
		 $args = array(
		    'labels'        => $labels,
		    'description'   => 'Store social posts',
		    'public'        => true,
		    'has_archive'   => true,
			'taxonomies' => array('post_tag'),
			'show_in_menu'	=> true
		  );
	 	register_post_type( 'little_social', $args );
	}

}
