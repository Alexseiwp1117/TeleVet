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

ParseClient::initialize(PARSE_APP_KEY, PARSE_REST_KEY, PARSE_MASTER_KEY);

class Chat extends MX_Controller{

        function __construct() {
            
                parent::__construct();
                
        }

        function index($consultId){

                $query= new ParseQuery("Consult");
                $consult= $query->get($consultId);
                $ownerId = $consult->get('owner');
                $vetId = $consult->get('vet');
                $state = $consult->get('state');

                $query = ParseUser::query();
                $owner = $query->get($ownerId);
                $vet = $query->get($vetId);

                $query = new ParseQuery("Message");
                $query->equalTo('consult',$consultId);
                $query->ascending("createdAt");
                $messages= $query->find();

                $ownername= $owner->get('firstName')." ".$owner->get('lastName');
                $file = $owner->get('photo');
                $ownerphoto="";

                if($file) {
                        $ownerphoto= str_replace("http://", "//", $file->getURL());
                } else {
                        $ownerphoto = base_url()."src/dist/img/nophoto.png";
                }



                $vetname= $vet->get('firstName')." ".$vet->get('lastName');

                $file = $vet->get('photo');
                $vetphoto="";
                if($file) {
                        $vetphoto= str_replace("http://", "//", $file->getURL());
                } else {
                        $vetphoto = base_url()."src/dist/img/nophoto.png";
                }

                $data=array();
                $data['ownerId'] = $ownerId;
                $data['owner_name'] = $ownername;
                $data['owner_photo']= $ownerphoto;
                $data['vet_name'] = $vetname;
                $data['vet_photo']= $vetphoto;
                $data['messages'] = $messages;
                $data['consult'] = $consult;
                $data['consultId'] = $consultId;
                $data['state']= $state;

                $this->load->view('chat',$data);
        }

        function videochat($consultId){

                $query= new ParseQuery("Consult");
                $consult= $query->get($consultId);
                $ownerId = $consult->get('owner');
                $vetId = $consult->get('vet');

                $data=array();
                $data['ownerId'] = $ownerId;
                $data['vetId']= $vetId;
                $data['consultId'] = $consultId;

                $this->load->view('videochat',$data);

        }

        function videochatoff($consultId){

                $query= new ParseQuery("Consult");
                $consult= $query->get($consultId);
                $ownerId = $consult->get('owner');
                $vetId = $consult->get('vet');

                $data=array();
                //$data['ownerId'] = $vetId;
                //$data['vetId']= $ownerId;
                $data['ownerId'] = $ownerId;
                $data['vetId']= $vetId;
                $data['consultId'] = $consultId;

                $this->load->view('videochat-offtest',$data);

        }

        function videochaton($consultId){

                $query= new ParseQuery("Consult");
                $consult= $query->get($consultId);
                $ownerId = $consult->get('owner');
                $vetId = $consult->get('vet');

                $data=array();
                $data['ownerId'] = $vetId;
                $data['vetId']= $ownerId;
                $data['consultId'] = $consultId;

                $this->load->view('videochat-offtest',$data);

        }

        function newmessage(){

                $consultId = $this->input->post('consultId');
                $message= $this->input->post('message');
                $push= $this->input->post('push');
                $ownerId = $this->input->post('ownerId');

                $mess = new ParseObject("Message");

                $res="";
                $data= array();
                if($message){ // save message
                        $mess->set("type", "TEXT");
                        $mess->set("detail", $message);
                        $res=$message;

                        $mess->set("consult", $consultId);
                        $mess->set("userType", "VET");
                        $mess->save();

                        echo($res);

                } else {

                        $mess->set("type", "FILE");
                        $file=null;
                        if ( isset( $_FILES['attach'] ) ) {
                                $filename=substr(md5(time()),-5);
                                $filename.=$_FILES['attach']['name'];
                                move_uploaded_file($_FILES['attach']['tmp_name'],FCPATH."src/uploads/".$filename);
                                $url=base_url()."src/uploads/".$filename;
                                $mess->set("attach",$url);
                                $data['attach'] =$url;
                        }

                        $mess->set("consult", $consultId);
                        $mess->set("userType", "VET");
                        $mess->save();

                        $file=$mess->get('thumbnail');
                        if(!empty($file)){
                                $data['thumb'] = str_replace("http://", "//", $file->getURL());
                        }

                        echo json_encode($data);

                }

                if($push=="false"){

                        $query = ParseUser::query();
                        $owner = $query->get($ownerId);

                        if($owner->get("pushNotification") != true ) {
                                exit;
                        }

                        $data['alert']= "You received new message.";
                        $data['type']="chatMessage";
                        $data['content-available'] = 1;
                        $data['consult'] =$consultId;

                        $query= new ParseQuery("Consult");
                        $consult = $query->get($consultId);
                        $data['pet']= $consult->get('pet');

                        ParsePush::send(array(
                                "channels" => [$ownerId],
                                "data" => $data
                        ));

                }
        }
}