<?php

class Helpers{

	public function checkIfSocialPostOlderThanKeepThreshold($socialPostDate){
		$isOlderThanThreshold = false;

		$numberOfDays = get_option('days_to_keep_social_posts');

		if(empty($numberOfDays) || is_null($numberOfDays)){
			return false;
		}

		$now = new DateTime();
		
		$lastUpdatedFormatted =  date('Y-m-d H:i:s',$socialPostDate);
		$last = new DateTime($lastUpdatedFormatted);

		if($last->diff($now)->days > $numberOfDays) {
		   $isOlderThanThreshold = true;
		}

		return $isOlderThanThreshold;
	}

}