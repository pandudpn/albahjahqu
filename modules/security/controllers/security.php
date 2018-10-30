<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Security extends Front_Controller {
	public function __construct() {
        parent::__construct();
    }

	public function index($id='0', $type='print'){
    	$key 				 = 'cT0B1LiN3dEvm2IDXBcmoWsFvsvR';
    	$issuing_key 		 = '98YTX2QA76Z';
    	$signature_data      = array( 'data' => 'CTO BilineDEV 2017 - Ali Fahmi PN, B.Eng, M.Eng' );
    	$signature_data_json = json_encode($signature_data);
    	$encrypted_signature_data = $this->encrypt($signature_data_json,$key);
    	echo $encrypted_signature_data; die;
    	// KqKEG7dd4LXRPi++VM4UBZ37uskqeM1IgQsTPruH9H8SUux9u5qJ+DsMDrfNjmRD/IKgAZuX1mQnjthQokQRv7CBUYapoe63SbQRdsijGXs=
    }

    private function encrypt($input, $key) {
		$size  = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB); 
		$input = $this->pkcs5_pad($input, $size); 
		$td    = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, ''); 
		$iv    = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND); 
		mcrypt_generic_init($td, $key, $iv); 
		$data = mcrypt_generic($td, $input); 
		mcrypt_generic_deinit($td); 
		mcrypt_module_close($td); 
		$data = base64_encode($data); 
		return $data; 
	}

	private function decrypt($sStr, $sKey) {
		$decrypted = mcrypt_decrypt(
			MCRYPT_RIJNDAEL_128,
			$sKey, 
			base64_decode($sStr), 
			MCRYPT_MODE_ECB
		);
		$dec_s     = strlen($decrypted); 
		$padding   = ord($decrypted[$dec_s-1]); 
		$decrypted = substr($decrypted, 0, -$padding);
		return $decrypted;
	}	

	private function pkcs5_pad($text, $blocksize) { 
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad); 
	} 

}