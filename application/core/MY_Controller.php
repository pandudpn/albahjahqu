<?php

class Base_controller extends MX_Controller{

    public function __construct() {
        parent::__construct();

        if(ENVIRONMENT == 'development'){
            $this->output->enable_profiler(TRUE);
        }
    }

    //DEBUG TOOLS
    public function last_query() {
        echo '<br/>'; 
        echo '<b>Query:</b> ' . $this->db->last_query();
        echo '<br/>';
    }

    public function inspect_variable($var){
        echo '<b>Variable:</b><br/>';
        echo '<pre>'; 
        print_r($var); 
        echo '</pre>';
    }

    //UPLOAD MULTIPLE FILES
    public function docs_upload($files,$type,$avatar=false)
    {
        if($type == 'image')
        {
            $docs_uploaded_path = array();
            $config             = array();
            $files              = $_FILES['pictures'];
            if($avatar)
                $path           = './data/avatars';
            else
                $path           = './data/images';
            $this->filepath     = $path;

            $config['upload_path']   = $path;
            $config['allowed_types'] = 'gif|GIF|jpg|JPG|jpeg|JPEG|png|PNG';
            $config['max_size']      = '1000000';
            $config['max_width']     = '7000';
            $config['max_height']    = '7000';
            $config['max_filename']  = '250';
            $config['overwrite']     = 0;
        }else if ($type == 'file') {
            $docs_uploaded_path = array();
            $config             = array();
            $files              = $_FILES['pictures'];
            $path               = './data/files';
            $this->filepath     = $path;

            $config['upload_path']   = $path;
            $config['allowed_types'] = 'gif|GIF|jpg|JPG|jpeg|JPEG|png|PNG|pdf|PDF|doc|DOC|docx|DOCX';;
            $config['max_size']      = '1000000';
            $config['max_width']     = '7000';
            $config['max_height']    = '7000';
            $config['max_filename']  = '250';
            $config['overwrite']     = 0;
        }

        //Configure upload.
        if(!is_dir($path))
        {
            mkdir($path, 0777, true);
        }

        $this->load->library('upload', $config);

        if ($files) 
        {
            $docs_uploaded_path = $this->upload_multiple_files('pictures', $type);
            return $docs_uploaded_path;
        }
        else
        {   
            //NULL
            return $docs_uploaded_path; 
        }
    }


    private function upload_multiple_files($field='pictures',$type)
    {
        $files = array();
        foreach( $_FILES[$field] as $key => $all ){
            foreach( $all as $i => $val )
            {
                $files[$i][$key] = $val;
            }
        }

        $files_uploaded = array();

        for ($i=0; $i < count($files); $i++) {
            $_FILES[$field] = $files[$i];

            if ($this->upload->do_upload($field))
                $files_uploaded[$i] = $this->upload->data($files);
            else
                //$files_uploaded[$i] = null;
                $error = array('error' => $this->upload->display_errors('',''));
                if($error['error'])
                {
                    $this->rest->set_error('failed upload image: '.$error['error']); 
                    $this->rest->render(); 
                    die();
                }
        }

        return $files_uploaded;
    }
}

class Front_Controller extends Base_Controller{

    public function __construct() {
        parent::__construct();
        $this->template->set_layout('index');
        $this->template->set_theme('default');
    }

    public function check_login(){
        if(!$this->session->userdata('logged_in')){
            redirect(site_url('signin'));
        }
    }

