<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notifications extends Front_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('transactions/transaction_model', 'transaction');
    }

    public function pending_trx()
    {
    	$pending = $this->transaction->pending()->pending;
    	echo $pending;
    }
}