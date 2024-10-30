<?php

class SocialMediaUpdater {

	public function __construct(){

	}

	public function updateSocialMedia() {
		
		
		
	}

	
	function twitter_get_token() {



		$twitter = [
			'key' => get_option('social_twitter_api_key'),
			'secret' => get_option('social_twitter_api_secret')
		];


		//$bearer_token_credential = 'EDbQt3R1B3ayccvVFpAzM4bgm:48d6kOvdoJ9x7vPRNmJZngqOjNGu3UREegygZgDHQoWCCb6euI';
        $credentials = base64_encode(  $twitter['key'] . ':' . $twitter['secret'] );

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

	function twitter_get() {

		$accessToken = $this->twitter_get_token();
		$username = get_option('social_twitter_username');

		$headers = [
			'Authorization: Bearer ' . $accessToken
		];

		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, 'https://api.twitter.com/1.1/statuses/user_timeline.json?count=5&screen_name=' . $username );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

		$res = curl_exec( $ch );
		curl_close( $ch );

		return json_decode( $res );

	}

}
