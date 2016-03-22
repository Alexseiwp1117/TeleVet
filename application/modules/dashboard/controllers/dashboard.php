<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require './vendor/autoload.php';
use Parse\ParseClient;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseFile;
use Parse\ParseUser;
use Parse\ParseGeoPoint;
use Parse\ParseInstallation;
use Parse\ParsePush;
use Parse\ParseCloud;

ParseClient::initialize(PARSE_APP_KEY, PARSE_REST_KEY, PARSE_MASTER_KEY);

class Dashboard extends MX_Controller{

    function __construct() {
            parent::__construct();
            if($this->session->userdata('sess_logged_in')!=true)
            {
                    redirect('login/index');
            }
    }

    function index(){
        
            $vetid= $this->session->userdata('sess_user_id');
            $zone= $this->session->userdata('sess_user_zone');
            $timezone= new DateTimeZone($zone);

            $date = DateTime::createFromFormat('m/Y-d h:i:s T', date("m/Y")."-01 00:00:00 ".$zone,$timezone);
            $fdate= date_timezone_set($date, new DateTimeZone( 'UTC' ));
            $date = DateTime::createFromFormat('m/d/Y h:i:s T', date("m/d/Y")." 00:00:00 ".$zone,$timezone);
            $ndate= date_timezone_set($date, new DateTimeZone( 'UTC' ));

            $req = [];
            $req["vetid"] = $vetid;
            $req["fdate"] = $fdate;
            $req["ndate"] = $ndate;
            $data = ParseCloud::run("getDashboardInfo", $req);

            $zone= $this->session->userdata('sess_user_zone');
            foreach ($data['myConsults'] as &$consult) {
                $datetime= new DateTime($consult['date']);
                $date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));
                $tz=new DateTimeZone($zone);
                $heredate= date_timezone_set($datetime, $tz);
                $consult['date'] = $heredate->format("m/d/Y h:i A");
            }
            foreach ($data['newConsults'] as &$consult) {
                $datetime= new DateTime($consult['date']);
                $date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));
                $tz=new DateTimeZone($zone);
                $heredate= date_timezone_set($datetime, $tz);
                $consult['date'] = $heredate->format("m/d/Y h:i A");
            }
            $page_details['page_title']='Dashboard';
            $this->load->Module('header')->index($page_details);
            $this->load->view('dashboard',$data);
    }

    function assign(){
        
            $consultid= $this->input->post('consult');

            $query= new ParseQuery("Consult");
            $consult= $query->get($consultid);

            $vetid= $this->session->userdata('sess_user_id');
            $consult->set('vet',$vetid);
            $consult->set('state',"OPENED");

            $zone= $this->session->userdata('sess_user_zone');

            $timezone= new DateTimeZone($zone);

            $date = new DateTime("now", $timezone);

            $utcdate= date_timezone_set($date, new DateTimeZone( 'UTC' ));

            $consult->set('openedAt', $utcdate);

            $consult->save();

            $data['alert']="Your consulations is assigned.";
            $data['type']="assign";
            $data['consult'] =$consultid;
            $data['content-available'] = 1;
            $data['vet'] = $vetid;
            $data['pet'] = $consult->get('pet');

            $ownerId = $consult->get('owner');

            $query = ParseUser::query();
            $owner = $query->get($ownerId);

            if($owner->get("pushNotification") != true ) {
                    exit;
            }
            
            ParsePush::send(array(
                    "channels" => [$ownerId],
                    "data" => $data
            ));

            redirect('consult/detail/'.$consultid);
    }

    function change_password(){
        
    }
}