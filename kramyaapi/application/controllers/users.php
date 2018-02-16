<?php
	include(APPPATH.'libraries/REST_Controller.php');
	class Users extends REST_Controller{
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
		
		// function to run queries based on id or username
		private function provide_get_by_values($row_name , $val){
			$this->db->select('*'); 
			$this->db->from('users');
			$this->db->where($row_name, $val); 
		  $query = $this->db->get()->result();
			return $query;
		}

		private function upload_image($img,$name){
			mkdir('./uploads/users/'.$name,777);
			$config=array(
				'upload_path'=>'./uploads/users/'.$name,
				'allowed_types'=>'jpg|png|JPG|PNG',
				'file_name'=>$name,
				'overwrite'=>true
				);
			$this->load->library('upload',$config);
			if($this->upload->do_upload($img))
			{
				$data = array('upload_data' => $this->upload->data());
				print_r($data);
				$file_path='http://192.168.69.15:8080/kramya-development/kramyaapi/uploads/users/'.$name.'/'.$data['upload_data']['file_name'];
				return $file_path;
			}
			else
			{
				$error = array('error' => $this->upload->display_errors());
				print_r($error);
				 return null;
			}
		}

		//get function for users
		public function index_get(){
			//calling function for id and username
			if($this->get('id')){
				$query=$this->provide_get_by_values('user_index' , $this->get('id'));
			}
			else if($this->get('username')){
				$query=$this->provide_get_by_values('user_id' , $this->get('username'));
			}
			// Main get 	
			else{
				$query = $this->db->get('users')->result();
			}

			// providing responce with proper errorcode
			if($query == []){
			$this->response((array(1=>'No Record Found')), 404);
		  }
		  else{
			$query=json_decode(json_encode($query),true);
			// feild implementation when ?feilds=usernam,id... are given
			if($this->get('feilds')){
				$feilds=explode(',' , $this->get('feilds'));
				//print_r($feilds);
				for($i=0;$i<count($query);$i++){
					for($j=0;$j<count($feilds);$j++)
						$responce_data[$i][$feilds[$j]]=$query[$i][$feilds[$j]];
				}
				$query=$responce_data;
					$this->response($query,200);
			}
				$this->response($query,200);
			}
		}

		public function index_post(){
			$check=$this->provide_get_by_values('user_id' , $this->post('user_id'));
			// print_r($check);
			if($check){
			$this->response(array(1=>'ID ALready Exists!'),409);
			}
			else{
				$img_path=$this->upload_image('user_img',$this->post('user_id'));
				if(!$img_path)
				$img_path="NULL";
				

				$data = array(
				'user_id' => $this->post('user_id'),
				'fullname' => $this->post('user_name'),
				'email_id' => $this->post('user_email_id'), 
				'password' => $this->post('user_password'),
				'user_img'=>$img_path,
				'user_location' => $this->post('user_location'),
				'contact_no'=>$this->post('user_contact_no'),
				);
				print_r($data);
				$dbRet=$this->db->insert('users', $data);
				//FULL LOG IN CASE OF QUERY FAILURE
				if( !$dbRet ){
				   $db = (array)get_instance()->db;
				   print_r($db);
				}

				//generating user key
				$key=$data['password'];
				$key_data=array(
				'api_key'=>$key,
				'level'=>1,	
				'date_created'=>date('Y-m-d H:i:s'),
				'api_user_id'=>$this->post('user_id'),
				);
				$keyRet=$this->db->insert('keys',$key_data);
				if( !$keyRet ){
				   $db = (array)get_instance()->db;
				   print_r($db);
				}

				//$this->response(array(1=>'You Have Been Registered'),201);
			}
		}


		public function index_put(){
			//print_r("data=".$key);
			//print_r('abc='.$this->put('user_id'));
			$check=$this->provide_get_by_values('user_id' , $this->put('user_id'));
			 
			if($check){
				//$this->response(array(1=>'ID ALready Exists!'),409);
				$data=array(
				'fullname' => $this->put('fullname'),
				'email_id' => $this->put('email_id'), 
				'password' => $this->put('password'),
				'user_location' => $this->put('user_location'),
				'contact_no'=>$this->put('contact_no')
						);
				$refined=[];
				foreach($data as $key=>$value){
					if($data[$key]!=NULL){
						$refined[$key]=$value;
					}
				}
				$this->db->where('user_id', $this->put('user_id'));
				$this->db->update('users',$refined); 
				$this->response(array(1=>'Records Updated Successfully'),200);
			}
			else{
				$this->response(array(1=>'No Records Found with this username'),404);
			}
		}


		public function index_delete(){


		}


		public function index_patch(){


		}

		// function to send email
		public function sendmail_get(){
			// config file for email
			$config=array(
			'useragent'=>'CodeIgniter',
			'protocol'=> 'smtp',
			'smtp_host' => 'ssl://smtp.zoho.com',
			'smtp_port' => 465,
			'smtp_user' => 'support@kramya.com',
			'smtp_pass' => 'huzoore@23',
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'wordwrap' => TRUE,
			'wrapchars'=>76,
			'validate'=>false,
			'newline'=>'\r\n',
			'crlf'=>'\r\n',
			'smtp_timeout'=>5,
			'priority'=>3,
			'bcc_batch_mode'=>TRUE,
			'bcc_batch_size'=>10,
			);

			// email body 
			$message="<h1>This is a test mail.Dont reply</h1>";
			$this->load->library('email');
			$this->email->initialize($config);
			$this->email->from('support@kramya.com'); // change it to yours
		    $this->email->to('abhivendrasingh@gmail.com');// change it to yours
		    $this->email->subject('Test Mail');
		    $this->email->message($message);

		    if($this->email->send()){
		       echo 'Email sent.';
		     }
		     else{
		      show_error($this->email->print_debugger());
		     }
		}
	}
?>