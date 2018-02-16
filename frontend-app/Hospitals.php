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
			
			if(empty($_FILES["hospital_img"])) {
				return false;
			}
			$count=0;
			if(!file_exists('./uploads/hospitals/'.$name))
			{
			$oldmask = umask(0);
			mkdir('./uploads/hospitals/'.$name, 0777 ,true);
			umask($oldmask);
			$count=0;
			}
			else
			{
				$count=0;
				$dir=opendir('./uploads/hospitals/'.$name);
				while($img=readdir($dir)){
					if($img=='.' || $img=='..'|| $img=='Thumbs.db'|| $img=='bookings')
						continue;
						++$count;			
					}
				//++$count;	
				closedir($dir);

			}
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
	    			$file_index=(string)$i+$count;
				$file_name = $name.'_'.$file_index.".".$ext;
			    $_FILES['hospital_img']['name'] = $file_name ;
			    $_FILES['hospital_img']['type'] = $files['hospital_img']['type'][$i];
			    $_FILES['hospital_img']['tmp_name'] = $files['hospital_img']['tmp_name'][$i];
			    $_FILES['hospital_img']['error'] = $files['hospital_img']['error'][$i];
			    $_FILES['hospital_img']['size']  = $files['hospital_img']['size'][$i];

			    if($this->upload->do_upload('hospital_img')){
			    	$oldmask = umask(0);
			    	chmod ($config['upload_path']."/".$_FILES["hospital_img"]["name"], 0777);
			    	umask($oldmask);
			        //print_r($this->upload->data());
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
	      		$result['profile_complete_percentage']=$row->profile_complete_percentage;
	      		$result['hospital_name']=$row->hospital_name;
				$result['hospitalEmailId']=$row->hospital_emailid;
				$result['website']=$row->website;
				$result['contact_person']=$row->contact_person;
				$result['hospital_contactno']=$row->hospital_contactno;
	      		$result['is_online_doctor_consultation']=$row->is_online_doctor_consultation;
	      		$result['is_medical_travel_insurance']=$row->is_medical_travel_insurance;
	      		$result['is_medical_records_transfer']=$row->is_medical_records_transfer;
	      		$result['is_health_insurance_coordination']=$row->is_health_insurance_coordination;
	      		$result['is_rehabilitation']=$row->is_rehabilitation;
	      		$result['is_translation_services']=$row->is_translation_services;
	      		$result['is_interpreter_services']=$row->is_interpreter_services;
	      		$result['is_personal_assistance']=$row->is_personal_assistance;
	      		$result['is_welcome_package']=$row->is_welcome_package;
	      		$result['is_airport_pickup']=$row->is_airport_pickup;
	      		$result['is_private_driver_or_limo']=$row->is_private_driver_or_limo;
	      		$result['is_car_hire']=$row->is_car_hire;
	      		$result['is_local_transportation_booking']=$row->is_local_transportation_booking;
	      		$result['is_hotel_booking']=$row->is_hotel_booking;
	      		$result['is_flight_booking']=$row->is_flight_booking;
	      		$result['is_local_tourism_option']=$row->is_local_tourism_option;
	      		$result['is_entertainment_option']=$row->is_entertainment_option;
	      		$result['is_shopping_trip_org']=$row->is_shopping_trip_org;
	      		$result['is_special_offer_for_group_stay']=$row->is_special_offer_for_group_stay;
	      		$result['is_free_wifi']=$row->is_free_wifi;
	      		$result['is_phone_in_the_room']=$row->is_phone_in_the_room;
	      		$result['is_tv_in_the_room']=$row->is_tv_in_the_room;
	      		$result['is_special_dietary_requests_accepted']=$row->is_special_dietary_requests_accepted;
	      		$result['is_private_rooms_for_patients_available']=$row->is_private_rooms_for_patients_available;
	      		$result['is_family_accommodation']=$row->is_family_accommodation;
	      		$result['is_parking_available']=$row->is_parking_available;
	      		$result['is_restaurant']=$row->is_restaurant;
	      		$result['is_cafe']=$row->is_cafe;
	      		$result['is_pharmacy']=$row->is_pharmacy;
	      		$result['is_shop']=$row->is_shop;
	      		$result['is_visa_or_travel_office']=$row->is_visa_or_travel_office;
	      		$result['is_buisness_center_services']=$row->is_buisness_center_services;
	      		$result['is_nursery_or_nanny_service']=$row->is_nursery_or_nanny_service;
	      		$result['is_document_legalisation']=$row->is_document_legalisation;
	      		$result['is_currency_exchange']=$row->is_currency_exchange;
	      		$result['is_laundry']=$row->is_laundry;
	      		$result['is_dry_cleaning']=$row->is_dry_cleaning;
	      		$result['is_religious_facilities']=$row->is_religious_facilities;
	      		$result['is_gym']=$row->is_gym;
	      		$result['is_mobile_accessible_rooms']=$row->is_mobile_accessible_rooms;
	      		$result['is_dedicated_smoking_area']=$row->is_dedicated_smoking_area;
	      		$result['is_locker']=$row->is_locker;
	      		$result['is_international_newspapers']=$row->is_international_newspapers;
	      		$result['is_room_rating']=$row->is_room_rating;
	      		$result['is_halal']=$row->is_halal;
	      		$result['is_air_ambulance']=$row->is_air_ambulance;
	      		$result['is_hbot']=$row->is_hbot;
	      		$result['is_jci']=$row->is_jci;
	      		$result['is_nabh']=$row->is_nabh;
	      		$result['hospital_image']=$row->hospital_image;
	      		$img_arr=[];
	      			if($result['hospital_image']!=''||$result['hospital_image']!=NULL){
	      				$path='./uploads/hospitals/'.$result['hospital_id'].'/';
								$dir=opendir($path);
								while($img=readdir($dir)){
									if($img=='.' || $img=='..'|| $img=='Thumbs.db'|| $img=='bookings')
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
	      		$result['dept_head_name']=$row->dept_head_name;
	      		$result['dept_head_email']=$row->dept_head_email;
	      		$result['dept_head_contact']=$row->dept_head_contact;
	      		$result['hospital_address']=$row->hospital_address;
	      		$result['city']=$row->city;
	      		$result['state']=$row->state;
	      		$result['country']=$row->country;
	      		$result['pincode']=$row->pincode;
	      		$result['latitude']=$row->latitude;
	      		$result['longitude']=$row->longitude;
	      		$result['procedure_supported']=$row->procedure_supported;
	      		if($result['procedure_supported']!=NULL || $result['procedure_supported']!='')
	      			{	
	      				$procedured_supported=explode(',',$result['procedure_supported']);
	      				$result['procedure_supported']=$procedured_supported;

	      			}
	      		$result['specialisation_area']=$row->specialisation_area;
	      			if($result['specialisation_area']!=NULL || $result['specialisation_area']!='')
	      			{	
	      				$specialisation_area=explode(',',$result['specialisation_area']);
	      				$result['specialisation_area']=$specialisation_area;

	      			}
	      		$result['special_packages']=$row->special_packages;
	      		$result['hospital_sort_desc']=$row->hospital_sort_desc;
	      		$result['hospital_long_desc']=$row->hospital_long_desc;
	      		$result['patients_served']=$row->patients_served;
	      		$result['count_specialisation']=$row->count_specialisation;
	      		$result['count_doctors']=$row->count_doctors;
	      		$result['count_nurses']=$row->count_nurses;
	      		$result['count_beds']=$row->count_beds;
	      		$result['since']=$row->since;
	      		$result['hospital_rating']=$row->rating;
	      		$result['other_email1']=$row->other_email1;
	      		$result['other_email2']=$row->other_email2;
	      		$result['other_email3']=$row->other_email3;

	      	}
				$this->response($result,200);
			}
		}

		public function profile_get()
			{
				$query=$this->provide_get_by_values('hospital_id' , $this->get('hospital_id'));
				$result=[];
				foreach ($query as $row){
	      		$result['hospital_id']=$row->hospital_id;
	      		$result['profile_complete_percentage']=$row->profile_complete_percentage;
	      		$result['hospital_name']=$row->hospital_name;
				$result['hospitalEmailId']=$row->hospital_emailid;
				$result['website']=$row->website;
				$result['contact_person']=$row->contact_person;
				$result['hospital_contactno']=$row->hospital_contactno;
	      		$result['is_online_doctor_consultation']=$row->is_online_doctor_consultation;
	      		$result['is_medical_travel_insurance']=$row->is_medical_travel_insurance;
	      		$result['is_medical_records_transfer']=$row->is_medical_records_transfer;
	      		$result['is_health_insurance_coordination']=$row->is_health_insurance_coordination;
	      		$result['is_rehabilitation']=$row->is_rehabilitation;
	      		$result['is_translation_services']=$row->is_translation_services;
	      		$result['is_interpreter_services']=$row->is_interpreter_services;
	      		$result['is_personal_assistance']=$row->is_personal_assistance;
	      		$result['is_welcome_package']=$row->is_welcome_package;
	      		$result['is_airport_pickup']=$row->is_airport_pickup;
	      		$result['is_private_driver_or_limo']=$row->is_private_driver_or_limo;
	      		$result['is_car_hire']=$row->is_car_hire;
	      		$result['is_local_transportation_booking']=$row->is_local_transportation_booking;
	      		$result['is_hotel_booking']=$row->is_hotel_booking;
	      		$result['is_flight_booking']=$row->is_flight_booking;
	      		$result['is_local_tourism_option']=$row->is_local_tourism_option;
	      		$result['is_entertainment_option']=$row->is_entertainment_option;
	      		$result['is_shopping_trip_org']=$row->is_shopping_trip_org;
	      		$result['is_special_offer_for_group_stay']=$row->is_special_offer_for_group_stay;
	      		$result['is_free_wifi']=$row->is_free_wifi;
	      		$result['is_phone_in_the_room']=$row->is_phone_in_the_room;
	      		$result['is_tv_in_the_room']=$row->is_tv_in_the_room;
	      		$result['is_special_dietary_requests_accepted']=$row->is_special_dietary_requests_accepted;
	      		$result['is_private_rooms_for_patients_available']=$row->is_private_rooms_for_patients_available;
	      		$result['is_family_accommodation']=$row->is_family_accommodation;
	      		$result['is_parking_available']=$row->is_parking_available;
	      		$result['is_restaurant']=$row->is_restaurant;
	      		$result['is_cafe']=$row->is_cafe;
	      		$result['is_pharmacy']=$row->is_pharmacy;
	      		$result['is_shop']=$row->is_shop;
	      		$result['is_visa_or_travel_office']=$row->is_visa_or_travel_office;
	      		$result['is_buisness_center_services']=$row->is_buisness_center_services;
	      		$result['is_nursery_or_nanny_service']=$row->is_nursery_or_nanny_service;
	      		$result['is_document_legalisation']=$row->is_document_legalisation;
	      		$result['is_currency_exchange']=$row->is_currency_exchange;
	      		$result['is_laundry']=$row->is_laundry;
	      		$result['is_dry_cleaning']=$row->is_dry_cleaning;
	      		$result['is_religious_facilities']=$row->is_religious_facilities;
	      		$result['is_gym']=$row->is_gym;
	      		$result['is_mobile_accessible_rooms']=$row->is_mobile_accessible_rooms;
	      		$result['is_dedicated_smoking_area']=$row->is_dedicated_smoking_area;
	      		$result['is_locker']=$row->is_locker;
	      		$result['is_international_newspapers']=$row->is_international_newspapers;
	      		$result['is_room_rating']=$row->is_room_rating;
	      		$result['is_halal']=$row->is_halal;
	      		$result['is_air_ambulance']=$row->is_air_ambulance;
	      		$result['is_hbot']=$row->is_hbot;
	      		$result['is_jci']=$row->is_jci;
	      		$result['is_nabh']=$row->is_nabh;
	      		$result['hospital_image']=$row->hospital_image;
	      		$img_arr=[];
	      			if($result['hospital_image']!=''||$result['hospital_image']!=NULL){
	      				$path='./uploads/hospitals/'.$result['hospital_id'].'/';
								$dir=opendir($path);
								while($img=readdir($dir)){
									if($img=='.' || $img=='..'|| $img=='Thumbs.db'|| $img=='bookings')
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
	      		$result['dept_head_name']=$row->dept_head_name;
	      		$result['dept_head_email']=$row->dept_head_email;
	      		$result['dept_head_contact']=$row->dept_head_contact;
	      		$result['hospital_address']=$row->hospital_address;
	      		$result['city']=$row->city;
	      		$result['state']=$row->state;
	      		$result['country']=$row->country;
	      		$result['pincode']=$row->pincode;
	      		$result['latitude']=$row->latitude;
	      		$result['longitude']=$row->longitude;
	      		$result['procedure_supported']=$row->procedure_supported;
	      		if($result['procedure_supported']!=NULL || $result['procedure_supported']!='')
	      			{	
	      				$procedured_supported=explode(',',$result['procedure_supported']);
	      				$result['procedure_supported']=$procedured_supported;

	      			}
	      		$result['specialisation_area']=$row->specialisation_area;
	      			if($result['specialisation_area']!=NULL || $result['specialisation_area']!='')
	      			{	
	      				$specialisation_area=explode(',',$result['specialisation_area']);
	      				$result['specialisation_area']=$specialisation_area;

	      			}
	      		$result['special_packages']=$row->special_packages;
	      		$result['hospital_sort_desc']=$row->hospital_sort_desc;
	      		$result['hospital_long_desc']=$row->hospital_long_desc;
	      		$result['patients_served']=$row->patients_served;
	      		$result['count_specialisation']=$row->count_specialisation;
	      		$result['count_doctors']=$row->count_doctors;
	      		$result['count_nurses']=$row->count_nurses;
	      		$result['count_beds']=$row->count_beds;
	      		$result['since']=$row->since;
	      		$result['hospital_rating']=$row->rating;
	      		$result['other_email1']=$row->other_email1;
	      		$result['other_email2']=$row->other_email2;
	      		$result['other_email3']=$row->other_email3;

	      		}

	      		$this->db->select('*');
	      		$this->db->from('review');
	      		$this->db->where('hospital_id',$this->get('hospital_id'));
	      		$comment_pool=$this->db->get()->result();
	      		
	      		$comment=[];
	      		$temp_pool=[];
	      		$counter=0;
	      			if($comment_pool!=NULL)
	      			{
	      		foreach($comment_pool as $row)
	      				{
	      			$temp_pool['review_index']=$row->review_index;
	      			$temp_pool['review_id']=$row->review_id;
	      			
	      			$this->db->select('fullname,user_img');
	      			$this->db->from('users');
	      			$this->db->where('user_id',$row->user_id);
	      			$user_res=$this->db->get()->row();


	      			$temp_pool['user_name']=$user_res->fullname;
	      			$temp_pool['user_image']=$user_res->user_img;
	      			$temp_pool['rating']=$row->rating;
	      			$temp_pool['comment']=$row->comment;
	      			$temp_pool['review_time']=date(DATE_ISO8601,strtotime($row->review_time));

	      			$comment[$counter]=$temp_pool;
	      			++$counter;
	      				}
	      			}	
	      		$result['reviews']=$comment;


	      		$final_filters=[];

			$this->db->select('hospitals.hospital_id,hospitals.hospital_image,hospitals.hospital_name');
			$this->db->from('hospitals');
			if($this->get('disease_id')=='' || $this->get('disease_id')==NULL){
			$final_filters['city']=$this->get('location');	
			}
			else{
				$final_filters['disease_id']=$this->get('disease_id');
				if($this->get('location') !=NULL || $this->get('location')!='')
					$final_filters['city']=$this->get('location');

				$this->db->join('hospital_disease_filter','hospital_disease_filter.hospital_id=hospitals.hospital_id','inner');
			}

			$this->db->where($final_filters);
			$this->db->order_by("hospitals.rating","desc");
			$this->db->limit(5);
			
	      		$query_res=$this->db->get()->result();
	      		$related=[];
	      		$temp_related=[];
	      		$count=0;
	      			foreach($query_res as $row){
	      				$temp_related['id']=$row->hospital_id;
	      				$temp_related['name']=$row->hospital_name;
	      				$temp_related['img']=$row->hospital_image;
	      				if($temp_related['img']!=''||$temp_related['img']!=NULL){
	      				$path='./uploads/hospitals/'.$temp_related['id'].'/';
								$dir=opendir($path);
								while($img=readdir($dir)){
									if($img=='.' || $img=='..'|| $img=='Thumbs.db'|| $img=='bookings')
										continue;
									//print_r($img);
									$img_path=$temp_related['img'].'/'.$img;
									break;	
								}
								
								closedir($dir);
	      				}
	      				$temp_related['img']=$img_path;
	      				$related[$count]=$temp_related;
	      				++$count;
	      			}

	      			$result['hospital_related']=$related;

	      		$this->response($result,200);
			}	

		private function insert_into_filters($procedures,$hospital_id){
			$exploded_procedures=explode(',',$procedures);
			
			$this->db->select('disease_id');
			$this->db->from('hospital_disease_filter');
			$this->db->where('hospital_id',$hospital_id);
			$temp_result=$this->db->get()->result();

			$inserted_procedures=[];
			$temp_count=0;
			foreach ($temp_result as $row){
				$inserted_procedures[$temp_count]=$row->disease_id;
				++$temp_count;
			}
				for($i=0;$i<count($exploded_procedures);$i++)
				{
				if($exploded_procedures[$i]!=''&& $exploded_procedures[$i]!=NULL)
					{	
					//print_r($exploded_procedures[$i]);
					$this->db->select('disease_id');
					$this->db->from('diseases');
					$this->db->where('disease_name',$exploded_procedures[$i]);
					$res=$this->db->get()->row();
					$disease_id=$res->disease_id;
					if(in_array($disease_id,$inserted_procedures))
						continue;
				$filter_data=array(
				'disease_id'=>$disease_id,
				'hospital_id'=>$hospital_id
					);	

				$fres=$this->db->insert('hospital_disease_filter',$filter_data);

				if(!$fres){
						$db = (array)get_instance()->db;
				   		//print_r($db);
					}
				}	
				
			}
		}


		private function compose_mail($user_id,$token){
			$act_link='http://kramya.com/kramyaapi/index.php/hospitalact?act_token='.$token.'&X-API-KEY=ANSHULVYAS16&hospital_id='.$user_id;

			$email='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <style type="text/css">
    body {
      width: 100% !important;
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
      margin: 0;
      padding: 0;
      mso-line-height-rule: exactly;
    }
    table td { border-collapse: collapse;}
    table td {border-collapse: collapse;}
    img {
      outline: none;
      text-decoration: none;
      -ms-interpolation-mode: bicubic;
    }
    a img {border: none;}
    @media only screen and (max-device-width: 480px) {
      table[id="outercontainer_div"] {
        max-width: 480px !important;
      }
      table[id="nzInnerTable"],
      table[class="nzpImageHolder"],
      table[class="imageGroupHolder"] {
        width: 100% !important;
        min-width: 320px !important;
      }
      table[class="nzpImageHolder"]
      td, td[class="table_seperator"],
      td[class="table_column"]{
        display: block !important;
        width: 100% !important;
      }
      table[class="nzpImageHolder"] img	{width: 100% !important;}
      table[class="nzpButt"] {
        display: block !important;
        width: auto !important;
      }
      #nzInnerTable td, #outercontainer_div td {padding: 0px !important; margin: 0px !important;}
    }
    </style>
  </head>
  <body style="padding: 0; margin: 0; -webkit-font-smoothing:antialiased; -webkit-text-size-adjust:none; background: #EEEEEE; background-image: url(\'http://www.mpzmail.com/cp3/images/newslettertextures/grayWallpaper.jpg"); background-repeat: repeat;" background="http://www.mpzmail.com/cp3/images/newslettertextures/grayWallpaper.jpg">
    <table width="100%"  cellpadding="30" id="outercontainer_div">
      <tr>
        <td align="center">
          <table width="600" bgColor="#FFFFFF" cellpadding="15" cellspacing="0" id="nzInnerTable" border="0" style="border: 1px solid #FFFFFF;">
            <tr>
              <td>
                <div id="innerContent"><table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 15px;" class="nzpImageHolder">
                  <tr>
                    <td align="center">
                      <div style="padding: 0px; background-color: ; ">
                        <a href="http://kramya.com/#/" target="_blank">
                          <img src="http://kramya.com/kramyaapi/img/kramya1.png" class="bigImg editableImg" id="img-1" width="302" border="0" alt="" title="">
                        </a>
                      </div>
                    </td>
                  </tr>
                </table>
                <table width="100%" cellspacing="0" cellpadding="0" style="padding: 30px; padding-top: 0px; padding-bottom: 15px;">
                  <tr>
                    <td>
                      <table width="100%" cellpadding="0" cellspacing="0" style="">
                        <tr>
                          <td bgColor="">
                            <div id="txtHolder-3" class="txtEditorClass" style="color: #000000; font-size: 22px; font-family: \'Arial\'; text-align: Left">
                          <br>
                            </div>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
                <table width="100%" cellspacing="0" cellpadding="0" style="padding: 30px; padding-top: 0px; padding-bottom: 15px;">
                  <tr>
                    <td>
                      <table width="100%" cellpadding="0" cellspacing="0" style="">
                        <tr>
                          <td bgColor="">
                            <div id="txtHolder-3" class="txtEditorClass" style="color: #16535C; font-size: 14px; font-family: \'Arial\'; text-align: center">
                              <font size="6">
                                <b>WELCOME TO KRAMYA </b> <br>
								
                              </font>
								 <font size="4">
                                <b>"WE CONNECT WITH CARE" </b>
							 <br><br> </div>
							  <div id="txtHolder-3" class="txtEditorClass" style="color: #666666; font-size: 14px; font-family: \'Arial\'; text-align: left">
							Hi, <br>
          Kindly click on the following link to activate your account and create your profile. <br>
                <a href='.$act_link.'>ACTIVATE YOUR ACCOUNT</a><br>
                We hope you have a good time with us.<br><br>
                              Cheers,<br>
                              Team Kramya<br>
                            </div>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
                <br><br>
				<table class="table table-condensed" border="0" align="center">
            <td><a href="http://facebook.com/KramyaHealth" target="_blank"><img src="http://kramya.com/kramyaapi/img/fb.png" width="50px"></a></td>
            <td><a href="https://plus.google.com/b/105121189650545397382/105121189650545397382/about/p/pub?pageId=105121189650545397382&hl=en&_ga=1.69627664.1642623259.1446271574" target="_blank"><img src="http://kramya.com/kramyaapi/img/gp.png" width="50px"></a></td>
            <td><a href="https://www.linkedin.com/company/10261550?trk=tyah&trkInfo=clickedVertical%3Acompany%2CentityType%3AentityHistoryName%2CclickedEntityId%3Acompany_10261550%2Cidx%3A2" target="_blank"><img src="http://kramya.com/kramyaapi/img/li.png" width="50px"></a></td>
            <td><a href="https://twitter.com/KramyaTweet" target="_blank"><img src="http://kramya.com/kramyaapi/img/tw.png" width="50px"></a></td>
          </table>
				
				
		
		
		
                <table width="100%" cellspacing="0" cellpadding="0" style="padding: 30px; padding-top: 0px; padding-bottom: 15px;">
                  <tr>
                    <td>
                      <table width="100%" cellpadding="20" cellspacing="0" style="">
                        <tr>
                          <td bgColor="">
                            <div id="txtHolder-4" class="txtEditorClass" style="color: #666666; font-size: 14px; font-family: \'Arial\'; text-align: center">
                              <font size="2">
                               By using our site, you agree to the KRAMYA Terms and Conditions. KRAMYA does not provide medical advice, diagnosis or treatment. The information provided on this site is designed to support, not replace, the relationship that exists between a patient/site visitor and his/her existing physician.<br>
Copyright 2015 © KRAMYA
<br><a href="http://www.kramya.com/#/contact">Customer Support</a> | <a href="http://www.kramya.com/#/terms">Terms and Conditions</a> | <a href="http://www.kramya.com/#/faqs">FAQ</a>
                
                              </font>
                            </div>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  </body>
</html>
';

			return $email;
		}

		private function send_mail($user_id,$user_email,$user_name,$token){

			$this->load->library('Mailer');
			$this->mailer->mail_init();

			$mail = new PHPMailer;

				//$mail->SMTPDebug = 1; //errors amd messages   
				$mail->isSMTP();// Set mailer to use SMTP
				$mail->Host = 'smtp.zoho.com';  // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;// Enable SMTP authentication
				$mail->Username = 'support@kramya.com';// SMTP username
				$mail->Password = 'huzoore@23';// SMTP password
				$mail->SMTPSecure = 'ssl';// Enable TLS encryption, `ssl` also accepted
				$mail->Port = 465; // TCP port to connect to

				$mail->setFrom('support@kramya.com', 'Kramya Support');
				$mail->addAddress($user_email,$user_name); // Add a recipient
				$mail->isHTML(true);// Set email format to HTML

				$mail->Subject = 'Activate Your Account';
				$mail->Body    = $this->compose_mail($user_id,$token);
				if(!$mail->send()) {
				  /*  echo 'Message could not be sent.';
				    echo 'Mailer Error: ' . $mail->ErrorInfo;*/
				} else {
				   /* echo 'Message has been sent';*/
				}

		}

		public function register_basic_post(){
				$check_hospital_email=$this->provide_get_by_values('hospital_emailid' , $this->post('hospital_emailid'));
					if($check_hospital_email){
					$this->response(array(1=>'EMAIL ID ALready Exists!'),409);	
					}
					else{
				$salt='';
				$updated_count=$this->db->count_all('hospitals')+1;
				$new_id=(string)$updated_count;
				for($i=0;$i<5;++$i)
				$salt.=(string)rand(0,9);
				$gen_id='KRMHSPTL'.$salt.$new_id;

			$email_act_token=md5($this->post('hospital_password'));
			$data=array(	
			'hospital_id' => $gen_id,	
			'hospital_name' => $this->post('hospital_name'),
			'hospital_emailid' => $this->post('hospital_emailid'),
			'hospital_password' => $this->post('hospital_password'),
			'registration_date'=>date('Y-m-d H:i:s'),
			'email_act_token'=> $email_act_token
			);

			$dbRet=$this->db->insert('hospitals', $data);

			if( !$dbRet ){
				   $db = (array)get_instance()->db;
				   //print_r($db);
				   $this->response(array(1=>$db,409));
				}
			else{

				$key=$data['hospital_password'];
				$key_data=array(
				'api_key'=>$key,
				'level'=>1,	
				'date_created'=>date('Y-m-d H:i:s'),
				'api_user_id'=>$gen_id,
				);
				$keyRet=$this->db->insert('keys',$key_data);
				if( !$keyRet ){
				   $db = (array)get_instance()->db;
				   //print_r($db);
				   $this->response(array(1=>$db,409));
					}
					else
					// sending email
					$this->send_mail($data['hospital_id'],$data['hospital_emailid'],$data['hospital_name'],$data['email_act_token']);
					$this->response(array(1=>'You Have Been Registered.Check Your email for Activation'),201);				
				}	

			}
		}

		public function register_complete_post()
			{
				$hospital_id=$this->post('hospital_id');
				

				$data=[];
				$data['profile_complete_percentage']=$this->post('profile_complete_percentage');
				
				if($this->post('hospital_name')!=NULL ||$this->post('hospital_img')!='')
					$data['hospital_name']=$this->post('hospital_name');
				if($this->post('patients_served')!=NULL ||$this->post('patients_served')!='')
					$data['patients_served']=$this->post('patients_served');
				if($this->post('count_beds')!=NULL ||$this->post('count_beds')!='')
					$data['count_beds']=$this->post('count_beds');
				if($this->post('specialisation_area')!=NULL ||$this->post('specialisation_area')!='')
					$data['specialisation_area']=$this->post('specialisation_area');
				if($this->post('count_nurses')!=NULL ||$this->post('count_nurses')!='')
					$data['count_nurses']=$this->post('count_nurses');
				if($this->post('since')!=NULL ||$this->post('since')!='')
					$data['since']=$this->post('since');
				
					$img_path=$this->upload_image($hospital_id);
					//print_r($img_path);
					if($img_path!=false)
					{
					if(is_array($img_path))
					$img_path="NULL";
					$data['hospital_image']=$img_path;
					}
				if($this->post('website')!=NULL ||$this->post('website')!='')
					$data['website']=$this->post('website');
				if($this->post('contact_person')!=NULL ||$this->post('contact_person')!='')
					$data['contact_person']=$this->post('contact_person');
				if($this->post('other_email1')!=NULL ||$this->post('other_email1')!='')
					$data['other_email1']=$this->post('other_email1');
				if($this->post('other_email2')!=NULL ||$this->post('other_email2')!='')
					$data['other_email2']=$this->post('other_email2');
				if($this->post('other_email3')!=NULL ||$this->post('other_email3')!='')
					$data['other_email3']=$this->post('other_email3');
				if($this->post('hospital_contactno')!=NULL ||$this->post('hospital_contactno')!='')
					$data['hospital_contactno']=$this->post('hospital_contactno');
				if($this->post('hospital_sort_desc')!=NULL ||$this->post('hospital_sort_desc')!='')
					$data['hospital_sort_desc']=$this->post('hospital_sort_desc');
				if($this->post('hospital_long_desc')!=NULL ||$this->post('hospital_long_desc')!='')
					$data['hospital_long_desc']=$this->post('hospital_long_desc');
				if($this->post('is_online_doctor_consultation')!=NULL ||$this->post('is_online_doctor_consultation')!='')
					$data['is_online_doctor_consultation']=$this->post('is_online_doctor_consultation');
				if($this->post('is_medical_travel_insurance')!=NULL ||$this->post('is_medical_travel_insurance')!='')
					$data['is_medical_travel_insurance']=$this->post('is_medical_travel_insurance');
				if($this->post('is_health_insurance_coordination')!=NULL ||$this->post('is_health_insurance_coordination')!='')
					$data['is_health_insurance_coordination']=$this->post('is_health_insurance_coordination');
				if($this->post('is_medical_records_transfer')!=NULL ||$this->post('is_medical_records_transfer')!='')
					$data['is_medical_records_transfer']=$this->post('is_medical_records_transfer');
				if($this->post('is_rehabilitation')!=NULL ||$this->post('is_rehabilitation')!='')
					$data['is_rehabilitation']=$this->post('is_rehabilitation');
				if($this->post('is_translation_services')!=NULL ||$this->post('is_translation_services')!='')
					$data['is_translation_services']=$this->post('is_translation_services');
				if($this->post('is_interpreter_services')!=NULL ||$this->post('is_interpreter_services')!='')
					$data['is_interpreter_services']=$this->post('is_interpreter_services');
				if($this->post('is_personal_assistance')!=NULL ||$this->post('is_personal_assistance')!='')
					$data['is_personal_assistance']=$this->post('is_personal_assistance');
				if($this->post('is_welcome_package')!=NULL ||$this->post('is_welcome_package')!='')
					$data['is_welcome_package']=$this->post('is_welcome_package');
				if($this->post('is_airport_pickup')!=NULL ||$this->post('is_airport_pickup')!='')
					$data['is_airport_pickup']=$this->post('is_airport_pickup');
				if($this->post('is_private_driver_or_limo')!=NULL ||$this->post('is_private_driver_or_limo')!='')
					$data['is_private_driver_or_limo']=$this->post('is_private_driver_or_limo');
				if($this->post('is_car_hire')!=NULL ||$this->post('is_car_hire')!='')
					$data['is_car_hire']=$this->post('is_car_hire');
				if($this->post('is_local_transportation_booking')!=NULL ||$this->post('is_local_transportation_booking')!='')
					$data['is_local_transportation_booking']=$this->post('is_local_transportation_booking');
				if($this->post('is_hotel_booking')!=NULL ||$this->post('is_hotel_booking')!='')
					$data['is_hotel_booking']=$this->post('is_hotel_booking');
				if($this->post('is_flight_booking')!=NULL ||$this->post('is_flight_booking')!='')
					$data['is_flight_booking']=$this->post('is_flight_booking');
				if($this->post('is_local_tourism_option')!=NULL ||$this->post('is_local_tourism_option')!='')
					$data['is_local_tourism_option']=$this->post('is_local_tourism_option');
				if($this->post('is_shopping_trip_org')!=NULL ||$this->post('is_shopping_trip_org')!='')
					$data['is_shopping_trip_org']=$this->post('is_shopping_trip_org');
				if($this->post('is_entertainment_option')!=NULL ||$this->post('is_entertainment_option')!='')
					$data['is_entertainment_option']=$this->post('is_entertainment_option');
				if($this->post('is_special_offer_for_group_stay')!=NULL ||$this->post('is_special_offer_for_group_stay')!='')
					$data['is_special_offer_for_group_stay']=$this->post('is_special_offer_for_group_stay');
				if($this->post('is_free_wifi')!=NULL ||$this->post('is_free_wifi')!='')
					$data['is_free_wifi']=$this->post('is_free_wifi');
				if($this->post('is_phone_in_the_room')!=NULL ||$this->post('is_phone_in_the_room')!='')
					$data['is_phone_in_the_room']=$this->post('is_phone_in_the_room');
				if($this->post('is_tv_in_the_room')!=NULL ||$this->post('is_tv_in_the_room')!='')
					$data['is_tv_in_the_room']=$this->post('is_tv_in_the_room');
				if($this->post('is_special_dietary_requests_accepted')!=NULL ||$this->post('is_special_dietary_requests_accepted')!='')
					$data['is_special_dietary_requests_accepted']=$this->post('is_special_dietary_requests_accepted');
				if($this->post('is_private_rooms_for_patients_available')!=NULL ||$this->post('is_private_rooms_for_patients_available')!='')
					$data['is_private_rooms_for_patients_available']=$this->post('is_private_rooms_for_patients_available');
				if($this->post('is_family_accommodation')!=NULL ||$this->post('is_family_accommodation')!='')
					$data['is_family_accommodation']=$this->post('is_family_accommodation');
				if($this->post('is_parking_available')!=NULL ||$this->post('is_parking_available')!='')
					$data['is_parking_available']=$this->post('is_parking_available');
				if($this->post('is_restaurant')!=NULL ||$this->post('is_restaurant')!='')
					$data['is_restaurant']=$this->post('is_restaurant');
				if($this->post('is_cafe')!=NULL ||$this->post('is_cafe')!='')
					$data['is_cafe']=$this->post('is_cafe');
				if($this->post('is_nursery_or_nanny_service')!=NULL ||$this->post('is_nursery_or_nanny_service')!='')
					$data['is_nursery_or_nanny_service']=$this->post('is_nursery_or_nanny_service');
				if($this->post('is_pharmacy')!=NULL ||$this->post('is_pharmacy')!='')
					$data['is_pharmacy']=$this->post('is_pharmacy');
				if($this->post('is_shop')!=NULL ||$this->post('is_shop')!='')
					$data['is_shop']=$this->post('is_shop');
				if($this->post('is_visa_or_travel_office')!=NULL ||$this->post('is_visa_or_travel_office')!='')
					$data['is_visa_or_travel_office']=$this->post('is_visa_or_travel_office');
				if($this->post('is_buisness_center_services')!=NULL ||$this->post('is_buisness_center_services')!='')
					$data['is_buisness_center_services']=$this->post('is_buisness_center_services');
				if($this->post('is_document_legalisation')!=NULL ||$this->post('is_document_legalisation')!='')
					$data['is_document_legalisation']=$this->post('is_document_legalisation');
				if($this->post('is_currency_exchange')!=NULL ||$this->post('is_currency_exchange')!='')
					$data['is_currency_exchange']=$this->post('is_currency_exchange');
				if($this->post('is_laundry')!=NULL ||$this->post('is_laundry')!='')
					$data['is_laundry']=$this->post('is_laundry');
				if($this->post('is_dry_cleaning')!=NULL ||$this->post('is_dry_cleaning')!='')
					$data['is_dry_cleaning']=$this->post('is_dry_cleaning');
				if($this->post('is_religious_facilities')!=NULL ||$this->post('is_religious_facilities')!='')
					$data['is_religious_facilities']=$this->post('is_religious_facilities');
				if($this->post('is_gym')!=NULL ||$this->post('is_gym')!='')
					$data['is_gym']=$this->post('is_gym');
				if($this->post('is_dedicated_smoking_area')!=NULL ||$this->post('is_dedicated_smoking_area')!='')
					$data['is_dedicated_smoking_area']=$this->post('is_dedicated_smoking_area');
				if($this->post('is_mobile_accessible_rooms')!=NULL ||$this->post('is_mobile_accessible_rooms')!='')
					$data['is_mobile_accessible_rooms']=$this->post('is_mobile_accessible_rooms');
				if($this->post('is_locker')!=NULL ||$this->post('is_locker')!='')
					$data['is_locker']=$this->post('is_locker');
				if($this->post('is_room_rating')!=NULL ||$this->post('is_room_rating')!='')
					$data['is_room_rating']=$this->post('is_room_rating');
				if($this->post('is_halal')!=NULL ||$this->post('is_halal')!='')
					$data['is_halal']=$this->post('is_halal');
				if($this->post('is_air_ambulance')!=NULL ||$this->post('is_air_ambulance')!='')
					$data['is_air_ambulance']=$this->post('is_air_ambulance');
				if($this->post('is_hbot')!=NULL ||$this->post('is_hbot')!='')
					$data['is_hbot']=$this->post('is_hbot');
				if($this->post('is_jci')!=NULL ||$this->post('is_jci')!='')
					$data['is_jci']=$this->post('is_jci');
				if($this->post('is_nabh')!=NULL ||$this->post('is_nabh')!='')
					$data['is_nabh']=$this->post('is_nabh');
				if($this->post('is_international_newspapers')!=NULL ||$this->post('is_international_newspapers')!='')
					$data['is_international_newspapers']=$this->post('is_international_newspapers');
				if($this->post('is_dedicated_dept')!=NULL ||$this->post('is_dedicated_dept')!='')
					$data['is_dedicated_dept']=$this->post('is_dedicated_dept');
				if($this->post('dept_head_name')!=NULL ||$this->post('dept_head_name')!='')
					$data['dept_head_name']=$this->post('dept_head_name');
				if($this->post('dept_head_email')!=NULL ||$this->post('dept_head_email')!='')
					$data['dept_head_email']=$this->post('dept_head_email');
				if($this->post('dept_head_contact')!=NULL ||$this->post('dept_head_contact')!='')
					$data['dept_head_contact']=$this->post('dept_head_contact');
				if($this->post('hospital_address')!=NULL ||$this->post('hospital_address')!='')
					$data['hospital_address']=$this->post('hospital_address');
				if($this->post('city')!=NULL ||$this->post('city')!='')
					$data['city']=$this->post('city');
				if($this->post('state')!=NULL ||$this->post('state')!='')
					$data['state']=$this->post('state');
				if($this->post('country')!=NULL ||$this->post('country')!='')
					$data['country']=$this->post('country');
				if($this->post('pincode')!=NULL ||$this->post('pincode')!='')
					$data['pincode']=$this->post('pincode');
				if($this->post('latitude')!=NULL ||$this->post('latitude')!='')
					$data['latitude']=$this->post('latitude');
				if($this->post('longitude')!=NULL ||$this->post('longitude')!='')
					$data['longitude']=$this->post('longitude');
				if($this->post('procedure_supported')!=NULL ||$this->post('procedure_supported')!='')
				{
					$data['procedure_supported']=$this->post('procedure_supported');
					$this->insert_into_filters($data['procedure_supported'],$hospital_id);
				}
				
				if($this->post('special_packages')!=NULL ||$this->post('special_packages')!='')
					$data['special_packages']=$this->post('special_packages');
				if($this->post('specialisation_area')!=NULL ||$this->post('specialisation_area')!='')
					$data['specialisation_area']=$this->post('specialisation_area');

				print_r($data);
				$this->db->where('hospital_id', $hospital_id);
				$this->db->update('hospitals', $data); 

				//$this->response(array(1=>'Records Updated Successfully'),200);

				
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