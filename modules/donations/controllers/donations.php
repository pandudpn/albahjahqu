<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class donations extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('donations/donations_model', 'donation');
        $this->load->model('donations/donation_photos_model', 'donation_photos');
        $this->load->model('donations/donation_trackings_model', 'donation_tracking');

        $this->load->helper('text');

        $this->app_id   = $this->session->userdata('user')->app_id;

        $this->check_login();
    }

    public function index()
    {
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
    		 ->build('index');
    }

    public function add()
    {
        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Buat Baru Donasi')
            ->build('form');
    }

    public function tracking($id) {
        $donation   = $this->donation->find($id);
        $tracking   = $this->donation_tracking->find_all_by(['donation_id' => $id]);

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Kabar Terbaru untuk Donasi - <b>'.$donation->title.'</b>')
            ->set('donation', $id)
            ->set('track', $tracking)
            ->build('tracking');
    }

    public function tracking_list($donation) {
        $tracking   = $this->donation_tracking->find_all_by(['donation_id' => $donation]);

        $status     = 400;
        $data       = '';
        if(count($tracking) > 0) {
            $status = 200;
            $data   = $tracking;
        }

        echo json_encode([
            'status'    => $status,
            'data'      => $data
        ]);
    }

    public function edit($id)
    {
        $is_exist = $this->donation->find($id);

        if($is_exist){
            $donation = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Ubah Data Donasi')
                ->set('data', $donation)
                ->build('form');
        }
    }

    public function track_edit($id) {
        $tracking   = $this->donation_tracking->get($id);

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Kabar Terbaru untuk Donasi - <b>'.$tracking->title_donation.'</b>')
            ->set('data', $tracking)
            ->set('donation', $tracking->donation_id)
            ->build('tracking');
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $title     		= $this->input->post('title');
        $desc           = $this->input->post('description');
        $type           = $this->input->post('type');
        $due_date       = $this->input->post('due_date');
        $target         = $this->input->post('target');
        $amount         = $this->input->post('slot_amount');
        $status         = $this->input->post('status');
        $satuan         = $this->input->post('satuan');

        $data = array(
            'app_id'        => $this->app_id,
            'title'         => $title,
            'description'   => $desc,
            'nominal_type'  => $type,
            'due_date'      => $due_date,
            'slot_amount'   => $amount,
            'target_amount' => $target,
            'type'          => $status,
            'unit'          => $satuan
        );

        if($amount == '') {
            $data['slot_amount']    = 0;
        }
        
        if(!$id){
            $insert = $this->donation->insert($data);

            if(!empty($_FILES['image']['name'])) {
                $number_files   = sizeof($_FILES['image']['tmp_name']);
                $files          = $_FILES['image'];
    
                for($i = 0; $i < $number_files; $i++) {
                    $_FILES['image']['name']    = $files['name'][$i];
                    $_FILES['image']['type']    = $files['type'][$i];
                    $_FILES['image']['tmp_name']= $files['tmp_name'][$i];
                    $_FILES['image']['error']   = $files['error'][$i];
                    $_FILES['image']['size']    = $files['size'][$i];
    
                    $config['upload_path']      = './data/images/donations/';
                    $config['allowed_types']    = 'jpg|png|gif|jpeg';
                    $config['encrypt_name']     = true;
    
                    $this->load->library('upload', $config);
    
                    if($this->upload->do_upload('image')) {
                        $file   = $this->upload->data();
                        $image  = site_url('data/images/donations').'/'.$file['file_name'];
    
                        $in     = $this->donation_photos->insert(['donation_id' => $insert, 'photo' => $image]);
                    }
                }
            }

        }else{
            $update = $this->donation->update($id, $data);

            if(!empty($_FILES['image']['name'])) {
                $number_files   = sizeof($_FILES['image']['tmp_name']);
                $files          = $_FILES['image'];
    
                for($i = 0; $i < $number_files; $i++) {
                    $_FILES['image']['name']    = $files['name'][$i];
                    $_FILES['image']['type']    = $files['type'][$i];
                    $_FILES['image']['tmp_name']= $files['tmp_name'][$i];
                    $_FILES['image']['error']   = $files['error'][$i];
                    $_FILES['image']['size']    = $files['size'][$i];
    
                    $config['upload_path']      = './data/images/donations/';
                    $config['allowed_types']    = 'jpg|png|gif|jpeg';
                    $config['encrypt_name']     = true;
    
                    $this->load->library('upload', $config);
    
                    if($this->upload->do_upload('image')) {
                        $file   = $this->upload->data();
                        $image  = site_url('data/images/donations').'/'.$file['file_name'];
    
                        $in     = $this->donation_photos->insert(['donation_id' => $id, 'photo' => $image]);
                    }
                }
            }
        }

        redirect(site_url('donations'), 'refresh');
    }

    public function tracking_save($donation){
        $id         = $this->input->post('id');
        $message    = $this->input->post('message');

        $data       = array(
            'donation_id'   => $donation,
            'message'       => $message
        );

        if(!$id) {
            $insert = $this->donation_tracking->insert($data);
        }else{
            $update = $this->donation_tracking->update($id, $data);
        }

        redirect(site_url('donations'), 'refresh');
    }

    public function delete($id)
    {
        $delete = $this->donation->delete($id);

        redirect(site_url('donations'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->donation->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $tracking   = $this->donation_tracking->get_all(['donation_id' => $l->id]);

            if($l->nominal_type == "freetext") {
                $type   = "Free Text";
            }else{
                $type   = "Slot (".number_format($l->slot_amount, 0, '.', '.')."/slot)";
            }

            $no++;
            $row   = array();

            $row['no']          = $no;
            $row['title']       = $l->title;
            $row['desc']        = word_limiter($l->description, 10);
            $row['type']        = $type;
            $row['due_date']    = date('d F Y', strtotime($l->due_date));
            $row['amount']      = number_format($l->amount, 0, '.', '.');
            $row['target']      = number_format($l->target_amount, 0, '.', '.');
            $row['status']      = $l->type;
            $row['edit']        = site_url('donations/edit/'.$l->id);
            $row['deleted']     = site_url('donations/delete/'.$l->id);

            $row['tracking']    = 0;
            $row['track_edit']  = '';
            $row['track_add']   = site_url('donations/tracking/'.$l->id);
            $row['track_list']  = '';
            if(count($tracking) > 0) {
                $row['tracking']    = count($tracking);
                $row['track_edit']  = site_url('donations/tracking/'.$l->id);
                $row['track_list']  = site_url('donations/tracking_list/'.$l->id);
            }

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->donation->count_all($this->app_id),
            "recordsFiltered"   => $this->donation->count_filtered($this->app_id),
            "data"              => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function imgupload()
    {
        $config['upload_path']      = './data/images/donations/';
        $config['allowed_types']    = '*';
        $config['max_size']         = 0;
        $config['encrypt_name']     = true;
        
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('file')) {
            $this->output->set_header('HTTP/1.0 500 Server Error');
            exit;
        } else {
            $file = $this->upload->data();
            
            $this->output
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(['location' => site_url('data/images/donations/'.$file['file_name'])]))
                ->_display();
            exit;
        }
    }

}
