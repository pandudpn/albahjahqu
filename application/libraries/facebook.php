<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
use Facebook\Facebook as FB;
use Facebook\Authentication\AccessToken;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookBatchResponse;
use Facebook\Helpers\FacebookCanvasHelper;
use Facebook\Helpers\FacebookJavaScriptHelper;
use Facebook\Helpers\FacebookPageTabHelper;
use Facebook\Helpers\FacebookRedirectLoginHelper; 

class Facebook {
  private $CI;
  private $facebook_app_id; 
  private $facebook_app_secret;
  private $facebook_login_type;
  private $facebook_login_redirect_url;
  private $facebook_logout_redirect_url;
  private $facebook_permissions;
  private $facebook_graph_version;
  private $facebook_auth_on_load;
  private $access_token;
  private $code;
  private $state;

  private $fb;
  private $helper;

  public function __construct() {
        // Load autoload FB
        require APPPATH.'third_party/Facebook/autoload.php';
        // Load CI Object instead of (object) $this 
        $this->CI =& get_instance();
        // Load required config
        $this->CI->load->config('facebook');
        // Load required libraries and helpers
        $this->CI->load->library('session');
        $this->CI->load->helper('url');

        $facebook_config =& get_config();
        try{
          $this->facebook_app_id              = $facebook_config['facebook_app_id'];
          $this->facebook_app_secret          = $facebook_config['facebook_app_secret'];
          $this->facebook_login_type          = $facebook_config['facebook_login_type'];
          $this->facebook_login_redirect_url  = $facebook_config['facebook_login_redirect_url'];
          $this->facebook_logout_redirect_url = $facebook_config['facebook_logout_redirect_url'];
          $this->facebook_permissions         = $facebook_config['facebook_permissions'];
          $this->facebook_graph_version       = $facebook_config['facebook_graph_version'];
          $this->facebook_auth_on_load        = $facebook_config['facebook_auth_on_load'];
          unset ($facebook_config);
        }catch(Exception $e){ 
          throw new Facebook_Exception('FB Library Configuration Error'); 
        }

        //Check cURL ext is installed for PHP
        if (!function_exists('curl_init'))
        {
            throw new Facebook_Exception('FB Library requires cURL extension to be loaded.');
        }
  }

  public function Facebook($code='', $state='')
  { 
      //Load FB Object
      if (!isset($this->fb))
      {
          $this->fb = new Facebook\Facebook([
                  'app_id'                  => $this->facebook_app_id, 
                  'app_secret'              => $this->facebook_app_secret,
                  'default_graph_version'   => $this->facebook_graph_version
                ]);
      }
      
      //Load correct helper depending on login type
      switch ($this->facebook_login_type)
      {
          case 'js':
              $this->helper = $this->fb->getJavaScriptHelper();
              break;
          case 'canvas':
              $this->helper = $this->fb->getCanvasHelper();
              break;
          case 'page_tab':
              $this->helper = $this->fb->getPageTabHelper();
              break;
          case 'web':
              $this->helper = $this->fb->getRedirectLoginHelper();
              break;
      }

      if(!empty($code)){
        $this->code = $code;
      }

      if(!empty($state)){
        $this->state = $state;
        $this->helper->getPersistentDataHandler()->set('state', $this->state);
      }

      if ($this->facebook_auth_on_load === TRUE)
      {
          // Try and authenticate the user right away (get valid access token)
          $this->authenticate();
      }

      return $this->fb;
  }

  public function Object(){ return $this->fb; }

  public function Helper(){ return $this->helper; }

  //Graph API Request Fn.
  //e.g.
  /*
   *  $message = $this->input->post('message');
   *  $result  = $this->facebook->Request('post', '/me/feed', ['message' => $message] );
  **/
  public function Request($method, $endpoint, $params = [], $access_token = null)
  {
    if(empty($access_token)){ $access_token = $this->access_token; }

      try
      {
          $response = $this->fb->{strtolower($method)}($endpoint, $params, $access_token);
          return $response->getDecodedBody();
      }
      catch(FacebookResponseException $e)
      {
          return $this->logError($e->getCode(), $e->getMessage());
      }
      catch (FacebookSDKException $e)
      {
          return $this->logError($e->getCode(), $e->getMessage());
      }
  }

  public function is_authenticated()
  {
      if(empty($this->access_token))
        $this->access_token = $this->authenticate();

      if (isset($this->access_token))
      {
          return $this->access_token;
      }
      return false;
  }

