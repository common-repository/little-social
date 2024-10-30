<?php

require plugin_dir_path( __FILE__ ) . 'models/FBPost.php';


class FacebookConnector extends SocialConnector{


	private $limit = 100;
	private $facebookUrl = 'https://graph.facebook.com/USERNAME/posts?fields=id,message,created_time,picture,likes,type,permalink_url,source,link,description,from&limit=';

	public function __construct(){
		$key = get_option('social_facebook_api_key');
		$secret = get_option('social_facebook_api_secret');
		$username = get_option('social_facebook_username');
		parent::__construct($key,$secret,$username);
		$this->replaceUsernameInUrl();
	}


	public function replaceUsernameInUrl(){
		$this->facebookUrl = str_replace("USERNAME", $this->username, $this->facebookUrl);
	}

	public function getLatestFbPosts(){

       
        $accessToken = $this->key . '|' . $this->secret;

		$apiUrl = $this->facebookUrl. $this->limit .'&access_token='.$accessToken;

		$fbPosts = $this->getLatestPosts($apiUrl,[]);
		
		//make sure no errors
		if(!property_exists($fbPosts, 'error')){	

			foreach($fbPosts->data as $post){
				if(property_exists($post, 'message')){
					$post->message = $this->removeEmoji($post->message);
				}		
			}

			return $this->createFacebookObjectsFromApi($fbPosts->data,"FBPost","facebook");
		}
		
	}


	public function createFbObjects($fbReponsePosts){

		$fbPosts = [];

		foreach($fbReponsePosts as $post){
			
			$fbpost = new FBPost();
			$fbpost->setId($post->id);
			$fbpost->setType('facebook');
			$fbpost->setLink($post->link);
			$fbpost->setTitle($post->name);
			$fbpost->setContent($post->message);
			$fbpost->setDate($post->created_time);
			$fbpost->setUserId($post->from->id);
			$fbpost->setUserName($post->from->name);
			$fbpost->setUserScreenName($post->from->name);
			$fbPosts[] = $fbpost;
		}
		
		return $fbPosts;
	}

}
