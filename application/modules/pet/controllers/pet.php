<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require './vendor/autoload.php';
use Parse\ParseClient;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseFile;
use Parse\ParseUser;
use Parse\ParseGeoPoint;

ParseClient::initialize(PARSE_APP_KEY, PARSE_REST_KEY, PARSE_MASTER_KEY);

class Pet extends MX_Controller{

        function __construct() {
                parent::__construct();
        }

        function index($petId){

                $query= new ParseQuery("Pet");
                $pet = $query->get($petId);

                $year = $pet->get('birth_year');
                $month = $pet->get('birth_month');
                $day = $pet->get('birth_date');
                if($year == NULL || $month == NULL || $day == NULL)
                        $data['dob'] = 'NONE';
                else{
                        $date = $month . '/' . $day . '/' . $year;
                        $data['dob'] = $date;
                }
                        

                $data['name'] = $pet->get('name');
                $data['breed'] = $pet->get('breed');
                $data['type'] = ucfirst(strtolower($pet->get('petType')));
                $data['weight'] = $pet->get('weight');
                $data['environment'] = $pet->get('environment');
                $data['sex'] = $pet->get('sex');
                $data['status'] = $pet->get('status');
                $attach=  $pet->get('photo');
                $data['photo'] = ($attach != NULL) ? str_replace("http://", "//", $attach->getURL()) : null;
                $this->load->view('pet_info',$data);
        }
}