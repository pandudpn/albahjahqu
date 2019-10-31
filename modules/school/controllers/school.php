<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class units extends Admin_Controller {
    
	public function __construct() {
        parent::__construct();
        $this->load->model('community/units_model', 'unit');
        $this->load->model('geo/geo_services_model', 'geo');

        $this->load->helper('text');

        $this->app_id   = $this->session->userdata('user')->app_id;

        $this->check_login();
    }

    public function index()
    {
        $this->template
             ->set('alert', $this->session->flashdata('alert'))
    		 ->build('school');
    }

    public function add()
    {
        $provinsi   = $this->geo->find_all_by('geo_provinces', ['deleted' => 0]);

        $this->template
            ->set('alert', $this->session->flashdata('alert'))
            ->set('title', 'Tambah Sekolah Baru')
            ->set('provinsi', $provinsi)
            ->build('school_form');
    }

    public function edit($id)
    {
        $is_exist   = $this->unit->find($id);

        $provinsi   = $this->geo->find_all_by('geo_provinces', ['deleted' => 0]);
        $kabupaten  = $this->geo->find_all_by('geo_cities', ['deleted' => 0, 'province_id' => $is_exist->province]);
        $kecamatan  = $this->geo->find_all_by('geo_districts', ['deleted' => 0, 'city_id' => $is_exist->city]);
        $kelurahan  = $this->geo->find_all_by('geo_villages', ['deleted' => 0, 'district_id' => $is_exist->district]);

        if($is_exist){
            $unit = $is_exist;

            $this->template
                ->set('alert', $this->session->flashdata('alert'))
                ->set('title', 'Ubah Data Sekolah - '.$unit->name)
                ->set('data', $unit)
                ->set('provinsi', $provinsi)
                ->set('kabupaten', $kabupaten)
                ->set('kecamatan', $kecamatan)
                ->set('kelurahan', $kelurahan)
                ->build('unit_form');
        }
    }

    public function save()
    {
        $id       		= $this->input->post('id');

        $app_id         = $this->app_id;
        $type           = $this->input->post('type');
        $name           = $this->input->post('name');
        $level          = $this->input->post('level');
        $date           = $this->input->post('date');
        $address        = $this->input->post('address');
        $province       = $this->input->post('province');
        $city           = $this->input->post('city');
        $district       = $this->input->post('district');
        $village        = $this->input->post('village');
        $lat            = $this->input->post('lat');
        $long           = $this->input->post('long');

        if($date == ''){
            $date   = null;
        }

        $data = array(
            'app_id'    => $app_id,
            'name'      => $name,
            'type'      => $type,
            'level'     => $level,
            'date'      => $date,
            'address'   => $address,
            'province'  => $province,
            'city'      => $city,
            'district'  => $district,
            'village'   => $village,
            'lat'       => $lat,
            'long'      => $long
        );
        
        if(!$id){
            $insert = $this->unit->insert($data);
        }else{
            $update = $this->unit->update($id, $data);
        }

        redirect(site_url('community/units'), 'refresh');
    }

    public function delete($id)
    {
        $delete = $this->unit->delete($id);

        redirect(site_url('community/units'), 'refresh');
    }

    public function datatables()
    {
        $list = $this->unit->get_datatables($this->app_id);
        
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $latlong    = null;
            if(($l->lat != 0) && ($l->long != 0)){
                $latlong    = $l->lat.','.$l->long;
            }

            $date   = '-';
            if($l->date != NULL){
                $date   = date('d F Y', strtotime($l->date));
            }
            $no++;
            $row   = array();

            $row['no']      = $no;
            $row['type']    = ucfirst($l->type);
            $row['name']    = $l->name;
            $row['date']    = $date;
            $row['address'] = $l->address.', '.$l->village_name.', '.$l->district_name.', '.$l->city_name;
            $row['maps']    = $latlong;
            $row['edit']    = site_url('community/units/edit/'.$l->id);
            $row['delete']  = site_url('community/units/delete/'.$l->id);

            $data[] = $row;
        }
 
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->unit->count_all($this->app_id),
            "recordsFiltered"   => $this->unit->count_filtered($this->app_id),
            "data"              => $data,
        );

        //output to json format
        echo json_encode($output);
    }

    public function cities($province){
        $status = 'error';
        $data   = '';

        if(!empty($province)){
            $city   = $this->geo->find_all_by('geo_cities', ['deleted' => 0, 'province_id' => $province]);

            if($city){
                $status = 'success';
                $data   = $city;
            }
        }

        echo json_encode([
            'status'    => $status,
            'data'      => $data
        ]);
    }

    public function districts($city){
        $status = 'error';
        $data   = '';

        if(!empty($city)){
            $district   = $this->geo->find_all_by('geo_districts', ['deleted' => 0, 'city_id' => $city]);

            if($district){
                $status = 'success';
                $data   = $district;
            }
        }

        echo json_encode([
            'status'    => $status,
            'data'      => $data
        ]);
    }

    public function villages($district){
        $status = 'error';
        $data   = '';

        if(!empty($district)){
            $village   = $this->geo->find_all_by('geo_villages', ['deleted' => 0, 'district_id' => $district]);

            if($village){
                $status = 'success';
                $data   = $village;
            }
        }

        echo json_encode([
            'status'    => $status,
            'data'      => $data
        ]);
    }

}