    public function load_pagination_config(){
        
        $config['suffix']          = '?' . http_build_query($_GET, '', "&");
        $config['first_url']       = $config['base_url'] . $config['suffix'];
        $config['full_tag_open']   = '<div class="pagination"><ul class="pagination">';
        $config['full_tag_close']  = '</ul></div><!--pagination-->';
        $config['first_link']      = '&laquo; Pertama';
        $config['first_tag_open']  = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';

        $config['last_link']       = 'Terakhir &raquo;';
        $config['last_tag_open']   = '<li class="next page">';
        $config['last_tag_close']  = '</li>';

        $config['next_link']       = 'Berikutnya &rarr;';
        $config['next_tag_open']   = '<li class="next page">';
        $config['next_tag_close']  = '</li>';

        $config['prev_link']       = '&larr; Sebelumnya';
        $config['prev_tag_open']   = '<li class="prev page">';
        $config['prev_tag_close']  = '</li>';

        $config['cur_tag_open']    = '<li class="active page-item"><a href="">';
        $config['cur_tag_close']   = '</a></li>';

        $config['num_tag_open']    = '<li class="page page-item">';
        $config['num_tag_close']   = '</li>';

        return $config;
    }

}


class Admin_Controller extends Base_Controller{

    public function __construct() {
        parent::__construct();
        $this->load->model('complaints/customer_support_model', 'customer_support');
        $this->load->model('complaints/customer_support_help_model', 'customer_support_help');
        $this->load->model('customers/kyc_model', 'kyc');
        $this->load->model('transactions/transaction_model', 'transaction');

        if($this->session->userdata('user')->role == 'kyc')
        {
            if (strpos(current_url(), 'kyc') == false) 
            {
                if(strpos(current_url(), 'user') == false)
                {
                    redirect(site_url('customers/kycs'), 'refresh');
                    die;
                }
            }
        }

        $unread = $this->customer_support->unread()->unread;
        $unread_count = $unread;

        if($unread > 0)
        {
            $unread = '<span class="label label-pill label-primary float-right">'.$unread.'</span>';
        }
        else
        {
            $unread = '';
        }

        $waiting = $this->kyc->waiting()->waiting;

        if($waiting > 0)
        {
            $waiting = '<span class="label label-pill label-primary float-right">'.$waiting.'</span>';
        }
        else
        {
            $waiting = '';
        }

        $pending = $this->transaction->pending()->pending;

        if($pending > 0)
        {
            $pending = '<span class="label label-pill label-primary float-right">'.$pending.'</span>';
        }
        else
        {
            $pending = '';
        }

        $dispute_topup = $this->transaction->dispute_topup()->dispute_topup;

        if($dispute_topup > 0)
        {
            $dispute_topup = '<span class="label label-pill label-primary float-right">'.$dispute_topup.'</span>';
        }
        else
        {
            $dispute_topup = '';
        }

        $open_status = $this->customer_support_help->open_status()->open_status;
        $open_status_count = $open_status;

        if($open_status > 0)
        {
            $open_status = '<span class="label label-pill label-primary float-right">'.$open_status.'</span>';
        }
        else
        {
            $open_status = '';
        }

        // var_dump($pending);die;

        $total_complaint = $open_status_count + $unread_count;

        if($total_complaint > 0)
        {
            $total_complaint = '<span class="label label-pill label-primary float-right">'.$total_complaint.'</span>';
        }
        else
        {
            $total_complaint = '';
        }

        $this->template->set_layout('index');
        $this->template->set_theme('admin');
        $this->template->set('unread', $unread);
        $this->template->set('waiting', $waiting);
        $this->template->set('pending', $pending);
        $this->template->set('dispute_topup', $dispute_topup);
        $this->template->set('open_status', $open_status);
        $this->template->set('total_complaint', $total_complaint);
    }

    public function check_login(){
        
        
        if(!$this->session->userdata('admin_logged_in')){

            $error = 'Error: please login first';
            $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $error));
            $this->template->set('alert', $this->session->flashdata('alert'));

            redirect('login', 'refresh');
        }
    }


}

class Api_Controller extends Base_Controller{

    public function __construct() {
        parent::__construct();
        // $this->key = d9d9b483f3b94c962f64424ce03d84ae0d330b0b
        /*if($this->input->get('key') != sha1('B1Lin3DEvl0pMenT')):
            $this->rest->set_error('Invalid key access');
            $this->rest->render();
            die();
        endif;*/
    }

}
