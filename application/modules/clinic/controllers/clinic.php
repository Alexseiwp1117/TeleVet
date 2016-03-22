<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require './vendor/autoload.php';
use Parse\ParseClient;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseFile;
use Parse\ParseUser;
use Parse\ParseGeoPoint;

ParseClient::initialize(PARSE_APP_KEY, PARSE_REST_KEY, PARSE_MASTER_KEY);

class Clinic extends MX_Controller{

	function __construct() {
		parent::__construct();
		if($this->session->userdata('sess_logged_in')!=true){
			redirect('login/index');
		}


    }

    function index(){
    	$vetid= $this->session->userdata('sess_user_id');
		
		$query= ParseUser::query();
		$me= $query->get($vetid);
		if($me->get('head') == false){
			redirect('dashboard');
		}
		$clinic= $me->get('clinic');

		$query= ParseUser::query();
		$query->equalTo('clinic',$clinic);
		$query->equalTo('head',false);
		$vets= $query->find();
		$data['vets']=$vets;
		$data['clinic'] = $clinic;

		$clinicid= $clinic;

		$query= new ParseQuery('Clinic');
		$data['clinic'] = $query->get($clinicid);
		

		$page_details['page_title']='Clinic Management';
        $this->load->Module('header')->index($page_details);
		$this->load->view('clinic',$data);
    }
	function addVet(){

		$fname= $this->input->post('fname');
		$lname= $this->input->post('lname');
		$email= $this->input->post('email');

		$vetid= $this->session->userdata('sess_user_id');
		
		$query= ParseUser::query();
		$me= $query->get($vetid);
		if($me->get('head') == false){
			redirect('dashboard');
		}
		$clinic= $me->get('clinic');

		$user= new ParseUser();

		$password= substr(md5(time()),-6);

		$user->set('firstName',$fname);
		$user->set('lastName',$lname);
		$user->set('email',$email);
		$user->set('username',$email);
		$user->set('clinic',$clinic);
		$user->set('head',false);
		$user->set('zone','CST');
		$user->set('active',true);
		$user->set('userType',"VET");
		$user->set('password',$password);

		try {
			$user->signUp();

			$message = "Your password is ".$password.". Please change your information from setting tab.";

			$this->load->library('email');

			$config['useragent']  = "Televet";
			$config['mailpath']  = "/usr/sbin/sendmail"; // or "/usr/sbin/sendmail"
			$config['protocol']   = "sendmail";
			$config['mailtype'] = 'html';
			$config['charset']  = 'utf-8';
			$config['newline']  = "\r\n";
			$config['wordwrap'] = TRUE;
			$config['validate']=TRUE;
			$this->email->initialize($config);
			$this->email->from($me->get('email'),$me->get('firstName')." ".$me->get('lastName'));
			$this->email->to($email);
			$this->email->subject("New Vet");
			$this->email->message($message);
			
			if($this->email->send()){
				//$deliveryStatus.="Email has been sent to:".$to."<br />";
			}else{
				//$deliveryStatus.="Email sending failed to:".$to."<br />";
			}

			$this->email->clear();

			redirect('clinic');
		} catch (Exception $ex) { 
			
		}
		
	}

	function stateVet(){
		$id=$this->input->post('id');
		$state=$this->input->post('state');
		$query= ParseUser::query();
		$vet= $query->get($id);
		if($state){
			$vet->set('active',true);
		} else {
			$vet->set('active',false);
		}
		$vet->save(1);
	}

	function updateprice(){

		$vetid= $this->session->userdata('sess_user_id');
		
		$query= ParseUser::query();
		$me= $query->get($vetid);
		if($me->get('head') == false){
			return;
		}
		$clinicid= $me->get('clinic');

		$query= new ParseQuery('Clinic');
		$clinic = $query->get($clinicid);

		$clinic->set('consultCost',intval($this->input->post('consultCost')));
		$clinic->set('followCost',intval($this->input->post('followCost')));
		$clinic->save();
	}

}