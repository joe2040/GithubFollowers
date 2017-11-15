<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Follower extends CI_Controller {

	private $access_token;
	public function __construct()
	{
			parent::__construct();
			$this->config->load('github');
			$this->access_token = $this->config->item('access_token');
	}
	public function index()	{
		$this->load->view('public/follower');
	}
	public function curl_get_contents($url)
	{
	  $ch = curl_init();
	  curl_setopt($ch, CURLOPT_URL, $url);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	  curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	  $data = curl_exec($ch);
	  curl_close($ch);
	  return $data;
	}
	public function get_github_followers() {
		$username=$this->input->get('username');
		$page_id = $this->input->get('page_id');
		if(isset($page_id)) {
				$page_id = $_GET['page_id'];
		} else {
			$page_id = 1;
		}
    $per_page = 10;
    $github_data = json_decode($this->curl_get_contents('https://api.github.com/users/'.$username.'?access_token='.$this->access_token), true);
		if(isset($github_data['followers'])){
	    $github_followers_count = $github_data['followers'];
	    $followers = json_decode($this->curl_get_contents('https://api.github.com/users/'.$username.'/followers?access_token='.$this->access_token.'&per_page='.$per_page.'&page='.$page_id), true);
	    $json = [];
	    if($github_followers_count >0) {
	        $pages = ceil($github_followers_count/$per_page);
	        $json['status'] = 'true';
	        $json['count'] = $github_followers_count;
	        if($pages > $page_id){
	            $next = $page_id+1;
	        } else{
	          $next = 'false';
	        }
	        $json['next'] = $next;
					$json['page'] = $page_id;
	        $avatars = [];
	        foreach ($followers as $follower) {
	            $avatars[] =$follower['avatar_url'];
	        }
	        $json['avatars'] = $avatars;
	        $json['username'] = $username;
	    }
		} else {
				$json['status'] = 'false';
				$json['response'] = 'No followers found';
		}
		echo json_encode($json,TRUE);
	}
}
