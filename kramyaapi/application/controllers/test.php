<?php
	include(APPPATH.'libraries/REST_Controller.php');
	$data;
	class Test extends REST_Controller
		{

			public function index_get()
				{
					$data=array();
					$data=$this->Kramya_Model->get_user_info();
					// print_r($data);
					$this->response($data,200);
				}
		}

?>