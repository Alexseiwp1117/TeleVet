<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require './vendor/autoload.php';
use Parse\ParseClient;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseFile;
use Parse\ParseUser;
use Parse\ParseGeoPoint;

ParseClient::initialize(PARSE_APP_KEY, PARSE_REST_KEY, PARSE_MASTER_KEY);

class Status extends MX_Controller{

	function __construct() {
		parent::__construct();
    }

    function index($consultId){
		$query= new ParseQuery("Consult");
		$consult= $query->get($consultId);

		$data=array();
		$data['createdAt'] = $consult->get('createdAt');
		$data['openedAt'] = $consult->get('openedAt');
		$data['scheduledAt']= $consult->get('scheduledAt');
		$data['finishedAt'] = $consult->get('finishedAt');
		
		$this->load->view('status',$data);
    }
}