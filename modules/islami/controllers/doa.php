<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class doa extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('islami/doa_model', 'doa');
        $this->load->model('islami/ayat_doa_model', 'ayat');

        $this->load->helper('text');

        // $this->app_id   = $this->session->userdata('user')->app_id;

        $this->check_login();
    }

    public function index()
    {
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
    		 ->build('doa');
    }

    public function add()
    {
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Add New Doa')
            ->build('doa_form');
    }

    public function edit($id)
    {
        $is_exist   = $this->doa->find($id);
        $ayat       = $this->ayat->find_all_by(['doa_id' => $id]);

        // $this->print_array($ayat); die;

        if($is_exist){
            $doa = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Edit Doa')
                ->set('data', $doa)
                ->set('ayat', $ayat)
                ->build('doa_form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $name           = $this->input->post('name');
        // $app_id     	= $this->session->userdata('user')->app_id;

        $data = array(
            'name'      => $name
            // 'app_id'     => $app_id
        );
        
        if(!$id){
            $insert = $this->doa->insert($data);

            $no = 1;
            foreach($this->input->post('ayat') AS $key => $val){
                $array  = [
                    'doa_id'    => $insert,
                    'no'        => $no++,
                    'text_ar'   => $val,
                    'latin'     => $this->input->post('latin')[$key],
                    'translate' => $this->input->post('translate')[$key]
                ];

                $ins    = $this->ayat->insert($array);
            }
        }else{
            $update = $this->doa->update($id, $data);

            if($update){
                $delete = $this->ayat->delete_all('doa_id', $id);

                if($delete){
                    $ayat       = $this->input->post('ayat');
                    $no         = 1;
                    $latin      = $this->input->post('latin');
                    $translate  = $this->input->post('translate');

                    foreach($ayat AS $key => $val){
                        $ar = [
                            'doa_id'    => $id,
                            'no'        => $no++,
                            'text_ar'   => $val,
                            'latin'     => $latin[$key],
                            'translate' => $translate[$key]
                        ];

                        $in     = $this->ayat->insert($ar);
                    }
                }
            }
        }

        redirect(site_url('islami/doa'), 'refresh');
    }

    public function delete($id)
    {
        $delete = $this->ayat->delete_all('doa_id', $id);;
        
        if($delete){
            $this->doa->delete($id);
        }

        redirect(site_url('islami/doa'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->doa->get_datatables();
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $no++;
            $row   = array();

            $row['no']      = $no;
            $row['name']    = $l->name;
            $row['id']      = $l->id;

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->doa->count_all(),
            "recordsFiltered"   => $this->doa->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

}
