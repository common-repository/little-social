<?php

require plugin_dir_path( __FILE__ ) . '/models/Linkedin.php';


class LinkedinConnector extends SocialConnector{


	public function __construct(){
		$key = '';
		$secret = '';
		$username = get_option('social_linkedin_username');
		
		parent::__construct($key,$secret,$username);
	}


	public function getLatestLinkedInPosts(){
		$accessToken = get_option('social_linkedin_access_token');
		
		$apiUrl = 'https://api.linkedin.com/v1/companies/'.$this->username.'/updates?format=json&oauth2_access_token=' . $accessToken;
		$linkedInPosts = $this->getLatestPosts($apiUrl,[]);
		return $this->createLinkedinObjectsFromApi($linkedInPosts->values,"Linkedin","linkedin");

	}


	public function getLatestPosts($url,$args){
		$response = wp_remote_get( $url, $args );
		return json_decode($response['body']);

	}
}
