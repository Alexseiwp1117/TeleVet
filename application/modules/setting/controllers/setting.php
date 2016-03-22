<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require './vendor/autoload.php';
use Parse\ParseClient;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseFile;
use Parse\ParseUser;
use Parse\ParseGeoPoint;

ParseClient::initialize(PARSE_APP_KEY, PARSE_REST_KEY, PARSE_MASTER_KEY);

class Setting extends MX_Controller{

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
		$data['me'] = $me;

		$page_details['page_title']='Setting';
        $this->load->Module('header')->index($page_details);
		$this->load->view('setting',$data);
    }

	function edit(){
		$vetid= $this->session->userdata('sess_user_id');
		
		$query= ParseUser::query();
		$me= $query->get($vetid);
		$data['me'] = $me;

		$page_details['page_title']='Setting';
        $this->load->Module('header')->index($page_details);
		$this->load->view('setting_edit',$data);
	}
	function save(){

		$fname= $this->input->post('fname');
		$lname= $this->input->post('lname');
		$email= $this->input->post('email');
		$phone= $this->input->post('phone');
		$zone=  $this->input->post('zone');

		$vetid= $this->session->userdata('sess_user_id');
		
		$query= ParseUser::query();
		$user= $query->get($vetid);

		$file=null;
		if ( !empty( $_FILES['photo']['name'] ) ) {

			$image_info = getimagesize($_FILES["photo"]["tmp_name"]);
			$image_width = $image_info[0];
			$image_height = $image_info[1];

			if($image_width <= 1500 && $image_height <=1500 ){
				$ext = substr($_FILES['photo']['name'],-5);
				$filename = "photo".$ext;
				$file = ParseFile::createFromData( file_get_contents( $_FILES['photo']['tmp_name'] ), $filename  );
				$file->save();
				$user->set("photo",$file);
			}
		}


		$user->set('firstName',$fname);
		$user->set('lastName',$lname);
		$user->set('email',$email);
		$user->set('username',$email);
		$user->set('phone',$phone);
		$user->set('zone',$zone);

		$this->session->set_userdata('sess_user_zone',$zone);

		$user->save(1);

		$this->session->set_userdata('sess_user_email',$email);
		redirect('setting');
	}

	function password(){
		$password = $this->input->post('newpassword');

		$vetid= $this->session->userdata('sess_user_id');
		
		$query= ParseUser::query();
		$me= $query->get($vetid);
		
		$me->set('password',$password);
		$me->save(1);

		redirect('setting');
	}
	function help(){
		$sms= $this->input->post('sms');
		$email= $this->input->post('email');
		$message = $this->input->post('message');

		$vetid= $this->session->userdata('sess_user_id');
		$query= ParseUser::query();
		$me= $query->get($vetid);

		if($sms){
			$receiver="Your phonenumber.";
			$url = "http://www.voodoosms.com/vapi/server/sendSMS?";
			 //Post variable names should be same as mentioned below example and its case sensitive as well
			$message=urlencode($message);
			$url .='dest=44'.$receiver;
			$url .='&orig=TeleVet';
			$url .='&msg='.$message;
			$url .='&uid=pms';
			$url .='&pass=wfcsuj6';
			$url .='&validity=1';
			$url .='&format=php';

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($ch);
			curl_close($ch);
		}

		if($email){
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
			$this->email->to("info@gettelevet.com");
			$this->email->subject("Help Support");
			$this->email->message($message);
			
			if($this->email->send()){
				//$deliveryStatus.="Email has been sent to:".$to."<br />";
			}else{
				//$deliveryStatus.="Email sending failed to:".$to."<br />";
			}

			$this->email->clear();
		}
	}
	function removeVet(){
		$id=$this->input->post('id');
		$query= ParseUser::query();
		$vet= $query->get($id);
		$vet->destroy(true);	
	}

}