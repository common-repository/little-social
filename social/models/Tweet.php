<?php

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/SocialPost.php';

class Tweet extends SocialPost {

	protected $hashtags;
	protected $retweeted = false;

	public function setHashtags($hashtags){
		$this->hashtags = $hashtags;
	}	

	public function getHashtags(){
		return $this->hashtags;
	}

	public function setRetweeted($retweeted){
		$this->retweeted = $retweeted;
	}

	public function getRetweeted(){
		return $this->retweeted;
	}

	public function getTweetUrl(){
		$screenname = get_option('social_twitter_username');
		return 'https://twitter.com/' . $screenname . '/status/' . $this->getId();
	}

	public function getTwitterUrl(){
		$screenname = get_option('social_twitter_username');
		return 'https://twitter.com/' . $screenname;
	}
}
