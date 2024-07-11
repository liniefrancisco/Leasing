<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PaymentAdviceController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('paymentadvicemodel');
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

    public function soabalances()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $tenantid = $this->session->userdata('tenant_id');
            $soadocs  = $this->paymentadvicemodel->getSoaWithBalances($tenantid);

            JSONResponse($soadocs);
        } else {
            redirect('leasingportal/Login');
        }
    }

    public function soaamount()
    {
        if($this->session->userdata('portal_logged_in'))
        {
            $soano = $this->input->post('soa');
            $soa   = $this->paymentadvicemodel->getSoa($soano);

            JSONResponse($soa);
        }
        else
        {
            redirect('leasingportal/Login');
        }
    }

    public function savepaymentadvice()
    {
        $data           = $this->input->post(NULL);
        $advicecode     = $this->paymentadvicemodel->advicecode(false);
        $multilocation  = array();
        $msg            = array();
        $payment_advice = array();
        $pa_soa         = array();

        # CHECK PROOF OF PAYMENT
        $proof_of_transfer = $_FILES['proofoftransfer']['name'];
        $file_name         = str_replace(['"', '[', ']'], '', json_encode($proof_of_transfer));
        $ext               = pathinfo($_FILES['proofoftransfer']['name'], PATHINFO_EXTENSION);
        $target            = getcwd() . DIRECTORY_SEPARATOR . 'assets/proof_of_payment/' . $file_name;


        if (!empty($data['multi'])) {
            foreach ($data['multi'] as $value) {
                if($value['locationamount'] != 0)
                {
                    $multilocation[] = $value;
                }
                else
                {
                    $msg = ['message' => 'Amount could not be zero, please input amount.', 'info' => 'Error'];
                    JSONResponse($msg);
                }
            }
        }

        if (isset($data)) {
                if ($file_name != '') {
                    if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png') {
                        $this->db->trans_start();

                        move_uploaded_file($_FILES['proofoftransfer']['tmp_name'], $target);

                        if ($data['paymenttype'] == 'One Location') {
                            $payment_advice =
                                [
                                    'advice_code'       => $advicecode,
                                    'store'             => $data['storelocation'],
                                    'tenant_id'         => $this->session->userdata('tenant_id'),
                                    'bank_account'      => $data['storebankaccount'],
                                    'store_account'     => $data['storeaccountnumber'],
                                    'tenant_bank'       => $data['tenantaccount'],
                                    'account_number'    => $data['tenantaccountnumber'],
                                    'account_name'      => $data['tenantaccountname'],
                                    'payment_date'      => $data['paymentdate'],
                                    'payment_type'      => $data['paymenttype'],
                                    'amount_paid'       => str_replace(',', '', $data['amounttopay']),
                                    'proof_of_transfer' => $file_name
                                ];

                            $this->db->insert('payment_advice', $payment_advice);

                            $pa_id = $this->db->insert_id();

                            $pa_soa =
                                [
                                    'payment_advice_id' => $pa_id,
                                    'soa_no'            => $data['soano'],
                                    'tenant_id'         => $this->session->userdata('tenant_id'),
                                    'total_payable'     => str_replace(',', '', $data['totalpayable'])
                                ];

                            $this->db->insert('payment_advice_soa', $pa_soa);
                        } else {
                            $payment_advice =
                                [
                                    'advice_code'       => $advicecode,
                                    'store'             => $data['storelocation'],
                                    'tenant_id'         => $this->session->userdata('tenant_id'),
                                    'bank_account'      => $data['storebankaccount'],
                                    'store_account'     => $data['storeaccountnumber'],
                                    'tenant_bank'       => $data['tenantaccount'],
                                    'account_number'    => $data['tenantaccountnumber'],
                                    'account_name'      => $data['tenantaccountname'],
                                    'payment_date'      => $data['paymentdate'],
                                    'payment_type'      => $data['paymenttype'],
                                    'amount_paid'       => str_replace(',', '', $data['totalamountpaid']),
                                    'proof_of_transfer' => $file_name
                                ];

                            $this->db->insert('payment_advice', $payment_advice);

                            $pa_id = $this->db->insert_id();

                            foreach ($multilocation as $value) {

                                $pa_soa =
                                    [
                                        'payment_advice_id' => $pa_id,
                                        'soa_no'            => $data['paymenttype'],
                                        'tenant_id'         => $value['locations'],
                                        'amount_paid'       => str_replace(',', '', $value['locationamount'])
                                    ];

                                $this->db->insert('payment_advice_soa', $pa_soa);
                            }
                        }

                        $this->db->trans_complete();

                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();

                            JSONResponse(['info' => 'error', 'message' => 'Something went wrong! Unable to send payment advice']);
                        } else {
                            $msg = ['message' => 'Payment Advice Posted.', 'info' => 'Success'];
                        }
                    } else {
                        $msg = ['message' => 'File Format not supported. Only image files are supported. Example: jpg, jpeg, and png.', 'info' => 'Error'];
                    }
                } else {
                    $msg = ['message' => 'Please upload proof of payment to continue.', 'info' => 'Error'];
                }
            } else {
                $msg = ['message' => 'Data sent seems to be empty. Please try again.', 'info' => 'Error'];
            }

            JSONResponse($msg);
    }
}