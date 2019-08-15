<?php

/**
 * Sendsms
 * 
 * Send SMS by Api mainapi.net
 * 
 * @package Sendsms
 * @author SystemFive <indra.gunanda@gmail.com>
 * @version 0.0.1
 */
require_once __DIR__ . '/curl/curl.php';

class SMSSender extends Curl {

    private $accessToken;
    private $response;
    private $msdn;
    private $content;

    /**
     * Constructor function
     *
     * @return SMSNotification 
     */
    public function __construct($ap, $content, $msdn, $editor = true) {
        $this->setContent($content, $editor);
        $this->msdn = $msdn;
        $this->accessToken = $ap;
        $this->sendSMS();
    }
    /**
     * 
     * @return array
     */
    function getResponse() {
        return $this->response;
    }
    /**
     * 
     * @param type $content
     * @param type $filter
     */
    function setContent($content, $filter = "true") {
        if (!$filter) {
            $content = strip_tags($content, "<p>");
            $content = str_replace('<p>', '', $content);
            $content = str_replace('</p>', '', $content);
        }
        $this->content = $content;
    }
    /**
     * 
     * @return type 
     */
    function sendSMS() {
        $ap = $this->accessToken;
        if ($ap != null) {
            $this->headers["Content-Type"]          = "application/x-www-form-urlencoded";
            $this->headers["Accept"]                = "application/json";
            $this->headers["Authorization"]         = "Bearer " . $ap;
            $this->headers["X-MainAPI-Username"]    = "okbabe";
            $this->headers["X-MainAPI-Password"]    = "P5NBZ9aP";
            $this->headers["X-MainAPI-Senderid"]    = "OKBABE";

            $res = $this->post("https://api.mainapi.net/smsnotification/1.0.0/messages", array("msisdn" => $this->msdn, "content" => $this->content));
            $this->response = json_decode($res->body);
        } else {
            $this->response = (object) array("code" => 0, "msg" => "Wrong Client ID or Secret ID");
        }
    }

}

/* End of file Sendsms.php */
/* Location: ./application/libraries/Sendsms.php */