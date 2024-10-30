<?php

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'social/TwitterConnector.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'social/FacebookConnector.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'social/InstagramConnector.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'social/LinkedinConnector.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'social/SocialRepository.php';

class SocialMediaGetter {

	protected $socialRepo;

	public function __construct(){
		$this->socialRepo = new SocialRepository();
	}

	public function insertPostsToDb($posts, $type = ''){

		//reverse so oldest posts are input first
		$posts = array_reverse($posts);
		$this->socialRepo->addSocialPosts($posts,$type);
	}

	public function getPostsFromTwitter(){
		if(get_option('social_twitter_show_flag')){
			$twitterConnector = new TwitterConnector();
			$posts = $twitterConnector->getLatestTweets();
			if(!is_null($posts)){
				$this->insertPostsToDb($posts);
			}
		}
	}


	public function getPostsFromFacebook(){
		if(get_option('social_facebook_show_flag')){
			$fbConnector = new FacebookConnector();
			$posts = $fbConnector->getLatestFbPosts();
			if(!is_null($posts)){
				$this->insertPostsToDb($posts);
			}	
		}
	}

	public function getPostsFromInstagram(){
		if(get_option('social_instagram_show_flag')){
			$instaConnector = new InstagramConnector();
			$posts = $instaConnector->getLatestInstaPosts();
			if(!is_null($posts)){
				$this->insertPostsToDb($posts,'instagram');
			}
		}
	}


	public function getPostsFromLinkedIn(){
		if(get_option('social_linkedin_show_flag')){
			$linkedInConnector = new LinkedinConnector();
			$posts = $linkedInConnector->getLatestLinkedInPosts();
			if(!is_null($posts)){
				$this->insertPostsToDb($posts,'linkedin');
			}
		}
	}

}
