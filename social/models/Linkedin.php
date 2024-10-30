<?php

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/SocialPost.php';

class Linkedin extends SocialPost {

	protected $eyebrowUrl;
	protected $description;
	protected $imageUrl;
	protected $thumbnailUrl;
	protected $title;

	public function getEyebrowUrl(){
		return $this->eyebrowUrl;
	}

	public function getDescription(){
		return $this->description;
	}

	public function getImageUrl(){
		return $this->imageUrl;
	}

	public function getThumbnailUrl(){
		return $this->thumbnailUrl;
	}

	public function getTitle(){
		return $this->title;
	}	

	/*
		Setters
	*/

	public function setEyebrowUrl($eyebrowUrl){
		$this->eyebrowUrl = $eyebrowUrl;
	}

	public function setDescription($description){
		$this->description = $description;
	}

	public function setImageUrl($imageUrl){
		$this->imageUrl = $imageUrl;
	}

	public function setThumbnailUrl($thumbnailUrl){
		$this->thumbnailUrl = $thumbnailUrl;
	}

	public function setTitle($title){
		$this->title = $title;
	}	

	public function getLinkedinUrl(){
		return get_option('social_linkedin_page_url');
	}


	/*
	public function daysOld(){

		$diff;
		$age;
		
		if(!is_null($this->getDate())){
			$postDate = new DateTime();

			//linkedin timestamp in milliseconds so divide by 1000
			$postDate->setTimestamp($this->getDate()/1000);

			$now = new DateTime;
			
			$diff = date_diff($now, $postDate);

			$days = $diff->days;
			
			if($diff->days == 0){

				if($diff->h == 0){
					//minutes
					$age = $diff->i . ' minutes ago';
				}else{
					//hours 
					$age = $diff->h . ' hours ago';
				}

			}else{
				//days
				$age = $diff->days . ' days ago';
			}

		}
		
		return $age;
	}*/
}
