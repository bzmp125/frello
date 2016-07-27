<?php
/*
 * Filename : Frello_Helper.php
 * Purpose : Example/Guide on how to send SMSes via the Frello API (v4). The API is restful and documentation is available online at
 *           http://docs.frello.co.zw/v4
 *
 * Dependencies : cURL.
 *
 * Author : Bakani Z.M Pilime - Lead Developer (@afrikancoder, http://fb.com/bzmpilime, https://github.com/bzmp125, http://goo.gl/ms4rcp)
 * Date : 27/07/16
 *
 */

class Frello{
    var $app_id, $app_secret;
    var $result;
    var $api_base_url = 'http://api.frello.co.zw/v4';
    function Frello($app_id, $app_secret){
        $this->app_id = $app_id;
        $this->app_secret = $app_secret;
    }
    
    function send_sms($message,$to,$from=null){
        if($from && strlen($from)>11){
            $from = substr($from,0,11);
        }
        $url = $this->api_base_url."/messages?app_id=".$this->app_id."&app_secret=".base64_encode($this->app_secret);
        $data['to'] = $to;    
        $data['from']= $from;
        $data['message'] = $message;

        $this->result = $this->send_request($url, "POST", [],$data, true);
        return ($this->result->success && $this->result->message=="MESSAGE SENT.");
    }

    function send_request($url, $method, $headers, $data, $json){
        $params = array();
        $request_method =$method;
        foreach($data as $key=>$value){
            $params[$key] = is_array($key) ? http_build_query($key) : $value;
        }
        $params = http_build_query($params);

        //initialize and setup the curl handler
        $ch = curl_init();
        //authentication via http
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        switch($request_method){
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        if($request_method!='GET')
            curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
        else{
            if(strpos($url, '?expand') === false)
                $url .= "?".$params;
            else
                $url .= "&".$params;
        }
        $url = str_replace(' ', '', $url);
        curl_setopt($ch, CURLOPT_URL, $url);
        //execute the request
        $result = curl_exec($ch);
        //if everything went great, return the data
        return ($result) ? (array) ($json) ? @json_decode($result) : $result : null;
    }

}
