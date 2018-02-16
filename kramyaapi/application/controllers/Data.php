<?php
include(APPPATH.'libraries/REST_Controller.php');

	 class Data extends REST_Controller
	{
		private function build_slider_data()
			{
				$data=[];

				$comments=["Kramya is the best.","Think Medical Tourism,Think Kramya","Dedicated personal assistance.","Internationally accredited hospitals","Healthcare for every budget."];
				$by=["India Times","Times of India","The Hindu","Surplus","The Economic Times"];

				$data['comments']=$comments;
				$data['by']=$by;

				return $data;

			}

		private function build_tophospitals_data()
			{
				$data=['http://chennaionline.com/images/gallery/2013/September/20130928033919/Top-10-Hospitals-in-India-08.jpg',
					'http://chennaionline.com/images/gallery/2013/September/20130928033919/Top-10-Hospitals-in-India-07.jpg',
					'http://chennaionline.com/images/gallery/2013/September/20130928033919/Top-10-Hospitals-in-India-06.jpg',
					'http://chennaionline.com/images/gallery/2013/September/20130928033919/Top-10-Hospitals-in-India-05.jpg',
					'http://chennaionline.com/images/gallery/2013/September/20130928033919/Top-10-Hospitals-in-India-04.jpg',
					'http://chennaionline.com/images/gallery/2013/September/20130928033919/Top-10-Hospitals-in-India-03.jpg'];

				$hsdata['images']=$data;

				return $hsdata;

			} 

		private function build_chartbustors()
			{
				$data=[];
				$hospital_names=["AIIMS","Apollo","Fortis Hospitals"];
				$desc=["All India Institute of Medical Sciences.",
				"Breaking record in the world of medicine.",
				"World Class Medical facilities."
					   ];
				$rating=['4.5','4.5','4.5'];
				$images=['http://www.medicaldialogues.in/wp-content/uploads/2015/06/AIIMS.jpg',
						'http://apolloahd.com/uploads/gallery/glry_g4_2013081239.jpg',
						'http://myinfocart.com/wp-content/uploads/2015/07/fortis12.jpg'
						];
				$address=["New Delhi,India","Indore,India","Torento,Canada"];
				$review=["150","200","250"];		
				
				$data['hospital_names']=$hospital_names;
				$data['desc']=$desc;
				$data['rating']=$rating;
				$data['images']=$images;
				$data['address']=$address;
				$data['review']=$review;
				return $data;

			}	

		private function testimonials_data()
			{
				$data=[];
				$comment=["We're very grateful to the Kramya Team for their professionalism,superb support and constant helpfulness. All in all, a fantastic partnership!",
					"The helpful guidance provided by the Kramya team from start to finish exceeded all my expectations. I would not hesitate to recommend this to anyone",
					"Just so simple and a wonderful system"
				         ];
				$by=["Silom Dental","Sheryln Gupta","Ramin Djwadi"];
				$country=['Thailand','Canada','USA'];    
				
				$data['comment']=$comment;
				$data['by']=$by;
				$data['country']=$country;

				return $data;
				
			}

		private function location_autocomplete()
			{

				$data=[];
				$this->db->distinct();
				$this->db->select('city,country');
				$this->db->from('hospitals'); 
				$query = $this->db->get()->result();

				$count=0;
				foreach($query as $row){
					$data[$count]['city']=$row->city;
					$data[$count]['country']=$row->country;
					++$count;
				}		
				
				return $data;
			}		

		private function disease_autocomplete()
			{
				
				$data=[];

				$this->db->select('disease_name,disease_id');
				$this->db->from('diseases');
				$query=$this->db->get()->result();

				$count=0;
				foreach ($query as $row) {
				$data[$count]['dis']=$row->disease_name;
				$data[$count]['id']=$row->disease_id;	
				++$count;
				}

				return $data;
			}		
		public function frontdata_get()
			{

				$front_page_data=[];

				// building slider data
				$slider_data=$this->build_slider_data();
				// Top destinations
				$top_destination=$this->build_tophospitals_data();
				// chart bustors
				$chart_bustors=$this->build_chartbustors();
				// testimonials
				$testimonials=$this->testimonials_data();
				//location autocomplete
				$location=$this->location_autocomplete();
				//disease autocomplete
				$disease=$this->disease_autocomplete();

				$front_page_data['silder_data']=$slider_data;
				$front_page_data['top_destination']=$top_destination;
				$front_page_data['chart_bustors']=$chart_bustors;
				$front_page_data['video']='http://192.168.68.204:8080/kramya-development/frontend-app/video/vid1.mp4';
				$front_page_data['$testimonial']=$testimonials;
				$front_page_data['location']=$location;
				$front_page_data['disease']=$disease;

				$this->response($front_page_data,200);
			}



	}
?>