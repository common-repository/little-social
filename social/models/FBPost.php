<?php

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/SocialPost.php';

class FBPost extends SocialPost {

	protected $title;
	protected $video;
	protected $postType;

	public function setTitle($title){
		$this->title = $title;
	}		

	public function setVideo($video){
		$this->video = $video;
	}	

	public function setPostType($postType){
		$this->postType = $postType;
	}

	public function getTitle(){
		return $this->title;
	}	

	public function getVideo(){
		return $this->video;
	}	

	public function getPostType(){
		return $this->postType;
	}

	public function getFacebookPostUrl(){
		$ids = explode('_', $this->getId());

		$userId = $ids[0];
		$postId = $ids[1];
		
		return 'https://www.facebook.com/' . $userId . '/posts/' . $postId;
	}

}
