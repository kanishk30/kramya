<?php
include(APPPATH.'libraries/REST_Controller.php');
	class Reviews extends REST_Controller
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

			public function index_post()
				{
					$new_count=(string)$this->db->count_all('review')+1;
					$new_review_id="KRMRVW".$new_count;

					$review_data=array(
						'review_id'=>$new_review_id,
						'user_id'=>$this->post('user_id'),
						'hospital_id'=>$this->post('hospital_id'),
						'rating'=>$this->post('rating'),
						'comment'=>$this->post('comment'),
						'review_time'=>date(DATE_ISO8601, strtotime("now"))
						);

					$ret=$this->db->insert('review',$review_data);
						if(!$ret)
						{
							 $this->response(array(1=>"Sorry,System Error!!!"),500);
							//$db = (array)get_instance()->db;
				   			//print_r($db);
						}
						else
						{
							$this->db->select_sum('rating');
							$this->db->from('review');
							$this->db->where('hospital_id',$this->post('hospital_id'));
							$sum_rating=$this->db->get()->row();


							$this->db->where('hospital_id',$this->post('hospital_id'));
							$this->db->from('review');
							$total_reviews=$this->db->count_all_results();

							$hospital_rating=ceil($sum_rating->rating/$total_reviews);
							if($hospital_rating>5)
							$hospital_rating=5;


							$this->db->where('hospital_id',$this->post('hospital_id'));
							$this->db->update('hospitals',array('rating'=>$hospital_rating));

							/*$db = (array)get_instance()->db;
				   			print_r($db);*/
							$this->response(array(1=>"Review added successfully"),200);
						}
				}



		}

?>