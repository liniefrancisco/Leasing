<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

class AdminController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('portal_model');
        $this->load->model('adminmodel');
        $this->load->model('basemodel');
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

    public function admindashboard()
    {
        if ($this->session->userdata('portal_logged_in')) {
            $data['title']        = 'Admin Dashboard';
            $data['status']       = 'admindashboard';
            $data['current_date'] = $this->_currentDate;

            $this->load->view('leasingportal/AdminHeader', $data);
            $this->load->view('leasingportal/AdminDashboard');
            $this->load->view('leasingportal/AdminFooter');
        }
        else
        {
            $this->load->view('leasingportal/Login');
        }
    }

    public function admininvoicepertenant()
    {
        if ($this->session->userdata('portal_logged_in')) 
        {
            $data['title']        = 'Admin Invoice Upload';
            $data['status']       = 'admininvoiceupload';
            $data['current_date'] = $this->_currentDate;

            $this->load->view('leasingportal/AdminHeader', $data);
            $this->load->view('leasingportal/AdminInvoicePerTenant');
            $this->load->view('leasingportal/AdminFooter');
        }
        else
        {
            $this->load->view('leasingportal/Login');
        }
    }

    public function adminsoa()
    {
        if ($this->session->userdata('portal_logged_in')) 
        {
            $data['title']        = 'Admin SOA Upload';
            $data['status']       = 'adminsoaupload';
            $data['current_date'] = $this->_currentDate;
            

            $this->load->view('leasingportal/AdminHeader', $data);
            $this->load->view('leasingportal/AdminSOA');
            $this->load->view('leasingportal/AdminFooter');
        }
        else
        {
            $this->load->view('leasingportal/Login');
        }
    }

    public function paymentpertenant()
    {
        if ($this->session->userdata('portal_logged_in')) 
        {
            $data['title']        = 'Admin Payment Upload';
            $data['status']       = 'adminpaymentupload';
            $data['current_date'] = $this->_currentDate;
            

            $this->load->view('leasingportal/AdminHeader', $data);
            $this->load->view('leasingportal/AdminPaymentPerTenant');
            $this->load->view('leasingportal/AdminFooter');
        }
        else
        {
            $this->load->view('leasingportal/Login');
        }
    }

    public function paymentperstore()
    {
        if ($this->session->userdata('portal_logged_in')) 
        {
            $data['title']        = 'Admin Payment Upload';
            $data['status']       = 'adminpaymentupload';
            $data['current_date'] = $this->_currentDate;
            

            $this->load->view('leasingportal/AdminHeader', $data);
            $this->load->view('leasingportal/AdminPaymentPerStore');
            $this->load->view('leasingportal/AdminFooter');
        }
        else
        {
            $this->load->view('leasingportal/Login');
        }
    }

    public function uploadinghistory()
    {
        if ($this->session->userdata('portal_logged_in')) 
        {
            $data['title']        = 'Admin Uploading History';
            $data['status']       = 'adminuploadinghistory';
            $data['current_date'] = $this->_currentDate;
            

            $this->load->view('leasingportal/AdminHeader', $data);
            $this->load->view('leasingportal/AdminUploadHistory');
            $this->load->view('leasingportal/AdminFooter');
        }
        else
        {
            $this->load->view('leasingportal/Login');
        }
    }

    public function paymentnotices()
    {
        if ($this->session->userdata('portal_logged_in')) 
        {
            $data['title']        = 'Admin Payment Advice';
            $data['status']       = 'adminupaymentadvice';
            $data['current_date'] = $this->_currentDate;
            

            $this->load->view('leasingportal/AdminHeader', $data);
            $this->load->view('leasingportal/AdminPaymentAdvice');
            $this->load->view('leasingportal/AdminFooter');
        }
        else
        {
            $this->load->view('leasingportal/Login');
        }
    }

    public function paymentnoticeshistory()
    {
        if ($this->session->userdata('portal_logged_in')) 
        {
            $data['title']        = 'Admin Payment Advice History';
            $data['status']       = 'adminupaymentadvicehistory';
            $data['current_date'] = $this->_currentDate;
            

            $this->load->view('leasingportal/AdminHeader', $data);
            $this->load->view('leasingportal/AdminPaymentAdviceHistory');
            $this->load->view('leasingportal/AdminFooter');
        }
        else
        {
            $this->load->view('leasingportal/Login');
        }
    }

    public function adminusers()
    {
        if ($this->session->userdata('portal_logged_in')) 
        {
            $data['title']        = 'Admin Users';
            $data['status']       = 'adminusers';
            $data['current_date'] = $this->_currentDate;
            

            $this->load->view('leasingportal/AdminHeader', $data);
            $this->load->view('leasingportal/AdminUser');
            $this->load->view('leasingportal/AdminFooter');
        }
        else
        {
            $this->load->view('leasingportal/Login');
        }
    }

    public function blastUser()
    {
        if ($this->session->userdata('portal_logged_in')) 
        {
            $data['title']        = 'Admin Blast';
            $data['status']       = 'adminblast';
            $data['current_date'] = $this->_currentDate;
            

            $this->load->view('leasingportal/AdminHeader', $data);
            $this->load->view('leasingportal/AdminBlast');
            $this->load->view('leasingportal/AdminFooter');
        }
        else
        {
            $this->load->view('leasingportal/Login');
        }
    }
}
