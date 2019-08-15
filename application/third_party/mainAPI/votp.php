<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Voice OTP by MainAPI.net
 *
 * @author Indra Guananda <indra.gunanda@gmail.com>
 */
class votp extends Curl {
    private $accessToken;
    private $key;
    private $msdn;
    private $response;
    function __construct($ap = "", $key = "", $msdn = "") {
        $this->accessToken = $ap;
        $this->key = $key;
        $this->msdn = $msdn;
    }
    function getResponse() {
        return $this->response;
    }
    function  otp($type = "send", $otpstr = "")
    {
        $ap = $this->accessToken;
        if ($ap != null) {
            $this->headers["Content-Type"] = "application/json";
            $this->headers["Accept"] = "application/json";
            $this->headers["Authorization"] = "Bearer " . $ap;
            if ($type == "send") {
                $res = $this->put("https://api.mainapi.net/voice-otp/0.0.1/otp/" . $this->key, json_encode(array("phoneNum" => $this->msdn, "digit" => count($this->msdn))));
                $this->response = $res->body;
            } else if ($type == "verify" && $otpstr != "") {
                $url = "https://api.mainapi.net/voice-otp/0.0.1/otp/".$this->key."/verifications";
                $res = $this->post($url, json_encode(array("otpstr" => $otpstr, "digit" => count($otpstr))));
                $this->response = json_decode($res->body);
            } else {
                $this->response = (object) array("code" => 0, "msg" => "OTP Cannot Null");
            }
        } else {
            $this->response = (object) array("code" => 0, "msg" => "Wrong Client ID or Secret ID ".$ap);
        }
    }

}
