<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

class AdminPaymentController extends CI_Controller
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

    public function getpayment()
    {
        $tenantID = $this->input->post('tenantID');
        $payment  = $this->adminmodel->getPayments($tenantID);

        JSONResponse($payment);
    }

    public function getpaymentperstore()
    {
        $tenantID  = $this->input->post('store') . '-' . $this->input->post('type');
        $startDate = $this->input->post('date1');
        $endDate   = $this->input->post('date2');
        $data      = array();

        $payment = $this->adminmodel->getPaymentsPerstore($tenantID, $startDate, $endDate);
        JSONResponse($payment);
    }

    public function uploadpaymentdata()
    {
        $paymentID = $this->input->post('paymentID');

        $this->db->trans_start();
        # CONTAINER FOR payment_scheme
        $paymentScheme = $this->adminmodel->getPaymentScheme($paymentID);

        if(isset($paymentScheme))
        {
            $slData  = array();
            $glData  = array();
            $Ledger  = array();

            # GET DATA FROM subsidiary_ledger
            $sl      = $this->adminmodel->getSL($paymentScheme->receipt_no);
            $payment = $this->adminmodel->getPaymentTable($paymentScheme->receipt_no);

            # LOOP TO GET THE REFERENCE NO
            foreach ($sl as $value) {
                $slData[] = $this->adminmodel->getLedgers($value['ref_no'], 'subsidiary_ledger');
            }

            # SUBSIDIARY AND GENERAL LEDGER CONTAINER
            $subsidiary = array();

            # LOOP TO GET THE ARRAYS CONTAINING THE DATA FROM subsidiary_ledger USING THE ref_no
            for ($i = 0; $i < count($slData); $i++) {
                for ($z = 0; $z < count($slData[$i]); $z++) {
                    $subsidiary[] = $slData[$i][$z];
                }
            }

            # CONTAINER
            $doc_no = array();

            foreach ($subsidiary as $value) {

                $doc_no[] = $value['doc_no'];
            }

            # DOCUMENT NUMBER FOR ledger TABLE
            $forLedger = array_unique($doc_no);
            $lData     = array();

            foreach ($forLedger as $value) {
                $lData[] = $this->adminmodel->getLedgerTable($value);
            }

            # LEDGER CONTAINER
            $ledger = array();

            for ($i = 0; $i < count($lData); $i++) {
                for ($z = 0; $z < count($lData[$i]); $z++) {
                    $ledger[] = $lData[$i][$z];
                }
            }

            # $subsidiary    = subsidiary_ledger and general_ledger
            # ledger         = $ledger
            # payment        = $payment
            # payment_scheme = $paymentScheme

            $soa_file = $this->adminmodel->getSoaForPayment($paymentScheme->soa_no);

            $url          = 'https://leasingportal.altsportal.com/uploadPaymentData';
            $notification = 'https://leasingportal.altsportal.com/paymentNotification';

            if ($this->isDomainAvailable('leasingportal.altsportal.com')) 
            {
                if (!empty($subsidiary) || !empty($ledger) || !empty($paymentScheme)) 
                {
                    $upload   = array('subsidiary' => $subsidiary, 'ledger' => $ledger, 'paymentScheme' => $paymentScheme, 'payment' => $payment);
                    // $response = $this->sendData($url, $upload);
                    // $sms      = $this->sendData($notification, array('payment_scheme' => $paymentScheme, 'soa' => $soa_file));
                    $response = 'success';

                    if ($response == 'success') {
                        $this->db->trans_complete();

                        $update = ['upload_status' => 'Uploaded', 'upload_date' => date('Y-m-d')];

                        foreach ($subsidiary as $value) {
                            $this->db->where('id', $value['id']);
                            $this->db->update('subsidiary_ledger', $update);
                        }

                        foreach ($ledger as $value) {
                            $this->db->where('id', $value['id']);
                            $this->db->update('ledger', $update);
                        }

                        if (isset($payment)) {
                            $this->db->where('id', $payment->id);
                            $this->db->update('payment', $update);
                        }

                        $this->db->where('id', $paymentScheme->id);
                        $this->db->update('payment_scheme', $update);

                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                            $msg = ['info' => 'error', 'message' => 'Data Seems to be empty, cant proceed.'];
                        } else {
                            $msg = ['info' => 'success', 'message' => 'Payment Data Succesfully Uploaded.'];
                        }
                    } else {
                        $msg = ['info' => 'error', 'message' => 'Something went wrong, please try again.'];
                    }
                } else {
                    $msg = ['info' => 'error', 'message' => 'Data Seems to be empty, cant proceed.'];
                }
            } else {
                $msg = ['info' => 'error', 'message' => 'PC has no connection, cant proceed.'];
            }
        }
        else
        {
            $msg = ['info' => 'error', 'message' => 'Data Seems to be empty, cant proceed.'];
        }

        $this->saveLog('Payment', $paymentScheme->receipt_no, $paymentScheme->tenant_id, $msg['info'], $msg['message']);
        JSONResponse($msg);
    }

    public function uploadpaymentdatachecked()
    {
        $data        = $this->input->post(NULL);
        $count       = count($data['paymentcheck']);
        $uploadCheck = $data['paymentcheck'];
        $update      = ['upload_status' => 'Uploaded', 'upload_date'   => date('Y-m-d')];
        $msg         = array();

        if (!empty($data['paymentcheck'])) {
            for ($id = 0; $id < $count; $id++) {

                $this->db->trans_start();
                # CONTAINER FOR payment_scheme
                $paymentScheme = $this->adminmodel->getPaymentScheme($uploadCheck[$id]);

                if(isset($paymentScheme))
                {

                    $slData  = array();
                    $glData  = array();
                    $Ledger  = array();

                    # GET DATA FROM subsidiary_ledger
                    $sl      = $this->adminmodel->getSL($paymentScheme->receipt_no);
                    $payment = $this->adminmodel->getPaymentTable($paymentScheme->receipt_no);

                    # LOOP TO GET THE REFERENCE NO
                    foreach ($sl as $value) {
                        $slData[] = $this->adminmodel->getLedgers($value['ref_no'], 'subsidiary_ledger');
                    }

                    # SUBSIDIARY AND GENERAL LEDGER CONTAINER
                    $subsidiary = array();

                    # LOOP TO GET THE ARRAYS CONTAINING THE DATA FROM subsidiary_ledger USING THE ref_no
                    for ($i = 0; $i < count($slData); $i++) {
                        for ($z = 0; $z < count($slData[$i]); $z++) {
                            $subsidiary[] = $slData[$i][$z];
                        }
                    }

                    # CONTAINER
                    $doc_no = array();

                    foreach ($subsidiary as $value) {

                        $doc_no[] = $value['doc_no'];
                    }

                    # DOCUMENT NUMBER FOR ledger TABLE
                    $forLedger = array_unique($doc_no);
                    $lData     = array();

                    foreach ($forLedger as $value) {
                        $lData[] = $this->adminmodel->getLedgerTable($value);
                    }

                    # LEDGER CONTAINER
                    $ledger = array();

                    for ($i = 0; $i < count($lData); $i++) {
                        for ($z = 0; $z < count($lData[$i]); $z++) {
                            $ledger[] = $lData[$i][$z];
                        }
                    }

                    # subsidiary_ledger = $subsidiary
                    # ledger            = $ledger
                    # payment           = $payment
                    # payment_scheme    = $paymentScheme

                    $soa_file = $this->adminmodel->getSoaForPayment($paymentScheme->soa_no);

                    $url          = 'https://leasingportal.altsportal.com/uploadPaymentData';
                    $notification = 'https://leasingportal.altsportal.com/paymentNotification';

                    if ($this->isDomainAvailable('leasingportal.altsportal.com')) 
                    {
                        if (!empty($subsidiary) || !empty($ledger) || !empty($paymentScheme)) 
                        {
                            $upload   = array('subsidiary' => $subsidiary, 'ledger' => $ledger, 'paymentScheme' => $paymentScheme, 'payment' => $payment);
                            // $response = $this->sendData($url, $upload);
                            // $sms      = $this->sendData($notification, array('payment_scheme' => $paymentScheme, 'soa' => $soa_file));

                            foreach ($subsidiary as $value) {
                                $this->db->where('id', $value['id']);
                                $this->db->update('subsidiary_ledger', $update);
                            }

                            foreach ($ledger as $value) {
                                $this->db->where('id', $value['id']);
                                $this->db->update('ledger', $update);
                            }

                            if (isset($payment)) {
                                $this->db->where('id', $payment->id);
                                $this->db->update('payment', $update);
                            }

                            $this->db->where('id', $paymentScheme->id);
                            $this->db->update('payment_scheme', $update);

                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                $this->db->trans_rollback();
                                $this->saveLog('Payment', $paymentScheme->receipt_no, $paymentScheme->tenant_id, 'error', 'Uploaded Failed');
                                $msg = ['info' => 'error', 'message' => 'Uploading Failed.'];
                                JSONResponse($msg);
                            } else {
                                $this->saveLog('Payment', $paymentScheme->receipt_no, $paymentScheme->tenant_id, 'success', 'Uploaded Successfully');
                                continue;
                            }
                        } else {
                            $msg = [
                                'info' => 'error', 'message' => 'Data Seems to be empty, cant proceed.'
                            ];
                        }
                    } else {
                        $msg = ['info' => 'error', 'message' => 'PC has no connection, cant proceed.'];
                    }
                }
                else
                {
                    $msg = ['info' => 'error', 'message' => 'Data Seems to be empty, cant proceed.'];
                    JSONResponse($msg);
                }
            }

            $msg = ['info' => 'success', 'message' => 'Payment Data Succesfully Uploaded.'];
        } else {
            $msg = ['message' => 'Please Check any Payment to upload.', 'info' => 'error'];
        }

        JSONResponse($msg);
    }

    public function saveLog($type, $docno, $tenant_id, $status, $statusMessage)
    {
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
            $docs = 
            [
                'uploadlogID' => $uploadlogID,
                'documentno'  => $docno
            ];

            $this->db->insert('upload_log_docs', $docs); 
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