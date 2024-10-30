<?php

/**
 * Plugin Name:  LITTLE Social
 * Description: Control all social media from one place
 * Author: Little Agency
 * Author URI: wearelittle.agency
 * Version: 1.4.3
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
*	This sets bespoke frequencies for cron jobs
*/
function social_cron_schedules($schedules){
    if(!isset($schedules["5min"])){
        $schedules["5min"] = array(
            'interval' => 5*60,
            'display' => __('Once every 5 minutes'));
    }
    if(!isset($schedules["30min"])){
        $schedules["30min"] = array(
            'interval' => 30*60,
            'display' => __('Once every 30 minutes'));
    }
    if(!isset($schedules["1min"])){
        $schedules["1min"] = array(
            'interval' => 5*60,
            'display' => __('Once every minute (no longer supported, reverts to 5mins)'));
    }

    return $schedules;
}
add_filter('cron_schedules','social_cron_schedules');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/little-social-media-activator.php';
	$activator = new SocialMediaActivator();
	$activator->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/little-social-media-deactivator.php';
	$deactivator = new SocialMediaDeactivator();
	$deactivator->deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/little-social-media-class.php';

require plugin_dir_path( __FILE__ ) . 'includes/little-social-media-updater.php';

require plugin_dir_path( __FILE__ ) . 'social/SocialConnector.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function social() {

	$social = new SocialMedia();
	$social->run();

}

social();

function getSocialPostsByPlatform(){
	$socialRepo = new SocialRepository();
	
	$socialPosts = [

		'twitter' => $socialRepo->getSocialModelsFromDb('twitter','Tweet'),
		'facebook' => $socialRepo->getSocialModelsFromDb('facebook','FBPost'),
		'instagram' => $socialRepo->getSocialModelsFromDb('instagram','Instagram'),
		'linkedin' => $socialRepo->getSocialModelsFromDb('linkedin','Linkedin'),
	];

	return $socialPosts;
}
	
//just gets any platform - newest first
function getAllSocialPosts(){
	$socialRepo = new SocialRepository();

	return $socialRepo->getSocialModelsFromDb('','',true);
}

function getSocialPosts(){

	if(get_option('social_random_posts_show_flag')){
		return getAllSocialPosts();
	}else{
		return getSocialPostsByPlatform();
	}

}

function showTwitterMedia(){
	return get_option('social_twitter_show_media_flag');
}

function showFacebookMedia(){
	return get_option('social_facebook_show_media_flag');
}

function showInstagramMedia(){
	return get_option('social_instagram_show_media_flag');
}

function showLinkedinMedia(){
	return get_option('social_linkedin_show_media_flag');
}

function showRetweets(){
	return get_option('social_twitter_show_retweet_flag');
}

function getProfileImage($platform){
	$setting = 'social_'. $platform .'_profile_image';

	if(!empty($setting) && !is_null($setting)){
		return get_option($setting);
	}

	return '';
}

