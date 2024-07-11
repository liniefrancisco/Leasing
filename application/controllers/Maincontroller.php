<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

class MainController extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('basemodel');
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
    
    function sanitize($string){
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = trim($string);
        return $string;
    }

    function get_dateTime(){
        $timestamp = time();
        $date_time = date('F j, Y g:i:s A  ', $timestamp);
        $result['current_dateTime'] = $date_time;
        echo json_encode($result);
    }

    public function checkuser(){
        $username = $this->sanitize($this->input->post('username'));
        $password = $this->sanitize($this->input->post('password'));
        $result   = $this->basemodel->check_login($username, $password);
        $msg      = array();

        if ($result) {
            if ($this->session->userdata('user_type') == 'Tenant') {
                $msg = ['message' => 'Tenant', 'info' => 'Tenant'];
            } else if ($this->session->userdata('user_type') == 'Admin' || $this->session->userdata('user_type') == 'Accounting') {
                $msg = ['message' => 'Admin', 'info' => 'Admin'];
            } 
            else 
            {
                $msg = ['message' => 'Invalid Login, Please try again.', 'info' => 'Error'];
            }
        } 
        else 
        {
            $msg = ['message' => 'Invalid Login, Please try again.', 'info' => 'Error'];
        }

        JSONResponse($msg);
    }

    public function login()
    {
        // echo "Hello";
        $this->load->view('leasingportal/Login');
    }

    public function logout()
    {
        $newdata = array(
            'id'                    => '',
            'username'              => '',
            'tenant_id'             => '',
            'password'              => '',
            'user_type'             => '',
            'portal_logged_in'     => FALSE
        );

        $session = (object)$this->session->userdata;

        if (isset($session->session_id)) {
            $user_session_data = ['date_ended' => date('Y-m-d H:i:s')];

            $this->db->where('session_id', $session->session_id);
            $this->db->update('user_session', $user_session_data);
        }

        $this->session->unset_userdata($newdata);
        $this->session->sess_destroy();
        $this->login();
    }

    public function changeusername()
    {
        if ($this->session->userdata('portal_logged_in')) 
        {
            $newusername = $this->input->post('username');
            $username    = $this->session->userdata('username');
            $duplicate   = $this->basemodel->getusercredentials($username);
            $credentials = $this->basemodel->getusercredentials($username);
            $msg         = array();

            if(empty($duplicate)):

                $data = ['username' => $newusername];

                $this->db->where('id', $credentials->id);
                $this->db->update('portal_user', $data);

                $msg = ['message' => 'Username Changed.', 'info' => 'Success'];

            elseif($duplicate->id == $credentials->id):

                $data = ['username' => $newusername];

                $this->db->where('id', $credentials->id);
                $this->db->update('portal_user', $data);

                $msg = ['message' => 'Username Changed.', 'info' => 'Success'];

            else:

                $msg = ['message' => 'Username Already Used', 'info' => 'Error'];

            endif;

            JSONResponse($msg);
        }
        else
        {
            $this->load->view('leasingportal/Login');
        }
    }

    public function changepassword()
    {
        if ($this->session->userdata('portal_logged_in')) 
        {
            $password    = $this->input->post('password');
            $username    = $this->session->userdata('username');
            $credentials = $this->basemodel->getusercredentials($username);
            $msg         = array();

            if(!empty($credentials))
            {
                $this->db->trans_start();
                $data = ['password' => MD5($password), 'password_in_roman' => $password];

                $this->db->where('id', $credentials->id);
                $this->db->update('portal_user', $data);

                $this->db->trans_complete();

                if($this->db->trans_status() === FALSE)
                {
                    $msg = ['message' => 'Something went wrong please try again.', 'info' => 'Error'];
                }
                else
                {
                    $msg = ['message' => 'Password Changed.', 'info' => 'Success'];
                }
            }
            else
            {
                $msg = ['message' => 'No Such User Found, Please Try Again.', 'info' => 'Empty'];
            }

            JSONResponse($msg);
        }
        else
        {
            $this->load->view('leasingportal/Login');
        }
    }

    public function userpasswordform()
    {
        if ($this->session->userdata('portal_logged_in')) 
        {
            $data['title']        = 'User Settings';
            $data['status']       = 'usersettings';
            $data['current_date'] = $this->_currentDate;
            $this->load->view('leasingportal/Header', $data);
            $this->load->view('leasingportal/UserPasswordForm');
            $this->load->view('leasingportal/Footer');
        }
        else
        {
            $this->load->view('leasingportal/Login');
        }
    }

    public function useruserform()
    {
        if ($this->session->userdata('portal_logged_in')) 
        {
            $data['title']        = 'User Settings';
            $data['status']       = 'usersettings';
            $data['current_date'] = $this->_currentDate;
            $this->load->view('leasingportal/Header', $data);
            $this->load->view('leasingportal/UserUserForm');
            $this->load->view('leasingportal/Footer');
        }
        else
        {
            $this->load->view('leasingportal/Login');
        }
    }

    public function home()
    {
        if ($this->session->userdata('portal_logged_in')) 
        {
            $data['title']        = 'My SOA';
            $data['status']       = 'soa';
            $data['current_date'] = $this->_currentDate;
            $this->load->view('leasingportal/Header', $data);
            $this->load->view('leasingportal/MySoa');
            $this->load->view('leasingportal/Footer');
        }
        else
        {
            $this->load->view('leasingportal/Login');
        }
    }

    public function mysoa()
    {
        if ($this->session->userdata('portal_logged_in')) 
        {
            $data['title']        = 'My SOA';
            $data['status']       = 'soa';
            $data['current_date'] = $this->_currentDate;
            $this->load->view('leasingportal/Header', $data);
            $this->load->view('leasingportal/MySoa');
            $this->load->view('leasingportal/Footer');
        }
        else
        {
            $this->load->view('leasingportal/Login');
        }
    }

    public function myledger()
    {
        if ($this->session->userdata('portal_logged_in')) 
        {
            $data['title']        = 'My Ledger';
            $data['status']       = 'ledger';
            $data['current_date'] = $this->_currentDate;
            $this->load->view('leasingportal/Header', $data);
            $this->load->view('leasingportal/MyLedger');
            $this->load->view('leasingportal/Footer');
        }
        else
        {
            $this->load->view('leasingportal/Login');
        }
    }

    public function utilityreadings()
    {
        if ($this->session->userdata('portal_logged_in')) 
        {
            $data['title']        = 'Utility Readings';
            $data['status']       = 'readings';
            $data['current_date'] = $this->_currentDate;
            $this->load->view('leasingportal/Header', $data);
            $this->load->view('leasingportal/UtilityReadings');
            $this->load->view('leasingportal/Footer');
        }
        else
        {
            $this->load->view('leasingportal/Login');
        }
    }

    public function paymentadvice()
    {   
        if ($this->session->userdata('portal_logged_in')) 
        {   
            $store                = explode('-', $this->session->userdata('tenant_id'))[0];
            $data['title']        = 'Payment Advice';
            $data['status']       = 'advice';
            $data['current_date'] = $this->_currentDate;
            $data['location']     = '';

            if ($store == 'ACT') {
                $data['location'] = 'Alta Citta';
            } else if ($store == 'AM') {
                $data['location'] = 'Alturas Mall';
            } else if ($store == 'ICM') {
                $data['location'] = 'Island City Mall';
            } else if ($store == 'PM') {
                $data['location'] = 'Plaza Marcela';
            }

            $this->load->view('leasingportal/Header', $data);
            $this->load->view('leasingportal/PaymentAdvice');
            $this->load->view('leasingportal/Footer');
        }
        else
        {
            $this->load->view('leasingportal/Login');
        }
    }

    public function adviceHistory()
    {
        if ($this->session->userdata('portal_logged_in')) 
        {
            $data['title']        = 'Utility Readings';
            $data['status']       = 'readings';
            $data['current_date'] = $this->_currentDate;
            $this->load->view('leasingportal/Header', $data);
            $this->load->view('leasingportal/PaymentAdviceHistory');
            $this->load->view('leasingportal/Footer');
        }
        else
        {
            $this->load->view('leasingportal/Login');
        }
    }

    public function maintenance()
    {
        $this->load->view('maintenance/maintenance');
    }

    # LEASING PORTAL ONLINE API AND FUNCTIONS

    public function uploadSoaDataAPI()
    {
        $soaFile = $this->input->post('soa_file');
        $soaLine = $this->input->post('soa_line');
        
        $duplicate = array();
        $notSaved  = array();
        $toSave    = array();
        
        if(!empty($soaFile))
        {
            $this->db->trans_start();
            
            $soa_file = 
            [

                'tenant_id'        => $soaFile['tenant_id'],
                'file_name'        => $soaFile['file_name'],
                'soa_no'           => $soaFile['soa_no'],
                'billing_period'   => $soaFile['billing_period'],
                'amount_payable'   => $soaFile['amount_payable'],
                'posting_date'     => $soaFile['posting_date'],
                'collection_date'  => $soaFile['collection_date'],
                'transaction_date' => $soaFile['transaction_date'],
                'upload_status'    => 'Uploaded',
                'upload_date'      => date('Y-m-d')
            ];
            
            $this->db->insert('soa_file', $soa_file);
            
            if(isset($soaLine))
            {
                foreach($soaLine as $value)
                {
                    $soa_line = 
                    [
                       
                        'soa_no'    => $value['soa_no'],
                        'doc_no'    => $value['doc_no'],
                        'amount'    => $value['amount'],
                        'tenant_id' => $value['tenant_id'],
                    ];
                    
                    $this->db->insert('soa_line', $soa_line);
                }
            }
            
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                die("error");
            }
            else
            {
                die("success");
            }
        }
        else
        {
            die("empty");
        }
    }

    public function uploadSoaFile()
    {
        $pdfb64   = $this->input->post('pdfB64');
        $filename = $this->input->post('file_name');
        $path     = '../leasingportal.altsportal.com/assets/pdf';
        
        if(!file_exists($path."/".$filename))
        {
            $file = fopen($path."/".$filename, "wb");
            $uploadedFile = fwrite($file, base64_decode($pdfb64));
            fclose($file);
            
            if($uploadedFile)
            {
                die("PDF Saved");
            }
            else
            {
                die("Error");
            }
        }
        else
        {
            die("Exist");
        }
    }

    public function notifications()
    {
        $soaFile  = $this->input->post('soa_file');
        $balance  = $this->input->post('balance');
        $previous = $this->input->post('previous');
        $tenant   = $this->input->post('tenant');
        $source   = '';
        
        $postingDate = date_create($soaFile['posting_date']);
        $dueDate     = date_create($soaFile['collection_date']);
        
        if(substr($soaFile['tenant_id'], 0, -9) == 'ICM' || substr($soaFile['tenant_id'], 0, -9) == 'AM' || substr($soaFile['tenant_id'], 0, -9) == 'ACT')
        {
            $source = 'asc.support@altsportal.com';
        }
        else if(substr($soaFile['tenant_id'], 0, -9) === 'PM')
        {
            $source = 'mfi.support@altsportal.com';
        }
        
        # - - PROCESS EMAIL AND SMS
        
        $subject      = "Statement of Account for " . $soaFile['tenant_id']; 
        $smsMessage   = "Hi! SOA for " . date_format(date_create($soaFile['posting_date']),"M d, Y") . " under Tenant ID: " . $soaFile['tenant_id'] . "  is now available for viewing at https://leasingportal.altsportal.com.\nYour total amount due is P " . number_format($soaFile['amount_payable'], 2) . ", due date on " . date_format(date_create($soaFile['collection_date']),"M d, Y") . "\nCurrent balance is now P " . number_format($balance, 2) . "\nThank you."; 
        $emailMessage = "Dear Tenant ID: " . $soaFile['tenant_id'] . ", " . $tenant['trade_name'] . ".\n \nSOA for " . date_format(date_create($soaFile['posting_date']),"M d, Y") . " under Tenant ID: " . $soaFile['tenant_id'] . "  is now available for viewing at https://leasingportal.altsportal.com.\nYour total amount due is P " . number_format($soaFile['amount_payable'], 2) . ", due date on " . date_format(date_create($soaFile['collection_date']),"M d, Y") . "\nCurrent balance is now P " . number_format($balance, 2) . "\n \nThank you.";
        
        $num      = str_replace(array(' ', '-'), '', explode("/", $tenant['contact_number']));
        $numCount = count($num);
        $emm      = explode("/", $tenant['email']);
        $emCount  = count($emm);
            
        for($n = 0; $n <= 0; $n++)
        {
            if (preg_match("/^[0-9]{4}[0-9]{3}[0-9]{4}$/", $num[$n])) {
                $sms = $this->sendSMS($num[$n],$smsMessage);
                $this->db->insert('sms_log', ['subject' => $subject, 'number' => $num[$n], 'message' => $smsMessage, 'date_sent' => date('Y-m-d')]);
            }
        }
            
        for($e = 0; $e < $emCount; $e++)
        {
            if($emm[$e] != NULL && $emm[$e] != 'N/A' && $emm[$e] != '')
            {
                $mail = $this->sendMail($subject, $emailMessage, $emm[$e], $source);
            
                if($mail == 'Sent')
                {
                    $this->db->insert('email_log', ['subject' => $subject, 'email' => $emm[$e], 'message' => $emailMessage, 'date_emailed' => date('Y-m-d')]);   
                }
            }
        }
        
        die('Success');
    }

    public function uploadAllInvoiceData()
    {
        $data      = $this->input->post('data');
        $table     = $this->input->post('table');
        $duplicate = array();
        $a         = array();
        $b         = array();
        $status    = '';
        
        if(!empty($data) && !empty($table))
        {
            $this->db->trans_start();
            
            foreach($data as $value)
            {
                $a = $value;
                $this->db->insert($table, $a);
            }
            
            $this->db->trans_complete();
            
            if($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                die("Uploading Failed.");
            }
            else
            {
                die('Success');
            }
        }
        else
        {
            die("No Data");
        }
    }

    public function perTenantUpload()
    {
        $subsidiary = $this->input->post('subsidiary');
        $ledger     = $this->input->post('ledger');
        $invoicing  = $this->input->post('invoicing');
        $charges    = $this->input->post('charges');
        
        if(!empty($subsidiary))
        {
            $this->db->trans_start();
            
            if(isset($invoicing))
            {
                foreach($invoicing as $value)
                {
                    $data = $value;
                    
                    $this->db->insert('invoicing', $data);
                }
            }
            
            
            foreach($subsidiary as $value)
            {
                $data = $value;
                
                $this->db->insert('subsidiary_ledger', $data);
                $this->db->insert('general_ledger', $data);
                
            }
            
            if(isset($ledger))
            {
                foreach($ledger as $value)
                {
                    $data = $value;
                    $this->db->insert('ledger', $data);
                }
            }
            
            if(isset($charges))
            {
                foreach($charges as $value)
                {
                    $data = $value;
                    $this->db->insert('tmp_preoperationcharges', $data);
                }   
            }
            
            $this->db->trans_complete();
            
            if($this->db->trans_status() === 'FALSE')
            {
                $this->db->trans_rollback();
                die('error');
            }
            else
            {
                die('success');                
            }
        }
        else
        {
            die('empty');
        }
    }

    public function uploadPaymentData(){
        $subsidiary    = $this->input->post('subsidiary');
        $ledger        = $this->input->post('ledger');
        $paymentScheme = $this->input->post('paymentScheme');
        $payment       = $this->input->post('payment');
        
        if(isset($subsidiary)){
            $this->db->trans_start();
            
            $data1 = array();
            
            foreach($subsidiary as $value){
                $this->db->insert('subsidiary_ledger', $value);
                $this->db->insert('general_ledger', $value);
            }
            
            
            $data2 = array();
            
            if(isset($ledger)){
                foreach($ledger as $value){
                    $this->db->insert('ledger', $value);
                }   
            }
            
            if(isset($paymentScheme)){
                $this->db->insert('payment_scheme', $paymentScheme);   
            }
            
            if(isset($payment)){
                $this->db->insert('payment', $payment);
            }
            
            $this->db->trans_complete();
            
            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                die("Uploading Failed.");
            }else{
                die('success');
            }
        }else{
            die("NO DATA");
        }
    }

    public function paymentNotification()
    {
        $payment_scheme = $this->input->post('payment_scheme');
        $soa            = $this->input->post('soa');
        $prospect       = $this->basemodel->getTenant($payment_scheme['tenant_id']);
        $tenantuser     = $this->basemodel->getTenantUser($payment_scheme['tenant_id']);
        $source         = '';
        
        $postingDate = date_create($soaFile['posting_date']);
        $dueDate     = date_create($soaFile['collection_date']);
        
        if(substr($payment_scheme['tenant_id'], 0, -9) == 'ICM' || substr($payment_scheme['tenant_id'], 0, -9) == 'AM' || substr($payment_scheme['tenant_id'], 0, -9) == 'ACT')
        {
            $source = 'asc.support@altsportal.com';
        }
        else if(substr($payment_scheme['tenant_id'], 0, -9) === 'PM')
        {
            $source = 'mfi.support@altsportal.com';
        }
        
        # - - PROCESS EMAIL AND SMS
        # - - PAYMENT
        
        $paymentMessageSMS  = "Thank you " . $payment_scheme['tenant_id'] . " for your payment. We have received P " . number_format($payment_scheme['amount_paid'], 2) . " as payment for " . date_format(date_create($soa['posting_date']),"M d, Y") . ".\n \nYour current balance now is P " . number_format($payment_scheme['amount_due'] - $payment_scheme['amount_paid'], 2). ".\n \nThank You.";
        $paymentMessageMail = "Dear " . $tenantuser->username . " (" . $payment_scheme['tenant_id'] . ", " . $prospect->trade_name . "),\n\nThank you for your payment. We have received P " . number_format($payment_scheme['amount_paid'], 2) . " as payment for " . date_format(date_create($soa['posting_date']),"M d, Y") . ".\n \nYour current balance now is P " . number_format($payment_scheme['amount_due'] - $payment_scheme['amount_paid'], 2). ".\n \nThank You.";
        
        $num      = str_replace(array(' ', '-'), '', explode("/", $prospect->contact_number));
        $numCount = count($num);
        $emm      = explode("/", $prospect->email);
        $emCount  = count($emm);
            
        for($n = 0; $n <= 0; $n++)
        {
            if (preg_match("/^[0-9]{4}[0-9]{3}[0-9]{4}$/", $num[$n])) {
                $sms = $this->sendSMS($num[$n],$paymentMessageSMS);
                $this->db->insert('sms_log', ['subject' => $payment_scheme['tenant_id'], 'number' => $num[$n], 'message' => $paymentMessageSMS, 'date_sent' => date('Y-m-d')]);
            }
        }
            
        for($e = 0; $e < $emCount; $e++)
        {
            if($emm[$e] != NULL && $emm[$e] != 'N/A' && $emm[$e] != '')
            {
                $mail = $this->sendMail($payment_scheme['tenant_id'] . " Payment Received", $paymentMessageMail, $emm[$e], $source);
                
                if($mail == 'Sent')
                {
                    $this->db->insert('email_log', ['subject' => $payment_scheme['tenant_id'] . " Payment Received", 'email' => $emm[$e], 'message' => $paymentMessageMail, 'date_emailed' => date('Y-m-d')]);
                }
            }
        }
            
        die('sent');
    }

    public function blastSend()
    {
        $tinfo   = $this->input->post('info');
        $apicode = "PR-ALTUR166130_RHH2A";
        $pswd    = "9)h!tc%#y$";
        $data    = array();
        
        foreach($tinfo as $ti) 
        {
            $defaultPassword = "Portal" . str_replace(array('ICM-LT', '0'), "", $ti['tenant_id']);
            $message         = "Good Day,\n\nWelcome ".$ti['trade_name']." (".$ti['tenant_id'].", ".$ti['location_desc'].", ".$ti['location_code'].") to the Leasing Portal! You can access your account on https://leasingportal.altsportal.com using \nUser: " . $ti['tenant_id'] ." \nPassword: ".$defaultPassword." \n\nThank You!";
            $num             = str_replace(array(' ', '-'), '', explode("/", $ti['contact_number']));
            $numCount        = count($num);
            $emm             = explode("/", $ti['email']);
            $emCount         = count($emm);
            
            $store = explode('-', $ti['tenant_id'])[0];
            
            if($store == 'ICM' || $store == 'AM' || $store == 'ACT')
            {
                $source = 'asc.support@altsportal.com';
            }
            else if($store === 'PM')
            {
                $source = 'mfi.support@altsportal.com';
            }
            
            for($n = 0; $n < $numCount; $n++)
            {
                if (preg_match("/^[0-9]{4}[0-9]{3}[0-9]{4}$/", $num[$n])) {
                    $sms = $this->sendSMS($num[$n],$message);
                }
                else
                {
                    $data = 
                    [
                        'trade_name'     => $ti['trade_name'],
                        'tenant_id'      => $ti['tenant_id'], 
                        'contact_number' => $num[$n], 
                        'email'          => '-', 
                        'date_sent'      => date('Y-m-d'), 
                        'time_sent'      => date("h:i:s a")
                    ];
                    
                    $this->db->insert('blastLog', $data);
                    
                }
            }
            
            for($e = 0; $e < $emCount; $e++)
            {
                if($emm[$e] != NULL && $emm[$e] != 'N/A' && $emm[$e] != '')
                {
                    $sendEmail = $this->sendMail('Update: Welcome To Leasing Portal', $message, $emm[$e], $source);
                    $sendEmail = $this->sendMail('Update - Welcome To Leasing Portal', $message, 'guiraldolyle@gmail.com', $source);
                }
                else
                {
                    $data = 
                    [
                        'trade_name'     => $ti['trade_name'],
                        'tenant_id'      => $ti['tenant_id'], 
                        'contact_number' => '-', 
                        'email'          => $emm[$e], 
                        'date_sent'      => date('Y-m-d'), 
                        'time_sent'      => date("h:i:s a")
                    ];
                    
                    $this->db->insert('blastLog', $data);
                }
            }
        }
         
         die('Success');
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
    
    public function sendMail($subject, $message, $email, $source)
    {
        $this->load->library('email');
        
        $this->email->from($source);
        $this->email->to($email);
        $this->email->set_header('Header1', 'Value1');
        $this->email->subject($subject);
        $this->email->message($message);
        
        if($this->email->send()) 
        {
            return 'Sent'; 
        } 
        else 
        {   
            return 'Error'; 
        }
    }

    public function sendSMS($number,$msg)
    {   
        $apicode = "PR-ALTUR166130_RHH2A";
        $pswd    = "9)h!tc%#y$";

        $sendSMS = $this->itexmo($number, $msg, $apicode, $pswd);       
        return $sendSMS;
    }

    public function itexmo($number, $message, $apicode, $passwd)
    {
        $ch = curl_init();
        $itexmo = array('1' => $number, '2' => $message, '3' => $apicode, 'passwd' => $passwd);
        curl_setopt($ch, CURLOPT_URL,"https://www.itexmo.com/php_api/api.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($itexmo));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return curl_exec ($ch);
        curl_close ($ch); 
    }
    
    public function test()
    {
        $query = $this->db->query('SELECT file_name FROM `soa_file`')->RESULT_ARRAY();
        
        foreach($query as $value)
        {
            echo $value['file_name'];
            echo "<br>";
        }
        
        
    }

    public function uploadNewDataOnline(){

        $data      = $this->input->post('data');
        $table     = $this->input->post('table');
        $container = array();
        
        $this->db->trans_start();
        foreach($data as $value){
            if($table == 'prospect'){
                $container = $value;
                $dups      = $this->basemodel->checkDups($table, ['id' => $value['id']]);

                if(empty($dups)){
                    $this->db->insert($table, $container);
                }
            }else if($table == 'tenants'){
                $container = $value;
                $dups      = $this->basemodel->checkDups($table, ['id' => $value['id']]);

                if(empty($dups)){
                    $this->db->insert($table, $container);
                }
            }else if($table == 'location_code'){
                $container = $value;
                $dups      = $this->basemodel->checkDups($table, ['id' => $value['id']]);

                if(empty($dups)){
                    $this->db->insert($table, $container);
                }
            }
        }
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            die('error');
        }
        else{
            die('success');
        }

    }
    
    # LEASING PORTAL ONLINE API AND FUNCTIONS

    // public function createUsers()
    // {
    //     $tenant      = $this->basemodel->tenantForUser();
    //     $newUsers    = array();
    //     $existedUser = array();

    //     $this->db->trans_start();
    //     foreach ($tenant as $key => $value) {

    //         $dup = $this->basemodel->checkUserDuplicate($value['tenant_id']);

    //         if(empty($dup))
    //         {
    //             $defaultPassword = MD5("Portal" . str_replace(array('ATT', 'ICM', 'AM', 'PM', 'ACT', '-', 'LT', 'ST', '0'), "", $value['tenant_id']));
    //             $password2       = "Portal" . str_replace(array('ATT', 'ICM', 'AM', 'PM', 'ACT', '-', 'LT', 'ST', '0'), "", $value['tenant_id']);

    //             $newUsers = 
    //             [
    //                 'tenant_id'         => $value['tenant_id'],
    //                 'name'              => $value['trade_name'],
    //                 'username'          => $value['tenant_id'],
    //                 'password'          => $defaultPassword,
    //                 'password_in_roman' => $password2,
    //                 'type'              => 'Tenant',
    //                 'status'            => 'Active'
    //             ];

    //             $this->db->insert('portal_user', $newUsers);
    //         }
    //     }
        
    //     $this->db->trans_complete();

    //     if($this->db->trans_status() === FALSE)
    //     {
    //         $this->db->trans_rollback();
    //         echo "Failed";
    //     }
    //     else
    //     {
    //         echo "Success";
    //     }
    // }

    // public function testingSMS(){
        
    //     ITEXMOSMS::send_sms([09773166658], 'same');
    // }
}
