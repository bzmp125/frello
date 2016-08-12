<?php
/*
 * Filename : frello.php
 * Purpose : Example/Guide on how to send SMSes via the Frello API (v4). The API is restful and documentation is available online at
 *           http://docs.frello.co.zw/v4
 *
 * Dependencies : Frello_Helper.php - Simple Class with a few methods to use when sending messages,creating lists, sending messages to a list etc over
 *            the API.
 *
 * Author : Bakani Z.M Pilime - Lead Developer (@afrikancoder, http://fb.com/bzmpilime, https://github.com/bzmp125, http://goo.gl/ms4rcp)
 * Date : 27/07/16
 *
 */

//include or require the helper
require_once 'Frello_Helper.php';

const APP_ID = 'replace_with_app_id_here';
const APP_SECRET = 'replace_with_app_secret_here';

$frello = new Frello(APP_ID,APP_SECRET);

//To send an sms to a number, make sure the number begins with the country code and does not have '00' or '+' appended to it

$message = "This is a test message.";
//you can send to multiple numbers by concatenating them using a comma
$to = "263*********,263*********";           //263 is the country code for Zimbabwe

//Then use the send_message method to send a single message

if($frello->send_sms($message,$to)){
    //message has been sent successfully
    echo 'Message has been sent';
}else{
    //message wasnt sent...check frello->result
    echo "<pre>", print_r($frello->result), "</pre>";    
}

