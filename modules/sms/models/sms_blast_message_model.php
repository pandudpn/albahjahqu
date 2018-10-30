<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class sms_blast_message_model extends MY_Model {

	protected $table 		= 'sms_blast_messages';
    protected $key          = 'id';
    protected $date_format  = 'datetime';
    protected $set_created  = true;

    public function __construct()
    {
        parent::__construct();
    }

}