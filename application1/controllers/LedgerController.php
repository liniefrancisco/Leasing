<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LedgerController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
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

    function sanitize($string)
    {
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = trim($string);
        return $string;
    }

    public function getforwardedbalance()
    {
        if ($this->session->userdata('portal_logged_in')) 
        {
            $balance          = array();
            $data             = $this->input->post(NULL);
            $forwardedbalance = $this->ledgermodel->get_forwarded_balance($data['tenantid'], $data['datefrom']);

            foreach ($forwardedbalance as $f) 
            {
                if ($f['balance'] == '') 
                {
                    $balance = ['forwardedbalance' => $f['balance']];
                } 
                else 
                {
                    $balance = ['forwardedbalance' => $f['balance']];
                }
            }

            JSONResponse($balance);
        }
    }

    public function gettenantledger()
    {
        if ($this->session->userdata('portal_logged_in')) {

            $prev_refno         = '';
            $beggining_balance  = 0;
            $running_balance    = 0;
            $ledger             = array();
            $result_withbalance = array();
            $data               = $this->input->post(NULL);
            $result             = $this->ledgermodel->get_tenant_ledger($data['tenantid'], $data['datefrom'], $data['dateto']);
            $forwardedbalance   = $this->ledgermodel->get_forwarded_balance($data['tenantid'], $data['datefrom']);

            foreach ($forwardedbalance as $f) 
            {
                $ledger['fwdbalance'] = ['forwardedbalance' => $f['balance']];
            }

            foreach ($result as $value) 
            {
                if ($value['ref_no'] != $prev_refno) 
                {
                    $ledger['withbalance'][] =
                        [
                            'document_type' => $value['document_type'],
                            'flag'          => $value['type'],
                            'due_date'      => $value['due_date'],
                            'doc_no'        => $value['doc_no'],
                            'ref_no'        => $value['ref_no'],
                            'posting_date'  => $value['posting_date'],
                            'debit'         => $value['debit'],
                            'credit'        => $value['credit'],
                            'balance'       => $value['balance'],
                            'adj_amount'    => $value['adj_amount'],
                            'inv_amount'    => $value['invoice_amount'],
                            'runningBalance' => $value['runningBalance']
                        ];

                    $running_balance = $value['balance'];
                } 
                else 
                {
                    if ($value['debit'] == "0.00" || $value['debit'] == null) 
                    {
                        $new_running_balance = $running_balance - $value['credit'];

                        $ledger['withbalance'][] =
                            [
                                'document_type' => $value['document_type'],
                                'flag'           => $value['type'],
                                'due_date'      => $value['due_date'],
                                'doc_no'        => $value['doc_no'],
                                'ref_no'        => $value['ref_no'],
                                'posting_date'  => $value['posting_date'],
                                'debit'         => $value['debit'],
                                'credit'        => $value['credit'],
                                'adj_amount'    => $value['adj_amount'],
                                'inv_amount'    => $value['invoice_amount'],
                                'runningBalance' => $value['runningBalance'],
                                'balance'       => $new_running_balance,
                            ];

                        $running_balance = $new_running_balance;
                    } 
                    else 
                    {

                        $new_running_balance = $running_balance + $value['debit'];

                        $ledger['withbalance'][] =
                            [
                                'document_type' => $value['document_type'],
                                'flag'          => $value['type'],
                                'due_date'      => $value['due_date'],
                                'doc_no'        => $value['doc_no'],
                                'ref_no'        => $value['ref_no'],
                                'posting_date'  => $value['posting_date'],
                                'debit'         => $value['debit'],
                                'credit'        => $value['credit'],
                                'adj_amount'    => $value['adj_amount'],
                                'inv_amount'    => $value['invoice_amount'],
                                'runningBalance' => $value['runningBalance'],
                                'balance'       => $new_running_balance,
                            ];

                        $running_balance = $new_running_balance;
                    }
                }

                $prev_refno = $value['ref_no'];
            }

            JSONResponse($ledger);
        }
    }

    public function getpaymentdetails()
    {
        if ($this->session->userdata('portal_logged_in')) {

            $data            = $this->input->post(NULL);
            $tenantid        = $this->session->userdata('tenant_id');
            $payment_details = $this->ledgermodel->get_payment_details($data['referenceno'], $tenantid);
            JSONResponse($payment_details);
        }
    }
}