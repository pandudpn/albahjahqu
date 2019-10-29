<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Push {
	function push_notification($gcm_ids, $title, $msg, $action='')
    {
    	$this->config->load('google');
        $url = 'https://fcm.googleapis.com/fcm/send';
        $message = array("title" => $title, "body" => $msg, "action" => $action);
        $fields  = array(
              'registration_ids'   => $gcm_ids,
              'data'               => $message
        );

        $headers = array(
             'Authorization: key='.$this->config->item('fcm_api_key'),
             'Content-Type: application/json'
        );

        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
    }
}