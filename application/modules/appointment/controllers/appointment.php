<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require './vendor/autoload.php';
use Parse\ParseClient;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseFile;
use Parse\ParseUser;
use Parse\ParseGeoPoint;

ParseClient::initialize(PARSE_APP_KEY, PARSE_REST_KEY, PARSE_MASTER_KEY);

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId(BRAIN_MEC_ID);
Braintree_Configuration::publicKey(BRAIN_PUB_KEY);
Braintree_Configuration::privateKey(BRAIN_PRI_KEY);

class Appointment extends MX_Controller{

        function __construct() {
                parent::__construct();
                if($this->session->userdata('sess_logged_in')!=true){
                        redirect('login/index');
                }
        }

        function index(){

                $vetid= $this->session->userdata('sess_user_id');		

                $day = date("Y-m-d");
                $ndate['__type']="Date";
                $ndate['iso']= $day."T00:00:00";

                $query= new ParseQuery("Consult");
                $query->equalTo('vet',$vetid);
                $query->equalTo('state',"OPENED");
                //$query->greaterThanOrEqualTo('scheduledAt',$ndate);
                $opens=$query->find();

                $appoints=array();
                if(count($opens) > 0) {
                        $count=1;
                        foreach($opens as $consult){
                                $cons=array();
                                $query = ParseUser::query();
                                $owner = $query->get($consult->get('owner'));
                                $owner->fetch();

                                $query = new ParseQuery('Pet');
                                $pet = $query->get($consult->get('pet'));

                                $cons['ownerName']=$owner->get('firstName')." ".$owner->get('lastName');
                                $cons['petName']=$pet->get('name');
                                $cons['petType']=ucfirst(strtolower($pet->get('petType')));
                                $cons['summary']=$consult->get('summary');

                                $zone= $this->session->userdata('sess_user_zone');

                                $datetime= $consult->get('scheduledAt');
                                if($datetime != NULL){
                                        $date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));
                                        $tz=new DateTimeZone($this->session->userdata('sess_user_zone'));
                                        $heredate= date_timezone_set($datetime, $tz);
                                        $cons['date'] = $heredate->format("m/d/Y h:i A"); 
                                }
                                else{
                                        $now = new DateTime();
                                        $cons['date'] = $now->format("m/d/Y h:i A");
                                }

                                $cons['id'] = $consult->getObjectId();

                                $appoints[]=$cons;
                        }
                }
                $data['appoints']=$appoints;
                
                // get clinic of the user. FMRGJ-KR
                $temp_email = $this->session->userdata('sess_user_email');
                $query = ParseUser::query();
                $query->equalTo('username', $temp_email);
                $query->equalTo('userType','VET');
                $owners = $query->find();
                foreach($owners as $owner){
                        if($owner->get('clinic') != NULL) $temp_clinic = $owner->get('clinic');
                }
                
                // get assigned id of owners from pet table. FMRGJ-KR
                $query = new ParseQuery("Pet");
                $query->equalTo("clinic", $temp_clinic);
                $assigned_owners_result = $query->find();
                $assigned_owners_ids = array();
                foreach($assigned_owners_result as $owner){
                        $assigned_owners_ids[] = $owner->get('owner');
                }
                
                // get all owners from user table. FMRGJ-KR
                $query = ParseUser::query();
                $query->equalTo("userType", "OWNER"); 
                $all_owners = $query->find();
                
                $assigned_owners = array();
                foreach($all_owners as $owner){
                        if(in_array($owner->getObjectId(), $assigned_owners_ids)){
                                $assigned_owners[] = $owner;
                        }
                }
                
                $assigned_owners_names = array();
                foreach($assigned_owners as $owner){
                        $assigned_owners_names[] = $owner->get('firstName') . ' ' . $owner->get('lastName');
                }
                sort($assigned_owners_names);
                
                $data['owners'] = $assigned_owners_names;
                
                $page_details['page_title']='Appointments';
                $this->load->Module('header')->index($page_details);
                $this->load->view('appointment',$data);
        }

        function detail($consultId){

                $query= new ParseQuery("Consult");
                $consult= $query->get($consultId);

                $state = $consult->get('state');
                if($state =="OPENED" ){
                        $status=true;
                } else {
                        $status=false;
                }
                $data['state'] = $state;
                $data['status'] = $status;
                $data['consult'] = $consult;
                $data['consultId'] = $consultId;

                $page_details['page_title']='Appointment';

                $this->load->Module('header')->index($page_details);
                $this->load->view('appointment_detail',$data);
        }
        function getpet(){

                $ownername = $this->input->post('owner');
                $ownernames = explode(" ",$ownername); 
                
                $query = ParseUser::query();
                $query->equalTo('firstName',$ownernames[0]);
                $query->equalTo('lastName',$ownernames[1]);
                $owner = $query->first();

                $ownerid= $owner->getObjectId();
                $query = new ParseQuery("Pet");
                $query->equalTo('owner',$ownerid);
                $pets=$query->find();
                $html="";
                foreach($pets as $pet){
                        $html.="<option value='".$pet->getObjectId()."' >".$pet->get('name')."</option>";
                }
                echo($html);
        }

        function newappointment(){
                
                $ownername = $this->input->post('owner1'); 
                // break string into word array. FMRGJ-KR
                $ownernames = explode(" ",$ownername); 

                $query = ParseUser::query();
                $query->equalTo('firstName',$ownernames[0]);
                $query->equalTo('lastName',$ownernames[1]);
                $owner = $query->first();
                $ownerid= $owner->getObjectId();

                $pet = $this->input->post('pet');
                $scdate = $this->input->post('scheduledDate');
                $summary = $this->input->post('summary');
                $detail = $this->input->post('detail');
                $vetid= $this->session->userdata('sess_user_id');

                $query = ParseUser::query();
                $vet = $query->get($vetid);
                $clinicid = $vet->get('clinic');

                $consult = new ParseObject('Consult');
                $consult->set('owner',$ownerid);
                $consult->set('pet',$pet);
                $consult->set('clinic',$clinicid);
                $consult->set('vet',$vetid);
                $consult->set('detail',$detail);
                $consult->set('summary',$summary);
                $consult->set('state', "OPENED");
                $consult->set("videoChat",true);
                $consult->set("videoChat",true);

                $zone= $this->session->userdata('sess_user_zone');
                $timezone= new DateTimeZone($zone);
                $date = new DateTime("now", $timezone);
                $utcdate= date_timezone_set($date, new DateTimeZone( 'UTC' ));
                $consult->set('openedAt', $utcdate);

                $date = DateTime::createFromFormat('m/d/Y h:i A T', $scdate." ".$zone,$timezone);
                $utcdate= date_timezone_set($date, new DateTimeZone( 'UTC' ));
                $consult->set('scheduledAt',$utcdate);

                $query = new ParseQuery('Clinic');
                $clinic = $query->get($clinicid);
                $followcost = $clinic->get('followCost');
                $ownertoken = $owner->get('token');

                $res['code'] = "SUCC";
                $res['msg'] = "";
                
                if(empty($pet)){
                        $res['code'] = "FAIL";
                        $res['msg'] = "Select Pet.";
                }

                if(empty($ownertoken)){
                        $res['code'] = "FAIL";
                        $res['msg'] = "Owner has not payment method.";
                        echo json_encode($res);
                        exit;
                }
                $result = Braintree_Transaction::sale([
                        'amount' => $followcost,
                        'paymentMethodToken' => $ownertoken
                ]);
                $res['success']=$result->success;
                if($result->success){

                        $transObject = new ParseObject("Transaction");
                        $transObject->set("transactionId",$result->transaction->id);
                        $transObject->set("amount",$followcost);
                        $transObject->set("consult",$consult->getObjectId());
                        $transObject->set("state","FUNDED");
                        $transObject->set("type","APPOINTMENT");
                        $transObject->save();

                        $transId = $result->transaction->id;
                        $consult->set('followTransaction',$transId);

                } else {
                        foreach ($result->errors->deepAll() as $error) {
                                $res['code'] = $error->code;
                                $res['msg'] = $error->message;

                                echo json_encode($res);
                                exit;
                        }
                }
                
                if($result->success){
                        $consult->save();
                        $transObject->set('consult',$consult->getObjectId());
                        $transObject->save();
                }
                else {
                        $res['code'] = "FAIL";
                }
                
                echo json_encode($res);
                
                //exit;
        }
        
        function update_appointment_table(){
                
                $vetid= $this->session->userdata('sess_user_id');		

                $day = date("Y-m-d");
                $ndate['__type']="Date";
                $ndate['iso']= $day."T00:00:00";

                $query= new ParseQuery("Consult");
                $query->equalTo('vet',$vetid);
                $query->equalTo('state',"OPENED");
                //$query->greaterThanOrEqualTo('scheduledAt',$ndate);
                $opens=$query->find();

                $appoints=array();
                if(count($opens) > 0) {
                        $count=1;
                        foreach($opens as $consult){
                                $cons=array();
                                $query = ParseUser::query();
                                $owner = $query->get($consult->get('owner'));
                                $owner->fetch();

                                $query = new ParseQuery('Pet');
                                $pet = $query->get($consult->get('pet'));

                                $cons['ownerName']=$owner->get('firstName')." ".$owner->get('lastName');
                                $cons['petName']=$pet->get('name');
                                $cons['petType']=ucfirst(strtolower($pet->get('petType')));
                                $cons['summary']=$consult->get('summary');

                                $zone= $this->session->userdata('sess_user_zone');

                                $datetime= $consult->get('scheduledAt');
                                if($datetime != NULL){
                                        $date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));
                                        $tz=new DateTimeZone($this->session->userdata('sess_user_zone'));
                                        $heredate= date_timezone_set($datetime, $tz);
                                        $cons['date'] = $heredate->format("m/d/Y h:i A"); 
                                }
                                else{
                                        $now = new DateTime();
                                        $cons['date'] = $now->format("m/d/Y h:i A");
                                }

                                $cons['id'] = $consult->getObjectId();

                                $appoints[]=$cons;
                        }
                }
                $data['appoints']=$appoints;

                $html = $this->load->view('appointment_table',$data, TRUE);
                echo $html;
            
        }

}