<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Google {
  //Global CI Objects
  private $CI;
  //Google API Client Configs
  private $google_client_id; 
  private $google_client_secret;
  private $google_client_scope;
  private $google_client_response_type;
  private $google_redirect_uri;
  private $google_oauth_grant_type;
  //Google API Endpoints
  public $google_oauth_token_url;
  public $google_oauth_profile_url;
  public $google_oauth_login_url;
  //Authorization Code
  private $auth_code;
  //OAuth Access Token
  public $access_token;
  //Google User Profile
  public $user_id;
  public $user_firstname;
  public $user_lastname;
  public $user_gender;
  public $user_email;

  public function __construct($code=null) {
      // Load CI Object instead of (object) "$this"
      $this->CI =& get_instance();
      // Load required configs
      $this->CI->load->config('google');
      // Load required libraries and helpers
      $this->CI->load->library('session');
      $this->CI->load->helper('url');
      //Assign Attribute Values
      $google_config =& get_config();
      try{
          $this->google_client_id      = $google_config['google_client_id'];
          $this->google_client_secret  = $google_config['google_client_secret'];
          $this->google_redirect_uri   = site_url($google_config['google_redirect_uri']);
          unset($google_config);

          $this->google_client_scope          = 'email%20profile';
          $this->google_client_response_type  = 'code';
          $this->google_oauth_grant_type      = 'authorization_code';
          $this->google_oauth_token_url       = 'https://www.googleapis.com/oauth2/v3/token';
          //Available endpoints: (1) oauth2/v2/userinfo (2) userinfo/v2/me
          $this->google_oauth_profile_url     = 'https://www.googleapis.com/userinfo/v2/me';
          $this->google_oauth_login_url       = 'https://accounts.google.com/o/oauth2/auth';
          if(!empty($code)){ $this->auth_code = $code; }

      }catch(Exception $e){ 
          throw new Google_Exception('Google Library Configuration Error'); 
      }

      //Check cURL ext is installed for PHP
      if (!function_exists('curl_init'))
      {
          throw new Google_Exception('Google Library requires cURL extension to be loaded.');
      }
  }

  public function login_url()
  {
      $google_login_url  = $this->google_oauth_login_url
                           .'?scope='        .$this->google_client_scope
                           .'&response_type='.$this->google_client_response_type
                           .'&redirect_uri=' .$this->google_redirect_uri
                           .'&client_id='    .$this->google_client_id ;
                           
      return $google_login_url;
  }

  public function logout_url()
  {
      return site_url('logout');
  }

  public function get_oauth_access_token()
  {
      $auth_code     = 'code='          .$this->auth_code;
      $client_id     = 'client_id='     .$this->google_client_id;
      $client_secret = 'client_secret=' .$this->google_client_secret;
      $redirect_uri  = 'redirect_uri='  .$this->google_redirect_uri;
      $grant_type    = 'grant_type='    .$this->google_oauth_grant_type;
      $url_token     = $this->google_oauth_token_url;

      $fields = array(
        'code'          => urlencode($this->auth_code),
        'client_id'     => urlencode($this->google_client_id),
        'client_secret' => urlencode($this->google_client_secret),
        'redirect_uri'  => urlencode($this->google_redirect_uri),
        'grant_type'    => urlencode($this->google_oauth_grant_type)
      );

      foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
      rtrim($fields_string, '&');

      $curl_conn = curl_init();
      //Set the url, number of POST vars, POST data
      curl_setopt($curl_conn, CURLOPT_URL,  $url_token);
      curl_setopt($curl_conn, CURLOPT_POST, count($fields));
      curl_setopt($curl_conn, CURLOPT_POSTFIELDS, $fields_string);
      curl_setopt($curl_conn, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl_conn, CURLOPT_CONNECTTIMEOUT, 5);
      curl_setopt($ch, CURLOPT_VERBOSE, 0);
      //Execute post & get data as JSON Object
      //if true: $token['access_token'] | else: $token->access_token
      $token = json_decode(curl_exec($curl_conn), true);
      //Close connection
      curl_close($curl_conn);
      $this->set_oauth_access_token($token['access_token']);

      return $this->access_token;
  }

  private function set_oauth_access_token($access_token = null){
      if(!empty($access_token)){
          $this->access_token = $access_token;
          $this->set_access_token_session();
      }
      else{
        
      }
  }

  public function get_google_user($access_token = null)
  {
      if(empty($access_token)){
        $access_token = $this->access_token;
      }

      $curl_conn = curl_init();
      //Set the url, number of GET vars, GET data
      $url_profile =  $this->google_oauth_profile_url .'?access_token=' .$access_token ;
      curl_setopt($curl_conn, CURLOPT_URL, $url_profile);
      curl_setopt($curl_conn, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl_conn, CURLOPT_CONNECTTIMEOUT, 5);
      curl_setopt($ch, CURLOPT_VERBOSE, 0);
      //Execute GET & get data as JSON Object
      //if true: $profile['id'] | else: $profile->id
      $profile = json_decode(curl_exec($curl_conn), true);
      //Close connection
      curl_close($curl_conn);

      if(!empty($profile))
      {
        $oauth_user = $this->set_google_user($profile);
        return $oauth_user;
      }
      else{ return false; }

  }

  private function set_google_user($profile = array()){
      if(!empty($profile))
      {
          //Set Return Objet
          $google_user = array(
              'firstname'  => $profile['given_name'],
              'lastname'   => $profile['family_name'],
              'email'      => $profile['email'],
              'gender'     => $profile['gender'],
              'google_id'  => $profile['id']
          );
          //Set Google Class Attributes
          $this->user_id         = $profile['id'];
          $this->user_firstname  = $profile['given_name'];
          $this->user_lastname   = $profile['family_name'];
          $this->user_gender     = $profile['gender'];
          $this->user_email      = $profile['email'];
      }

      return $google_user;
  }

  //Used upon successful logout from our site
  //e.g. { $this->google->destroy_session(); redirect('login', redirect); }
  public function destroy_session()
  { 
    $this->session->unset_userdata('google_access_token'); 
  }

  private function get_access_token_session()
  { 
    return $this->session->userdata('google_access_token'); 
  }

  private function set_access_token_session($access_token = null)
  {
      if(empty($access_token)){
        $access_token = $this->access_token;
      }

      $this->session->set_userdata('google_access_token', $access_token);
  }

  private function logError($code, $message)
  {
      log_message('error', '[GOOGLE LIBRARY] code: ' . $code.' | message: '.$message);
      return ['error' => $code, 'message' => $message];
  }
  //Enables the use of CI super-global without having to define an extra variable.
  //e.g. (CONTROLLER) $google = $this->google->__get(google);
  public function __get($var)
  {
      return get_instance()->$var;
  }

}

class Google_Exception extends Exception {}

?>
