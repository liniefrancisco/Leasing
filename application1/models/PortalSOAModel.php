<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PortalSOAModel extends CI_model
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->_user_group = $this->session->userdata('user_group');
        $this->_user_id = $this->session->userdata('id');
    }

    function sanitize($string)
    {
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = trim($string);
        return $string;
    }

    public function get_tenantSoa($trade_name)
    {
        if ($this->_user_group == '0' || $this->_user_group == NULL)
        {
                $query = $this->db->query(
                    "SELECT
                        sf.tenant_id,
                        sf.soa_no,
                        sf.file_name,
                        IFNULL(sf.collection_date, s.collection_date) as collection_date
                    FROM
                        soa_file sf
                    LEFT JOIN 
                        (SELECT distinct(prospect_id) as prospect_id, tenant_id, store_code  FROM tenants) t 
                        ON t.tenant_id = sf.tenant_id
                    LEFT JOIN 
                        prospect p on p.id = t.prospect_id
                    LEFT JOIN
                        soa s
                        ON sf.soa_no = s.soa_no AND sf.tenant_id = s.tenant_id
                    WHERE 
                         p.trade_name = '$trade_name'
                    GROUP BY
                        sf.soa_no
                    ORDER BY
                        collection_date DESC, sf.id DESC");

            return $query->result_array();
        }
        else
        {

            $store_code = $this->session->userdata('store_code');
            $query = $this->db->query(
                "SELECT
                    sf.tenant_id,
                    sf.soa_no,
                    sf.file_name,
                    IFNULL(sf.collection_date, s.collection_date) as collection_date
                FROM
                    soa_file sf
                LEFT JOIN 
                    (SELECT distinct(prospect_id) as prospect_id, tenant_id, store_code  FROM tenants) t 
                    ON t.tenant_id = sf.tenant_id
                LEFT JOIN 
                    prospect p on p.id = t.prospect_id
                LEFT JOIN
                    soa s
                    ON sf.soa_no = s.soa_no AND sf.tenant_id = s.tenant_id
                WHERE 
                     p.trade_name = '$trade_name'
                AND
                    t.store_code = '$store_code'
                GROUP BY
                    sf.soa_no
                ORDER BY
                    collection_date DESC, sf.id DESC");
            return $query->result_array();
        }

    }
}