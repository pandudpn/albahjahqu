<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class post extends Admin_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('post/post_model', 'post');
        $this->load->model('category/category_model', 'category');
        $this->load->model('category/post_category_model', 'post_category');
        $this->load->model('tag/tag_model', 'tag');
        $this->load->model('tag/post_tag_model', 'post_tag');
        $this->load->model('user/user_admin_model', 'user_admin');
        $this->load->model('post/notification_model', 'notification');

        $this->check_login();
    }

    public function index()
    {
    	$this->template->set('alert', $this->session->flashdata('alert'));
    	$this->template->build('index');
    }

    public function create()
    {
        if($this->input->post())
        {
            //change url image
            $content = str_replace('https://admin.sinergi46.com/data/images/', 'https://assets.sinergi46.com/images/', trim($this->input->post('content')));

            $featured_video = trim($this->input->post('featured_video'));

            if(empty($featured_video))
            {
                $featured_video = NULL;
            }

            $status = $this->input->post('status');

            if($status == 'published_now')
            {
                $status         = 'published';
                $published_date = date("Y-m-d H:i:s", strtotime('-7 hours', time()));
            }
            else if($status == 'published_later')
            {
                $status         = 'published';
                $published_date = trim($this->input->post('published_date'));
                $published_date = date("Y-m-d H:i:s", strtotime('-7 hours', strtotime($published_date)));
            }
            else
            {
                $status         = 'draft';
                $published_date = NULL;
            }
            
            $data = array(
                'title'             => trim($this->input->post('title')), 
                'slug'              => trim($this->input->post('slug')), 
                'intro'             => trim($this->input->post('intro')), 
                'content'           => $content, 
                'category_id'       => trim($this->input->post('category')), 
                'category_name'     => $this->category->find(trim($this->input->post('category')))->name, 
                'category_type'     => $this->category->find(trim($this->input->post('category')))->type, 
                'author_id'         => $this->session->userdata('user')->id, 
                'author_name'       => ucwords($this->user_admin->find($this->session->userdata('user')->id)->name), 
                'headline'          => ($this->input->post('headline') == 'on' ? 'yes' : 'no'),
                'pinned'            => ($this->input->post('pinned') == 'on' ? 'yes' : 'no'),
                'featured'          => ($this->input->post('featured') == 'on' ? 'yes' : 'no'),
                'featured_video'    => $featured_video, 
                'status'            => $status,
                'published_date'    => $published_date
            );

            //update all previous headline to 'no'
            if($data['headline'] == 'yes')
            {
                $update = $this->post->update_multiple_where(array('deleted'), array('0'), array('headline' => 'no'));
                $data['pinned']     = 'no';
                $data['featured']   = 'no';
            }

            //update all previous pinned to 'no'
            if($data['pinned'] == 'yes')
            {
                $update = $this->post->update_multiple_where(array('deleted'), array('0'), array('pinned' => 'no'));
            }

            $config['upload_path']      = './data/images/';
            $config['allowed_types']    = '*';
            $config['max_size']         = 0;
            $config['encrypt_name']     = true;

            $this->load->library('upload', $config);

            if(!empty($_FILES['featured_image']['name']))
            {
                
                if(!$this->upload->do_upload('featured_image')) {
                    $msg = $this->upload->display_errors('','');
                    $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $msg));
                    redirect(site_url('post/create'), 'refresh');
                } 
                else 
                {
                    $file                   = $this->upload->data();
                    $data['featured_image'] = $file['file_name'];

                    // copy to assets
                    copy( './data/images/'.$data['featured_image'], '/srv/www/html/sinergi46/assets/images/'.$data['featured_image'] );
                }
            }

            // if(!empty($_FILES['featured_video']['name']))
            // {
            //     $config['upload_path']      = './data/videos/';
            //     $config['allowed_types']    = '*';
            //     $config['max_size']         = 0;
            //     $config['encrypt_name']     = true;

            //     $this->upload->initialize($config);
                
            //     if(!$this->upload->do_upload('featured_video')) {
            //         $msg = $this->upload->display_errors('','');
            //         $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $msg));
            //         redirect(site_url('post/create'), 'refresh');
            //     } 
            //     else 
            //     {
            //         $file                   = $this->upload->data();
            //         $data['featured_video'] = $file['file_name'];

            //         // copy to assets
            //         copy( './data/videos/'.$data['featured_video'], '/srv/www/html/sinergi46/assets/files/'.$data['featured_video'] );
            //     }
            // }

            $post_id = $this->post->insert($data);

            //update all previous feature to 'no' except last 10
            if($data['featured'] == 'yes')
            {
                $posts = $this->post->order_by('id', 'desc');
                $posts = $this->post->find_all_by(array('deleted' => '0', 'featured' => 'yes'));

                for ($i=10; $i < count($posts); $i++) { 
                    $update = $this->post->update($posts[$i]->id, array('featured' => 'no'));
                }
            }

            //insert tags
            $tag_id = $this->input->post('tag_id');

            if(!empty($tag_id))
            {
                for ($i=0; $i < count($tag_id); $i++) { 

                    if(is_numeric($tag_id[$i]))
                    {
                        $tag_name      = $this->tag->find($tag_id[$i])->name;
                        $post_tag_id   = $this->post_tag->insert(array('post_id' => $post_id, 'tag_id' => $tag_id[$i], 'tag_name' => $tag_name));
                    }
                    else
                    {
                        $new_tag_id     = $this->tag->insert(array('name' => $tag_id[$i]));
                        $tag_name       = $this->tag->find($new_tag_id)->name;
                        $post_tag_id    = $this->post_tag->insert(array('post_id' => $post_id, 'tag_id' => $new_tag_id, 'tag_name' => $tag_name));
                    }
                }
            }

            //insert category
            $category_id = $this->post_category->insert(array('post_id' => $post_id, 'category_id' => $data['category_id'], 'category_type' => $data['category_type']));

            if($post_id)
            {
                $msg = 'save post success';
                $this->session->set_flashdata('alert', array('type' => 'success', 'msg' => $msg));

                //push notification
                if($this->input->post('status') == 'published_now')
                {
                    $this->push($post_id);
                }

                redirect(site_url('post/edit/'.$post_id), 'refresh');
            }
            else
            {
                $msg = 'save post failed';
                $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $msg));

                redirect(site_url('post/create'), 'refresh');
            }
        }

        $categories = $this->category->order_by('name', 'asc');
        $categories = $this->category->find_all_by(array('deleted' => '0'));

        $tags = $this->tag->order_by('name', 'asc');
        $tags = $this->tag->find_all_by(array('deleted' => '0'));
    	
        $this->template->set('alert', $this->session->flashdata('alert'));
        $this->template->set('title', 'Add new post');
        $this->template->set('categories', $categories);
    	$this->template->set('tags', $tags);
    	$this->template->build('form');
    }

    public function edit($id=null)
    {
        $data = $this->post->find_by(array('id' => $id, 'deleted' => '0'));

        if(!$id || !$data)
        {
            $error = 'Error: data failed';
            $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $error));

            redirect(site_url('post'), 'refresh');
        }

        if($this->input->post())
        {
            //change url image
            $content = str_replace('https://admin.sinergi46.com/data/images/', 'https://assets.sinergi46.com/images/', trim($this->input->post('content')));

            $featured_video = trim($this->input->post('featured_video'));

            if(empty($featured_video))
            {
                $featured_video = NULL;
            }

            $status = $this->input->post('status');

            if($status == 'published_now')
            {
                $status         = 'published';
                $published_date = date("Y-m-d H:i:s", strtotime('-7 hours', time()));
            }
            else if($status == 'published_later')
            {
                $status         = 'published';
                $published_date = trim($this->input->post('published_date'));
                $published_date = date("Y-m-d H:i:s", strtotime('-7 hours', strtotime($published_date)));
            }
            else
            {
                $status         = 'draft';
                $published_date = NULL;
            }

            $data = array(
                'title'             => trim($this->input->post('title')), 
                'slug'              => trim($this->input->post('slug')), 
                'intro'             => trim($this->input->post('intro')), 
                'content'           => $content, 
                'category_id'       => trim($this->input->post('category')), 
                'category_name'     => $this->category->find(trim($this->input->post('category')))->name, 
                'category_type'     => $this->category->find(trim($this->input->post('category')))->type,
                'author_id'         => $this->session->userdata('user')->id, 
                'author_name'       => ucwords($this->user_admin->find($this->session->userdata('user')->id)->name), 
                'headline'          => ($this->input->post('headline') == 'on' ? 'yes' : 'no'),
                'pinned'            => ($this->input->post('pinned') == 'on' ? 'yes' : 'no'),
                'featured'          => ($this->input->post('featured') == 'on' ? 'yes' : 'no'),
                'featured_video'    => $featured_video, 
                'status'            => $status,
                'published_date'    => $published_date
            );

            $config['upload_path']      = './data/images/';
            $config['allowed_types']    = '*';
            $config['max_size']         = 0;
            $config['encrypt_name']     = true;

            $this->load->library('upload', $config);

            if(!empty($_FILES['featured_image']['name']))
            {
                
                if(!$this->upload->do_upload('featured_image')) {
                    $msg = $this->upload->display_errors('','');
                    $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $msg));
                    redirect(site_url('post/edit/'.$id), 'refresh');
                } 
                else 
                {
                    $file                   = $this->upload->data();
                    $data['featured_image'] = $file['file_name'];

                    // copy to assets
                    copy( './data/images/'.$data['featured_image'], '/srv/www/html/sinergi46/assets/images/'.$data['featured_image'] );
                }
            }

            // if(!empty($_FILES['featured_video']['name']))
            // {
            //     $config['upload_path']      = './data/videos/';
            //     $config['allowed_types']    = '*';
            //     $config['max_size']         = 0;
            //     $config['encrypt_name']     = true;

            //     $this->upload->initialize($config);
                
            //     if(!$this->upload->do_upload('featured_video')) {
            //         $msg = $this->upload->display_errors('','');
            //         $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $msg));
            //         redirect(site_url('post/edit/'.$id), 'refresh');
            //     } 
            //     else 
            //     {
            //         $file                   = $this->upload->data();
            //         $data['featured_video'] = $file['file_name'];

            //         // copy to assets
            //         copy( './data/videos/'.$data['featured_video'], '/srv/www/html/sinergi46/assets/files/'.$data['featured_video'] );
            //     }
            // }

            //update all previous headline to 'no'
            if($data['headline'] == 'yes')
            {
                $update = $this->post->update_multiple_where(array('deleted'), array('0'), array('headline' => 'no'));
                $data['pinned']     = 'no';
                $data['featured']   = 'no';
            }

            //update all previous pinned to 'no'
            if($data['pinned'] == 'yes')
            {
                $update = $this->post->update_multiple_where(array('deleted'), array('0'), array('pinned' => 'no'));
            }

            //update all previous feature to 'no' except last 10
            if($data['featured'] == 'yes')
            {
                $posts = $this->post->order_by('id', 'desc');
                $posts = $this->post->find_all_by(array('deleted' => '0', 'featured' => 'yes'));

                for ($i=10; $i < count($posts); $i++) { 
                    $update = $this->post->update($posts[$i]->id, array('featured' => 'no'));
                }
            }

            $post_id = $this->post->update($id, $data);

            //insert tags
            $delete = $this->post_tag->update_where('post_id', $id, array('deleted' => '1'));
            $tag_id = $this->input->post('tag_id');

            if(!empty($tag_id))
            {
                for ($i=0; $i < count($tag_id); $i++) { 
                
                    if(is_numeric($tag_id[$i]))
                    {
                        $tag_name      = $this->tag->find($tag_id[$i])->name;
                        $post_tag_id   = $this->post_tag->insert(array('post_id' => $id, 'tag_id' => $tag_id[$i], 'tag_name' => $tag_name));
                    }
                    else
                    {
                        $new_tag_id     = $this->tag->insert(array('name' => $tag_id[$i]));
                        $tag_name      = $this->tag->find($new_tag_id)->name;
                        $post_tag_id    = $this->post_tag->insert(array('post_id' => $id, 'tag_id' => $new_tag_id, 'tag_name' => $tag_name));
                    }
                }
            }

            //insert category
            $delete      = $this->post_category->update_where('post_id', $id, array('deleted' => '1'));
            $category_id = $this->post_category->insert(array('post_id' => $id, 'category_id' => $data['category_id'], 'category_type' => $data['category_type']));

            if($post_id)
            {
                $msg = 'save post success';
                $this->session->set_flashdata('alert', array('type' => 'success', 'msg' => $msg));

                redirect(site_url('post/edit/'.$id), 'refresh');
            }
            else
            {
                $msg = 'save post failed';
                $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $msg));

                redirect(site_url('post/edit/'.$id), 'refresh');
            }
        }

        $categories = $this->category->order_by('name', 'asc');
        $categories = $this->category->find_all_by(array('deleted' => '0'));

        $tags = $this->tag->order_by('name', 'asc');
        $tags = $this->tag->find_all_by(array('deleted' => '0'));
        $post_tags = $this->post_tag->find_all_by(array('post_id' => $id, 'deleted' => '0'));
        foreach ($post_tags as $key => $pt) {
            $post_tags_selected[] = $pt->tag_id;
        }

        $data = $this->post->find($id);
        $data->published_date = date("Y-m-d H:i:s", strtotime('+7 hours', strtotime($data->published_date)));
        
        $this->template->set('alert', $this->session->flashdata('alert'));
        $this->template->set('title', 'Edit post');
        $this->template->set('d', $data);
        $this->template->set('categories', $categories);
        $this->template->set('tags', $tags);
        $this->template->set('post_tags', $post_tags);
        $this->template->set('post_tags_selected', $post_tags_selected);
        $this->template->build('form');
    }

    public function delete($id=null)
    {
    	$data = $this->post->find_by(array('id' => $id, 'deleted' => '0'));

        if(!$id || !$data)
        {
            $error = 'Error: data failed';
            $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $error));

            redirect(site_url('post'), 'refresh');
        }
        else
        {
            $delete = $this->post->set_soft_deletes(TRUE);
            $delete = $this->post->delete($id);

            if($delete)
            {
                $msg = 'delete success';
                $this->session->set_flashdata('alert', array('type' => 'success', 'msg' => $msg));

                redirect(site_url('post'), 'refresh');
            }
            else
            {
                $error = 'Error: something error while deleting';
                $this->session->set_flashdata('alert', array('type' => 'danger', 'msg' => $error));

                redirect(site_url('post'), 'refresh');
            }
        }
    }

    public function push($id=null, $redirect = 'no')
    {
        //Populating FCM IDs
        $max_fcm_users = 1000;
        $users_num     = $this->notification->count_all_gcm_users();
        $users_queue   = floor($users_num/$max_fcm_users);
        //$users_queue   = 20;

        $post         = $this->post->find($id);

        $title         = "Sinergi46";
        $message       = $post->title;
        for ($i=0; $i <= $users_queue; $i++) { 

            $fcm_ids   = Array();
            $offset    = $i * $max_fcm_users;
            $limit     = $max_fcm_users;
            $users     = $this->notification->get_all_gcm_users($offset, $limit);
            foreach ($users as $user) {
                array_push($fcm_ids, $user->gcm_regid);
            }
            //Send to All FCM IDs in $i-st Batch of A Thousand.
            if(count($fcm_ids) > 0){
                $this->push_message_group($fcm_ids, $title, $message, $id);
            }
        }

        if($redirect == 'yes')
        {
            $msg = 'sending push notification to all user success';
            $this->session->set_flashdata('alert', array('type' => 'success', 'msg' => $msg));

            redirect(site_url('post'), 'refresh');
        }
    }

    private function push_message_group($gcm_ids, $title, $msg, $news_id)
    {
        $url        = 'https://fcm.googleapis.com/fcm/send';
        $message    = array(
                            "title"     => $title, 
                            "body"      => $msg, 
                            "message"   => $msg, 
                            "icon"      => "ic_notification_above_lollipop", 
                            "color"     => "#F15A23",
                            "id"        => $news_id,
                            "click_action" => "news_detail"
        );

        $fields  = array(
              'registration_ids'   => $gcm_ids,
              'data'               => $message,
              'notification'       => $message
        );

        $headers = array(
             'Authorization: key=AIzaSyAhogUeu2tTPxTJ6E7ld2zeDpNn4bi7F8Q',
             'Content-Type: application/json'
        );

        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);
    }

    public function imgupload()
    {
        $config['upload_path']      = './data/images/';
        $config['allowed_types']    = '*';
        $config['max_size']         = 0;
        $config['encrypt_name']     = true;
        
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('file')) {
            $this->output->set_header('HTTP/1.0 500 Server Error');
            exit;
        } else {
            $file = $this->upload->data();
            
            // copy to assets
            copy( './data/images/'.$file['file_name'], '/srv/www/html/sinergi46/assets/images/'.$file['file_name'] );

            $this->output
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(['location' => 'https://assets.sinergi46.com/images/'.$file['file_name']]))
                ->_display();
            exit;
        }
    }

    public function datatables()
    {
    	$list = $this->post->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        foreach ($list as $l) {
            $title = $l->title.'<br/>';

            if($l->headline == 'yes')
            {
                $title .= '<span class="label label-primary">Headline</span>&nbsp;';
            }

            if($l->featured == 'yes')
            {
                $title .= '<span class="label label-success">Featured</span>&nbsp;';
            }

            if($l->pinned == 'yes')
            {
                $title .= '<span class="label label-warning">Pinned</span>';
            }

            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $title;
            $row[] = $l->author_name;
            $row[] = $l->category_name;
            $row[] = $l->view;
            $row[] = $l->status;
            $row[] = date("Y-m-d H:i:s", strtotime('+7 hours', strtotime($l->published_date)));

            //button edit & delete
            $btn  = '<a href="#" onclick="alert_push(\''.site_url('post/push/'.$l->id.'/yes').'\')" class="btn btn-primary btn-sm">
                        <i class="fa fa-bullhorn"></i>
                      </a> &nbsp;';

            $btn  .= '<a href="'.site_url('post/edit/'.$l->id).'" class="btn btn-success btn-sm">
            			<i class="fa fa-pencil"></i>
            		  </a> &nbsp;';

            $btn  .= '<a href="#" onclick="alert_delete(\''.site_url('post/delete/'.$l->id).'\')" class="btn btn-danger btn-sm">
            			<i class="fa fa-trash"></i>
            		  </a>';

            $row[] = $btn;

            $data[] = $row;
        }
 
        $output = array(
            "draw" 				=> $_POST['draw'],
            "recordsTotal" 		=> $this->post->count_all(),
            "recordsFiltered" 	=> $this->post->count_filtered(),
            "data" 				=> $data,
        );
        //output to json format
        echo json_encode($output);
    }
}