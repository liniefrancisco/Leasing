<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AdminModel extends CI_model
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

    public function admingettenant($tenant_id)
    {
        $result = $this->db->query("SELECT t.id, t.tenant_id, p.trade_name 
                                    FROM tenants t 
                                    LEFT JOIN prospect p 
                                    ON t.prospect_id = p.id 
                                    WHERE t.status = 'Active' 
                                    AND t.tenant_id LIKE '%" . $tenant_id . "%' 
                                    ORDER BY p.trade_name")->RESULT_ARRAY();
        return $result;
    }

    public function populate_tradeNameInternal($flag, $store, $trade_name)
    {
        $query;
        if ($flag == 'Long Term') {
            $query = $this->db->query(
                "SELECT `p`.`trade_name`, `t`.`tenant_id`
                 FROM `tenants` `t`, `prospect` `p`, `stores` `s`
                 WHERE `t`.`tenancy_type` = 'Long Term'
                 AND (`t`.`status` = 'Active' || `t`.`status` = 'Terminated')
                 AND (`t`.`flag` = 'Posted' || `t`.`flag` = 'Pending')
                 AND `s`.`id` = '" . $store . "'
                 AND `p`.`trade_name` LIKE '%" . $trade_name . "%'
                 AND `s`.`store_code` = `t`.`store_code` 
                 AND `t`.`prospect_id` = `p`.`id`
                 GROUP BY `t`.`tenant_id`
                 ORDER BY `p`.`trade_name` ASC"
            );
        } else {
            $query = $this->db->query(
                "SELECT `p`.`trade_name`, `t`.`tenant_id`
                 FROM `tenants` `t`, `prospect` `p`, `stores` `s`
                 WHERE `t`.`tenancy_type` = 'Short Term'
                 AND (`t`.`status` = 'Active' || `t`.`status` = 'Terminated')
                 AND (`t`.`flag` = 'Posted' || `t`.`flag` = 'Pending')
                 AND `s`.`id` = '" . $store . "'
                 AND `p`.`trade_name` LIKE '%" . $trade_name . "%'
                 AND `s`.`store_code` = `t`.`store_code`
                 AND `t`.`prospect_id` = `p`.`id`
                 GROUP BY `t`.`tenant_id`
                 ORDER BY `p`.`trade_name` ASC"
            );
        }

        // var_dump($this->db->last_query());
        return $query->result_array();
    }

    public function populate_tradeNameCAS($flag, $store, $trade_name)
    {
        $query;

        if ($flag == 'Long Term') {
            $query = $this->cas->query(
                "SELECT `p`.`trade_name`, `t`.`tenant_id`
                     FROM `tenants` `t`, `prospect` `p`, `stores` `s`
                     WHERE `t`.`tenancy_type` = 'Long Term'
                     AND (`t`.`status` = 'Active' || `t`.`status` = 'Terminated')
                     AND (`t`.`flag` = 'Posted' || `t`.`flag` = 'Pending')
                     AND `s`.`id` = '" . $store . "'
                     AND `p`.`trade_name` LIKE '%" . $trade_name . "%'
                     AND `s`.`store_code` = `t`.`store_code` 
                     AND `t`.`prospect_id` = `p`.`id`
                     GROUP BY `t`.`tenant_id`
                     ORDER BY `p`.`trade_name` ASC"
            );
        } else {
            $query = $this->cas->query(
                "SELECT `p`.`trade_name`, `t`.`tenant_id`
                     FROM `tenants` `t`, `prospect` `p`, `stores` `s`
                     WHERE `t`.`tenancy_type` = 'Short Term'
                     AND (`t`.`status` = 'Active' || `t`.`status` = 'Terminated')
                     AND (`t`.`flag` = 'Posted' || `t`.`flag` = 'Pending')
                     AND `s`.`id` = '" . $store . "'
                     AND `p`.`trade_name` LIKE '%" . $trade_name . "%'
                     AND `s`.`store_code` = `t`.`store_code`
                     AND `t`.`prospect_id` = `p`.`id`
                     GROUP BY `t`.`tenant_id`
                     ORDER BY `p`.`trade_name` ASC"
            );
        }
        return $query->result_array();
    }

    public function getDataV1($tenant_id, $startDate, $endDate, $table)
    {
        $result = $this->db->SELECT('*')
            ->FROM($table)
            ->WHERE("document_type IN ('Invoice', 'Invoice Adjustment')")
            ->WHERE("upload_status <> 'Uploaded'")
            ->WHERE("posting_date BETWEEN '" . $startDate . "' AND '" . $endDate . "'")
            ->WHERE('tenant_id', $tenant_id)
            ->GET()
            ->RESULT_ARRAY();

        return $result;
    }

    public function getDataV2($tenant_id, $startDate, $endDate, $table)
    {
        $result = $this->db->SELECT('*')
            ->FROM($table)
            ->WHERE("upload_status <> 'Uploaded'")
            ->WHERE("posting_date BETWEEN '" . $startDate . "' AND '" . $endDate . "'")
            ->WHERE('tenant_id', $tenant_id)
            ->GET()
            ->RESULT_ARRAY();

        return $result;
    }

    public function getDataV1CAS($tenant_id, $startDate, $endDate, $table)
    {
        $result = $this->cas->SELECT('*')
            ->FROM($table)
            ->WHERE("document_type IN ('Invoice', 'Invoice Adjustment')")
            ->WHERE("upload_status <> 'Uploaded'")
            ->WHERE("posting_date BETWEEN '" . $startDate . "' AND '" . $endDate . "'")
            ->WHERE('tenant_id', $tenant_id)
            ->GET()
            ->RESULT_ARRAY();

        return $result;
    }

    public function getDataV2CAS($tenant_id, $startDate, $endDate, $table)
    {
        $result = $this->cas->SELECT('*')
            ->FROM($table)
            ->WHERE("upload_status IS NULL OR upload_status = ''")
            ->WHERE("posting_date BETWEEN '" . $startDate . "' AND '" . $endDate . "'")
            ->WHERE('tenant_id', $tenant_id)
            ->GET()
            ->RESULT_ARRAY();

        // var_dump($this->cas->last_query());
        return $result;
    }

    public function getDataV3CAS($tenant_id, $startDate, $endDate, $table)
    {
        $result = $this->cas->SELECT('*')
            ->FROM($table)
            ->WHERE("upload_status IS NULL OR upload_status = ''")
            ->WHERE("posting_date BETWEEN '" . $startDate . "' AND '" . $endDate . "'")
            ->WHERE('tenant_id', $tenant_id)
            ->GET()
            ->RESULT_ARRAY();

        // var_dump($this->cas->last_query());
        return $result;
    }

    public function invoicingCAS($tenant_id, $startDate, $endDate)
    {
        return $this->cas->query("SELECT *
        FROM `invoicing`
        WHERE tenant_id = '$tenant_id' AND `upload_status` IS NULL OR `upload_status` = '' AND `posting_date` BETWEEN '$startDate' AND '$endDate'")->RESULT_ARRAY();
    }

    public function get_soa($tenant_type, $start, $end, $from = null)
    {
        $type = ($from === 'OLD') ? 'db' : 'cas';

        $sql = "SELECT 
                p.trade_name, 
                s.* 
                FROM soa_file s 
                LEFT JOIN tenants t ON s.tenant_id = t.tenant_id
                LEFT JOIN prospect p ON t.prospect_id = p.id
                WHERE s.upload_status <> 'Uploaded' 
                AND s.tenant_id LIKE '%$tenant_type%' 
                AND s.posting_date BETWEEN '$start' AND '$end'
                GROUP BY s.soa_no";

        // $query  = ($from === 'OLD') ? $this->db : $this->cas;
        $result = $this->{$type}->QUERY($sql)->RESULT_ARRAY();

        return $result;
    }

    public function getSOAFile($id, $from = null)
    {
        $type = ($from === 'OLD') ? 'db' : 'cas';

        $result = $this->{$type}->SELECT('*')
            ->FROM('soa_file')
            ->WHERE('id', $id)
            ->GET()
            ->ROW();
        return $result;
    }

    public function getSOALine($soa_no, $from = null)
    {
        $type = ($from === 'OLD') ? 'db' : 'cas';

        $result = $this->{$type}->SELECT('*')
            ->FROM('soa_line')
            ->WHERE('soa_no', $soa_no)
            ->GET()
            ->RESULT_ARRAY();
        return $result;
    }

    public function getPreviousBalance($postingdate, $tenant_id, $from = null)
    {
        $type = ($from === 'OLD') ? 'db' : 'cas';

        $result = $this->{$type}->SELECT('*')
            ->FROM('soa_file')
            ->WHERE('posting_date <= ', $postingdate)
            ->WHERE('tenant_id', $tenant_id)
            ->ORDER_BY('posting_date', 'DESC')
            ->LIMIT(1)
            ->GET()
            ->ROW();

            // echo($this->{$type}->last_query($result));
        return $result;
    }

    public function getPayments($tenant_id)
    {
        $result = $this->db->QUERY("SELECT * FROM payment_scheme 
                                    WHERE tenant_id = '$tenant_id' 
                                    AND upload_status <> 'Uploaded'")->RESULT_ARRAY();
        return $result;
    }

    public function getPaymentScheme($id)
    {
        $result = $this->db->query("SELECT * FROM payment_scheme WHERE id = '$id' AND upload_status <> 'Uploaded'")->ROW();
        return $result;
    }

    public function getSL($doc_no)
    {
        $result = $this->db->query("SELECT distinct ref_no FROM subsidiary_ledger WHERE doc_no = '$doc_no' AND upload_status <> 'Uploaded'")->RESULT_ARRAY();
        return $result;
    }

    public function getPaymentTable($doc_no)
    {
        $result = $this->db->query("SELECT * FROM payment WHERE doc_no = '$doc_no' AND upload_status <> 'Uploaded'")->ROW();
        return $result;
    }

    public function getLedgers($ref_no, $table)
    {
        $result = $this->db->query("SELECT * FROM $table WHERE ref_no = '$ref_no' AND upload_status <> 'Uploaded'")->RESULT_ARRAY();
        return $result;
    }

    public function getLedgerTable($doc_no)
    {
        $result = $this->db->query("SELECT * FROM ledger WHERE doc_no = '$doc_no' AND upload_status <> 'Uploaded'")->RESULT_ARRAY();
        return $result;
    }

    public function getSoaForPayment($soa_no)
    {
        $query = $this->db->query("SELECT * FROM soa_file WHERE soa_no = '$soa_no'")->ROW();
        return $query;
    }

    public function getPaymentsPerstore($tenant_id, $start, $end)
    {
        $result = $this->db->QUERY("SELECT p.posting_date, pp.trade_name, ps.*
                                    FROM payment p
                                    LEFT JOIN payment_scheme ps ON p.doc_no = ps.receipt_no
                                    LEFT JOIN tenants t ON ps.tenant_id = t.tenant_id
                                    LEFT JOIN prospect pp ON t.prospect_id = pp.id
                                    WHERE p.tenant_id LIKE '%$tenant_id%'
                                    AND p.upload_status <> 'Uploaded'
                                    AND p.doc_no NOT LIKE '%UFT%' 
                                    AND p.posting_date BETWEEN '$start' AND '$end'
                                    GROUP BY ps.receipt_no")->RESULT_ARRAY();
        return $result;
    }

    public function gethistory($store, $date1, $date2)
    {
        $result = $this->db->query("SELECT ul.*, t.username  
                                    FROM upload_log ul
                                    LEFT JOIN portal_user t
                                    ON ul.user_id = t.id
                                    WHERE ul.tenant_id LIKE '%" . $store . "%' 
                                    AND ul.date_uploaded 
                                    BETWEEN '" . $date1 . "' AND '" . $date2 . "'")->RESULT_ARRAY();
        return $result;
    }

    public function getHistoryDocs($id)
    {
        $result = $this->db->SELECT('*')
            ->FROM('upload_log_docs')
            ->WHERE('uploadlogID', $id)
            ->GET()
            ->RESULT_ARRAY();
        return $result;
    }

    public function getPaymentAdvice()
    {
        $result = $this->db->SELECT('*')
            ->FROM('payment_advice')
            ->WHERE("status <> 'Posted'")
            ->GET()
            ->RESULT_ARRAY();
        return $result;
    }

    public function getPaymentAdviceHistory()
    {
        $result = $this->db->SELECT('*')
            ->FROM('payment_advice')
            ->WHERE("status = 'Posted'")
            ->GET()
            ->RESULT_ARRAY();
        return $result;
    }

    public function getAdvices($id)
    {
        $query = $this->db->query("SELECT p.*, pa.soa_no, pa.tenant_id, pa.total_payable 
                                   FROM payment_advice p
                                   LEFT JOIN payment_advice_soa pa ON p.id = pa.payment_advice_id
                                   WHERE p.id = '$id'
                                   AND p.status <> 'Posted'")->ROW();
        return $query;
    }

    public function getAdvicesHistory($id)
    {
        $query = $this->db->query("SELECT p.*, pa.soa_no, pa.tenant_id, pa.total_payable 
                                   FROM payment_advice p
                                   LEFT JOIN payment_advice_soa pa ON p.id = pa.payment_advice_id
                                   WHERE p.id = '$id'
                                   AND p.status = 'Posted'")->ROW();
        return $query;
    }

    public function getPaymentAdviceSoa($id)
    {
        $result = $this->db->QUERY("SELECT * FROM payment_advice_soa WHERE payment_advice_id = '$id'")->RESULT_ARRAY();
        return $result;
    }

    public function getPaymentAdviceSoaHistory($id)
    {
        $result = $this->db->QUERY("SELECT * FROM payment_advice_soa WHERE payment_advice_id = '$id'")->RESULT_ARRAY();
        return $result;
    }

    public function getUsers()
    {
        $result = $this->db->SELECT('*')
            ->FROM('portal_user')
            ->GET()
            ->RESULT_ARRAY();
        return $result;
    }

    public function getProspects($store, $type)
    {
        $query = $this->db->QUERY("SELECT
                                        `t`.`id`,
                                        `t`.`tenant_id`,
                                        `p`.`trade_name`
                                    FROM
                                        `tenants` `t`,
                                        `prospect` `p`,
                                        `stores` `s`,
                                        `floors` `f`,
                                        `leasee_type` `lt`,
                                        `location_code` `lc`,
                                        `area_classification` `ac`,
                                        `area_type` `at`,
                                        `portal_user` `pu`
                                    WHERE
                                        `p`.`id` = `t`.`prospect_id`
                                    AND
                                        `p`.`lesseeType_id` = `lt`.`id`
                                    AND
                                        `t`.`status` = 'Active'
                                    AND
                                        `t`.`flag` = 'Posted'
                                    AND
                                        `lc`.`status` = 'Active'
                                    AND
                                        `t`.`locationCode_id` = `lc`.`id`
                                    AND
                                        `lc`.`floor_id` = `f`.`id`
                                    AND
                                        `t`.`tenancy_type` = '" . $type . "'
                                    AND
                                        `f`.`store_id` = '" . $store . "'
                                    AND 
                                        `t`.`tenant_id` NOT IN (SELECT tenant_id FROM portal_user)
                                    GROUP BY
                                        `p`.`trade_name`")->RESULT_ARRAY();
        return $query;
    }

    public function getProspectName($tenantID)
    {
        $result = $this->db->QUERY("SELECT p.trade_name 
                                    FROM prospect p
                                    LEFT JOIN tenants t ON p.id = t.prospect_id
                                    WHERE t.tenant_id = '" . $tenantID . "'
                                    AND t.status = 'Active'")->ROW();
        return $result;
    }

    public function getUserBy($username)
    {
        $result = $this->db->SELECT('*')
            ->FROM('portal_user')
            ->WHERE('username', $username)
            ->GET()
            ->ROW();
        return $result;
    }

    public function getUserDetails($id)
    {
        $result = $this->db->SELECT('*')
            ->FROM('portal_user')
            ->WHERE('id', $id)
            ->GET()
            ->ROW();
        return $result;
    }

    public function prospectUsers($store, $tenant_type)
    {
        $query = $this->db->query("SELECT
                            `t`.`id`,
                            `t`.`tenant_id`,
                            `p`.`trade_name`,
                            `p`.`contact_number`,
                            `p`.`email`,
                            `lc`.`location_code`,
                            `lc`.`location_desc`
                        FROM
                            `tenants` `t`,
                            `prospect` `p`,
                            `stores` `s`,
                            `floors` `f`,
                            `leasee_type` `lt`,
                            `location_code` `lc`,
                            `area_classification` `ac`,
                            `area_type` `at`
                        WHERE
                            `p`.`id` = `t`.`prospect_id`
                        AND
                            `p`.`lesseeType_id` = `lt`.`id`
                        AND
                            `t`.`status` = 'Active'
                        AND
                            `t`.`flag` = 'Posted'
                        AND
                            `lc`.`status` = 'Active'
                        AND
                            `t`.`locationCode_id` = `lc`.`id`
                        AND
                            `lc`.`floor_id` = `f`.`id`
                        AND
                            `t`.`tenancy_type` = '" . $tenant_type . "'
                        AND
                            `f`.`store_id` = '" . $store . "'
                        GROUP BY
                            `t`.`id`")->RESULT_ARRAY();

        return $query;
    }
}
