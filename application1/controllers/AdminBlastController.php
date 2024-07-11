<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

class AdminBlastController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('adminmodel');
        $this->load->model('basemodel');
        $this->load->model('ledgermodel');
        $this->load->library('upload');
        date_default_timezone_set('Asia/Manila');
        $timestamp = time();
        $this->_currentDate = date('Y-m-d', $timestamp);
        $this->_currentYear = date('Y', $timestamp);
        $this->load->library('excel');
        $this->load->library('PHPExcel');


        # Disable Cache
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        ('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }

    # TABLE OF CONTENTS
    # PORTAL INDEX FUNCTION

    
    function sanitize($string)
    {
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = trim($string);
        return $string;
    }

    function get_dateTime()
    {
        $timestamp = time();
        $date_time = date('F j, Y g:i:s A  ', $timestamp);
        $result['current_dateTime'] = $date_time;
        echo json_encode($result);
    }

    public function senUserBlast()
    {
        $data      = $this->input->post(NULL);

        $prospects = $this->adminmodel->prospectUsers($data['tenanttype'], $data['store']);
        $chunked   = array_chunk($prospects, 50);
        $msg       = array();

        foreach($chunked as $value)
        {
            $blast = $this->sendData('https://leasingportal.altsportal.com/blastSend', ['info' => $value]);

            $blast = 'success';

            if($blast == 'success')
            {
                sleep(10);
                $msg = ['info' => 'success'];
            }
            else
            {
                $msg = ['info' => 'Error', 'message' => 'Something Went Wrong, Please try again.'];
                continue;
            }
        }

        JSONResponse($msg);
    }

    public function sendData($url, $data)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $server_output = curl_exec($ch);

        return $server_output;

        curl_close($ch);
    }
}