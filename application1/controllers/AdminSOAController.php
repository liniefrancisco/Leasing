<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

class AdminSOAController extends CI_Controller
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

    public function getsoa()
    {
        $data      = $this->input->post(NULL);
        $tenantID  = $data['store'] . '-' . $data['type'];
        $datefrom  = $data['date1'];
        $dateto    = $data['date2'];

        $soa = $this->adminmodel->get_soa($tenantID, $datefrom, $dateto, $data['from']);

        JSONResponse($soa);
    }

    public function uploadsoadata()
    {
        $this->cas = $this->load->database('cas', true);
        $soaID = $this->input->post('soaID');
        $from = $this->input->post('from');

        $url1     = 'https://leasingportal.altsportal.com/uploadSoaDataAPI';
        $url2     = 'https://leasingportal.altsportal.com/uploadSoaFile';
        $url3     = 'https://leasingportal.altsportal.com/notifications';

        $msg   = array();
        $data  = array();
        $b     = array();

        $soafiledata = $this->adminmodel->getSOAFile($soaID, $from);
        $soalinedata = $this->adminmodel->getSOALine($soafiledata->soa_no, $from);
        $tenant      = $this->basemodel->getTenant($soafiledata->tenant_id, $from);


        $balance        = $this->ledgermodel->get_forwarded_balance($soafiledata->tenant_id, $soafiledata->posting_date, $from);
        $previousAmount = $this->adminmodel->getPreviousBalance($soafiledata->posting_date, $soafiledata->tenant_id, $from);

        # $defaultPassword = MD5("Portal" . str_replace(array('ICM', 'AM', 'PM', 'ACT', '-', 'LT', 'ST', '0'), "", $soafiledata->tenant_id));

        foreach ($balance as $value) {
            $b = $value['balance'];
        }

        $web_ip = ($from === 'OLD') ? '172.16.161.37/agc-pms' : '172.16.170.10/PMS';
        $filePath = "http://$web_ip/assets/pdf/" . $soafiledata->file_name;

        $type = ($from === 'OLD') ? 'db' : 'cas';
        $b64Doc   = chunk_split(base64_encode(file_get_contents($filePath)));

        if (!empty($soafiledata)) {
            if ($this->isDomainAvailable('leasingportal.altsportal.com')) {
                $this->{$type}->trans_start();

                $data    = array('soa_file' => $soafiledata, 'soa_line' => $soalinedata, 'tenant' => $tenant);
                $soaData = $this->sendData($url1, $data);

                if ($soaData == 'success') {
                    $soaFile = $this->sendData($url2, array('pdfB64' => $b64Doc, 'file_name' => $soafiledata->file_name));

                    if ($soaFile == 'PDF Saved') {
                        $data    = array('soa_file' => $soafiledata, 'balance' => $b, 'previous' => $previousAmount->amount_payable, 'tenant' => $tenant);
                        $soaData = $this->sendData($url3, $data);

                        $update =
                            [
                                'upload_status' => 'Uploaded',
                                'upload_date'   => date('Y-m-d')
                            ];

                        $this->{$type}->where('id', $soafiledata->id);
                        $this->{$type}->update('soa_file', $update);


                        if (isset($soalinedata)) {
                            foreach ($soalinedata as $value) {
                                $this->{$type}->where('id', $value['id']);
                                $this->{$type}->update('soa_line', $update);
                            }
                        }

                        $this->{$type}->trans_complete();

                        if ($this->{$type}->trans_status() === FALSE) {
                            $this->{$type}->trans_rollback();
                            $msg = ['message' => 'Uploading Data Failed. Try Again.', 'info' => 'error'];
                        } else {
                            // $this->saveLog('SOA', $soafiledata->soa_no, $soafiledata->tenant_id, $msg['info'], $msg['message']);
                            $msg = ['message' => 'Uploaded Succesfully.', 'info' => 'success'];
                        }
                    } else if ($soaFile == 'Error') {
                        $msg = ['message' => 'Uploading SOA Failed. Try Again.', 'info' => 'error'];
                    } else if ($soaFile == 'Exist') {
                        $msg = ['message' => 'SOA already uploaded.', 'info' => 'exist'];
                    }
                } else if ($soaData == 'error') {
                    $msg = ['message' => 'Uploading Failed, something went wrong. Please try again.', 'info' => 'error'];
                } else if ($soaData == 'empty') {
                    $msg = ['message' => 'Data uploaded seems to be empty, Please try again.', 'info' => 'empty'];
                }
            } else {
                $msg = ['message' => 'Connection Error, Please check your internet connection and try again.', 'info' => 'error'];
            }
        } else {
            $msg = ['message' => 'Error, It seems there are no data found to be uploaded, Please try again.', 'info' => 'error'];
        }

        JSONResponse($msg);
    }

    public function uploadsoadatachecked()
    {
        $data        = $this->input->post(NULL);
        $msg         = array();
        $b           = array();
        $b64Doc      = '';
        $soaData     = '';
        $soaFile     = '';
        $count       = count($data['soacheck']);
        $uploadCheck = $data['soacheck'];

        # PORTAL URLs
        $url1     = 'https://leasingportal.altsportal.com/uploadSoaDataAPI';
        $url2     = 'https://leasingportal.altsportal.com/uploadSoaFile';
        $url3     = 'https://leasingportal.altsportal.com/notifications';

        $testing  = 'https://leasingportal.altsportal.com/testingUpload1';

        $update = ['upload_status' => 'Uploaded', 'upload_date'   => date('Y-m-d')];

        if (!empty($data['soacheck'])) {
            for ($i = 0; $i < $count; $i++) {
                $soafiledata = $this->adminmodel->getSOAFile($uploadCheck[$i], $data['from']);
                $soalinedata = $this->adminmodel->getSOALine($soafiledata->soa_no, $data['from']);
                $tenant      = $this->basemodel->getTenant($soafiledata->tenant_id, $data['from']);

                $balance        = $this->ledgermodel->get_forwarded_balance($soafiledata->tenant_id, $soafiledata->posting_date, $data['from']);
                $previousAmount = $this->adminmodel->getPreviousBalance($soafiledata->posting_date, $soafiledata->tenant_id, $data['from']);

                foreach ($balance as $value) {
                    $b = $value['balance'];
                }

                $web_ip = ($data['from'] === 'OLD') ? '172.16.161.37/agc-pms' : '172.16.170.10/PMS';
                $filePath = "http://$web_ip/assets/pdf/" . $soafiledata->file_name;

                if (!file_exists($filePath)) {
                    $b64Doc = chunk_split(base64_encode(file_get_contents($filePath)));
                } else {
                    $b64Doc = '';
                }

                if (!empty($soafiledata)) {
                    if ($this->isDomainAvailable('leasingportal.altsportal.com')) {

                        # SOA LINE AND SOA FILE UPLOAD
                        $data    = array('soa_file' => $soafiledata, 'soa_line' => $soalinedata, 'tenant' => $tenant);
                        $soaData = $this->sendData($url1, $data);
                        $soaFile = $this->sendData($url2, array('pdfB64' => $b64Doc, 'file_name' => $soafiledata->file_name));

                        # SOA SMS AND EMAIL
                        $data    = array('soa_file' => $soafiledata, 'balance' => $b, 'previous' => $previousAmount->amount_payable, 'tenant' => $tenant);
                        $soaData = $this->sendData($url3, $data);

                        $this->db->trans_start();
                        $this->db->where('id', $soafiledata->id);
                        $this->db->update('soa_file', $update);

                        if (isset($soalinedata)) {
                            foreach ($soalinedata as $value) {
                                $this->db->where('id', $value['id']);
                                $this->db->update('soa_line', $update);
                            }
                        }

                        $this->db->trans_complete();

                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                            $this->saveLog('SOA', $soafiledata->soa_no, $soafiledata->tenant_id, 'error', 'Uploaded Failed');
                        } else {
                            $this->saveLog('SOA', $soafiledata->soa_no, $soafiledata->tenant_id, 'success', 'Uploaded Successfully');
                            continue;
                        }
                    } else {
                        $msg = ['message' => 'Connection Error, Please check your internet connection and try again.', 'info' => 'error'];
                    }
                } else {
                    $msg = ['message' => 'Error, It seems there are no data found to be uploaded, Please try again.', 'info' => 'error'];
                }
            }

            $msg = ['message' => 'SOA Data Uploaded Successfully.', 'info' => 'success'];
        } else {

            $msg = ['message' => 'Please Check any SOA to upload.', 'info' => 'error'];
        }

        JSONResponse($msg);
    }

    public function saveLog($type, $docno, $tenant_id, $status, $statusMessage)
    {
        $data   =
            [
                'type_uploaded'  => $type,
                'tenant_id'      => $tenant_id,
                'upload_status'  => $status,
                'status_message' => $statusMessage,
                'date_uploaded'  => date('Y-m-d'),
                'user_id'        => $this->session->userdata('id')
            ];

        $this->db->trans_start();

        $this->db->insert('upload_log', $data);
        $uploadlogID = $this->db->insert_id();

        if ($status === 'success') {
            $docs =
                [
                    'uploadlogID' => $uploadlogID,
                    'documentno'  => $docno
                ];

            $this->db->insert('upload_log_docs', $docs);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === 'FALSE') {
            $this->db->trans_rollback();
        }
    }

    public function isDomainAvailable($domain)
    {
        $file = @fsockopen($domain, 80); #@fsockopen is used to connect to a socket

        # Verify whether the internet is working or not
        if ($file) {
            return true;
        } else {
            return false;
        }
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
}
