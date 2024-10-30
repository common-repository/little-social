<?php


class SocialMediaFactory {


	/*
		Generic - can be used by fb and twitter
	*/
	public function createSocialObjectsFromApi($response,$model,$type){


		$posts = [];
	
		try{
			foreach($response as $responseObj){
				
				$post = new $model;
				$post->setId($responseObj->id);
				$post->setType($type);
				$post->setContent($responseObj->text);
				
				$post->setUserId($responseObj->user->id);
				$post->setUserName($responseObj->user->name);
				$post->setUserScreenName($responseObj->user->screen_name);
				$post->setUserUrl($responseObj->user->url);


				if(strcasecmp('twitter', $type) == 0){
					$post->setHashtags($responseObj->entities->hashtags);

					//image
					if(property_exists($responseObj->entities, 'media') && isset($responseObj->entities->media[0])){
						$post->setContentImage($responseObj->entities->media[0]->media_url_https);
					}

					if(property_exists($responseObj, 'retweeted_status')){
						//was retweeted
						$post->setRetweeted(true);
					}

				}

				//need to convert to unix timestamp so can order properly
				$postDate = $this->convertDateToUnixTimestamp($responseObj->created_at);
				$post->setDate($postDate);


				$posts[] = $post;
			}
		}catch(Exception $e){
			error_log(print_r($e, true));
		}
		
		return $posts;
	}

	public function convertDateToUnixTimestamp($createdDate){
		$dateObj = new DateTime($createdDate);
		$postDate = $createdDate;

		if($dateObj)
		{
		    $postDate = $dateObj->format('Y-m-d H:i:s');
		}
		$postDate = strtotime($postDate);
		return $postDate;
	}


	public function createFacebookObjectsFromApi($response,$model,$type){
		
		$posts = [];

		try{
			foreach($response as $responseObj){
				
				$post = new $model;
				$post->setId($responseObj->id);
				
				if(property_exists($responseObj, 'type')){
					$post->setType($type);
					$post->setPostType($responseObj->type);
				}

				if(property_exists($responseObj, 'likes')){
					$post->setLikes($responseObj->likes);
				}
				
				if(property_exists($responseObj, 'message')){
					$post->setContent($responseObj->message);
				}
				
				if(property_exists($responseObj, 'link')){
					$post->setLink($responseObj->link);
				}

				if(property_exists($responseObj, 'source')){
					$post->setVideo($responseObj->source);
				}
				

				//need to convert to unix timestamp so can order properly
				$postDate = $this->convertDateToUnixTimestamp($responseObj->created_time);
				$post->setDate($postDate);

				if(property_exists($responseObj, 'description')){
					$post->setDescription($responseObj->description);
				}
				
				$post->setUserName($responseObj->from->name);
				$post->setUserScreenName($responseObj->from->name);
				$post->setUserId($responseObj->from->id);

				if(property_exists($responseObj, 'picture')){
					$post->setProfilePic($responseObj->picture);
				}
				

				
				$posts[] = $post;
			}
		}catch(Exception $e){
			error_log(print_r($e, true));
		}
		
		return $posts;
	}

	public function createLinkedinObjectsFromApi($response,$model,$type){


		$posts = [];

		try{
			foreach($response as $responseObj){
				
				$shareObj = $responseObj->updateContent->companyStatusUpdate->share;

				$post = new $model;
				$post->setId($shareObj->id);
				$post->setType($type);
				$post->setDate($responseObj->timestamp/1000);
				$post->setLikes($responseObj->likes);
				$post->setContent($shareObj->comment);
				$post->setLink($shareObj->content->shortenedUrl);
				$post->setEyebrowUrl($shareObj->content->eyebrowUrl);
				$post->setDescription($shareObj->content->description);
				$post->setImageUrl($shareObj->content->submittedImageUrl);
				$post->setThumbnailUrl($shareObj->content->thumbnailUrl);
				$post->setTitle($shareObj->content->title);
				$post->setUserScreenName($responseObj->updateContent->company->name);
				$post->setUserId($responseObj->updateContent->company->id);
				$posts[] = $post;

			}
		}catch(Exception $e){
			error_log(print_r($e, true));
		}
		
		return $posts;
	}

