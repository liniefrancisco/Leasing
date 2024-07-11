<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

class AdminUsersController extends CI_Controller
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

    public function getUsers()
    {
        $users = $this->adminmodel->getUsers();
        JSONResponse($users);
    }

    public function getProspects()
    {
        $store     = $this->input->post('store');
        $type      = $this->input->post('type');
        $prospects = $this->adminmodel->getProspects($store, $type);

        JSONResponse($prospects);
    }

    public function saveNewUser()
    {
        $data      = $this->input->post(NULL);
        $duplicate = array();
        $msg       = array();

        if(isset($data))
        {
            $this->db->trans_start();
            if($data['userType'] == 'Tenant')
            {              

                if($data['tradeName'] == '')
                {
                    $msg = ['message' => 'Please Select Trade Name.', 'info' => 'error'];
                    JSONResponse($msg);
                }
                else if($data['tenantUserName'] == '')
                {
                    $msg = ['message' => 'Please Input Username', 'info' => 'error'];
                    JSONResponse($msg);
                }
                else if($data['password'] == '')
                {
                    $msg = ['message' => 'Please Input Password', 'info' => 'error'];
                    JSONResponse($msg);
                }

                $tradename = $this->adminmodel->getProspectName($data['tenantUserName']);
                $duplicate = $this->adminmodel->getUserBy($data['tenantUserName']);

                if(empty($duplicate))
                {
                    $data = 
                    [
                        'tenant_id'         => $data['tradeName'],
                        'name'              => $tradename->trade_name,
                        'username'          => $data['tenantUserName'],
                        'password'          => MD5($data['password']),
                        'password_in_roman' => $data['password'],
                        'type'              => $data['userType'],
                        'status'            => 'Active'
                    ];

                    $this->db->insert('portal_user', $data);
                }
                else
                {
                    $msg = ['message' => 'User already exist. Try another.', 'info' => 'error'];
                    JSONResponse($msg);
                }
            }
            else
            {
                if($data['firstName'] == '')
                {
                    $msg = ['message' => 'Please Firstname', 'info' => 'error'];
                    JSONResponse($msg);
                }
                else if($data['lastName'] == '')
                {
                    $msg = ['message' => 'Please Lastname', 'info' => 'error'];
                    JSONResponse($msg);
                }
                else if($data['userName'] == '')
                {
                    $msg = ['message' => 'Please Input Username', 'info' => 'error'];
                    JSONResponse($msg);
                }
                else if($data['password'] == '')
                {
                    $msg = ['message' => 'Please Input Password', 'info' => 'error'];
                    JSONResponse($msg);
                }

                $duplicate = $this->adminmodel->getUserBy($data['userName']);

                if(empty($duplicate))
                {
                    $data = 
                    [
                        'tenant_id'         => 'N/A',
                        'name'              => $data['firstName'] . ' ' . $data['middleName'][0] .'. '. $data['lastName'],
                        'username'          => $data['userName'],
                        'password'          => MD5($data['password']),
                        'password_in_roman' => $data['password'],
                        'type'              => $data['userType'],
                        'status'            => 'Active'
                    ];

                    $this->db->insert('portal_user', $data);
                }
                else
                {
                    $msg = ['message' => 'User already exist. Try another.', 'info' => 'error'];
                    JSONResponse($msg);
                }
            }
            $this->db->trans_complete();

            if($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                $msg = ['message' => 'Something went wrong. Please try again.', 'info' => 'error'];
            }
            else
            {
                $msg = ['message' => 'User Added.', 'info' => 'success'];
            }
        }
        else
        {
            $msg = ['message' => 'Data submitted seems to be empty. Please try again.', 'info' => 'error'];
        }

        JSONResponse($msg);
    }

    public function updateUser()
    {
        $data      = $this->input->post(NULL);
        $duplicate = array();
        $msg       = array();

        if(isset($data))
        {
            $this->db->trans_start();

            if($data['userType'] == 'Tenant')
            {              

                if($data['tradeName'] == '')
                {
                    $msg = ['message' => 'Please Input Trade Name.', 'info' => 'error'];
                    JSONResponse($msg);
                }
                else if($data['tenantUserName'] == '')
                {
                    $msg = ['message' => 'Please Input Username', 'info' => 'error'];
                    JSONResponse($msg);
                }
                else if($data['password'] == '')
                {
                    $msg = ['message' => 'Please Input Password', 'info' => 'error'];
                    JSONResponse($msg);
                }

                $duplicate = $this->adminmodel->getUserBy($data['tenantUserName']);

                if(empty($duplicate)):
                
                    $update = 
                    [
                        'name'              => $data['tradeName'],
                        'username'          => $data['tenantUserName'],
                        'password'          => MD5($data['password']),
                        'password_in_roman' => $data['password'],
                        'type'              => $data['userType']
                    ];

                    $this->db->where('id', $data['userID'])
                             ->update('portal_user', $update);

                elseif($duplicate->id == $data['userID']):

                    $update = 
                    [
                        'name'              => $data['tradeName'],
                        'username'          => $data['tenantUserName'],
                        'password'          => MD5($data['password']),
                        'password_in_roman' => $data['password'],
                        'type'              => $data['userType']
                    ];

                    $this->db->where('id', $data['userID'])
                             ->update('portal_user', $update);

                else:
                
                    $msg = ['message' => 'User already exist. Try another.', 'info' => 'error'];
                    JSONResponse($msg);

                endif;
            }
            else
            {
                if($data['fullName'] == '')
                {
                    $msg = ['message' => 'Please Input Fullname', 'info' => 'error'];
                    JSONResponse($msg);
                }
                else if($data['userName'] == '')
                {
                    $msg = ['message' => 'Please Input Username', 'info' => 'error'];
                    JSONResponse($msg);
                }
                else if($data['password'] == '')
                {
                    $msg = ['message' => 'Please Input Password', 'info' => 'error'];
                    JSONResponse($msg);
                }

                $duplicate = $this->adminmodel->getUserBy($data['userName']);

                if(empty($duplicate)):
                
                    $update = 
                    [
                        'name'              => $data['fullName'],
                        'username'          => $data['userName'],
                        'password'          => MD5($data['password']),
                        'password_in_roman' => $data['password'],
                        'type'              => $data['userType']
                    ];

                    $this->db->where('id', $data['userID'])
                             ->update('portal_user', $update);

                elseif($duplicate->id == $data['userID']):
                    
                    $update = 
                    [
                        'name'              => $data['fullName'],
                        'username'          => $data['userName'],
                        'password'          => MD5($data['password']),
                        'password_in_roman' => $data['password'],
                        'type'              => $data['userType']
                    ];

                    $this->db->where('id', $data['userID'])
                             ->update('portal_user', $update);

                else:
                
                    $msg = ['message' => 'User already exist. Please Try again.', 'info' => 'error'];
                    JSONResponse($msg);

                endif;    
            }

            $this->db->trans_complete();

            if($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                $msg = ['message' => 'Something went wrong. Please try again.', 'info' => 'error'];
            }
            else
            {
                $msg = ['message' => 'User Updated.', 'info' => 'success'];
            }
        }
        else
        {
            $msg = ['message' => 'Data submitted seems to be empty. Please try again.', 'info' => 'error'];
        }

        JSONResponse($msg);
    }

    public function deactivate()
    {
        $userID = $this->uri->segment(2);
        $msg    = array();

        $this->db->trans_start();
        $this->db->where('id', $userID)
                 ->update('portal_user', ['status' => 'Deactivated']);
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE)
        {
            $msg = ['message' => 'Deactivating Unsuccessfull.', 'info' => 'error'];
        }
        else
        {
            $msg = ['message' => 'Deactivated.', 'info' => 'success'];
        }

        JSONResponse($msg);
    }

    public function resetPassword()
    {
        $userID    = $this->uri->segment(2);
        $msg       = array();
        $user      = $this->adminmodel->getUserDetails($userID);
        $remove    = array('ICM-LT', 'ICM-ST', 'AM-LT', 'AM-ST', 'ACT-LT', 'ACT-ST', 'PM-LT', 'PM-ST', '0');
        $password1 = '';
        $password2 = '';

        if($user->type == 'Tenant')
        {
            $password1 = MD5('Portal' . str_replace($remove, '', $user->tenant_id));
            $password2 = 'Portal' . str_replace($remove, '', $user->tenant_id);
        }
        else
        {
            $password1 = MD5('Portal1234');
            $password2 = 'Portal1234';
        }

        $this->db->trans_start();
        $this->db->where('id', $userID)
                 ->update('portal_user', ['password' => $password1, 'password_in_roman' => $password2]);
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE)
        {
            $msg = ['message' => 'Password Reset Unsuccessfull.', 'info' => 'error'];
        }
        else
        {
            $msg = ['message' => 'Password Reset.', 'info' => 'success'];
        }

        JSONResponse($msg);
    }

    public function reactivate()
    {
        $userID = $this->uri->segment(2);
        $msg    = array();

        $this->db->trans_start();
        $this->db->where('id', $userID)
                 ->update('portal_user', ['status' => 'Active']);
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE)
        {
            $msg = ['message' => 'Reactivating Unsuccessfull.', 'info' => 'error'];
        }
        else
        {
            $msg = ['message' => 'Reactivated.', 'info' => 'success'];
        }

        JSONResponse($msg);
    }
}