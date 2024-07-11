<?php 

class ITEXMOSMS{
    public static function send_sms($recipients, $message) {
        try {
            $credentials = [
                'api' => 'https://api.itexmo.com/api/broadcast',
                'email' => 'itsysdev@alturasbohol.com',
                'password' => '!t5y5d3v@',
                'senderID' => 'ASC',
                'apiCode' => 'PR-ALTUR152758_ITHWZ'
            ];
        
            $curl = curl_init();
        
            $fields_string = http_build_query([
                'Email' => $credentials['email'],
                'Password' => $credentials['password'],
                'SenderId' => $credentials['senderID'],
                'ApiCode' => $credentials['apiCode'],
                'Recipients' => $recipients,
                'Message' => $message
            ]);

            
            curl_setopt($curl, CURLOPT_URL, $credentials['api']);
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $fields_string);

            curl_exec($curl);

            curl_close($curl);

            die('success');
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}