	public function createInstagramObjectsFromApi($response,$model,$type){


		$posts = [];

		try{
			foreach($response as $responseObj){
				$post = new $model;
				$post->setId($responseObj->id);
				$post->setType($type);
				$post->setLink($responseObj->link);
				$post->setThumbnails($responseObj->images->thumbnail);
				$post->setLowRes($responseObj->images->low_resolution);
				$post->setStandardRes($responseObj->images->standard_resolution);
				$post->setCaption($responseObj->caption);
				$post->setDate($responseObj->created_time);
				$post->setUserId($responseObj->user->id);
				$post->setUserName($responseObj->user->username);
				if(!is_null($responseObj->user)){
					if(property_exists($responseObj->user, 'url')){
						$post->setUserUrl($responseObj->user->url);
					}	
				}
				$post->setLikes($responseObj->likes);
				$post->setProfilePic($responseObj->user->profile_picture);
				$post->setComments($responseObj->comments);
				$post->setTags($responseObj->tags);
				$post->setLocation($responseObj->location);
				$posts[] = $post;
			}
		}catch(Exception $e){
			error_log(print_r($e, true));
		}
		
		return $posts;
	}


	public function createSocialObjectsFromDB($posts,$modelType){

		$models = [];

		try{
			foreach($posts as $post){

				$postMeta = get_post_meta($post->ID);
		
				if(!empty($postMeta)){				

					$socialModelType = $modelType; 

					//if were getting different platform posts at once we need to figure out what model to instantiate
					if(empty($socialModelType)){
						if(array_key_exists ( 'platform' , $postMeta ) && isset($postMeta['platform'][0])){
							$platform = $postMeta['platform'][0];

							if(strcasecmp($platform, "facebook") == 0){
								$socialModelType = "FBPost";
							}else if(strcasecmp($platform, "twitter") == 0){
								$socialModelType = "Tweet";
							}else if(strcasecmp($platform, "instagram") == 0){
								$socialModelType = "Instagram";
							}else if(strcasecmp($platform, "linkedin") == 0){
								$socialModelType = "Linkedin";
							}

						}
					}

					//check jsut to make sure model type isnt still empty
					if(!empty($socialModelType)){
						$model = new $socialModelType;

						$model->setId($post->post_title);

						if(array_key_exists ( 'platform' , $postMeta ) && isset($postMeta['platform'][0])){
							$model->setType($postMeta['platform'][0]);
						}

						if(array_key_exists ( 'link' , $postMeta ) && isset($postMeta['link'][0])){
							$model->setLink($postMeta['link'][0]);
						}

						if(array_key_exists ( 'content' , $postMeta ) && isset($postMeta['content'][0])){
							$model->setContent($postMeta['content'][0]);
						}

						if(array_key_exists ( 'contentImage' , $postMeta ) && isset($postMeta['contentImage'][0])){
							$model->setContentImage($postMeta['contentImage'][0]);
						}

						if(array_key_exists ( 'date' , $postMeta ) && isset($postMeta['date'][0])){
							$model->setDate($postMeta['date'][0]);
						}

						if(array_key_exists ( 'userId' , $postMeta ) && isset($postMeta['userId'][0])){
							$model->setUserId($postMeta['userId'][0]);
						}

						if(array_key_exists ( 'userName' , $postMeta ) && isset($postMeta['userName'][0])){
							$model->setUserName($postMeta['userName'][0]);
						}

						if(array_key_exists ( 'userScreenName' , $postMeta ) && isset($postMeta['userScreenName'][0])){
							$model->setUserScreenName($postMeta['userScreenName'][0]);
						}

						if(array_key_exists ( 'userUrl' , $postMeta ) && isset($postMeta['userUrl'][0])){
							$model->setUserUrl($postMeta['userUrl'][0]);
						}

						if(array_key_exists ( 'hashtags' , $postMeta )){
							$model->setHashtags($postMeta['hashtags']);
						}

						if(array_key_exists ( 'title' , $postMeta ) && isset($postMeta['title'][0])){
							$model->setTitle($postMeta['title'][0]);
						}

						if(array_key_exists ( 'fb_post_type' , $postMeta ) && isset($postMeta['fb_post_type'][0])){
							$model->setPostType($postMeta['fb_post_type'][0]);
						}

						if(array_key_exists ( 'source' , $postMeta ) && isset($postMeta['source'][0])){
							$model->setVideo($postMeta['source'][0]);
						}

						if(array_key_exists ( 'profile_pic' , $postMeta ) && isset($postMeta['profile_pic'][0])){
							$model->setProfilePic($postMeta['profile_pic'][0]);
						}

						/*
							Twitter
						*/

						if(array_key_exists ( 'retweeted' , $postMeta ) && isset($postMeta['retweeted'][0])){
							$model->setRetweeted($postMeta['retweeted'][0]);
						}


						/*
							Instagram only
						*/

						if(array_key_exists ( 'caption' , $postMeta ) && isset($postMeta['caption'][0])){
							$model->setCaption(unserialize($postMeta['caption'][0]));
						}

						if(array_key_exists ( 'location' , $postMeta ) && isset($postMeta['location'][0])){
							$model->setLocation($postMeta['location'][0]);
						}


						if(array_key_exists ( 'tags' , $postMeta ) && isset($postMeta['tags'][0])){
							$model->setTags(unserialize($postMeta['tags'][0]));
						}

						if(array_key_exists ( 'likes' , $postMeta ) && isset($postMeta['likes'][0])){
							$model->setLikes($postMeta['likes'][0]);
						}

						if(array_key_exists ( 'thumnails' , $postMeta ) && isset($postMeta['thumnails'][0])){
							$model->setThumbnails(unserialize($postMeta['thumnails'][0]));
						}

						if(array_key_exists ( 'low_res' , $postMeta ) && isset($postMeta['low_res'][0])){
							$model->setLowRes(unserialize($postMeta['low_res'][0]));
						}

						if(array_key_exists ( 'standard_res' , $postMeta ) && isset($postMeta['standard_res'][0])){
							$model->setStandardRes(unserialize($postMeta['standard_res'][0]));
						}

						if(array_key_exists ( 'videos' , $postMeta ) && isset($postMeta['videos'][0])){
							$model->setVideos(unserialize($postMeta['videos'][0]));
						}

						if(array_key_exists ( 'comments' , $postMeta ) && isset($postMeta['comments'][0])){
							$model->setComments($postMeta['comments'][0]);
						}

						/*
							linkedin only
						*/


						if(array_key_exists ( 'eyebrowurl' , $postMeta ) && isset($postMeta['eyebrowurl'][0])){
							$model->setEyebrowurl($postMeta['eyebrowurl'][0]);
						}

						if(array_key_exists ( 'description' , $postMeta ) && isset($postMeta['description'][0])){
							$model->setDescription($postMeta['description'][0]);
						}

						if(array_key_exists ( 'imageUrl' , $postMeta ) && isset($postMeta['imageUrl'][0])){
							$model->setImageUrl($postMeta['imageUrl'][0]);
						}

						if(array_key_exists ( 'thumbnailUrl' , $postMeta ) && isset($postMeta['thumbnailUrl'][0])){
							$model->setThumbnailUrl($postMeta['thumbnailUrl'][0]);
						}
					}

					//only add if postmeta wasnt empty
					$models[] = $model;

				}
				
				
			}
		}catch(Exception $e){
			error_log(print_r($e, true));
		}
	
		return $models;
	}

}
