<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class pdf {

    //README: https://davidsimpson.me/2013/05/19/using-mpdf-with-codeigniter/
    
    function pdf()
    {
        $CI = & get_instance();
        log_message('Debug', 'mPDF class is loaded.');
    }
 
    function load($param=NULL)
    {
        include_once APPPATH.'/third_party/mPDF/mpdf.php';
         
        if ($params == NULL)
        {
            $param = '"en-GB-x","A4","","",10,10,10,10,6,3';          
        }
         
        return new mPDF($param);
    }
}