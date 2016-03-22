<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require './vendor/autoload.php';
use Parse\ParseClient;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseFile;
use Parse\ParseUser;
use Parse\ParseGeoPoint;

ParseClient::initialize(PARSE_APP_KEY, PARSE_REST_KEY, PARSE_MASTER_KEY);

class Record extends MX_Controller{

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
            $query->containedIn('state',["RESOLVED","CLOSED"]);
            $finished=$query->find();

            $records=array();
            if(count($finished) > 0) {
                    $count=1;
                    foreach($finished as $consult){
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

                            $datetime= $consult->get('finishedAt');

                            $zone= $this->session->userdata('sess_user_zone');

                            $date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));

                            $tz=new DateTimeZone($zone);
                            $heredate= date_timezone_set($datetime, $tz);
                            $cons['date'] = $heredate->format("m/d/Y h:i A");

                            $cons['id'] = $consult->getObjectId();
                            $cons['state'] = $consult->get('state');

                            $records[]=$cons;
                    }
            }
            $data['records']=$records;

            $page_details['page_title']='Records';
            $this->load->Module('header')->index($page_details);
            $this->load->view('record',$data);
    }
    
    
    
}