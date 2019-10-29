<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
require_once APPPATH."/third_party/PhpWord/Autoloader.php"; 
 
class Word { 
    public function __construct() { 
        //parent::__construct();
		//PhpWord/Autoloader.php
		
		\PhpOffice\PhpWord\Autoloader::register();
    } 
}

?>