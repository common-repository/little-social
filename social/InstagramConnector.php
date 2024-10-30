<?php

require plugin_dir_path( __FILE__ ) . '/models/Instagram.php';


class InstagramConnector extends SocialConnector{


	public function __construct(){
		$key = '';
		$secret = '';
		$username = '';
		
		parent::__construct($key,$secret,$username);
	}


	public function getLatestInstaPosts(){
		$accessToken = get_option('social_instagram_access_token');

		$apiUrl = 'https://api.instagram.com/v1/users/self/media/recent/?access_token=' . $accessToken;
		$instaPosts = $this->getLatestPosts($apiUrl,[]);
		
		//make sure success
		if($instaPosts->meta->code == 200){
			foreach($instaPosts->data as $post){
				if(!is_null($post->caption->text)){
					$post->caption->text = $this->removeEmoji($post->caption->text);
				}
			}
			return $this->createInstagramObjectsFromApi($instaPosts->data,"Instagram","instagram");
		}
	}



}
