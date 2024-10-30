<?php

require_once( ABSPATH . "wp-includes/pluggable.php" );
require_once( ABSPATH . 'wp-admin/includes/post.php' );
require plugin_dir_path( __FILE__ ) . 'Helpers.php';

class SocialRepository {

	private $postType;
	private $socialMediaFactory;
	private $helpers;

	public function __construct(){
		$this->postType = 'little_social';
		$this->socialMediaFactory = new SocialMediaFactory();
		$this->helpers = new Helpers();
	}

	public function getSocialPosts($type = ''){

		$option = '';
		$count = 3;

		//do checks to see if social network wanted
		if((strcasecmp($type, 'instagram') == 0)){
			$option = 'instagram_number_of_posts';

			if(!get_option('social_instagram_show_flag')){
				return [];
			}
			//set num of posts to get
			$count = $this->getNumberOfPosts('instagram_number_of_posts');
		}else if((strcasecmp($type, 'facebook') == 0)){
			$option = 'facebook_number_of_posts';

			if(!get_option('social_facebook_show_flag')){
				return [];
			}
			//set num of posts to get
			$count = $this->getNumberOfPosts('facebook_number_of_posts');
		}else if((strcasecmp($type, 'twitter') == 0)){
			$option = 'twitter_number_of_posts';

			if(!get_option('social_twitter_show_flag')){
				return [];
			}
			//set num of posts to get
			$count = $this->getNumberOfPosts('twitter_number_of_posts');
		}else if((strcasecmp($type, 'linkedin') == 0)){
			$option = 'linkedin_number_of_posts';

			if(!get_option('social_linkedin_show_flag')){
				return [];
			}
			//set num of posts to get
			$count = $this->getNumberOfPosts('linkedin_number_of_posts');
		}


		$args = array(
		    'meta_query' => array(
		       array(
		           'key' => 'platform',
		           'value' => $type,
		           'compare' => '=',
		       )
		   ),
		    'post_type' => 'little_social',
		    'post_status' => 'any',
		    'meta_key' => 'date',
            'orderby' => 'meta_value_num',
		    'posts_per_page'   => $count,
		);
		
		return get_posts($args);
	}

	public function getAllSocialPosts(){

		$platforms = [];

		$count = get_option('social_number_of_posts');

		if(is_null($count)){
			$count = 3;
		}
		
		if(get_option('social_instagram_show_flag')){
			$platforms[] = 'instagram';
		}
		if(get_option('social_facebook_show_flag')){
			$platforms[] = 'facebook';
		}
		if(get_option('social_twitter_show_flag')){
			$platforms[] = 'twitter';
		}
		if(get_option('social_linkedin_show_flag')){
			$platforms[] = 'linkedin';
		}

		$args = array(
		    'meta_query' => array(
		       array(
		           'key' => 'platform',
		           'value' => $platforms,
            	   'compare' => 'IN'
		       )
		   ),
		    'post_type' => 'little_social',
		    'post_status' => 'any',
		    'meta_key' => 'date',
            'orderby' => 'meta_value_num',
		    'posts_per_page'   => $count,
		);

		return get_posts($args);
	}

	public function getSocialModelsFromDb($type,$model, $getAll = false){

		if(!$getAll){
			$posts = $this->getSocialPosts($type);
		}else{
			$posts = $this->getAllSocialPosts();
		}

		foreach($posts as $post){
			$id = $post->ID;
			$date = get_post_meta( $id, 'date');

			if(isset($date[0])){
				$this->deleteIfOlderThanThreshold($id,$date[0]);
			}
			
		}
		
		return $this->socialMediaFactory->createSocialObjectsFromDB($posts,$model);
	}

	public function getNumberOfPosts($option){
		$count = get_option($option);

		if(!is_null($count)){
			return $count;
		}

		return 0;
	}

	/*

	Keep this - just in case we revert
	public function getAllSocialPosts(){

		$platforms = [];
		$count;

		if(get_option('social_instagram_show_flag')){
			$platforms[] = 'instagram';
			$count += $this->getNumberOfPosts('instagram_number_of_posts');
		}
		if(get_option('social_facebook_show_flag')){
			$platforms[] = 'facebook';
			$count += $this->getNumberOfPosts('facebook_number_of_posts');
		}
		if(get_option('social_twitter_show_flag')){
			$platforms[] = 'twitter';
			$count += $this->getNumberOfPosts('twitter_number_of_posts');
		}
		if(get_option('social_linkedin_show_flag')){
			$platforms[] = 'linkedin';
			$count += $this->getNumberOfPosts('linkedin_number_of_posts');
		}

		$args = array(
		    'meta_query' => array(
		       array(
		           'key' => 'platform',
		           'value' => $platforms,
            	   'compare' => 'IN'
		       )
		   ),
		    'post_type' => 'little_social',
		    'post_status' => 'any',
		    'meta_key' => 'date',
            'orderby' => 'meta_value_num',
		    'posts_per_page'   => $count,
		);

		return get_posts($args);
	}*/


	public function addSocialPosts($posts,$type = ''){

		$insta = (strcasecmp($type, 'instagram') == 0);

		foreach($posts as $post){

			if($insta){
				$this->addInstagramPost($post);
			}else{
				$this->addSocialPost($post);
			}
			
		}
	}


