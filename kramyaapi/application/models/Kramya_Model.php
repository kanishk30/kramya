<?php
class Kramya_Model extends CI_Model {

        

        public function get_user_info()
        {
                $this->load->database();
                $this->db->select('user_index,fullname,email_id,user_location'); 
				$this->db->from('users');   
				// $this->db->where('user_id', 'abhi_007');
                $query = $this->db->get();
                return $query->result();
        }

        public function get_hospital_info()
        {
                $this->load->database();
    //             $this->db->select('fullname'); 
				// $this->db->from('users');   
				// $this->db->where('user_id', 'abhi_007');
                $query = $this->db->get('hospitals');
                return $query->result();
        }

}
?>