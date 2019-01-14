<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front extends Front_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('articles/article_model', 'article');
        $this->load->helper('text');
    }

    public function index($id=null)
    {
    	$data = $this->article->find($id);

    	if(!$data)
    	{
            redirect(site_url('https://okbabe.id'), 'refresh');
    		die;
    	}

        $this->template
        	 ->set_layout('barebone')
             ->set('alert', $this->session->flashdata('alert'))
             ->set('data', $data)
    		 ->build('front');
    }
}