	/*
		Reponse from insta differs quite a lot from others, so have bespoke function to add data to db
	*/
	public function addInstagramPost($socialPost){
		try{
			$newSocialPost = array(
			  'post_title'    => $socialPost->getId(),
			  'post_type'	  => $this->postType
			);	

			$id = $this->insertPostIfNotExists($socialPost->getId());
			
			update_post_meta($id, 'platform', $socialPost->getType());
			update_post_meta($id, 'link', $socialPost->getLink());
			update_post_meta($id, 'caption', $socialPost->getCaption());
			update_post_meta($id, 'date', $socialPost->getDate());
			update_post_meta($id, 'userId', $socialPost->getUserId());
			update_post_meta($id, 'userName', $socialPost->getUserName());
			update_post_meta($id, 'userScreenName', $socialPost->getUserScreenName());
			update_post_meta($id, 'userUrl', $socialPost->getUserUrl());
			update_post_meta($id, 'tags', $socialPost->getTags());
			update_post_meta($id, 'likes', $socialPost->getLikes());
			update_post_meta($id, 'thumnails', $socialPost->getThumbnails());//serialize($socialPost->getThumbnails()));
			update_post_meta($id, 'low_res', $socialPost->getLowRes());
			update_post_meta($id, 'standard_res', $socialPost->getStandardRes());
			update_post_meta($id, 'videos', $socialPost->getVideos());
			update_post_meta($id, 'comments', $socialPost->getComments());
				
			$this->deleteIfOlderThanThreshold($id,$socialPost->getDate());

		}catch(Exception $e){
			error_log(print_r($e, true));
		}

	}

	function insertPostIfNotExists($title){
		$postId = post_exists($title);

		//check if post exists
		if($postId != 0){
			$id = $postId;
		}else{
			// Insert the post into the database

			$newSocialPost = array(
			  'post_title'    => $title,
			  'post_type'	  => $this->postType
			);
			
			$id = wp_insert_post($newSocialPost);
		}

		return $id;
	}

	public function deleteIfOlderThanThreshold($id, $date){
		$deletePropertyRes = $this->helpers->checkIfSocialPostOlderThanKeepThreshold($date);

		if($deletePropertyRes){
			wp_delete_post( $id , true );
		}
	}

	// public function checkIfSocialPostOlderThanKeepThreshold($socialPostDate){
	// 	$isOlderThanThreshold = false;

	// 	$numberOfDays = get_option('days_to_keep_social_posts');

	// 	if(empty($numberOfDays) || is_null($numberOfDays)){
	// 		return false;
	// 	}

	// 	$now = new DateTime();

	// 	$lastUpdatedFormatted =  date('Y-m-d H:i:s',$socialPostDate);
	// 	$last = new DateTime($lastUpdatedFormatted);

	// 	if($last->diff($now)->days > $numberOfDays) {
	// 	   $isOlderThanThreshold = true;
	// 	}

	// 	return $isOlderThanThreshold;
	// }

	public function addSocialPost($socialPost){

		try{
			$newSocialPost = array(
			  'post_title'    => $socialPost->getId(),
			  'post_type'	  => $this->postType
			);
			 
			$platform = $socialPost->getType();
			$id = $this->insertPostIfNotExists($socialPost->getId());

			update_post_meta($id, 'platform', $platform);
			update_post_meta($id, 'link', $socialPost->getLink());
			update_post_meta($id, 'content', $socialPost->getContent());
			update_post_meta($id, 'date', $socialPost->getDate());
			update_post_meta($id, 'userScreenName', $socialPost->getUserScreenName());
			update_post_meta($id, 'userId', $socialPost->getUserId());

			//if not linkedin - general for facebook and twitter
			if(strcasecmp($platform, 'linkedin') != 0){		
				update_post_meta($id, 'userName', $socialPost->getUserName());			
				update_post_meta($id, 'userUrl', $socialPost->getUserUrl());
			}

			//if linkedin
			if(strcasecmp($platform, 'linkedin') == 0){
				update_post_meta($id, 'likes', $socialPost->getLikes());
				update_post_meta($id, 'eyebrowurl', $socialPost->getEyebrowUrl());
				update_post_meta($id, 'description', $socialPost->getDescription());
				update_post_meta($id, 'imageUrl', $socialPost->getImageUrl());
				update_post_meta($id, 'thumbnailUrl', $socialPost->getThumbnailUrl());
			}
				

			//if facebook
			if(strcasecmp($platform, 'facebook') == 0){
				update_post_meta($id, 'title', $socialPost->getTitle());
				update_post_meta($id, 'likes', $socialPost->getLikes());
				update_post_meta($id, 'fb_post_type', $socialPost->getPostType());
				update_post_meta($id, 'source', $socialPost->getVideo());

				if(!is_null($socialPost->getDescription())){
					update_post_meta($id, 'description', $socialPost->getDescription());
				}

				update_post_meta($id, 'profile_pic', $socialPost->getProfilePic());
				
			}
			

			//if tweet 
			if(strcasecmp($platform, 'twitter') == 0){
				update_post_meta($id, 'hashtags', $socialPost->getHashtags());
				update_post_meta($id, 'contentImage', $socialPost->getContentImage());
				update_post_meta($id, 'retweeted', $socialPost->getRetweeted());
			}

			$this->deleteIfOlderThanThreshold($id,$socialPost->getDate());
			
		}catch(Exception $e){
			error_log(print_r($e, true));
		}

	}

}
