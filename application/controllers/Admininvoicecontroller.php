<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

class AdminInvoiceController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('adminmodel');
        $this->load->library('upload');
        date_default_timezone_set('Asia/Manila');
        $timestamp = time();
        $this->_currentDate = date('Y-m-d', $timestamp);
        $this->_currentYear = date('Y', $timestamp);
        $this->load->library('excel');
        //$this->load->library('PHPExcel');


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

    public function admingettenant()
    {
        $tenant_id = $this->input->post('store') . "-" . $this->input->post('type');
        $tenants   = $this->adminmodel->admingettenant($tenant_id);
        JSONResponse($tenants);
    }

    public function uploadinvoicepertenant()
    {
        $data       = $this->input->post(NULL, FILTER_SANITIZE_STRING);
        $tenantID   = $data['tradename'];
        $url        = 'https://leasingportal.altsportal.com/perTenantUpload';
        $msg        = array();
        $subsidiary = array(); #SUBSIDIARY LEDGER AND GENERAL LEDGER
        $ledger     = array();
        $invoice    = array();
        $tmpcharges = array();
        $toUpload   = array();
        $update     = array();
        $portal     = '';

        if (isset($data)) {
            $this->db->trans_start();

            $subsidiary = $this->adminmodel->getDataV1($tenantID, $data['datefrom'], $data['dateto'], 'subsidiary_ledger');
            $ledger     = $this->adminmodel->getDataV1($tenantID, $data['datefrom'], $data['dateto'], 'ledger');
            $invoice    = $this->adminmodel->getDataV2($tenantID, $data['datefrom'], $data['dateto'], 'invoicing');
            $tmpcharges = $this->adminmodel->getDataV2($tenantID, $data['datefrom'], $data['dateto'], 'tmp_preoperationcharges');

            if (!empty($invoice)) {
                if ($this->isDomainAvailable('leasingportal.altsportal.com')) {
                    $toUpload = ['subsidiary' => $subsidiary, 'ledger' => $ledger, 'invoicing' => $invoice, 'charges' => $tmpcharges];
                    // $portal   = $this->sendData($url, $toUpload);

                    $portal = 'success';

                    if ($portal == 'success') 
                    {
                        $update = ['upload_status' => 'Uploaded', 'upload_date' => date('Y-m-d')];

                        foreach ($subsidiary as $value) {
                            $this->db->where('id', $value['id'])
                                     ->update('subsidiary_ledger', $update);
                        }

                        foreach ($ledger as $value) {
                            $this->db->where('id', $value['id'])
                                     ->update('ledger', $update);
                        }

                        foreach ($invoice as $value) {
                            $this->db->where('id', $value['id'])
                                    ->update('invoicing', $update);
                        }

                        if (isset($tmpcharges)) {
                            foreach ($tmpcharges as $value) {
                                $this->db->where('id', $value['id'])
                                         ->update('tmp_preoperationcharges', $update);
                            }
                        }

                        $this->db->trans_complete();

                        if ($this->db->trans_status() === 'FALSE') 
                        {
                            $this->db->trans_rollback();
                            $msg = ['info' => 'error', 'message' => 'Something went wrong, please check data uploaded.'];
                        } 
                        else 
                        {
                            $msg = ['info' => 'success', 'message' => 'Invoices Uploaded Succesfully.'];
                        }
                    } 
                    else if ($portal == 'error') 
                    {
                        $msg = ['info' => 'error', 'message' => 'Failed Uploading Invoices, Please try again.'];
                    } 
                    else if ($portal == 'empty') 
                    {
                        $msg = ['info' => 'error', 'message' => 'Failed Uploading Invoices, no data to upload.'];
                    }
                } 
                else 
                {
                    $msg = ['info' => 'error', 'message' => 'PC has no connection, please try again.'];
                }
            } 
            else 
            {
                $msg = ['info' => 'Warning', 'message' => 'No New Invoices Found.'];
            }
        } 
        else 
        {
            $msg = ['info' => 'NO DATA', 'message' => 'Data seems to be empty. Please try again.'];
        }

        if($msg['info'] != 'Warning')
        {
            $this->saveLog('Invoice', $subsidiary, $tenantID, $msg['info'], $msg['message']);
        }

        JSONResponse($msg);
    }

    public function saveLog($type, $docno, $tenant_id, $status, $statusMessage)
    {
        $doc_no = '';
        $data   =
        [                
            'type_uploaded'  => $type,
            'tenant_id'      => $tenant_id,
            'upload_status'  => $status,
            'status_message' => $statusMessage,
            'date_uploaded'  => date('Y-m-d'),
            'user_id'        => $this->session->userdata('id')
        ];

        $this->db->trans_start();

        $this->db->insert('upload_log', $data);
        $uploadlogID = $this->db->insert_id();
        
        if($status === 'success')
        {
            foreach ($docno as $value) 
            {
                if($doc_no != $value['doc_no'])
                {
                    $docs = 
                    [
                        'uploadlogID' => $uploadlogID,
                        'documentno'  => $value['doc_no']
                    ];

                    $this->db->insert('upload_log_docs', $docs);
                }

                $doc_no = $value['doc_no'];
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === 'FALSE') 
        {
            $this->db->trans_rollback();
        }
    }

    public function isDomainAvailable($domain)
    {
        $file = @fsockopen($domain, 80); #@fsockopen is used to connect to a socket

        # Verify whether the internet is working or not
        if ($file) {
            return true;
        } else {
            return false;
        }
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