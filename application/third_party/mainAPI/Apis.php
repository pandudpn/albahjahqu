<?php

/**
 * Bridge main Library to Other MainAPI Function
 *
 * @author Indra Guananda <indra.gunanda@gmail.com>
 */
require __DIR__ . '/SMSNotification.php';
require __DIR__ . '/OTP.php';
require __DIR__ . '/votp.php';

class SMSNotification extends SMSSender {

    /**
     * 
     * @param type $ap Access Token
     * @param type $content Content
     * @param type $msdn Phone Number
     * @param type $editor Verify You Use a Editor or Not ? That Replace <p> to /n
     */
    function __construct($ap, $content, $msdn, $editor) {
        /* @var $content type */
        parent::__construct($ap, $content, $msdn, $editor);
    }

    /**
     * 
     * @return getResponse
     */
    function response() {
        return $this->getResponse();
    }

}

class SMSOTP extends OTP {

    function __construct($ap = "", $key = "", $msdn = "") {
        parent::__construct($ap, $key, $msdn);
    }
    function response() {
        return $this->getResponse();
    }
    
    function send()
    {
        $this->otp("send");
    }
    function verify($otpstr="")
    {
        $this->otp("verify", $otpstr);
    }

}
class VOICEOT extends votp{
    function __construct($ap, $key, $msdn) {
        parent::__construct($ap, $key, $msdn);
    }
    function response() {
        return $this->getResponse();
    }
    
    function send()
    {
        $this->otp("send");
    }
    function verify($otpstr="")
    {
        $this->otp("verify", $otpstr);
    }
}
