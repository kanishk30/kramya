<?php
	include(APPPATH.'libraries/REST_Controller.php');

	 class Useract extends REST_Controller
		{
			public function index_get()
				{
					$act_token=$this->get('act_token');
					$user_id=$this->get('user_id');

					$this->db->select('email_act_token,registration_date');
					$this->db->from('users');
					$this->db->where('user_id',$user_id);

					$result=$this->db->get()->result();
					print_r($result);
					if($result->email_act_token==$act_token)
					{
						echo strtotime($result->registration_date);
						if(time()-strtotime($result->registration_date)<86400)
						{
							$updatedata = array(
							        'email_act'=>1
							);

							$this->db->where('user_id', $user_id);
							$this->db->update('users', $updatedata);
						}
						else
						{
							echo 'The Link Has Expired!!!';
						}	
					}
					else
					{
						echo 'Sorry Link is broken!!!!';
					}

				}

		}
?>	