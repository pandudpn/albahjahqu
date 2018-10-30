<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class References extends Front_Controller {
	
    const CONST_PI = 3.14798023;

	public function __construct() {
        parent::__construct();
        $this->load->model('references/reference_model', 'reference');
        $this->load->library('facebook');
        $this->load->library('google');
    }

    public function index(){
        $this->load->library('ci_pusher');
        $pusher = $this->ci_pusher->getPusher();
        $data['message'] = 'hello world';
        $pusher->trigger('my-channel', 'my-event', $data);
    	$this->template->build('index');
    }

    public function pusher(){
        $this->load->library('ci_pusher');
        $pusher = $this->ci_pusher->getPusher();
        $data['type']    = 'ping';
        $data['trx_id']  = time();
        $data['message'] = 'hello world. let us integrate, again. now with mas kris.';
        $data['payload'] = 'AT+CMD =1, 6282233043298';
        $pusher->trigger('ussd-box-dago', 'tsel-slot-one', $data);
        $this->template->build('pusher');
    }

    public function stream_event(){
        header('Content-Type: text/event-stream');
        // recommended to prevent caching of event data.
        header('Cache-Control: no-cache'); 

        //Constructs the SSE data format and flushes that data to the client.
        $highest_bid = array(
            'auction_id' => '1', 'dealer_id' => '2', 'car_id' => '3', 'bid_value' => '750'
        );
        $stream_data = '{'.
                       '"auction_id": "'.$highest_bid['auction_id'].'", '.
                       '"dealer_id": "' .$highest_bid['dealer_id'].'", '.
                       '"car_id": "'    .$highest_bid['car_id'].'", '.
                       '"bid_value": "' .$highest_bid['bid_value'].'"'.
                       '}';
        
        echo "id: "   . time() . PHP_EOL;
        echo "data: " . $stream_data . PHP_EOL . PHP_EOL;
        ob_flush(); flush();
    }

    public function sample_output(){
        echo    '{
                  "request_param": "",
                  "status": "success",
                  "error_message": null,
                  "data": {
                    "auction_id": "190",
                    "auction_car": "205",
                    "multimedia_file": "aaaa.jpg",
                    "multimedia_uri": "http://localhost/api/data/images/aaaa.jpg",
                    "multimedia_code": "5",
                    "multimedia_name": "Interior"
                  },
                  "next": ""
                }';
    }

    public function facebook_login(){
        $fb = $this->facebook->Facebook();
        if(!$this->facebook->is_authenticated()){
            $fb_login_url = $this->facebook->login_url();
            $this->template->set('fb_login_url', $fb_login_url);
        }
        $this->template->build('index');
    }

    //Facebook Social Login Client Callback
    public function facebook_connect()
    {
        $code  = $this->input->get('code');
        $state = $this->input->get('state');
        if(!empty($code)){
            $fb = $this->facebook->Facebook($code, $state);
            $fb_access_token = $this->facebook->is_authenticated();
            if($fb_access_token){
                $fb_user_data = $this->facebook->Request('get', '/me?fields=id,name,birthday,email,gender', [], $fb_access_token);
                $facebook_user = array(
                    'facebook_id' => $fb_user_data['id'],
                    'fullname'    => $fb_user_data['name'],
                    'birthday'    => $fb_user_data['birthday'],
                    'email'       => $fb_user_data['email'],
                    'gender'      => $fb_user_data['gender']
                );
                $this->session->set_userdata('facebook_user', $facebook_user);
                //(1) Attempt Login using FB ID by HTTP Client
                $url       = $this->config->item('api_url');
                $resource  = 'users';
                $query     = '/social_login/facebook';
                $key       = '?key=312fd05d107f1b0a42ea36a7b4dfa282019d42be';
                $api_url   = $url . $resource . $query . $key;

                $client    = new GuzzleHttp\Client([ 'base_uri' => $this->url ]);
                $response  = $client->request('POST', $api_url,
                             [
                                'form_params' =>
                                [
                                    'social_id'    => $facebook_user['facebook_id'],
                                    'social_email' => $facebook_user['email']
                                ]
                             ]
                );

                $user  = $response->getBody()->getContents();
                $user  = json_decode($user);
                $user  = $user->data;
                //(2.1) If Found, Login to Member Dashboard.
                if(!empty($user)){ redirect('member', 'refresh'); }
                //(2.2) If Not Found, Load Registration Form with FB User Data
                else{ redirect('register', 'refresh'); }
            }
        }
        else{
            redirect('login?error=facebook_auth', 'refresh'); 
        } 
    }

    public function google_login(){
        $google           = new Google();
        $google_login_url = $google->login_url();
        $this->template->build('index');
    }

    //Google+ Social Login Client Callback
    public function google_connect()
    {
        $google_code  = $this->input->get('code');
        $google_error = $this->input->get('error');
        if($google_code){
            $google = new Google($google_code);
            //EQUAL WITH: $google->access_token; $this->session->userdata('google_access_token');
            $google_token    = $google->get_oauth_access_token();
            $google_profile  = $google->get_google_user($google_token);
            $google_user     = array(
                    'google_id' => $google_profile['google_id'],
                    'fullname'  => $google_profile['firstname'].' '.$google_profile['lastname'],
                    'birthday'  => $google_profile['birthday'],
                    'email'     => $google_profile['email'],
                    'gender'    => $google_profile['gender']
            );
            $this->session->set_userdata('google_user', $google_user);

            //(1) Attempt Login using Google ID by HTTP Client
            $url       = $this->config->item('api_url');
            $resource  = 'users';
            $query     = '/social_login/google';
            $key       = '?key=312fd05d107f1b0a42ea36a7b4dfa282019d42be';
            $api_url   = $url . $resource . $query . $key;

            $client    = new GuzzleHttp\Client([ 'base_uri' => $this->url ]);
            $response  = $client->request('POST', $api_url,
                         [
                            'form_params' =>
                            [
                                'social_id'    => $google_user['google_id'],
                                'social_email' => $google_user['email']
                            ]
                         ]
            );

            $user  = $response->getBody()->getContents();
            $user  = json_decode($user);
            $user  = $user->data;
            //(2.1) If Found, Login to Member Dashboard.
            if(!empty($user)){ 
              if($user->type == "Seller"){
                  $this->session->set_userdata('login', true);
                  $this->session->set_userdata('seller_profile', $user);
                  redirect('seller', 'refresh');
              }elseif($user->type == "Dealer"){
                  $this->session->set_userdata('login', true);
                  $this->session->set_userdata('dealer_profile', $user);
                  redirect('dealer', 'refresh');
              }
            }
            //(2.2) If Not Found, Load Registration Form with Google User Data
            else{ 
              $this->session->set_userdata('social_register_gid', $google_user['google_id']);
              $this->session->set_userdata('social_register_email', $google_user['email']);
              $this->session->set_userdata('social_register_name', $google_user['fullname']);
              redirect('register', 'refresh'); 
            }
        }
        else
        {
            if($google_error=='access_denied'){ 
              redirect('login?error=google_auth', 'refresh'); 
            }
        }
    }

    //API Social Login by ID/Email + Auto Linked Mechanism
    public function social_login($platform='facebook') {
        
        $social_id    = $this->input->post('social_id');
        $social_email = $this->input->post('social_email');

        if(empty($platform) || empty($social_id)|| empty($social_email)){
            $this->rest->set_error('Missing Parameters: Platform/Social ID/Social Email. ');
        }

        switch ($platform) {
            case 'facebook':
                $social_data = array('facebook_id' => $social_id);
                break;
            case 'google':
                $social_data = array('google_id' => $social_id);
                break;
            default:
                $social_data = array();
                break;
        }

        //By ID First, By Email Then. //
        /* Assume that this user has soc-med-linked account */
        $linked_account = true; 
        $user           = $this->user->find_by($social_data);
        if(empty($user)) //No Social ID for this User
        { 
            //So, this user has NO soc-med-linked account
            $linked_account = false; 
            $user           = $this->user->find_by( array('email' => $social_email) );
        }

        //If There is account by either Social ID or Social Email
        if(!empty($user))
        {
            $data = array(
                        'id'            => $user->id,
                        'email'         => $user->email,
                        'first_name'    => $user->first_name,
                        'last_name'     => $user->last_name,
                        'email'         => $user->email,
                        'address'       => $user->address,
                        'city'          => $user->city,
                        'state'         => $user->state,
                        'cellphone'     => $user->cellphone,
                        'avatar'        => $user->avatar,
                        'type'          => 'user'
            );

            if(!$linked_account){
                $linked_account = $this->user->update($user->id, $social_data);
            }
        }
        else{ $data = array(); }
        
        $this->rest->set_data($data);
        $this->rest->render();
    }

    //DATA STRUCTURE MANIPULATION
    //Making Array of Combination from 2/More Arrays
    public function combinations(){
        $data = array(
            array('Honda', 'Toyota'),
            array('SUV', 'Sedan', 'Hatchback')
        );
        
        $dealer_filter_clause = array();
        $combinations = $this->generate_combinations($data);  
        foreach ($combinations as $key => $c) {
            $clause = "(c.make = '".$c[0]."' and c.body_style LIKE '%".$c[1]."%')";
            array_push($dealer_filter_clause, $clause);
        }
        
        echo "<pre>";
        print_r($dealer_filter_clause); 
        echo "</pre>";
        echo "<pre>";
        print_r(implode(" or ", $dealer_filter_clause)); 
        echo "</pre>";
        die;
        $this->template->build('index');
    }

    private function generate_combinations(array $data, array &$all = array(), array $group = array(), $value = null, $i = 0)
    {
        $keys = array_keys($data);
        if (isset($value) === true) {
            array_push($group, $value);
        }

        if ($i >= count($data)) {
            array_push($all, $group);
        } else {
            $currentKey     = $keys[$i];
            $currentElement = $data[$currentKey];
            foreach ($currentElement as $val) {
                $this->generate_combinations($data, $all, $group, $val, $i + 1);
            }
        }

        return $all;
    }

    //NOTES ON DATETIME
    public function timestamp(){
        /* Counting DAYS */
        // $from = strtotime('2016-09-08');
        // $to   = strtotime('2016-12-31');

        // $seconds = abs($to - $from);
        // $days    = $seconds / (60 * 60 * 24);

        // echo $days;

        /* Counting WEEKS */
        // $from  = DateTime::createFromFormat('m/d/Y', '09/08/2016');
        // $to    = DateTime::createFromFormat('m/d/Y', '12/31/2016');

        // $weeks = floor($from->diff($to)->days/7);
        // var_dump($weeks + 1); 
        //Why + 1? because we include the starting week in duration

        /* UPDATE MULTIPLE WHERE */
        // $member_data = array(
        //    "fullname" => "Ali Fahmi Update Multiple Where",
        //    "email"    => "ali.fahmi2@bilinedev.com",
        //    "phone"    => "0896"
        // );

        // $member_where        = ["email", "phone"];
        // $member_where_values = ["ali.fahmi2@bilinedev.com", "0896"];

        // SAMPLE UPDATE MEMBER
        // $membership = $this->members->update('2', $member_data);
        // $membership = $this->members->update_where('email', 'ali.fahmi2@bilinedev.com', $member_data);
        // $membership = $this->members->update_multiple_where($member_where, $member_where_values, $member_data);
        $this->template->build('index');
    }

    //PDF MODULE
    public function write_pdf(){
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        ini_set('memory_limit','128M');
        
        $db_data       = 'Ali Fahmi';
        //METHOD 1: Embedded HTML Fn
        //$pdf_content = $this->html_template_content($db_data);
        //METHOD 2: Templates in ASETS.
        $filepath      = FCPATH . 'assets/templates/email.php';
        $pdf_content   = file_get_contents($filepath);
        //LOOP DATA YOU WANT TO REPLACE
        $pdf_content   = str_replace("{content}", $db_data, $pdf_content);

        // die($pdf_content);
        $pdf->WriteHTML($pdf_content);
        $pdf->Output();
    }

    //GUZZLE HTTP CLIENT
    public function http_get()
    {
        $this->load->library('guzzle');
        $url       = 'http://api.20fit.online/';
        $resource  = 'addresses';
        $query     = '/city?';
        $key       = 'key=d9d9b483f3b94c962f64424ce03d84ae0d330b0b';
        $api_url   = $url . $resource . $query . $key;

        $credentials = '';
        $client      = new GuzzleHttp\Client([ 
                            'base_uri' => $url,
                            'headers'  => [ 'Authorization' => 'Basic ' . $credentials ]
                        ]);
        $response  = $client->request('GET', $api_url);
        $cities    = $response->getBody()->getContents();
        $cities    = json_decode($cities);

        /* ACCESSIBLE DATA */
        // var_dump($cities->request_param);
        // var_dump($cities->status);
        // var_dump($cities->error_message);

        var_dump($cities->data);
        // echo count($cities->data); [=> need to count to init a for-loop it later on]
        // var_dump($cities->next);
        // var_dump($cities->data[0]->title);

        /* MANIPULATING DATA WITHOUT COUNTING */
        // $cities = (object) $cities->data;
        // foreach ($cities as $c) {
        //   var_dump($c); die;
        // }

        // WHILE $base_uri DEFINED AS 'https://foo.com/api/';
        // [SAMPLE 1] Sending a request to https://foo.com/api/test
        // $response = $client->request('GET', 'test');
        // [SAMPLE 2] Sending a request to https://foo.com/root
        // $response = $client->request('GET', '/root');
    }

    public function http_post()
    {
        $this->load->library('guzzle');
        $url             = $this->config->item('api_url');       
        $resource        = 'appointment';
        $query           = '/create?';
        $key             = 'key=d9d9b483f3b94c962f64424ce03d84ae0d330b0b';      
        $api_url         = $url . $resource . $query . $key;

        $credentials     = '';
        $client          = new GuzzleHttp\Client([ 
                                'base_uri' => $url,
                                'headers'  => [ 'Authorization' => 'Basic ' . $credentials ]
                            ]);
        $response        = $client->request('POST', $api_url, 
                              [
                                'form_params' => 
                                  [
                                      'a'  => $x,
                                      'b'  => $y
                                  ]
                              ]
                            );
          
        $result          = $response->getBody()->getContents();
        $result          = json_decode($result);

        var_dump($result->data);
    }

    public function http_post_json(){
        $this->load->library('guzzle');
        $url             = 'https://www.googleapis.com/urlshortener';       
        $resource        = '/v1';
        $query           = '/url?';
        $key             = 'key=AIzaSyDUn3Gr1sk_l50lUVlToIE-6Hem68RgeNE';      
        $api_url         = $url . $resource . $query . $key;

        $credentials     = '';
        //$client          = new GuzzleHttp\Client();
        $client          = new GuzzleHttp\Client([ 
                                'base_uri' => $url,
                                'headers'  => [ 'Content-Type' => 'application/json' ]
                            ]);
        $response        = $client->request('POST', $api_url, 
                              [
                                'json' => 
                                  [
                                      'longUrl'  => 'http://192.168.88.36'
                                  ]
                              ]
                            );
          
        $result          = $response->getBody()->getContents();
        $result          = json_decode($result);

        var_dump($result);
    }

    public function http_post_accept_json($input_source = "php://input")
    {
        if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0){ throw new Exception('Request method must be POST!'); }
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        if(strcasecmp($contentType, 'application/json') != 0){ throw new Exception("Content type must be: 'application/json'"); }

        $raw_json_data = file_get_contents($input_source);
        $encoded_data  = json_decode($raw_json_data, true);
        //$encoded_data['assoc_key'];
        if(!is_array($decoded)){ throw new Exception('Received content contains invalid JSON!'); }
    }

    //FCM PUSH NOTIFICATION
    private function push_notification($gcm_ids, $title, $msg, $action='feed', $id='')
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $message = array("title" => $title, "message" => $msg, "action" => $action, "" => $id);
        $fields  = array(
              'registration_ids'   => $gcm_ids,
              'data'               => $message
        );

        $api_key = "";
        $headers = array(
             'Authorization: key='.$api_key,
             'Content-Type: application/json'
        );

        // Open connection
        $ch = curl_init();
        try{
            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        } 
        catch(Exception $e){ echo $e; }
        // Execute post
        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
    }

    //EMAIL DELIVERY
    public function send_email(){
        //Mailgun
        $recipient     = 'Ali Fahmi PN';
        $to            = 'ali.fahmi.pn@gmail.com';
        $title         = 'Hello From Intro HMVC by BilineDEV';
        $content       = 'Sent from Intro HMVC by PT Biline Aplikasi Digital';
        $content_txt   = 'Sent from Intro HMVC by PT Biline Aplikasi Digital';
        $sender        = 'PT Biline Aplikasi Digital <admin@bilinedev.com>';
        $this->sending_email_mailgun($recipient, $to, $sender, $title, $content, $content_txt);
        //Mandrill
        $parameters = array(
            'to'        => 'ali.fahmi.pn@gmail.com',
            'from'      => 'admin@bilinedev.com',
            'sender'    => 'Admin BilineDEV',
            'title'     => 'Verfikasi Email: Forest Management Apps',
            'content'   => 'Sent from Intro HMVC by PT Biline Aplikasi Digital',
            'text'      => 'Sent from Intro HMVC by PT Biline Aplikasi Digital'

        );
        $this->sending_email_mandrill($parameters);
    }

    //MAILGUN EMAIL DELIVERY IMPLEMENTATION
    private function sending_email_mailgun($fullname, $email, $sender, $title, $content, $content_txt)
    {
        
        $html_content = $this->html_template_content($content);    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:key-a8e3fbf754faa2d02282d277b62150c3');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v3/mg.20fit.co.id/messages');
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            array(
                  'from'    => $sender,
                  'to'      => $fullname.' <'.$email.'>',
                  'subject' => $title,
                  'text'    => $content_txt,
                  'html'    => $html_content
            ));

        $result = curl_exec($ch);
        $info   = curl_getinfo($ch);

        if($info['http_code'] != 200){ $data = 'failed'; }
        else{ $data = 'success'; }
 
        curl_close($ch);
        
        return $data;

        
    }

    //MANDRILL EMAIL DELIVERY IMPLEMENTATION
    private function sending_email_mandrill($parameters){
        
        $mandrill_ready = NULL;

        try {
            $this->load->config('mandrill', TRUE);
            $api_key = $this->config->item('mandrill_api_key');
            $this->mandrill->init($api_key);
            $mandrill_ready = TRUE;

        } catch(Mandrill_Exception $e) {

            $mandrill_ready = FALSE;

        }

        if( $mandrill_ready ) {
            
            $email = array(
                'html'       => $parameters['content'], 
                'text'       => $parameters['text'],
                'subject'    => $parameters['title'],
                'from_email' => $parameters['from'],
                'from_name'  => $parameters['sender'],
                'to'         => array(array('email' => $parameters['to'] )) 
                //'to'       => array(
                //                  array('email' => 'joe@example.com' ),
                //                  array('email' => 'joe2@example.com' )
                //)
                );

            $result = $this->mandrill->messages_send($email);

        }
       
    }

    private function html_template_content($content)
    {

        $message = '<meta name="viewport" content="width=device-width" /><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        $message .= '<table style="background-color: #f6f6f6;width: 100%;">';
        $message .= '<tbody><tr><td>&nbsp;</td><td style="display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;" width="600">';
        $message .= '<div style="max-width: 600px;margin: 0 auto;display: block;padding: 20px;">';
        $message .= '<table cellpadding="0" cellspacing="0" class="main" style="background: #fff;border: 1px solid #e9e9e9;border-radius: 3px;" width="100%">';
        $message .= '<tbody><tr><td style="padding: 20px;">';
        $message .= '<table cellpadding="0" cellspacing="0" width="100%">';
        $message .= '<tbody><tr><td style="content-block">';
        $message .= '<div class="content">Dear '.$content.',</div>';
        $message .= '</td></tr>';
        $message .= '<tr><td align="center" class="content-block social-block" style="background: #efefef;border: 1px solid #cecece;padding: 10px 10px 5px 10px;">';
        $message .= '<table align="center"><tbody align="center"><tr align="center">';
        $message .= '<td align="center" style="padding-right: 10px;">';
        $message .= '<a href="http://www.facebook.com/duapuluhfit" style="color: #acacac;text-decoration: none;font-size: 12px;"><img alt="facebook" class="social" src="http://20fit.co.id/files/facebook-4-48.png"/>';
        $message .= '<br />Facebook </a></td>';
        $message .= '<td align="center" style="padding-right: 10px;">';
        $message .= '<a href="http://www.twitter.com/20_fit" style="color: #acacac;text-decoration: none;font-size: 12px;"><img alt="twitter" class="social" src="http://20fit.co.id/files/twitter-4-48.png"/>';
        $message .= '<br/>Twitter </a></td>';
        $message .= '<td align="center" style="padding-right: 10px;">';
        $message .= '<a href="http://instagram.com/20_fit" style="color: #acacac;text-decoration: none;font-size: 12px;"><img alt="instagram" class="social" src="http://20fit.co.id/files/instagram-4-48.png"/>';
        $message .= '<br/>Instagram </a></td>';
        $message .= '<td align="center" style="padding-right: 10px;">';
        $message .= '<a href="http://youtube.com/20fitID" style="color: #acacac;text-decoration: none;font-size: 12px;"><img alt="youtube" class="social" src="http://20fit.co.id/files/youtube-4-48.png"/>';
        $message .= '<br />Youtube </a></td>';
        $message .= '<td align="center" style="padding-right: 10px;">';
        $message .= '<a href="http://www.20fit.co.id" style="color: #acacac;text-decoration: none;font-size: 12px;">';
        $message .= '<img alt="website" class="social" src="http://20fit.co.id/files/website-optimization-2-48.png"/>';
        $message .= '<br />Website </a></td>';
        $message .= '</tr></tbody></table></td></tr>';
        $message .= '<tr><td class="content-block">';
        $message .= '<p>&nbsp;</p>';
        $message .= '<p align="center" style="margin-bottom: 10px;"><em>Copyright &copy; 2016 PT. Biline Aplikasi Digital, All rights reserved.</em></p>';
        $message .= '<p align="center" style="margin-bottom: 10px;"><a href="http://www.20fit.co.id" target="_blank"><em><img alt="" src="http://bilinedev.com/web/wp-content/uploads/2016/04/BilineDev-Logo-Horizontal-On-Light-copy-e1461573051717.png" style="width: 100px; height: 30px;" /></em></a></p>';
        $message .= '<p align="center">';
        $message .= 'PT Biline Aplikasi Digital (Biline DEV) is an established IT & Digital Media Agency since 2008 & based on Jakarta - Bandung.';
        $message .= '</p>';
        $message .= '<p align="center">Call our <a href="http://www.20fit.co.id/branch">STUDIO</a> now!<br /><br /></p>';
        $message .= '<p align="center">';
        $message .= '<strong>Head Office:</strong><br />';
        $message .= 'Grand ITC Permata Hijau, Blok Ruby No. 8<br />';
        $message .= 'Jl. Letjen Soepono (Arteri Permata Hijau)<br />';
        $message .= 'Kebayoran Lama, Jakarta Selatan 12210<br />';
        $message .= 'Ph. 021-53668573 /74<br />';
        $message .= '<a href="mailto:info@20fit.co.id">info@20fit.co.id</a><br />';
        $message .= 'Operational hours: 8.00 AM - 5.00 PM (Mon - Fri)<br />';
        $message .= '</p></td></tr></tbody>';
        $message .= '</table></td></tr></tbody></table></div>';
        $message .= '</td><td>&nbsp;</td></tr></tbody></table>';

        $message .= '<style type="text/css">';
        $message .= '<style type="text/css">*{margin: 0;padding: 0;';
        $message .= 'font-family: "Helvetica Neue", "Helvetica",Helvetica, Arial, sans-serif;box-sizing: border-box;';
        $message .= 'font-size: 12px;}';
        $message .= 'img {max-width: 100%;}';
        $message .= 'body {-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;}';
        $message .= 'a{color: #00F;font-weight: bold;text-decoration: underline;}';
        $message .= '.content{margin: 10px auto;font-size: 14px;line-height: 24px;}';
        $message .= '.content p{margin-bottom: 20px;font-size: 14px;}';
        $message .= '@media only screen and (max-width: 640px) {';
        $message .= '.images{ width: 100%;height: auto;}}';
        $message .= '</style>';
  
        return $message;                               
                                        
    } 
}

?>