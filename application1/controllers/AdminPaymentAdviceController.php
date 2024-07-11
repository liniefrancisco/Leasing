<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

class AdminPaymentAdviceController extends CI_Controller
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

    public function getNotices()
    {
        $paymentAdvice = $this->adminmodel->getPaymentAdvice();
        JSONResponse($paymentAdvice);
    }

    public function getNoticesHistory()
    {
        $paymentAdvice = $this->adminmodel->getPaymentAdviceHistory();
        JSONResponse($paymentAdvice);
    }

    public function gethistorydocs()
    {
        $historyID   = $this->input->post('historyID');
        $historyDocs = $this->adminmodel->getHistoryDocs($historyID);
        JSONResponse($historyDocs);
    }

    public function getAdvices()
    {
        $paymentAdviceID   = $this->uri->segment(2);
        $paymentAdviceType = str_replace("%20", " ", $this->uri->segment(3));
        $advices           = array();

        if($paymentAdviceType == 'One Location')
        {
            $advices = $this->adminmodel->getAdvices($paymentAdviceID);
        }
        else
        {
            $advices['paymentadvice']    = $this->adminmodel->getAdvices($paymentAdviceID);
            $advices['paymentadvicesoa'] = $this->adminmodel->getPaymentAdviceSoa($paymentAdviceID);
        }

        JSONResponse($advices);
    }

    public function getAdvicesHistory()
    {
        $paymentAdviceID   = $this->uri->segment(2);
        $paymentAdviceType = str_replace("%20", " ", $this->uri->segment(3));
        $advices           = array();

        if($paymentAdviceType == 'One Location')
        {
            $advices = $this->adminmodel->getAdvicesHistory($paymentAdviceID);
        }
        else
        {
            $advices['paymentadvice']    = $this->adminmodel->getAdvicesHistory($paymentAdviceID);
            $advices['paymentadvicesoa'] = $this->adminmodel->getPaymentAdviceSoaHistory($paymentAdviceID);
        }

        JSONResponse($advices);
    }

    public function postAdviceOne()
    {
        $data     = $this->input->post(NULL, FILTER_SANITIZE_STRING);
        $status   = ['status' => 'Posted', 'posted_by' => $this->session->userdata('id')];
        $multiPay = array();
        $msg      = array();

        if ($data['type'] == 'One Location') 
        {
            if (!empty($data)) 
            {
                $this->db->trans_start();
                $this->db->where('id', $data['adviceID'])
                         ->update('payment_advice', $status);
                $this->db->trans_complete();

                if ($this->db->trans_status() === 'FALSE') 
                {
                    $this->db->trans_rollback();
                    $msg = ['info' => 'error', 'message' => 'Something went wrong, cant post Payment Advice.'];
                } 
                else 
                {
                    $msg = ['info' => 'success', 'message' => 'Payment Advice Posted.'];
                }
            } 
            else 
            {
                $msg = ['info' => 'error', 'message' => 'No Data to be Posted.'];
            }
        } 
        else if ($data['type'] == 'Multi Location') 
        {
            if (!empty($data)) 
            {
                $counter1   = count($data['tenantidtoapply']);
                $tenantid   = $data['tenantidtoapply'];
                $soaapplied = $data['soaapplied'];

                if(empty($data['soaapplied'])) 
                {
                    $msg = ['info' => 'Error', 'message' => 'Please Input any SOA applied'];
                    JSONResponse($msg);
                }

                $this->db->trans_start();
                $this->db->where('id', $data['adviceID']);
                $this->db->update('payment_advice', $status);

                for($z = 0; $z < $counter1; $z++)
                {
                    $advice_soa = $this->db->query("SELECT * FROM payment_advice_soa WHERE tenant_id = '" . $tenantid[$z] . "' AND payment_advice_id = '" . $data['adviceID'] . "'")->ROW();
                    $this->db->where('id', $advice_soa->id)
                             ->update('payment_advice_soa', ['soa_no' => $soaapplied[$z]]);
                }

                $this->db->trans_complete();

                if ($this->db->trans_status() === 'FALSE') {
                    $this->db->trans_rollback();
                    $msg = ['info' => 'error', 'message' => 'Something went wrong, cant post Payment Advice.'];
                } else {
                    $msg = ['info' => 'success', 'message' => 'Payment Advice Posted.'];
                }
            } else {
                $msg = ['info' => 'error', 'message' => 'No Data to be Posted.'];
            }
        }

        JSONResponse($msg);
    }
}