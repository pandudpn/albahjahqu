<?php
/*
|--------------------------------------------------------------------------
| Credentials
|--------------------------------------------------------------------------
|
| Required credentials for Pusher service. You can get your credentials
| from your account at https://app.pusher.com/
|
| pusher_app_id      string  Your application id.
| pusher_app_key     string  Your application key.
| pusher_app_secret  string  Your application secret.
| pusher_debug       bool    Turn debug on/off. Debug is logged to CodeIgniters log.
|
*/
$config['pusher_app_id']      = '443394'; 
$config['pusher_app_key']     = '4319a32bebe5a27c5962'; 
$config['pusher_app_secret']  = 'efa0ee7aa91a03b2b28d'; 
$config['pusher_app_cluster'] = 'ap1';
$config['pusher_debug']       = FALSE;
/*
|--------------------------------------------------------------------------
| Other parameters
|--------------------------------------------------------------------------
|
| Optional parameters that can be configures. Uncomment the parameters
| that you want to use.
|
| pusher_scheme     string  e.g. http or https.
| pusher_host       string  The host e.g. api.pusherapp.com. No trailing forward slash.
| pusher_port       int     The http port.
| pusher_timeout    int     The HTTP timeout.
| pusher_encrypted  bool    Quick option to use scheme of https and port 443.
|
*/
$config['pusher_scheme']    = 'http';
$config['pusher_host']      = '';
$config['pusher_port']      = 80;
$config['pusher_timeout']   = 30;
$config['pusher_encrypted'] = TRUE;