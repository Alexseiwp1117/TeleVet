<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require './vendor/autoload.php';
use Parse\ParseClient;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseFile;
use Parse\ParseUser;

ParseClient::initialize(PARSE_APP_KEY, PARSE_REST_KEY, PARSE_MASTER_KEY);

class Header extends MX_Controller{
    
    
	function __construct() {
            
                parent::__construct();
                $this->load->helper('url');
                
        }

        
        function index($page_data){
            
                $email = $this->session->userdata('sess_user_email');
                /* here vetID is value of objectID field in the logged in user. FMRGJ-KR*/
                $vetid = $this->session->userdata("sess_user_id");
                $zone= $this->session->userdata('sess_user_zone');

                $timezone= new DateTimeZone($zone);
                $date = new DateTime("now", $timezone);
                $now= date_timezone_set($date, new DateTimeZone( 'UTC' ));

                $date = new DateTime("tomorrow", $timezone);
                $ndate= date_timezone_set($date, new DateTimeZone( 'UTC' ));

                $query= ParseUser::query();
                /* get entire row of the specific user. FMRGJ-KR*/
                $user=$query->get($vetid);

                /* create query of Consult table. FMRGJ-KR*/
                $query= new ParseQuery("Consult");
                /* same as where in sql. FMRGJ-KR*/
                $query->equalTo('clinic',$user->get('clinic'));
                $query->equalTo('state',"CREATED");
                /* run query.  FMRGJ-KR*/
                $news=$query->find();
                $page_data['news'] = $news;

                //appointments

                $query= new ParseQuery("Consult");
                $query->equalTo('vet',$vetid);
                $query->equalTo('state',"OPENED");
                /*$query->greaterThanOrEqualTo('scheduledAt',$now);
                $query->lessThan('scheduledAt',$ndate);*/
                $page_data['schedules'] = $query->find();

                //messages

                $query = new ParseQuery("Consult");
                $query->equalTo('vet',$vetid);
                $userConsults = $query->find();

                $consults=[];
                foreach($userConsults as $eachone) {
                    $consults[]=$eachone->getObjectId();
                }

                $query = new ParseQuery("Message");
                $query->containedIn('consult',$consults);
                $query->equalTo('read',false);
                $page_data['messages'] = $query->find();
                $page_data['user']=$user;
                $this->load->view('header',$page_data);

        }
        
        function do_caseNotification(){
            
                $vetid = $this->session->userdata("sess_user_id");
                $query= ParseUser::query();
                $user=$query->get($vetid);

                /* create query of Consult table. FMRGJ-KR*/
                $query= new ParseQuery("Consult");
                $query->equalTo('clinic',$user->get('clinic'));
                $query->equalTo('state',"CREATED");
                $news=$query->find();
                $page_data['news'] = $news;
                echo $this->load->view('case_notification',$page_data, TRUE);
        }
        
        function do_appointmentNotification(){
                
                $zone= $this->session->userdata('sess_user_zone');
                $timezone= new DateTimeZone($zone);
                $date = new DateTime("now", $timezone);
                $ndate= date_timezone_set($date, new DateTimeZone( 'UTC' ));
                $now= date_timezone_set($date, new DateTimeZone( 'UTC' ));

                $vetid = $this->session->userdata("sess_user_id");
                $query= new ParseQuery("Consult");
                $query->equalTo('vet',$vetid);
                $query->equalTo('state',"OPENED");
                //$query->greaterThanOrEqualTo('scheduledAt',$now);
                //$query->lessThan('scheduledAt',$ndate);
                $page_data['schedules'] = $query->find();

                $html = $this->load->view('appointment_notification', $page_data, TRUE);
                echo $html;
        }
        
        function do_messageNotification(){
            
                $vetid = $this->session->userdata("sess_user_id");
                $query = new ParseQuery("Consult");
                $query->equalTo('vet',$vetid);
                $userConsults = $query->find();

                $consults=[];
                foreach($userConsults as $eachone) {
                        $consults[]=$eachone->getObjectId();
                }

                $query = new ParseQuery("Message");
                $query->containedIn('consult',$consults);
                $query->equalTo('read',false);
                $page_data['messages'] = $query->find();

                echo $this->load->view('message_notification', $page_data, TRUE);
        }
        
        
}