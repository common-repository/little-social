<?php

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/little-social-media-scheduler.php';

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
class SocialAdmin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */


	private $page;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->page = 'little_social_media';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/social-admin.js', array( 'jquery' ), $this->version, false );

	}


	// Load the plugin admin page partial.
	public function create_social_media_settings_content() {
	    //require_once plugin_dir_path( __FILE__ ). 'partials/little-social-media-admin-display.php';

		// check user capabilities
	    if (!current_user_can('manage_options')) {
	        return;
	    }
	 
	    // add error/update messages
	 
	    // check if the user have submitted the settings
	    // wordpress will add the "settings-updated" $_GET parameter to the url
	    if (isset($_GET['settings-updated'])) {
	        // add settings saved message with the class of "updated"
	        add_settings_error('wporg_messages', 'wporg_message', __('Settings Saved', $this->page), 'updated');


	        //if settings have been changed - reset the cron with new value
	        $scheduler = new SocialMediaScheduler();
	        $scheduler->addSocialScheduleEvent();

	    }
	 	

	    if( isset( $_GET[ 'tab' ] ) ) {
		    $active_tab = $_GET[ 'tab' ];
		}else{
			$active_tab = 'general';
		}

	    // show error/update messages
	    settings_errors('wporg_messages');
	    ?>
	    <div class="wrap">
	    	<h1><?= esc_html(get_admin_page_title()); ?></h1>

		    <h2 class="nav-tab-wrapper">
	            <a href="?page=<?= $this->page ?>&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">General settings</a>
	            <a href="?page=<?= $this->page ?>&tab=facebook" class="nav-tab <?php echo $active_tab == 'facebook' ? 'nav-tab-active' : ''; ?>">Facebook</a>
	            <a href="?page=<?= $this->page ?>&tab=twitter" class="nav-tab <?php echo $active_tab == 'twitter' ? 'nav-tab-active' : ''; ?>">Twitter</a>
	            <a href="?page=<?= $this->page ?>&tab=instagram" class="nav-tab <?php echo $active_tab == 'instagram' ? 'nav-tab-active' : ''; ?>">Instagram</a>
	            <a href="?page=<?= $this->page ?>&tab=linkedin" class="nav-tab <?php echo $active_tab == 'linkedin' ? 'nav-tab-active' : ''; ?>">Linkedin</a>
				<a href="?page=<?= $this->page ?>&tab=errors" class="nav-tab <?php echo $active_tab == 'errors' ? 'nav-tab-active' : ''; ?>">Errors</a>
	        </h2>
	        
	        
			<form id="little_social_media_settings" action="options.php" method="post" <?php if(strcasecmp($active_tab, 'errors') == 0) {?> style="display:none" <?php } ?>>
	            <?php

	            if( strcasecmp($active_tab, 'general' ) == 0 ) {
	            	settings_fields('general_social_settings_section');
	            	do_settings_sections('general_social_settings_section');
	            }else if( strcasecmp($active_tab, 'facebook' ) == 0 ) {
	            	settings_fields('facebook_settings_section');
	            	do_settings_sections('facebook_settings_section');
	            }else if( strcasecmp($active_tab, 'twitter' ) == 0 ) {
	            	 settings_fields('twitter_settings_section');
	            	 do_settings_sections('twitter_settings_section');
	            }else if( strcasecmp($active_tab, 'instagram' ) == 0 ) {
	            	settings_fields('instagram_settings_section');
	            	do_settings_sections('instagram_settings_section');
	            }else if( strcasecmp($active_tab, 'linkedin' ) == 0 ) {
	            	settings_fields('linkedin_settings_section');
	            	do_settings_sections('linkedin_settings_section');
	            }

	            // output setting sections and their fields	            
	            
	            // output save settings button
	            submit_button('Save Settings');
	            ?>
	        </form>
				
			<?php if(strcasecmp($active_tab, 'errors') == 0) { 
				$this->social_errors_display();
			} ?>

	    </div>
	    <?php

	}

	public function create_social_media_menu_item( ) {

		add_menu_page(
	        'LITTLE Social',
	        'LITTLE Social',
	        'manage_options',
	        $this->page,
	        array( $this, 'create_social_media_settings_content' )
	    );
	}

	function create_social_settings(){

		 add_settings_section(
	        'general_social_settings_section_id',         // ID used to identify this section and with which to register options
	        'General settings',                  // Title to be displayed on the administration page
	        null, // Callback used to render the description of the section
	        'general_social_settings_section'                        // Page on which to add this section of options
	    );

	     add_settings_section(
	        'twitter_settings_section_id',         // ID used to identify this section and with which to register options
	        'Twitter details',                  // Title to be displayed on the administration page
	        null, // Callback used to render the description of the section
	       'twitter_settings_section'                      // Page on which to add this section of options
	    );


	     add_settings_section(
	        'facebook_settings_section_id',         // ID used to identify this section and with which to register options
	        'Facebook details',                  // Title to be displayed on the administration page
	        null, // Callback used to render the description of the section
	        'facebook_settings_section'                         // Page on which to add this section of options
	    );

	     add_settings_section(
	        'instagram_settings_section_id',         // ID used to identify this section and with which to register options
	        'Instagram details',                  // Title to be displayed on the administration page
	        null, // Callback used to render the description of the section
	        'instagram_settings_section'    
	    );

	    add_settings_section(
	        'linkedin_settings_section_id',         // ID used to identify this section and with which to register options
	        'LinkedIn details',                  // Title to be displayed on the administration page
	        null, // Callback used to render the description of the section
	        'linkedin_settings_section'    
		);
		
		

	   
		$this->create_social_settings_fields();
		$this->register_social_settings();
	    
	    
	}

	function social_section_callback() {
	    echo '<p>Set social api keys.</p>';
	}

	function twitter_section_callback() {
	    echo '<p>Set social api keys.</p>';
	}

	function register_social_settings(){
		//general

		register_setting("general_social_settings_section", "social_random_posts_show_flag");
	    register_setting("general_social_settings_section", "social_number_of_posts");
	    
	    register_setting("general_social_settings_section", "facebook_number_of_posts");
	    register_setting("general_social_settings_section", "linkedin_number_of_posts");
	    register_setting("general_social_settings_section", "twitter_number_of_posts");
	    register_setting("general_social_settings_section", "instagram_number_of_posts");

	    register_setting("general_social_settings_section", "social_cron_frequency");
	    register_setting("general_social_settings_section", "days_to_keep_social_posts");

	    //twitter
	    register_setting("twitter_settings_section", "social_twitter_api_key");
	    register_setting("twitter_settings_section", "social_twitter_api_secret");
	    register_setting("twitter_settings_section", "social_twitter_username");
	    register_setting("twitter_settings_section", "social_twitter_show_flag");
	    register_setting("twitter_settings_section", "social_twitter_show_retweet_flag");
	    register_setting("twitter_settings_section", "social_twitter_show_media_flag");
	    register_setting("twitter_settings_section", "social_twitter_profile_image");

	    //facebook
	    register_setting("facebook_settings_section", "social_facebook_api_secret");
	    register_setting("facebook_settings_section", "social_facebook_api_key");
	    register_setting("facebook_settings_section", "social_facebook_username");
	    register_setting("facebook_settings_section", "social_facebook_show_flag");
	    register_setting("facebook_settings_section", "social_facebook_show_media_flag");
	    register_setting("facebook_settings_section", "social_facebook_profile_image");

	    //instagram
	    register_setting("instagram_settings_section", "social_instagram_client_id");
	    register_setting("instagram_settings_section", "social_instagram_redirect_url");
	    register_setting("instagram_settings_section", "social_instagram_access_token");
	    register_setting("instagram_settings_section", "social_instagram_access_token_link");    
	    register_setting("instagram_settings_section", "social_instagram_show_flag");
	    register_setting("instagram_settings_section", "social_instagram_show_media_flag");
	    register_setting("instagram_settings_section", "social_instagram_profile_image");

	    //linkedin
	    register_setting("linkedin_settings_section", "social_linkedin_show_flag");
	    register_setting("linkedin_settings_section", "social_linkedin_username");
	    register_setting("linkedin_settings_section", "social_linkedin_access_token");
	    register_setting("linkedin_settings_section", "social_linkedin_page_url");
	    register_setting("linkedin_settings_section", "social_linkedin_show_media_flag");
		register_setting("linkedin_settings_section", "social_linkedin_profile_image");
		
		
	    
	}	

	function create_social_settings_fields(){

		/* GENERAL Fields */

		add_settings_field(
	        'social_random_posts_show_flag',
	        'Compiled Feed',
	        array( $this, 'social_random_posts_show_flag_callback' ),
	        'general_social_settings_section',
	        'general_social_settings_section_id'
	    );

		add_settings_field(
	        'social_number_of_posts',
	        'Number of Posts to Show',
	        array( $this, 'social_number_of_posts_callback' ),
	        'general_social_settings_section',
	        'general_social_settings_section_id'
	    );

		if(get_option('social_facebook_show_flag')){
		    add_settings_field(
		        'facebook_number_of_posts',
		        'Number of Facebook Posts to Show',
		        array( $this, 'facebook_number_of_posts_callback' ),
		        'general_social_settings_section',
		        'general_social_settings_section_id'
		    );
		}

		if(get_option('social_twitter_show_flag')){
		    add_settings_field(
		        'twitter_number_of_posts',
		        'Number of Twitter Posts to Show',
		        array( $this, 'twitter_number_of_posts_callback' ),
		        'general_social_settings_section',
		        'general_social_settings_section_id'
		    );
		}

		if(get_option('social_linkedin_show_flag')){
		    add_settings_field(
		        'linkedin_number_of_posts',
		        'Number of Linkedin Posts to Show',
		        array( $this, 'linkedin_number_of_posts_callback' ),
		        'general_social_settings_section',
		        'general_social_settings_section_id'
		    );
		}

		if(get_option('social_instagram_show_flag')){
		    add_settings_field(
		        'instagram_number_of_posts',
		        'Number of Instagram Posts to Show',
		        array( $this, 'instagram_number_of_posts_callback' ),
		        'general_social_settings_section',
		        'general_social_settings_section_id'
		    );
		}

	    add_settings_field(
	        'social_cron_frequency',
	        'Cron Frequency',
	        array( $this, 'social_cron_frequency_callback' ),
	        'general_social_settings_section',
	        'general_social_settings_section_id'
	    );

	    add_settings_field(
	        'days_to_keep_social_posts',
	        'Number of days to keep posts in Database',
	        array( $this, 'days_to_keep_social_posts_callback' ),
	        'general_social_settings_section',
	        'general_social_settings_section_id'
	    );
	    

		/* FACEBOOK Fields */

		add_settings_field(
	        'social_facebook_show_flag',
	        'Show Facebook',
	        array( $this, 'social_facebook_show_flag_callback' ),
	        'facebook_settings_section',
	        'facebook_settings_section_id'
	    );

	    add_settings_field(
	        'social_facebook_show_media_flag',
	        'Show Media',
	        array( $this, 'social_facebook_show_media_flag_callback' ),
	        'facebook_settings_section',
	        'facebook_settings_section_id'
	    );

		add_settings_field(
	        'social_facebook_api_key',
	        'Facebook API',
	        array( $this, 'social_facebook_key_callback' ),
	        'facebook_settings_section',
	        'facebook_settings_section_id'
	    );

		add_settings_field(
	        'social_facebook_api_secret',
	        'Facebook Secret',
	        array( $this, 'social_facebook_secret_callback' ),
	        'facebook_settings_section',
	        'facebook_settings_section_id'
	    );

	    add_settings_field(
	        'social_facebook_username',
	        'Facebook Username',
	        array( $this, 'social_facebook_username_callback' ),
	        'facebook_settings_section',
	        'facebook_settings_section_id'
	    );

	    add_settings_field(
	        'social_facebook_profile_image',
	        'Profile Image',
	        array( $this, 'social_facebook_profile_image_callback' ),
	        'facebook_settings_section',
	        'facebook_settings_section_id'
	    );
	    

	    /* TWITTER Fields */
	    
	    add_settings_field(
	        'social_twitter_show_flag_callback',
	        'Show twitter',
	        array( $this, 'social_twitter_show_flag_callback' ),
	        'twitter_settings_section',
	        'twitter_settings_section_id'
	    );

	    add_settings_field(
	        'social_twitter_show_retweet_flag',
	        'Show retweets',
	        array( $this, 'social_twitter_show_retweet_flag_callback' ),
	        'twitter_settings_section',
	        'twitter_settings_section_id'
	    );
	    
	    add_settings_field(
	        'social_twitter_show_media_flag',
	        'Show Media',
	        array( $this, 'social_twitter_show_media_flag_callback' ),
	        'twitter_settings_section',
	        'twitter_settings_section_id'
	    );

	    add_settings_field(
	        'social_twitter_api_key',
	        'Twitter API',
	        array( $this, 'social_twitter_key_callback' ),
	        'twitter_settings_section',
	        'twitter_settings_section_id'
	    );

	    add_settings_field(
	        'social_twitter_api_secret',
	        'Twitter Secret',
	        array( $this, 'social_twitter_secret_callback' ),
	       'twitter_settings_section',
	        'twitter_settings_section_id'
	    );

	    add_settings_field(
	        'social_twitter_username',
	        'Twitter Username',
	        array( $this, 'social_twitter_username_callback' ),
	        'twitter_settings_section',
	        'twitter_settings_section_id'
	    );

	    add_settings_field(
	        'social_twitter_profile_image',
	        'Profile Image',
	        array( $this, 'social_twitter_profile_image_callback' ),
	        'twitter_settings_section',
	        'twitter_settings_section_id'
	    );

	    /* INSTAGRAM fields */

	    add_settings_field(
	        'social_instagram_show_flag',
	        'Show insta',
	        array( $this, 'social_instagram_show_flag_callback' ),
	        'instagram_settings_section',
	        'instagram_settings_section_id'
	    );
	    
	    add_settings_field(
	        'social_instagram_show_media_flag',
	        'Show Media',
	        array( $this, 'social_instagram_show_media_flag_callback' ),
	        'instagram_settings_section',
	        'instagram_settings_section_id'
	    );

	    add_settings_field(
	        'social_instagram_client_id',
	        'Client ID',
	        array( $this, 'social_instagram_client_id_callback' ),
	        'instagram_settings_section',
	        'instagram_settings_section_id'
	    );

	    add_settings_field(
	        'social_instagram_redirect_url',
	        'Redirect URL',
	        array( $this, 'social_instagram_redirect_url_callback' ),
	        'instagram_settings_section',
	        'instagram_settings_section_id'
	    );
	    
	    add_settings_field(
	        'social_instagram_access_token_link',
	        'Access token',
	        array( $this, 'social_instagram_access_token_link_callback' ),
	        'instagram_settings_section',
	        'instagram_settings_section_id'
	    );

	    add_settings_field(
	        'social_instagram_access_token',
	        'Instagram Access Token',
	        array( $this, 'social_instagram_access_token_callback' ),
	        'instagram_settings_section',
	        'instagram_settings_section_id'
	    );

	    add_settings_field(
	        'social_instagram_profile_image',
	        'Profile Image',
	        array( $this, 'social_instagram_profile_image_callback' ),
	        'instagram_settings_section',
	        'instagram_settings_section_id'
	    );

	    /*
			LinkedIn
	    */

		add_settings_field(
	        'social_linkedin_show_flag',
	        'Show Linkedin',
	        array( $this, 'social_linkedin_show_flag_callback' ),
	        'linkedin_settings_section',
	        'linkedin_settings_section_id'
	    );

	    add_settings_field(
	        'social_linkedin_show_media_flag',
	        'Show Media',
	        array( $this, 'social_linkedin_show_media_flag_callback' ),
	        'linkedin_settings_section',
	        'linkedin_settings_section_id'
	    );

	    add_settings_field(
	        'social_linkedin_username',
	        'LinkedIn Username',
	        array( $this, 'social_linkedin_username_callback' ),
	        'linkedin_settings_section',
	        'linkedin_settings_section_id'
	    );

	    add_settings_field(
	        'social_linkedin_access_token',
	        'LinkedIn Access Token',
	        array( $this, 'social_linkedin_access_token_callback' ),
	        'linkedin_settings_section',
	        'linkedin_settings_section_id'
	    );

	    add_settings_field(
	        'social_linkedin_page_url',
	        'LinkedIn Page Url',
	        array( $this, 'social_linkedin_page_url_callback' ),
	        'linkedin_settings_section',
	        'linkedin_settings_section_id'
	    );
	    	
	    add_settings_field(
	        'social_linkedin_profile_image',
	        'Profile Image',
	        array( $this, 'social_linkedin_profile_image_callback' ),
	        'linkedin_settings_section',
	        'linkedin_settings_section_id'
		);
		
		

	}
	
	function social_random_posts_show_flag_callback(){
		$this->outputGeneralCheckboxSetting('social_random_posts_show_flag');
	}

	function facebook_number_of_posts_callback(){
	    // output the field
	    $this->outputInputSetting('facebook_number_of_posts','social-num-posts');
	}

	function twitter_number_of_posts_callback(){
	    // output the field
	    $this->outputInputSetting('twitter_number_of_posts','social-num-posts');
	}

	function instagram_number_of_posts_callback(){
	    // output the field
	    $this->outputInputSetting('instagram_number_of_posts','social-num-posts');
	}

	function linkedin_number_of_posts_callback(){
	    // output the field
	    $this->outputInputSetting('linkedin_number_of_posts','social-num-posts');
	}

	function social_number_of_posts_callback(){
	    // output the field
	    $this->outputInputSetting('social_number_of_posts','random-posts');
	}

	function social_cron_frequency_callback(){
		$this->outputCronSelectSetting('social_cron_frequency');
	}

	function days_to_keep_social_posts_callback(){
	    $this->outputInputSetting('days_to_keep_social_posts');
	}

	public function social_linkedin_show_flag_callback(){
		$this->outputCheckboxSetting('social_linkedin_show_flag');
	}

	public function social_linkedin_username_callback(){
		$this->outputInputSetting('social_linkedin_username');
	}

	public function social_linkedin_access_token_callback(){
		$this->outputInputSetting('social_linkedin_access_token');
	}

	public function social_linkedin_page_url_callback(){
		$this->outputInputSetting('social_linkedin_page_url');
	}

	public function social_instagram_client_id_callback(){
		$this->outputInputSetting('social_instagram_client_id','insta-client');
	}

	public function social_instagram_redirect_url_callback(){
		$this->outputInputSetting('social_instagram_redirect_url','insta-redirect');
	}

	public function social_instagram_access_token_link_callback(){
		$redirectUrl = get_option('social_instagram_redirect_url');
		$clientId = get_option('social_instagram_client_id');

		?>
	    <a id="insta-token-link" href="https://api.instagram.com/oauth/authorize/?client_id=<?= $clientId; ?>&redirect_uri=<?= $redirectUrl; ?>&response_type=token" target="_blank">Get access token</a>
	    <?php
	}

	function social_instagram_access_token_callback(){
	    // output the field
	    $this->outputInputSetting('social_instagram_access_token');
	}

	function social_instagram_show_flag_callback(){
		$this->outputCheckboxSetting('social_instagram_show_flag');
	}
	
	function social_facebook_key_callback(){
	    // output the field
	    $this->outputInputSetting('social_facebook_api_key');
	}

	function social_facebook_secret_callback(){
	    // output the field
	    $this->outputInputSetting('social_facebook_api_secret');
	}

	function social_facebook_username_callback(){
	    // output the field
	    $this->outputInputSetting('social_facebook_username');
	}

	function social_facebook_show_flag_callback(){
		$this->outputCheckboxSetting('social_facebook_show_flag');
	}

	function social_twitter_key_callback(){
	    // output the field
	    $this->outputInputSetting('social_twitter_api_key');
	}

	function social_twitter_secret_callback(){
	    // output the field
	    $this->outputInputSetting('social_twitter_api_secret');
	}

	function social_twitter_username_callback(){
	    // output the field
	    $this->outputInputSetting('social_twitter_username');
	}	

	function social_twitter_show_flag_callback(){
		$this->outputCheckboxSetting('social_twitter_show_flag');
	}

	function social_twitter_show_retweet_flag_callback(){
		$this->outputCheckboxSetting('social_twitter_show_retweet_flag');
	}


	/*
		Show media callbacks
	*/

	function social_facebook_show_media_flag_callback(){
		$this->outputCheckboxSetting('social_facebook_show_media_flag');
	}

	function social_twitter_show_media_flag_callback(){
		$this->outputCheckboxSetting('social_twitter_show_media_flag');
	}

	function social_instagram_show_media_flag_callback(){
		$this->outputCheckboxSetting('social_instagram_show_media_flag');
	}

	function social_linkedin_show_media_flag_callback(){
		$this->outputCheckboxSetting('social_linkedin_show_media_flag');
	}


	/* 
		Profile image callbacks
	*/

	function social_facebook_profile_image_callback(){
		$this->outputInputSetting('social_facebook_profile_image');
	}

	function social_twitter_profile_image_callback(){
		$this->outputInputSetting('social_twitter_profile_image');
	}

	function social_instagram_profile_image_callback(){
		$this->outputInputSetting('social_instagram_profile_image');
	}

	function social_linkedin_profile_image_callback(){
		$this->outputInputSetting('social_linkedin_profile_image');
	}

	function social_errors_display() {
		if(file_exists(plugin_dir_path( dirname( __FILE__ ) ) . 'social/social_errors.txt')) {
		?>
			<textarea readonly style="height:500px; width:100%"><?=file_get_contents(plugin_dir_path( dirname( __FILE__ ) ) . 'social/social_errors.txt')?>
			</textarea>
			
		<?php }

		else { ?>
			<textarea readonly style="height:500px; width:100%">There are no errors</textarea>
		<?php }
	}
	function outputInputSetting($settingName, $classes = ''){
		$setting = get_option($settingName);

		?>
	    <input class="social-input <?= $classes; ?>" type="text" name="<?= $settingName; ?>" value="<?= isset($setting) ? esc_attr($setting) : ''; ?>">
	    <?php
	}

	function outputCronSelectSetting($settingName, $classes = ''){
		$setting = get_option($settingName);

		?>
	    <select class="<?= $classes; ?>" name="<?= $settingName; ?>">
            <!--<option <?= ($setting == '1min' ) ? ' selected' : '';?> value="1min">1 min</option>-->
            <option <?= ($setting == '5min' ) ? ' selected' : '';?> value="5min">5 mins</option>
            <option <?= ($setting == '30min' ) ? ' selected' : '';?> value="30min" >30 mins</option>
            <option <?= ($setting == 'hourly' ) ? ' selected' : '';?> value="hourly" >Hourly</option>
            <option <?= ($setting == 'daily' ) ? ' selected' : '';?> value="daily" >Daily</option>
        </select>
	    <?php
	}

	function outputCheckboxSetting($settingName){
		$setting = get_option($settingName);

		?>
	    <input class="social-checkbox" type="checkbox" name="<?= $settingName; ?>" value="1" <?php checked( '1', $setting ); ?>">
	    <?php
	}

	function outputGeneralCheckboxSetting($settingName){
		$setting = get_option($settingName);

		?>
	    <input type="checkbox" class="random-post-checkbox" name="<?= $settingName; ?>" value="1" <?php checked( '1', $setting ); ?>">
	    <?php
	}

}
