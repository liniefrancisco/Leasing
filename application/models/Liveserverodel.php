<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LiveServerModel extends CI_model
{
    private $livedb;
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $date = new DateTime();
        $this->_timeStamp = $date->format('Y-m-d H:i:s');
        $this->livedb = $this->load->database('livedb', true);
    }  

    public function getProspects($tenant_type, $store)
    {
        $query = $this->livedb->query("SELECT
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

        // echo $this->livedb->last_query();
    } 
}