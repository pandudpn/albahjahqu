<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
use Pusher\Pusher as Pusher;
use Pusher\PusherException;
use Pusher\PusherInstance;

class CI_Pusher {
  private $CI;
  private $pusher_app_id; 
  private $pusher_app_key;
  private $pusher_app_secret;
  private $pusher_app_cluster;
  private $pusher_app_options;

  private $pusher;

  public function __construct() {
        // Load CI Object instead of (object) $this 
        $this->CI =& get_instance();
        // Load required config
        $this->CI->load->config('pusher');
        $pusher_config =& get_config();

        try{
          $this->setPusher($pusher_config); unset ($pusher_config);
        }catch(Exception $e){ 
          throw new Pusher_PusherException('Pusher Library Configuration Error'); 
        }

        
  }

  public function setPusher($pusher_config)
  { 
      $this->pusher_app_options = $this->setPusherOptions($pusher_config);
      
      if (!isset($this->pusher))
      {
        require APPPATH.'third_party/Pusher/Pusher.php';
        //$this->pusher = new Pusher\Pusher();
        $this->pusher = new Pusher(
                      $pusher_config['pusher_app_key'], 
                      $pusher_config['pusher_app_secret'], 
                      $pusher_config['pusher_app_id'], 
                      $this->pusher_app_options
        );
        log_message('debug', 'Pusher library loaded.');
      }

      if (!isset($this->pusher))
      {
        throw new Pusher_PusherException('Error on Setting Up Pusher.');
      }

      return $this->pusher;
  }

  public function getPusher(){ return $this->pusher; }

  private function setPusherOptions($pusher_config)
  {
        $options['scheme']    = ($pusher_config['pusher_scheme']) ?: NULL;
        $options['host']      = ($pusher_config['pusher_host']) ?: NULL;
        $options['port']      = ($pusher_config['pusher_port']) ?: NULL;
        $options['timeout']   = ($pusher_config['pusher_timeout']) ?: NULL;
        $options['encrypted'] = ($pusher_config['pusher_encrypted']) ?: NULL;
        $options['cluster']   = ($pusher_config['pusher_app_cluster']) ?: NULL;
        $options = array_filter($options);
        return $options;
  }

  //Enables the use of CI super-global without having to define an extra variable.
  //e.g. (CONTROLLER) $fb = $this->facebook->__get(fb);
  public function __get($var)
  {
      return get_instance()->$var;
  }

}

?>
