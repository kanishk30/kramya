<?php
include(APPPATH.'libraries/REST_Controller.php');

class Login extends REST_Controller
	{
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

		// function to retrive users specific data
		private function get_user_data($user_email)
			{
				$this->db->select('user_id,password,email_act,user_img');
				$this->db->from('users');
				$this->db->where('email_id',$user_email);
				$result=$this->db->get()->row();
				return $result;
			}

		private function  get_hospital_data($hospital_email)
			{
				$this->db->select('hospital_id,hospital_password,email_act');
				$this->db->from('hospitals');
				$this->db->where('hospital_emailid',$hospital_email);
				$result=$this->db->get()->row();
				return $result;
			}	

		// function for user login
		public function users_post()
			{
				
				$data=$this->get_user_data($this->post('email'));
				$user_password=$this->post('password');
				if($data==[]){
					$this->response(array(1=>'You are not a registered user'),404);
				}
				else{
					// checking for password correction
					if($user_password==$data->password){
						// checking for account activation
						if($data->email_act==1){
							// creating user session
							$this->load->library('JWT');
							// selecting user key
							$this->db->select('api_key');
							$this->db->from('keys');
							$this->db->where('api_user_id',$data->user_id);
							$res=$this->db->get()->row();
							$USER_KEY=$res->api_key;
							$USER_SECRET=$data->password;
							$CONSUMER_TTL = 86400;

							$jwt= $this->jwt->encode(array(
						      'userKey'=>$USER_KEY,
						      'userId'=>$data->user_id,
						      'userImage'=>$data->user_img,
						       'role'=>'user',
						      'issuedAt'=>date(DATE_ISO8601, strtotime("now")),
						      'ttl'=>$CONSUMER_TTL
						    ), $USER_SECRET);
						  $this->response(array("jwt"=>$jwt),200);  
						 // header("jwt:".$jwt);

						}
						else{
							$this->response(array(1=>'Your account is not activated'),401);	
						}
					}
					else{
						$this->response(array(1=>'WRONG PASSWORD'),401);
					}

				}

			}

		//function for hospital login
		public function hospitals_post()
			{
				$data=$this->get_hospital_data($this->post('email'));
				$hospital_password=$this->post('password');
				// check for existence of hospital
				if($data==[]){
					$this->response(array(1=>'You are not a registered user'),404);
				}
				else{
					// checking for password correction
					if($hospital_password==$data->hospital_password){
						// checking for account activation
						if($data->email_act==1){
							// creating hospital session
							$this->load->library('session');
							$newdata = array(
        					'hospitalname'  => $data->hospital_id,
        					'hospitalemail'     => $this->post('email'),
        					'hospital_logged_in' => TRUE
							);
						$this->session->set_userdata($newdata);
						print_r('Hello '.$_SESSION['hospitalname']);
						}
						else{
							$this->response(array(1=>'Your account is not activated'),401);	
						}
					}
					else{
						$this->response(array(1=>'WRONG PASSWORD'),401);
					}

				}
			}
	}
?>