<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LedgerModel extends CI_model
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

    public function get_forwarded_balance($tenant_id, $startDate, $from = null)
    {
        $type = ($from === 'OLD') ? 'db' : 'cas';

        $sql = "SELECT  inv.id,
                    inv.document_type,
                    (CASE 
                        WHEN inv.gl_accountID = '4' THEN 'Basic Rent' ELSE 'Other Charges' 
                    END) AS type,
                    inv.ref_no,
                    inv.doc_no,
                    inv.posting_date,
                    inv.due_date,
                    inv.amount as invoice_amount,
                    memo.amount as adj_amount,
                    SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) as debit,
                    SUM(IFNULL(pmt.amount, 0)) as credit,
                    SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) + SUM(IFNULL(pmt.amount, 0)) AS balance

            FROM

                (SELECT *, SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount 
                    FROM subsidiary_ledger
                    WHERE gl_accountID IN (22,29,4)
                    AND document_type = 'Invoice'
                    AND tenant_id = '$tenant_id'
                    AND posting_date < '$startDate'
                    GROUP BY ref_no, gl_accountID, tenant_id) as inv

            LEFT JOIN

                (SELECT tenant_id,
                        doc_no,
                        ref_no,
                        gl_accountID,
                        SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                    FROM subsidiary_ledger
                    WHERE gl_accountID IN (22,29,4)
                    AND document_type IN ('Credit Memo', 'Debit Memo', 'Invoice Adjustment')
                    AND tenant_id <> 'DELETED'
                    GROUP BY ref_no, gl_accountID, tenant_id) AS memo
            ON
                inv.ref_no = memo.ref_no AND inv.gl_accountID = memo.gl_accountID  AND inv.gl_accountID = memo.gl_accountID

            LEFT JOIN

                (SELECT tenant_id,
                        doc_no,
                        ref_no,
                        gl_accountID,
                        SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                FROM subsidiary_ledger
                WHERE gl_accountID IN (22,29,4)
                AND document_type = 'Payment'
                AND tenant_id <> 'DELETED'
                GROUP BY ref_no, gl_accountID, tenant_id) AS pmt
            ON 
                inv.ref_no = pmt.ref_no AND inv.gl_accountID = pmt.gl_accountID  AND inv.gl_accountID = pmt.gl_accountID";
        
        
        $query = $this->{$type}->query($sql);
        return $query->result_array();
    }

    public function get_tenant_ledger($tenant_id, $startDate, $endDate)
    {
        $query = $this->db->query(" SELECT  inv.id,
                                            inv.document_type,
                                            (CASE 
                                                WHEN inv.gl_accountID = '4' THEN 'Basic Rent' ELSE 'Other Charges' 
                                            END) AS type,
                                            inv.ref_no,
                                            inv.doc_no,
                                            inv.posting_date,
                                            inv.due_date,
                                            inv.amount as invoice_amount,
                                            memo.amount as adj_amount,
                                            SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) as debit,
                                            SUM(IFNULL(pmt.amount, 0)) as credit,
                                            SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) + SUM(IFNULL(pmt.amount, 0)) AS balance

                                    FROM

                                        (SELECT *, SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount 
                                            FROM subsidiary_ledger
                                            WHERE gl_accountID IN (22,29,4)
                                            AND document_type = 'Invoice'
                                            AND tenant_id = '$tenant_id'
                                            AND posting_date BETWEEN '$startDate' AND '$endDate'
                                            GROUP BY ref_no, gl_accountID, tenant_id) as inv

                                    LEFT JOIN

                                        (SELECT tenant_id,
                                                doc_no,
                                                ref_no,
                                                gl_accountID,
                                                SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                                            FROM subsidiary_ledger
                                            WHERE gl_accountID IN (22,29,4)
                                            AND document_type IN ('Credit Memo', 'Debit Memo', 'Invoice Adjustment')
                                            AND tenant_id <> 'DELETED'
                                            GROUP BY ref_no, gl_accountID, tenant_id) AS memo
                                    ON
                                        inv.ref_no = memo.ref_no AND inv.gl_accountID = memo.gl_accountID  AND inv.gl_accountID = memo.gl_accountID

                                    LEFT JOIN
    
                                        (SELECT tenant_id,
                                                doc_no,
                                                ref_no,
                                                gl_accountID,
                                                SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount
                                        FROM subsidiary_ledger
                                        WHERE gl_accountID IN (22,29,4)
                                        AND document_type IN ('Payment', 'Payment Adjustment')
                                        AND tenant_id <> 'DELETED'
                                        GROUP BY ref_no, gl_accountID, tenant_id) AS pmt
                                    ON 
                                        inv.ref_no = pmt.ref_no AND inv.gl_accountID = pmt.gl_accountID  AND inv.gl_accountID = pmt.gl_accountID
                                    GROUP BY inv.ref_no, inv.gl_accountID, inv.tenant_id
                                    ORDER BY inv.posting_date, inv.id, inv.gl_accountID ");

        // GROUP BY ref_no, gl_accountID, tenant_id

        
        $result = $query->result_array();
        $fBalance = $this->get_forwarded_balance($tenant_id, $startDate);
        $runningBalance = empty($fBalance)?0: $fBalance[0]['balance'];

        foreach ($result as $key => $row) 
        {
            $result[$key]['runningBalance'] = $runningBalance += $row['balance'];
        }

        return $result;
    }

    public function get_payment_details($ref_no, $tenant_id)
    {
        $query = $this->db->query("
                SELECT 
                    `doc_no`, 
                    `posting_date`, 
                    `gl_accounts`.`gl_account` as `payment_type`, 
                    SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount 
                FROM 
                    `subsidiary_ledger`
                LEFT JOIN 
                    `gl_accounts` ON `subsidiary_ledger`.`gl_accountID` = `gl_accounts`.`id`
                WHERE 
                    `ref_no` = '$ref_no'
                AND 
                    `tenant_id` = '$tenant_id' 
                AND 
                    `document_type` IN ('Payment', 'Payment Adjustment') 
                AND 
                    `gl_accountID` NOT IN (4,22,29) GROUP BY ref_no, gl_accountID, tenant_id, doc_no");

        
        if (!empty($query)) 
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }
}