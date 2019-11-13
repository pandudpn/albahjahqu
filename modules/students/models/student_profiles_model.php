<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class student_profiles_model extends MY_Model {

    protected $table         = 'partner_student_profiles';

    protected $key           = 'id';
    protected $date_format   = 'datetime';

    public function __construct()
    {
        parent::__construct();
    }

}