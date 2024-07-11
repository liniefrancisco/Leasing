<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BaseModel extends CI_model
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->_user_group = $this->session->userdata('user_group');
        $this->_user_id = $this->session->userdata('id');
        $this->cas = $this->load->database('cas', true);
    }

    function sanitize($string)
    {
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = trim($string);
        return $string;
    }

    public function check_login($username, $password)
    {
        $userType = '';
        $query1   = $this->db->query("SELECT * FROM portal_user WHERE password = '" . MD5($password) . "' AND username = '" . $username . "'")->RESULT_ARRAY();

        foreach ($query1 as $value) {
            $userType = $value['type'];
        }

        if ($userType == 'Admin' || $userType == 'Accounting') 
        {
            $query = $this->db->query("SELECT * FROM portal_user WHERE password = '" . MD5($password) . "' AND username = '$username'");

            if ($query->num_rows() > 0) 
            {
                foreach ($query->result() as $rows) 
                {
                    $data = array(
                        'id'                => $rows->id,
                        'username'          => $rows->username,
                        'password'          => $rows->password,
                        'user_type'         => $rows->type,
                        'tenant_id'         => $rows->tenant_id,
                        'name'              => $rows->name,
                        'portal_logged_in'  =>  TRUE
                    );

                    $this->session->set_userdata($data);
                }

                $session = (object)$this->session->userdata;

                $user_session_data = [
                    'user_id'    => isset($session->id)         ? $session->id         : '',
                    'session_id' => isset($session->session_id) ? $session->session_id : '',
                    'ip_address' => isset($session->ip_address) ? $session->ip_address : '',
                    'user_agent' => isset($session->user_agent) ? $session->user_agent : '',
                    'user_data'  => json_encode($session),
                    'login_in'   => 'portal_leasing'
                ];

                $this->db->insert('user_session', $user_session_data);

                return true;
            }
        } else {

            $query = $this->db->query("SELECT pu.*, t.tenancy_type, p.trade_name, t.status 
                                       FROM portal_user pu
                                       LEFT JOIN tenants t ON pu.tenant_id = t.tenant_id
                                       LEFT JOIN prospect p ON t.prospect_id = p.id
                                       WHERE t.status = 'ACTIVE' 
                                       AND pu.password = '" . MD5($password) . "' AND pu.username = '" . $username . "'");

            if ($query->num_rows() > 0) 
            {
                foreach ($query->result() as $rows) 
                {
                    $data = array(
                        'id'                => $rows->id,
                        'username'          => $rows->username,
                        'password'          => $rows->password,
                        'user_type'         => $rows->type,
                        'tenant_id'         => $rows->tenant_id,
                        'tenancy_type'      => $rows->tenancy_type,
                        'trade_name'        => $rows->trade_name,
                        'status'            => $rows->status,
                        'portal_logged_in' =>  TRUE
                    );

                    $this->session->set_userdata($data);
                }

                $session = (object)$this->session->userdata;

                $user_session_data = [
                    'user_id'    => isset($session->id)         ? $session->id         : '',
                    'session_id' => isset($session->session_id) ? $session->session_id : '',
                    'ip_address' => isset($session->ip_address) ? $session->ip_address : '',
                    'user_agent' => isset($session->user_agent) ? $session->user_agent : '',
                    'user_data'  => json_encode($session),
                    'login_in'   => 'portal_leasing'
                ];

                $this->db->insert('user_session', $user_session_data);

                return true;
            }
        }

        return false;
    }

    public function getTenant($tenant_id, $from = null)
    {
        $type = ($from === 'OLD') ? 'db' : 'cas';
        $result = $this->{$type}->SELECT('p.*')
            ->FROM('prospect p')
            ->JOIN('tenants t', 'p.id = t.prospect_id', 'LEFT')
            ->WHERE('t.tenant_id', $tenant_id)
            ->GET()
            ->ROW();
        return $result;
    }

    public function getTenantUser($tenant_id)
    {
        $result = $this->db->SELECT('*')
                            ->FROM('portal_admin')
                            ->WHERE('tenant_id', $tenant_id)
                            ->GET()
                            ->ROW();
        return $result;
    }

    public function getusercredentials($username)
    {
        $result = $this->db->SELECT('*')
                            ->FROM('portal_admin')
                            ->WHERE('username', $username)
                            ->GET()
                            ->ROW();
        return $result;
    }

    public function tenantForUser()
    {
        $result = $this->db->QUERY("SELECT t.*, p.trade_name 
                                    FROM tenants t 
                                    LEFT JOIN prospect p
                                    ON t.prospect_id = p.id
                                    WHERE t.status = 'Active'
                                    AND t.flag = 'Posted' ")->RESULT_ARRAY();
        return $result;
    }

    public function checkUserDuplicate($tenant_id)
    {
        $result = $this->db->query("SELECT * FROM portal_user WHERE tenant_id = '$tenant_id'")->ROW();
        return $result;
    }

    public function getProspects()
    {
        $prospect = $this->db->query("SELECT * FROM prospect WHERE status = 'On Contract'")->result_array();

        return $prospect;
    }

    public function getLocationCode()
    {
        $locationcode = $this->db->query("SELECT * FROM location_code WHERE status = 'Active'")->result_array();

        return $locationcode;
    }

    public function getTenants()
    {
        $tenants = $this->db->query("SELECT * FROM tenants WHERE status = 'Active'")->result_array();

        return $tenants;
    }
}