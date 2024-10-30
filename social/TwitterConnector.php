<?php

require plugin_dir_path( __FILE__ ) . '/models/Tweet.php';


class TwitterConnector extends SocialConnector{


	public function __construct(){
		$key = get_option('social_twitter_api_key');
		$secret = get_option('social_twitter_api_secret');
		$username = get_option('social_twitter_username');
		parent::__construct($key,$secret,$username);
	}

	public function getAuthToken(){

		$key = get_option('social_twitter_api_key');
		$secret = get_option('social_twitter_api_secret');

        $credentials = base64_encode(  $key . ':' . $secret );

		$args = array(
            'method' => 'POST',
            'httpversion' => '1.1',
            'blocking' => true,
            'headers' => array( 
                'Authorization' => 'Basic ' . $credentials,
                'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
            ),
            'body' => array( 'grant_type' => 'client_credentials' )
        );

        $response = wp_remote_post( 'https://api.twitter.com/oauth2/token', $args );
        $keys = json_decode($response['body']);

        return $keys->access_token;
	}

	public function getLatestTweets(){

		$accessToken = $this->getAuthToken();
		

		$args = array(
		    'httpversion' => '1.1',
		    'blocking' => true,
		    'headers' => array( 
		        'Authorization' => "Bearer $accessToken"
		    )
		);
		$apiUrl = "https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=$this->username";

		$tweets = $this->getLatestPosts($apiUrl,$args);
		foreach($tweets as $tweet){
			$tweet->text = $this->removeEmoji($tweet->text);
		}
		return $this->createSocialObjects($tweets,"Tweet","twitter");

	}
}
