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

    public function admingettenant(){
        $from        = $this->input->get('from');
        $store       = $this->input->get('store');
        $tenant_type = $this->input->get('type');
        $trade_name  = $this->input->get('trade_name');
        $storeID     = '';

        if($store == 'AM'){
            $storeID = '1';
        }elseif($store == 'ICM'){   
            $storeID = '2';
        }elseif($store == 'ACT'){
            $storeID = '4';
        }elseif($store == 'ASCT'){
            $storeID = '5';
        }elseif($store == 'ATT'){
            $storeID = '7';
        }

        if($from === 'Internal'){
            $tenants = $this->adminmodel->populate_tradeNameInternal($tenant_type, $storeID, $trade_name);
            JSONResponse($tenants);
        }else{
            $tenants = $this->adminmodel->populate_tradeNameCAS($tenant_type, $storeID, $trade_name);
            JSONResponse($tenants);
        }
    }

    public function uploadinvoicepertenant(){
        $data         = $this->input->post(NULL, FILTER_SANITIZE_STRING);
        $tenantID     = $data['tenant_id'];
        $tenantFrom   = $data['tenant_from'];
        $url          = 'https://leasingportal.altsportal.com/perTenantUpload';
        $msg          = array();
        $subsidiary   = array(); #SUBSIDIARY LEDGER AND GENERAL LEDGER
        $ledger       = array();
        $invoice      = array();
        $tmpcharges   = array();
        $toUpload     = array();
        $update       = array();
        $this->portal = $this->load->database('portal', true);
        $this->cas = $this->load->database('cas', true);
        $tenantP      = $this->portal->query("SELECT * FROM `duxvwc44_agc-pms`.tenants WHERE tenant_id = '{$tenantID}'")->ROW();

        if (isset($data)) {
            $this->db->trans_start();

            if($tenantFrom === 'Internal'){
                $subsidiary = $this->adminmodel->getDataV1($tenantID, $data['datefrom'], $data['dateto'], 'subsidiary_ledger');
                $ledger     = $this->adminmodel->getDataV1($tenantID, $data['datefrom'], $data['dateto'], 'ledger');
                $invoice    = $this->adminmodel->getDataV2($tenantID, $data['datefrom'], $data['dateto'], 'invoicing');
                $tmpcharges = $this->adminmodel->getDataV2($tenantID, $data['datefrom'], $data['dateto'], 'tmp_preoperationcharges');
            }else{
                $subsidiary = $this->adminmodel->getDataV1CAS($tenantID, $data['datefrom'], $data['dateto'], 'subsidiary_ledger');
                $ledger     = $this->adminmodel->getDataV1CAS($tenantID, $data['datefrom'], $data['dateto'], 'ledger');
                $invoice    = $this->adminmodel->invoicingCAS($tenantID, $data['datefrom'], $data['dateto']);
                $tmpcharges = $this->adminmodel->getDataV3CAS($tenantID, $data['datefrom'], $data['dateto'], 'tmp_preoperationcharges');
            }

            $cnt = 0;
            $this->portal->trans_start();
            if (!empty($subsidiary)) {
                foreach ($subsidiary as $s) {
                    $d = [
                        'posting_date' => $s['posting_date'],
                        'transaction_date' => $s['transaction_date'],
                        'due_date' => $s['due_date'],
                        'document_type' => $s['document_type'],
                        'ref_no' => $s['ref_no'],
                        'doc_no' => $s['doc_no'],
                        'cas_doc_no' => $s['cas_doc_no'],
                        'tenant_id' => $s['tenant_id'],
                        'gl_accountID' => $s['gl_accountID'],
                        'company_code' => $s['company_code'],
                        'department_code' => $s['department_code'],
                        'debit' => $s['debit'],
                        'credit' => $s['credit'],
                        'bank_name' => $s['bank_name'],
                        'bank_code' => $s['bank_code'],
                        'tag' => $s['tag'],
                        'status' => $s['status'],
                        'with_penalty' => $s['with_penalty'],
                        'prepared_by' => $s['prepared_by'],
                        'ft_ref' => $s['ft_ref'],
                        'export_batch_code' => $s['export_batch_code'],
                        'export_batch_internal' => $s['export_batch_internal'],
                        'adj_tag' =>$s['adj_tag'],
                        'adj_ref' => $s['adj_ref'],
                        'upload_status' => $s['upload_status'],
                        'upload_date' => $s['upload_date'],
                    ];
    
                    $this->portal->insert('`duxvwc44_agc-pms`.subsidiary_ledger', $d);
                }

                $cnt += 1;

                if(!empty($ledger)){
                    foreach ($ledger as $l) {
                        $led = [
                            'posting_date'     => $l['posting_date'],
                            'transaction_date' => $l['transaction_date'],
                            'document_type'    => $l['document_type'],
                            'ref_no'           => $l['ref_no'],
                            'doc_no'           => $l['doc_no'],
                            'tenant_id'        => $l['tenant_id'],
                            'contract_no'      => $l['contract_no'],
                            'description'      => $l['description'],
                            'debit'            => $l['debit'],
                            'credit'           => $l['credit'],
                            'balance'          => $l['balance'],
                            'due_date'         => $l['due_date'],
                            'charges_type'     => $l['charges_type'],
                            'with_penalty'     => $l['with_penalty'],
                            'bank_name'        => $l['bank_name'],
                            'bank_code'        => $l['bank_code'],
                            'flag'             => $l['flag'],
                            'prepared_by'      => $l['prepared_by'],
                            'upload_status'    => $l['upload_status'],
                            'upload_date'      => $l['upload_date'],
                        ];
                        $this->portal->insert('`duxvwc44_agc-pms`.ledger', $led);
                    }
    
                    $cnt += 1;
                }
    
                if(!empty($invoice)){
                    foreach ($invoice as $i) {
                        $inv = [
                            'tenant_id' => $i['tenant_id'],
                            'contract_no' => $i['contract_no'],
                            'trade_name' => $i['trade_name'],
                            'doc_no' => $i['doc_no'],
                            'posting_date' => $i['posting_date'],
                            'transaction_date' => $i['transaction_date'],
                            'due_date' => $i['due_date'],
                            'charges_type' => $i['charges_type'],
                            'charges_code' => $i['charges_code'],
                            'description' => $i['description'],
                            'uom' => $i['uom'],
                            'prev_reading' => $i['prev_reading'],
                            'curr_reading' => $i['curr_reading'],
                            'unit_price' => $i['unit_price'],
                            'total_unit' => $i['total_unit'],
                            'expected_amt' => $i['expected_amt'],
                            'actual_amt' => $i['actual_amt'],
                            'balance' => $i['balance'],
                            'total_gross' => $i['total_gross'],
                            'store_code' => $i['store_code'],
                            'flag' => $i['flag'],
                            'tag' => $i['tag'],
                            'with_penalty' => $i['with_penalty'],
                            'status' => $i['status'],
                            'receipt_no' => $i['receipt_no'],
                            'days_in_month' =>$i['days_in_month'],
                            'days_occupied' => $i['days_occupied'],
                            'upload_status' => $i['upload_status'],
                            'upload_date' => $i['upload_date']
                        ];
                        $this->portal->insert('`duxvwc44_agc-pms`.invoicing', $inv);
                        // var_dump($this->portal->error());
                    }
    
                    $cnt += 1;
                }
    
                $this->portal->trans_complete();
    
                if($this->portal->trans_status() === FALSE){
                    $msg = ['info' => 'error', 'message' => "Failed Upload."];
                }else{
                    $this->db->trans_start();
    
                    if(isset($subsidiary)){
                        foreach ($subsidiary as $value) {
                            $this->db->where('id', $value['id'])
                                     ->update('subsidiary_ledger', $update);
                        }
                    }
    
                    if(isset($ledger)){
                        foreach ($ledger as $value) {
                            $this->db->where('id', $value['id'])
                                     ->update('ledger', $update);
                        }
                    }
    
                    if(isset($invoice)){
                        foreach ($invoice as $value) {
                            $this->db->where('id', $value['id'])
                                    ->update('invoicing', $update);
                        }
                    }
    
                    $this->db->trans_complete();
    
                    if ($this->db->trans_status() === 'FALSE') {
                        $this->db->trans_rollback();
                        $msg = ['info' => 'error', 'message' => 'Something went wrong, please check data uploaded.'];
                    } else {
                        $msg = ['info' => 'success', 'message' => 'Invoices Uploaded Succesfully.'];
                    }
                }

            }else{
                $msg = ['info' => 'No Data', 'message' => 'No Invoices Found.'];
            }

            if(!empty($tmpcharges)){
                foreach ($tmpcharges as $tmp) {
                    $tmpc = [
                        'tenant_id' => $tmp['tenant_id'], 
                        'doc_no' => $tmp['doc_no'], 
                        'cas_doc_no' => $tmp['cas_doc_no'], 
                        'description' => $tmp['description'], 
                        'posting_date' => $tmp['posting_date'], 
                        'due_date' => $tmp['due_date'], 
                        'amount' => $tmp['amount'], 
                        'org_amount' => $tmp['org_amount'], 
                        'tag' => $tmp['tag'], 
                        'upload_status' => $tmp['upload_status'], 
                        'upload_date' => $tmp['upload_date'], 
                        'export_batch_code' => $tmp['export_batch_code']
                    ];
                    $this->portal->insert('`duxvwc44_agc-pms`.tmp_preoperationcharges', $tmpc);
                }

                foreach ($tmpcharges as $value) {
                    $this->db->where('id', $value['id'])
                             ->update('tmp_preoperationcharges', $update);
                }

                $cnt += 1;
            }
        } else {
            $msg = ['info' => 'NO DATA', 'message' => 'Data seems to be empty. Please try again.'];
        }

        if($msg['info'] != 'Warning'){
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
            if(isset($docno))
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

                    // if ($this->isDomainAvailable('leasingportal.altsportal.com')) {
                //     $toUpload = ['subsidiary' => $subsidiary, 'ledger' => $ledger, 'invoicing' => $invoice, 'charges' => $tmpcharges];
                //     $portal   = $this->sendData($url, $toUpload);

                //     if ($portal == 'success') 
                //     {
                //         $update = ['upload_status' => 'Uploaded', 'upload_date' => date('Y-m-d')];

                //         foreach ($subsidiary as $value) {
                //             $this->db->where('id', $value['id'])
                //                      ->update('subsidiary_ledger', $update);
                //         }

                //         if(isset($ledger))
                //         {
                //             foreach ($ledger as $value) {
                //                 $this->db->where('id', $value['id'])
                //                          ->update('ledger', $update);
                //             }
                //         }

                //         if(isset($invoice))
                //         {
                //             foreach ($invoice as $value) {
                //                 $this->db->where('id', $value['id'])
                //                         ->update('invoicing', $update);
                //             }
                //         }

                //         if (isset($tmpcharges)) {
                //             foreach ($tmpcharges as $value) {
                //                 $this->db->where('id', $value['id'])
                //                          ->update('tmp_preoperationcharges', $update);
                //             }
                //         }

                //         $this->db->trans_complete();

                //         if ($this->db->trans_status() === 'FALSE') 
                //         {
                //             $this->db->trans_rollback();
                //             $msg = ['info' => 'error', 'message' => 'Something went wrong, please check data uploaded.'];
                //         } 
                //         else 
                //         {
                //             $msg = ['info' => 'success', 'message' => 'Invoices Uploaded Succesfully.'];
                //         }
                //     } 
                //     else if ($portal == 'error') 
                //     {
                //         $msg = ['info' => 'error', 'message' => 'Failed Uploading Invoices, Please try again.'];
                //     } 
                //     else if ($portal == 'empty') 
                //     {
                //         $msg = ['info' => 'error', 'message' => 'Failed Uploading Invoices, no data to upload.'];
                //     }
                // } 
                // else 
                // {
                //     $msg = ['info' => 'error', 'message' => 'PC has no connection, please try again.'];
                // }
}