  public function authenticate()
  {
      $this->access_token = $this->get_access_token();
      if ($this->access_token && $this->get_expire_time() > (time() + 30) || $this->access_token && !$this->get_expire_time())
      {
          $this->fb->setDefaultAccessToken($this->access_token);
          return $this->access_token;
      }

      // If No stored access token/it has expired, get a new access token
      if (!$this->access_token)
      {
          try
          {
              $this->access_token = $this->helper->getAccessToken();
          }
          catch (FacebookResponseException $re)
          {
              // When Graph returns an error
              echo 'Graph returned an error: ' . $re->getCode() . ' error: ' . $re->getMessage(); exit;
              // $this->logError($re->getCode(), $re->getMessage());
              // return null;
          }
          catch (FacebookSDKException $se)
          {
              // When Validation fails or other local issues
              echo 'Facebook SDK returned an ' . $se->getCode() . ' error: ' . $se->getMessage(); exit;
              // exit;
              // $this->logError($se->getCode(), $se->getMessage());
              // return null;
          }

          // If we got a session we need to exchange it for a long lived session.
          if (isset($this->access_token))
          {
              $this->access_token = $this->set_long_lived_token($this->access_token);
              $this->set_expire_time($this->access_token->getExpiresAt());
              $this->set_access_token($this->access_token);
              $this->fb->setDefaultAccessToken($this->access_token);
              return $this->access_token;
          }
      }

      // Collect errors if any when using web redirect based login
      if ($this->facebook_login_type === 'web')
      {
          if ($this->helper->getError())
          {
              // Collect error data
              $error = array(
                  'error'             => $this->helper->getError(),
                  'error_code'        => $this->helper->getErrorCode(),
                  'error_reason'      => $this->helper->getErrorReason(),
                  'error_description' => $this->helper->getErrorDescription()
              );
              return $error;
          }
      }

      //Finally
      return $this->access_token;
  }

  public function login_url()
  {
      if ($this->facebook_login_type != 'web'){ return ''; }
      
      return $this->helper->getLoginUrl(
          base_url() . $this->facebook_login_redirect_url, $this->facebook_permissions
      );
  }

  public function logout_url()
  {
      if ($this->facebook_login_type != 'web'){ return ''; }
      
      return $this->helper->getLogoutUrl(
          $this->get_access_token(), site_url() . $this->facebook_logout_redirect_url
      );
  }

  //Used upon successful logout from our site
  //e.g. { $this->facebook->destroy_session(); redirect('login', redirect); }
  public function destroy_session()
  { 
    $this->session->unset_userdata('fb_access_token'); 
  }

  private function get_access_token()
  { 
    return $this->session->userdata('fb_access_token'); 
  }

  private function set_access_token(AccessToken $access_token)
  {
      $this->session->set_userdata('fb_access_token', $access_token->getValue());
  }

  private function set_long_lived_token(AccessToken $access_token = null)
  {   
      if (!$access_token->isLongLived())
      {
          $oauth2_client = $this->fb->getOAuth2Client();
          try
          {
              return $oauth2_client->getLongLivedAccessToken($access_token);
          }
          catch (FacebookSDKException $e)
          {
              $this->logError($e->getCode(), $e->getMessage());
              return null;
          }
      }
      return $access_token;
  }

  private function get_expire_time()
  {
      return $this->session->userdata('fb_expire');
  }
  
  private function set_expire_time(DateTime $time = null)
  {
      if ($time) {
          $this->session->set_userdata('fb_expire', $time->getTimestamp());
      }
  }

  private function logError($code, $message)
  {
      log_message('error', '[FACEBOOK PHP SDK] code: ' . $code.' | message: '.$message);
      return ['error' => $code, 'message' => $message];
  }
  //Enables the use of CI super-global without having to define an extra variable.
  //e.g. (CONTROLLER) $fb = $this->facebook->__get(fb);
  public function __get($var)
  {
      return get_instance()->$var;
  }

}

class Facebook_Exception extends Exception {}

//Sample FB Access Token
/*
object(Facebook\Authentication\AccessToken)[42]
  protected 'value' => string 'R@nDoM57R!N6' (length=170)
  protected 'expiresAt' => 
    object(DateTime)[44]
      public 'date' => string '2017-07-02 19:25:10.000000' (length=26)
      public 'timezone_type' => int 3
      public 'timezone' => string 'Asia/Jakarta' (length=12)
*/

?>
