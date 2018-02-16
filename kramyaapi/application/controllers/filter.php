<?php
include(APPPATH.'libraries/REST_Controller.php');

class Filter extends REST_Controller
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

		private function get_by_value($filters)
		{
			$final_filters=[];

			$this->db->select('hospitals.hospital_index,hospitals.hospital_id,hospitals.hospital_name,hospitals.hospital_image,hospitals.hospital_sort_desc,hospitals.count_beds,hospitals.count_doctors,hospitals.latitude,hospitals.longitude,hospitals.count_nurses,hospitals.count_specialisation,hospitals.since,hospitals.rating,hospitals.city,hospitals.state,hospitals.country,hospitals.hospital_long_desc');
			$this->db->from('hospitals');
			$this->db->join('hospital_disease_filter','hospital_disease_filter.hospital_id=hospitals.hospital_id','inner');
			foreach ($filters as $key => $value){
				if($value!=NULL)
					$final_filters[$key]=$value;
			}

			$this->db->where($final_filters);
			$result=$this->db->get()->result();
			//print_r($result);
			return $result;

		}

		public function search_get()
		{
			$filters=array(
			'disease_id'=> $this->get('disease'),
			'city'=>$this->get('location'),
			);				
			$result=$this->get_by_value($filters);
			// print_r($result);
			$final_hospitals=array();
			if($result!=NULL){
				$data_counter=0;
				$search_results=[];
				foreach($result as $row){
					$final_hospitals['id']=$row->hospital_index;
					$final_hospitals['hospital_id']=$row->hospital_id;
					$final_hospitals['hospital_name']=$row->hospital_name;
					$final_hospitals['hospital_rating']=$row->rating;
					$final_hospitals['hospital_image']=$row->hospital_image;
					$img_arr=[];
					// obtaining hospital image
					if($final_hospitals['hospital_image']!=''){
							$path='./uploads/hospitals/'.$final_hospitals['hospital_id'].'/';
							// print_r($path);	
							$dir=opendir($path);
							while($img=readdir($dir)){
								if ($img == '.' or $img == '..') continue;	
							// print_r($img);
								$img_path=$final_hospitals['hospital_image'].'/'.$img;
								array_push($img_arr,$img_path);
							}
							$final_hospitals['hospital_image']=$img_arr;
							closedir($dir);
						}
					$final_hospitals['beds']=$row->count_beds;
					$final_hospitals['doctors']=$row->count_doctors;
					$final_hospitals['nurses']=$row->count_nurses;
					$final_hospitals['hospital_short_desc']=$row->hospital_sort_desc;
					$final_hospitals['specialisation_count']=$row->count_specialisation;
					$final_hospitals['since']=$row->since;
					$final_hospitals['latitude']=$row->latitude;
					$final_hospitals['longitude']=$row->longitude;
					$final_hospitals['city']=$row->city;
					$final_hospitals['state']=$row->state;
					$final_hospitals['country']=$row->country;

					$search_results[$data_counter]=$final_hospitals;
					$data_counter++;
				}
				//print_r($search_results);
			$this->response($search_results,200);
			}
			else{
				$this->response(array('error'=>'NO RESULTS FOUND'),201);
			}


		}
			
			

	}
?>
