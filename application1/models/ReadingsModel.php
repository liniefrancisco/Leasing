<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReadingsModel extends CI_model
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->_user_group = $this->session->userdata('user_group');
        $this->_user_id = $this->session->userdata('id');
    }

    function sanitize($string)
    {
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = trim($string);
        return $string;
    }

    public function getReading($tenant_id, $dateStart, $dateEnd)
    {
        $result = $this->db->QUERY("SELECT * 
                                    FROM invoicing 
                                    WHERE tenant_id = '$tenant_id' 
                                    AND charges_type = 'Other'
                                    AND charges_code IN ('PC000002', 'PC000007', 'PC000005')  
                                    AND posting_date
                                    BETWEEN '$dateStart' 
                                    AND '$dateEnd'")->RESULT_ARRAY();

        return $result;
    }
}