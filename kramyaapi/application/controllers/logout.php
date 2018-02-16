<?php
include(APPPATH.'libraries/REST_Controller.php');

class Logout extends REST_Controller
	{
		// logging out user
		
		function __construct() {

	    header('Access-Control-Allow-Origin: *');
	    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
	    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	    $method = $_SERVER['REQUEST_METHOD'];
	    if($method == "OPTIONS") {
	        die();
    	}
    	parent::__construct();
		}
		public function users_post()
		{
			$this->load->library('session');
			
			if($this->session->has_userdata('username'))
			{
				//print_r($_SESSION);
				unset(
					$_SESSION['username'],
					$_SESSION['useremail'],
					$_SESSION['user_logged_in']
					);
				//print_r($_SESSION);
				$this->response(array(1=>'Successfully logged out'),200);
			}
			
		}
		// logging hospitals out
		public function hospitals_post()
		{
			$this->load->library('session');
			
			if($this->session->has_userdata('hospitalname'))
			{
				//print_r($_SESSION);
				unset(
					$_SESSION['hospitalname'],
					$_SESSION['hospitalemail'],
					$_SESSION['hospital_logged_in']
					);
				$this->response(array(1=>'Successfully logged out'),200);
				//print_r($_SESSION);
			}
			

		}


	}
?>