<?php

/**
 * OTP SMS MainAPI
 * 
 * OTP SMS by Api mainapi.net
 * 
 * @package Sendsms
 * @author SystemFive <indra.gunanda@gmail.com>
 * @version 0.0.1
 */
require_once __DIR__ . '/curl/curl.php';

class OTP extends Curl {

    private $accessToken;
    private $key;
    private $msdn;
    private $response;

    public function __construct($ap = "", $key = "", $msdn = "") {
        $this->accessToken = $ap;
        $this->key = $key;
        $this->msdn = $msdn;
    }

    function getResponse() {
        return $this->response;
    }

    /**
     * 
     * @param type $type Set Type of Function send|verify
     * @param type $otpstr Set OTP String
     * 
     */
    function otp($type = "send", $otpstr = "") {
        $ap = $this->accessToken;
        if ($ap != null) {
            $this->headers["Content-Type"] = "application/x-www-form-urlencoded";
            $this->headers["Accept"] = "application/json";
            $this->headers["Authorization"] = "Bearer " . $ap;
            if ($type == "send") {
                $res = $this->put("https://api.mainapi.net/smsotp/1.0.1/otp/" . $this->key, array("phoneNum" => $this->msdn, "digit" => count($this->msdn)));
                $this->response = json_decode($res->body);
            } else if ($type == "verify" && $otpstr != "") {
                $url = "https://api.mainapi.net/smsotp/1.0.1/otp/" . $this->key . "/verifications";
                $res = $this->post($url, array("otpstr" => $otpstr, "digit" => count($otpstr)));
                $this->response = json_decode($res->body);
            } else {
                $this->response = (object) array("code" => 0, "msg" => "OTP Cannot Null");
            }
        } else {
            $this->response = (object) array("code" => 0, "msg" => "Wrong Client ID or Secret ID " . $ap);
        }
    }

}
