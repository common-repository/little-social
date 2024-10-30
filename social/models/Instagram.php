<?php

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/SocialPost.php';

class Instagram extends SocialPost {

	protected $tags;
	protected $thumbnails;
	protected $standardRes;
	protected $lowRes;
	protected $videos;
	protected $comments;
	protected $caption;
	protected $location;

	public function setTags($tags){
		$this->tags = $tags;
	}	

	public function setThumbnails($thumbnails){
		$this->thumbnails = $thumbnails;
	}	

	public function setLowRes($lowRes){
		$this->lowRes = $lowRes;
	}	

	public function setStandardRes($standardRes){
		$this->standardRes = $standardRes;
	}	

	public function setComments($comments){
		$this->comments = $comments;
	}	

	public function setVideos($videos){
		$this->videos = $videos;
	}	

	public function setCaption($caption){
		$this->caption = $caption;
	}	

	public function setLocation($location){
		$this->location = $location;
	}


	public function getTags(){
		return $this->tags;
	}

	public function getComments(){
		return $this->comments;
	}

	public function getVideos(){
		return $this->videos;
	}

	public function getCaption(){
		return $this->caption;
	}

	public function getLocation(){
		return $this->location;
	}

	public function getThumbnails(){
		return $this->thumbnails;
	}	

	public function getLowRes(){
		return $this->lowRes;
	}	

	public function getStandardRes(){
		return $this->standardRes;
	}	

	public function daysOld(){

		$diff;
		$age;
		
		if(!is_null($this->getDate())){
			$postDate = new DateTime();
			$postDate->setTimestamp($this->getDate());

			$now = new DateTime;
			
			$diff = date_diff($now, $postDate);

			$days = $diff->days;
			
			if($diff->days == 0){

				if($diff->h == 0){
					//minutes
					
					if($diff->i <= 2){
						$age = 'just now';
					}else{
						$age = $diff->i . ' minutes ago';			
					}

					

				}else{
					//hours 
					$hours = $diff->h;

					if($hours < 2){
						$age = $hours . ' hour ago';
					}else{
						$age = $hours . ' hours ago';		
					}
				}

			}else{
				//days

				$days = $diff->days;

				if($days < 2){
					$age = $days . ' day ago';
				}else{
					$age = $days . ' days ago';	
				}

				
			}

		}
		
		return $age;
	}
}
