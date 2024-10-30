<?php

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/little-social-media-scheduler.php';

class SocialMediaDeactivator {

	protected $scheduler;

	public function __construct(){
		$this->scheduler = new SocialMediaScheduler();
	}

	public function deactivate(){
		$this->scheduler->removeSocialScheduleEvent();
	}

}
