<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PaymentAdviceModel extends CI_model
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

    public function advicecode($useNext = true)
    {
        $sequence = getSequenceNo(
            [   
                'code'          => "PA",
                'number'        => '1',
                'lpad'          => '7',
                'pad_string'    => '0',
                'description'   => "Payment Advice"
            ],
            [
                'table'     =>  'payment_advice',
                'column'    => 'advice_code'
            ],

            $useNext
        );

        return $sequence;
    }

    public function getSoaWithBalances($tenant_id)
    {
        $query = $this->db->query("SELECT
                                    s.id,
                                    s.tenant_id, 
                                    s.soa_no,
                                    f.billing_period,
                                    f.collection_date,
                                    f.posting_date,
                                    IFNULL(i.balance, 0) as inv_balance,
                                    IFNULL(p.balance, 0) as pre_balance,
                                    SUM(IFNULL(i.balance, 0)+IFNULL(p.balance, 0)) AS balance
                                FROM 
                                    soa_line s
                                LEFT JOIN
                                    soa_file AS f
                                ON
                                    f.soa_no = s.soa_no AND f.tenant_id = s.tenant_id
                                LEFT JOIN
                                    (SELECT  
                                        (CASE 
                                            WHEN inv.tag = 'Retro Rent' THEN 'Retro'
                                            ELSE inv.document_type
                                        END) AS document_type,
                                        inv.doc_no,
                                        inv.tenant_id, 
                                        SUM(IFNULL(inv.amount, 0) + IFNULL(memo.amount, 0)) + SUM(IFNULL(pmt.amount, 0)) AS balance
                                    FROM
                                        (SELECT *, SUM(IFNULL(debit,0) + IFNULL(credit,0)) as amount 
                                            FROM subsidiary_ledger
                                            WHERE gl_accountID IN (22,29,4)
                                            AND document_type = 'Invoice'
                                            AND tenant_id = '$tenant_id'
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
                                        GROUP BY ref_no, gl_accountID, tenant_id
                                        ) AS memo
                                        ON
                                            inv.ref_no = memo.ref_no AND 
                                            inv.gl_accountID = memo.gl_accountID AND 
                                            inv.tenant_id = memo.tenant_id
                                    LEFT JOIN
                                        (SELECT p.tenant_id,
                                            p.doc_no,
                                            p.ref_no,
                                            p.gl_accountID,
                                            SUM(IFNULL(p.debit,0) + IFNULL(p.credit,0)) as amount
                                        FROM subsidiary_ledger p
                                        WHERE p.gl_accountID IN (22,29,4)
                                        AND (p.document_type = 'Payment' OR  p.document_type = 'Payment Adjustment')
                                        AND p.tenant_id <> 'DELETED'
                                        GROUP BY p.ref_no, p.gl_accountID, p.tenant_id) AS pmt
                                        ON  inv.ref_no = pmt.ref_no AND 
                                            inv.gl_accountID = pmt.gl_accountID  AND 
                                            inv.tenant_id = pmt.tenant_id
                                    GROUP BY 
                                        inv.ref_no, inv.gl_accountID, inv.tenant_id
                                    HAVING
                                        ABS(balance) >= 1
                                    ORDER BY 
                                        document_type ASC, inv.gl_accountID ASC, inv.posting_date ASC) 
                                AS i
                                ON s.doc_no = i.doc_no AND s.tenant_id = i.tenant_id
                                LEFT JOIN
                                    (SELECT 
                                        id,
                                        doc_no,
                                        tenant_id,
                                        amount as balance
                                    FROM 
                                        `tmp_preoperationcharges` 
                                    WHERE 
                                        `tenant_id` = '$tenant_id'
                                    AND 
                                        (`tag` = '' || `tag` = 'Posted')
                                    AND 
                                        amount >= 1
                                    ORDER BY
                                        posting_date ASC, id ASC)
                                AS p
                                ON s.preop_id = p.id AND s.tenant_id = p.tenant_id
                                LEFT JOIN payment_advice_soa pa ON s.soa_no = pa.soa_no 

                                WHERE 
                                    s.tenant_id = '$tenant_id'
                                AND s.soa_no NOT IN (SELECT soa_no FROM payment_advice_soa)
                                GROUP BY
                                    s.soa_no, s.tenant_id
                                HAVING 
                                    balance >= 1
                                ORDER BY f.posting_date DESC, s.id DESC")->RESULT_ARRAY();

        return $query;
    }

    public function getSoa($soa_no)
    {
        $query = $this->db->query("SELECT SUM(amount) as amount FROM soa_line WHERE soa_no = '$soa_no'")->ROW();
        return $query;
    }
}