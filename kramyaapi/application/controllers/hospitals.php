<?php
	include(APPPATH.'libraries/REST_Controller.php');
	class Hospitals extends REST_Controller{
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

		private function provide_get_by_values($row_name , $val){
			$this->db->select('*'); 
			$this->db->from('hospitals');
			$this->db->where($row_name, $val); 
	      	$query = $this->db->get()->result();
			return $query;
		}

		private function upload_image($name){
			//print_r($_FILES);
			mkdir('./uploads/hospitals/'.$name,777);
			$errors=array();
			$files= $_FILES;
			$config=array(
				'upload_path'=>'./uploads/hospitals/'.$name,
				'allowed_types'=>'jpg|png|PNG|JPG|GIF|gif',
				);
			$this->load->library('upload', $config);
			$file_count=count($_FILES['hospital_img']['name']);
			for($i = 0; $i < $file_count; $i++){

			    // Overwrite the default $_FILES array with a single file's data
			    // to make the $_FILES array consumable by the upload library
	    		$ext = end((explode(".", $files['hospital_img']['name'][$i])));
				$file_name = $name.'_'.$i.".".$ext;
			    $_FILES['hospital_img']['name'] = $file_name ;
			    $_FILES['hospital_img']['type'] = $files['hospital_img']['type'][$i];
			    $_FILES['hospital_img']['tmp_name'] = $files['hospital_img']['tmp_name'][$i];
			    $_FILES['hospital_img']['error'] = $files['hospital_img']['error'][$i];
			    $_FILES['hospital_img']['size']  = $files['hospital_img']['size'][$i];

			    if($this->upload->do_upload('hospital_img')){
			       // print_r($this->upload->data());
			    }
			    else{
			        array_push($errors,$this->upload->display_errors());
			    }
			} 
			if($errors!=NULL)
				return $errors;
			else{
			$file_url='http://kramya.com/kramyaapi/uploads/hospitals/'.$name;
			return $file_url;
			}
		}

		public function index_get(){
			if($this->get('id')){
			$query=$this->provide_get_by_values('hospital_index' , $this->get('id'));}
			else if($this->get('hospital_id')){
				$query=$this->provide_get_by_values('hospital_id' , $this->get('hospital_id'));
			}
	        
			else{
	      	$query = $this->db->get('hospitals')->result();
			}
			//Sending Proper responce
			if($query == []){
	      	$this->response('No Record Found', 404);
	       }
	      else{
	      	$result=[];
	      	foreach ($query as $row){
	      		$result['hospital_id']=$row->hospital_id;
	      		$result['hospital_name']=$row->hospital_name;
	      		$result['is_medical_store']=$row->is_medical_store;
	      		$result['is_medi_travel_insurance']=$row->is_medi_travel_insurance;
	      		$result['is_health_insurance_coordination']=$row->is_health_insurance_coordination;
	      		$result['is_rehabilitation']=$row->is_rehabilitation;
	      		$result['is_translation_services']=$row->is_translation_services;
	      		$result['is_interpreter_services']=$row->is_interpreter_services;
	      		$result['is_airport_pickup']=$row->is_airport_pickup;
	      		$result['is_local_transportation_booking']=$row->is_local_transportation_booking;
	      		$result['is_hotel_booking']=$row->is_hotel_booking;
	      		$result['is_flight_booking']=$row->is_flight_booking;
	      		$result['is_free_wifi']=$row->is_free_wifi;
	      		$result['is_phone_in_the_room']=$row->is_phone_in_the_room;
	      		$result['is_tv_in_the_room']=$row->is_tv_in_the_room;
	      		$result['is_special_dietary_requests_accepted']=$row->is_special_dietary_requests_accepted;
	      		$result['is_private_rooms_for_patients_available']=$row->is_private_rooms_for_patients_available;
	      		$result['is_family_accommodation']=$row->is_family_accommodation;
	      		$result['is_parking_available']=$row->is_parking_available;
	      		$result['is_restaurant']=$row->is_restaurant;
	      		$result['is_cafe']=$row->is_cafe;
	      		$result['is_document_legalisation']=$row->is_document_legalisation;
	      		$result['is_religious_facilities']=$row->is_religious_facilities;
	      		$result['is_safe_in_the_room']=$row->is_safe_in_the_room;
	      		$result['is_international_newspapers']=$row->is_international_newspapers;
	      		$result['hospital_image']=$row->hospital_image;
	      		$img_arr=[];
	      			if($result['hospital_image']!=''){
	      				$path='./uploads/hospitals/'.$result['hospital_id'].'/';
								$dir=opendir($path);
								while($img=readdir($dir)){
									if($img=='.' || $img=='..'|| $img=='Thumbs.db')
										continue;
									//print_r($img);
									$img_path=$result['hospital_image'].'/'.$img;
									array_push($img_arr,$img_path);
									
								}
								//print_r($img_arr);
								$result['hospital_image']=$img_arr;
								closedir($dir);
	      			}

	      		$result['is_dedicated_dept']=$row->is_dedicated_dept;
	      		$result['first_street_add']=$row->first_street_add;
	      		$result['second_street_add']=$row->second_street_add;
	      		$result['city']=$row->city;
	      		$result['state']=$row->state;
	      		$result['country']=$row->country;
	      		$result['pincode']=$row->pincode;
	      		$result['latitude']=$row->latitude;
	      		$result['longitude']=$row->longitude;
	      		$result['procedure_supported']=$row->procedure_supported;
	      		$result['specialisation_area']=$row->specialisation_area;
	      		$result['special_packages']=$row->special_packages;
	      		$result['hospital_long_desc']=$row->hospital_long_desc;
	      		$result['patients_served']=$row->patients_served;
	      		$result['count_specialisation']=$row->count_specialisation;
	      		$result['count_doctors']=$row->count_doctors;
	      		$result['count_nurses']=$row->count_nurses;
	      		$result['count_beds']=$row->count_beds;
	      		$result['since']=$row->since;
	      		$result['hospital_rating']=$row->rating;
	      	}


	      	
				$this->response($result,200);
			}
		}

		// for posting hospital data
		public function index_post(){
			//print_r($_POST);
			$check=$this->provide_get_by_values('hospital_id' , $this->post('hospital_id'));
			// print_r($check);
			if($check){
			$this->response(array(1=>'ID ALready Exists!'),409);
			}
			else{
				$img_path=$this->upload_image($this->post('hospital_id'));
				print_r($img_path);
				if(is_array($img_path))
					$img_path="";
				
				$data = array(
			        'hospital_id' => $this->post('hospital_id'),
			        'hospital_name' => $this->post('hospital_name'),
			        'hospital_password' => $this->post('hospital_password'),
			        'hospital_emailid' => $this->post('hospital_emailid'),
			        'contact_person'=>$this->post('contact_person'),
			        'hospital_contactno'=>$this->post('hospital_contactno'),
			        'website'=>$this->post('website'),
			        'is_medical_store'=>$this->post('is_medical_store'),
			        'is_medi_travel_insurance'=>$this->post('is_medi_travel_insurance'),
			        'is_health_insurance_coordination'=>$this->post('is_health_insurance_coordination'),
			        'is_rehabilitation'=>$this->post('is_rehabilitation'),
			        'is_translation_services'=>$this->post('is_translation_services'),
			        'is_interpreter_services'=>$this->post('is_interpreter_services'),
			        'is_airport_pickup'=>$this->post('is_airport_pickup'),
			        'is_local_transportation_booking'=>$this->post('is_local_transportation_booking'),
			        'is_hotel_booking'=>$this->post('is_hotel_booking'),
			        'is_flight_booking'=>$this->post('is_flight_booking'),
			        'is_free_wifi'=>$this->post('is_free_wifi'),
			        'is_phone_in_the_room'=>$this->post('is_phone_in_the_room'),
			        'is_tv_in_the_room'=>$this->post('is_tv_in_the_room'),
			        'is_special_dietary_requests_accepted'=>$this->post('is_special_dietary_requests_accepted'),
			        'is_private_rooms_for_patients_available'=>$this->post('is_private_rooms_for_patients_available'),
			        'is_family_accommodation'=>$this->post('is_family_accommodation'),
			        'is_parking_available'=>$this->post('is_parking_available'),
			        'is_restaurant'=>$this->post('is_restaurant'),
			        'is_cafe'=>$this->post('is_cafe'),
			        'is_document_legalisation'=>$this->post('is_document_legalisation'),
			        'is_religious_facilities'=>$this->post('is_religious_facilities'),
			        'is_safe_in_the_room'=>$this->post('is_safe_in_the_room'),
			        'is_international_newspapers'=>$this->post('is_international_newspapers'),
			        'hospital_image' => $img_path,
			        'is_dedicated_dept' => $this->post('is_dedicated_dept'),
			        'dept_head_name' => $this->post('dept_head_name'),
			        'dept_head_email' => $this->post('dept_head_email'),
			        'dept_head_contact' => $this->post('dept_head_contact'),
			        'first_street_add' => $this->post('first_street_add'),
			        'second_street_add' => $this->post('second_street_add'),
			        'city' => $this->post('city'),
			        'state' => $this->post('state'),
			        'country' => $this->post('country'),
			        'pincode' => $this->post('pincode'),
			        'latitude'=>$this->post('latitude'),
			        'longitude'=>$this->post('longitude'),
			        'procedure_supported'=>$this->post('procedure_supported'),
			        'specialisation_area'=>$this->post('specialisation_area'),
			        'special_packages'=>$this->post('special_packages'),
			        'hospital_sort_desc'=>$this->post('hospital_sort_desc'),
			        'hospital_long_desc'=>$this->post('hospital_long_desc'),
			        'patients_served'=>$this->post('patients_served'),
			        'count_beds'=>$this->post('count_beds'),
			        'count_specialisation'=>$this->post('count_specialisation'),
			        'count_doctors'=>$this->post('count_doctors'),
			        'count_nurses'=>$this->post('count_nurses'),
			        'since'=>$this->post('since')
				);
				print_r($data);
			$dbRet=$this->db->insert('hospitals', $data);
				//FULL LOG IN CASE OF QUERY FAILURE
				if( !$dbRet ){
				   $db = (array)get_instance()->db;
				   print_r($db);
				}
				/*$response=$this->post('hospital_name').' has Been Registered';
				$this->response(array(1=>$response),201);*/
			}
		}

		//for updating:put
		public function index_put(){
			//print_r("data=".$key);
			//print_r('abc='.$this->put('hospital_id'));
			$check=$this->provide_get_by_values('hospital_id' , $this->put('hospital_id'));
			 
			if($check){
				//$this->response(array(1=>'ID ALready Exists!'),409);
				$data=array(
		          'hospital_name' => $this->put('hospital_name'),
			        'hospital_desc' => $this->put('hospital_desc'), 
			        'hospital_image' => $this->put('hospital_image'),
			        'hospital_location' => $this->put('hospital_location'),
			        'hospital_emailid' => $this->put('hospital_emailid'),
			        'hospital_password' => $this->put('hospital_password'),
			        'hospital_contactno'=>$this->put('hospital_contactno')
						);
				$refined=[];
				foreach($data as $key=>$value){
					if($data[$key]!=NULL){
						$refined[$key]=$value;
					}
				}
				$this->db->where('hospital_id', $this->put('hospital_id'));
				$this->db->update('hospitals',$refined); 
				$this->response(array(1=>'Records Updated Successfully'),200);
			}
			else{
				$this->response(array(1=>'No Records Found with this username'),404);
			}
		}
	}

?>