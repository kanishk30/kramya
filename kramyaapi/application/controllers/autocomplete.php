<?php
include(APPPATH.'libraries/REST_Controller.php');

class Autocomplete extends REST_Controller
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

		// to autocomplete locations
		function location_get()
			{
				$autocomplete_location=[];
				$this->db->distinct();
				$this->db->select('city,country');
				$this->db->from('hospitals'); 
				$query = $this->db->get()->result();

				$count=0;
				foreach($query as $row){
					$autocomplete_location[$count]['city']=$row->city;
					$autocomplete_location[$count]['country']=$row->country;
					++$count;
				}	
				//$cities=array(1=>$cities);
				$this->response($autocomplete_location,200);

			}
		// to auto complete disease	
		function disease_get()
			{
				$autocomplete_disease=[];
				$this->db->select('disease_name,disease_id');
				$this->db->from('diseases');
				$query=$this->db->get()->result();

				$count=0;
				foreach ($query as $row) {
				$autocomplete_disease[$count]['dis']=$row->disease_name;
				$autocomplete_disease[$count]['id']=$row->disease_id;	
				++$count;
				}
				$this->response($autocomplete_disease,200);
			}	


	}
?>