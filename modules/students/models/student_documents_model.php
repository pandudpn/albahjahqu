<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class student_documents_model extends MY_Model {

    protected $table         = 'partner_student_documents';

    protected $key           = 'id';
    protected $date_format   = 'datetime';

    public function __construct()
    {
        parent::__construct();
    }

}