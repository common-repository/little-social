<?php

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/little-social-media-scheduler.php';

class SocialMediaActivator {

	protected $scheduler;

	public function __construct(){
		$this->scheduler = new SocialMediaScheduler();
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function activate() {
		$this->scheduler->addSocialScheduleEvent();
	}


}
