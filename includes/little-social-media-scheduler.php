<?php

class SocialMediaScheduler {

	public function __construct(){
		
	}
	
	public function addSocialScheduleEvent(){
		wp_clear_scheduled_hook('little_social_cron_job');

		if ( ! wp_next_scheduled( 'little_social_cron_job' ) ) {

			$frequency = get_option('social_cron_frequency');
			
			if(empty($frequency) || is_null($frequency)){
				$frequency = '5min';
			}

			wp_schedule_event(time(), $frequency, 'little_social_cron_job');
		}	
	}

	public function removeSocialScheduleEvent(){
		wp_clear_scheduled_hook('little_social_cron_job');
	}


}
