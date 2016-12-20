<?php
/*
 * Filename : Frello_Helper.php
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

    //send sms to single or multiple numbers (comma-separated)
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
    
    //sending an sms to a list
    function send_sms_to_list($list_id, $message, $from=null){
        if($from && strlen($from)>11){
            $from = substr($from,0,11);
        }
        $url = $this->api_base_url."/lists/$list_id/send?app_id=".$this->app_id."&app_secret=".base64_encode($this->app_secret);
        $data['to'] = $to;    
        $data['from']= $from;
        $data['message'] = $message;
        $this->result = $this->send_request($url, "POST", [],$data, true);
        return ($this->result->success && $this->result->message=="MESSAGE SENT TO LIST.");
    }    
    
    //sending a template message to a list 
    
    function send_template_sms_to_list($template_id,$list_id,$variables, $from=null){
        if($from && strlen($from)>11){
            $from = substr($from,0,11);
        }
        $url = $this->api_base_url."/templates/$template_id?app_id=".$this->app_id."&app_secret=".base64_encode($this->app_secret);
        $data = $variables;
        $data['list_id'] = $list_id;
        $data['from']= $from;
        $this->result = $this->send_request($url, "POST", [],$data, true);
        return ($this->result->success && $this->result->message=="MESSAGE SENT TO LIST.");
    }

    //sending a template message to a single number 

    function send_template_sms_to_single($template_id,$to,$variables, $from=null){
        if($from && strlen($from)>11){
            $from = substr($from,0,11);
        }
        $url = $this->api_base_url."/templates/$template_id?app_id=".$this->app_id."&app_secret=".base64_encode($this->app_secret);
        $data = $variables;
        $data['to'] = $to;
        $data['from']= $from;
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
        //uncomment the following line if your internet connection is proxied.
//        curl_setopt($ch, CURLOPT_PROXY,"proxy:port");

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
