<?php

require plugin_dir_path( __FILE__ ) . 'SocialMediaFactory.php';
require plugin_dir_path( __FILE__ ) . 'Logger.php';

class SocialConnector {

	protected $key;
	protected $secret;
	protected $username;
	protected $socialMediaFactory;

	public function __construct($key,$secret,$username){
		$this->key = $key;
		$this->secret = $secret;
		$this->username = $username;
		$this->socialMediaFactory = new SocialMediaFactory();
	}

	public function getLatestPosts($url,$args){

		
		try {
			$response = wp_remote_get($url, $args);

			if(is_wp_error($response)) {
				throw new Exception(json_encode($response));
			}
			

			//Let's check that the response is actually JSON and status is 200
			if($this->isJson($response['body']) && $response['response']['code'] === 200) {
				
				//Decode the JSON - Decodes as a stdClass, so access props with -> 
				$res = json_decode($response['body']);
				
				return $res;
			}

			else {
				new Logger($url. "-----".json_encode($response));
				return false;	
			}
		} catch(Exception $e) {
			
			new Logger($e->getMessage() . "----" . $url);
		}
	}

	public function createSocialObjects($response,$model,$type){
		return $this->socialMediaFactory->createSocialObjectsFromApi($response,$model,$type);
	}

	public function createFacebookObjectsFromApi($response,$model,$type){
		return $this->socialMediaFactory->createFacebookObjectsFromApi($response,$model,$type);
	}
	
	public function createInstagramObjectsFromApi($response,$model,$type){
		return $this->socialMediaFactory->createInstagramObjectsFromApi($response,$model,$type);
	}
	public function createLinkedinObjectsFromApi($response,$model,$type){
		return $this->socialMediaFactory->createLinkedinObjectsFromApi($response,$model,$type);
	}
		
	/*
		Helper function
	*/
	//https://stackoverflow.com/questions/43097087/how-to-remove-non-text-chars-from-string-php
	function removeEmoji($text){
	    $text=preg_replace('/[^ -\x{2122}]/u','',$text);  // no icons
		$text=trim(preg_replace('/(?<=\s)\s+/','',$text));  // no leading/trailing/multi spaces
		return $text;
	}

	public function isJson($str) {
	    $json = json_decode($str);
	    return $json && $str != $json;
	}

}
