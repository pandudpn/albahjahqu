<?php

/**
 * Mainapi
 * 
 * MainAPI SDK UNOFFICIAL
 * 
 * @package Mainapi
 * @author Indra Guananda <indra.gunanda@gmail.com>
 * @version 1.0.0
 */
require_once __DIR__ . '/mainAPI/Apis.php';
class Mainapi {

    private $client_id;
    private $secret_id;
    private $ap;
    /**
     * Set Client_ID MainAPI.net
     * @param type $client_id
     */
    function setClient_id($client_id) {
        $this->client_id = $client_id;
    }

    /**
     * Set Secret_ID MainAPI.net
     * @param type $secret_id
     */
    function setSecret_id($secret_id) {
        $this->secret_id = $secret_id;
    }

    /**
     * Get Access Token MainAPI.net
     * @return type
     */
    private function getAccessToken() {

        $base = "Basic " . base64_encode($this->client_id . ":" . $this->secret_id);
        $instances = new Curl();
        $instances->headers['Authorization'] = $base;
        $response = $instances->post("https://api.mainapi.net/token", array("grant_type" => "client_credentials"));
        $data = json_decode($response->body);
        $ap = (isset($data->access_token)) ? $data->access_token : null;
        $this->ap =  $ap;
    }
    /**
     * 
     * @param type $msdn Your Phone Number
     * @param type $content Your SMS Content
     * @param type $editor  If You User Editor Like CKEditor or TinyMCE set $editor Value to TRUE
     * @return type return $response 
     */
    function SendSMS($msdn="",$content="",$editor="")
    {
        $this->getAccessToken();
        $sendSMS = new SMSNotification($this->ap,$content,$msdn,$editor);
        if($msdn != null && $content != null)
        {
            return $sendSMS->response();
        }else{
            return (object) array("code"=>false,"msg"=>"MSDN & Content Cannot to Be Null");
        }
    }
    function otp($key,$msdn,$type="send",$otpstr="")
    {
        $this->getAccessToken();
        $otp = new SMSOTP($this->ap, $key, $msdn);
        if($type == "send")
        {
            $otp->otp("send");
        }elseif ($type == "verify" && $otpstr != "") {
            $otp->verify($otpstr);
        }
        return $otp->response();
    }
    function voiceotp($key,$msdn,$type="send",$otpstr="")
    {
        $this->getAccessToken();
        $votp = new VOICEOT($this->ap,$key,$msdn);
        if($type == "send")
        {
            $votp->otp("send");
        }elseif ($type == "verify" && $otpstr != "") {
            $votp->verify($otpstr);
        }
        return $votp->response();
    }

}

/* End of file Mainapi.php */
/* Location: ./application/libraries/Mainapi.php */