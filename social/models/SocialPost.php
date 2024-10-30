<?php

class SocialPost {

	protected $id;
	protected $type;
	protected $link;
	protected $content;
	protected $date;
	protected $userId;
	protected $userName;
	protected $userScreenName;
	protected $userUrl;
	protected $likes;
	protected $profilePic;
	protected $description;
	protected $contentImage;

	public function setId($id){
		$this->id = $id;
	}

	public function setType($type){
		$this->type = $type;
	}

	public function setLink($link){
		$this->link = $link;
	}

	public function setContent($content){
		$this->content = $content;
	}

	public function setDate($date){
		$this->date = $date;
	}

	public function setUserId($userId){
		$this->userId = $userId;
	}

	public function setUserName($userName){
		$this->userName = $userName;
	}

	public function setUserScreenName($userScreenName){
		$this->userScreenName = $userScreenName;
	}

	public function setUserUrl($userUrl){
		$this->userUrl = $userUrl;
	}

	public function setLikes($likes){
		$this->likes = $likes;
	}

	public function setProfilePic($profilePic){
		$this->profilePic = $profilePic;
	}

	public function setContentImage($contentImage){
		$this->contentImage = $contentImage;
	}

	public function setDescription($description){
		$this->description = $description;
	}

	public function getId(){
		return $this->id;
	}

	public function getType(){
		return $this->type;
	}

	public function getLink(){
		return $this->link;
	}

	public function getContent(){
		return $this->content;
	}

	public function getDate(){
		return $this->date;
	}

	public function getUserId(){
		return $this->userId;
	}

	public function getUserName(){
		return $this->userName;
	}

	public function getUserScreenName(){
		return $this->userScreenName;
	}

	public function getUserUrl(){
		return $this->userUrl;
	}

	public function getLikes(){
		return $this->likes;
	}

	public function getProfilePic(){
		return $this->profilePic;
	}

	public function getContentImage(){
		return $this->contentImage;
	}

	public function getDescription(){
		return $this->description;
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
