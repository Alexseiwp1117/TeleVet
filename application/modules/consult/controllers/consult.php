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


Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId(BRAIN_MEC_ID);
Braintree_Configuration::publicKey(BRAIN_PUB_KEY);
Braintree_Configuration::privateKey(BRAIN_PRI_KEY);

class Consult extends MX_Controller{

    function __construct() {
        parent::__construct();
    }

    function index($consultId){
        
        $query= new ParseQuery("Consult");
        $consult= $query->get($consultId);

        $query = ParseUser::query();
        $owner = $query->get($consult->get('owner'));
        $owner->fetch();

        $data['ownername'] = $owner->get('firstName').' '.$owner->get('lastName');

        $query = new ParseQuery('Pet');
        $pet = $query->get($consult->get('pet'));
        $data['petname'] = $pet->get('name');
        $data['pettype'] = $pet->get('petType');

        $datetime= $consult->getCreatedAt();

        $zone= $this->session->userdata('sess_user_zone');

        $date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));

        $tz=new DateTimeZone($zone);
        $heredate= date_timezone_set($datetime, $tz);
        $showdate = $heredate->format("m/d/Y h:i A");

        $data['date']= $showdate;

        $data['summary'] = $consult->get('summary');
        $data['detail'] = $consult->get('detail');

        $query = new ParseQuery('Attach');
        $query->equalTo('consultId',$consultId);
        $attaches = $query->find();
        $count= count($attaches);

        $data['attaches'] = $attaches;
        $data['attachcount'] = $count;

        $this->load->view('consult_info',$data);
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

        $page_details['page_title']='Record';
        if($status) 
            $page_details['page_title']='Consultation';

        $this->load->Module('header')->index($page_details);
        $this->load->view('consult_detail',$data);
    }

    function book(){
        
        $consultid= $this->input->post('consult');
        $time = $this->input->post('time');

        $query= new ParseQuery("Consult");
        $consult= $query->get($consultid);

        $zone= $this->session->userdata('sess_user_zone');
        $timezone= new DateTimeZone($zone);
        $date = DateTime::createFromFormat('m/d/Y h:i A T', $time." ".$zone,$timezone);
        $utcdate= date_timezone_set($date, new DateTimeZone( 'UTC' ));
        $consult->set('scheduledAt',$utcdate);

        //get ready for payment

        $clinicid = $consult->get('clinic');
        $query = new ParseQuery('Clinic');
        $clinic = $query->get($clinicid);
        $followcost = $clinic->get('followCost');

        $ownerid = $consult->get('owner');
        $query = ParseUser::query();
        $owner = $query->get($ownerid);
        $onwertoken = $owner->get('token');
        //payment fund
        $follow= $consult->get('followTransaction');

        $res['code'] = "SUCC";
        $res['msg'] = "";

        if(!empty($follow)){
            $query = new ParseQuery('Transaction');
            $query->equalTo('transactionId',$follow);
            $transaction = $query->first();
            if($transaction && $transaction->get('state') == "FUNDED" ){

            } else {
                //new transaction

                $result = Braintree_Transaction::sale([
                    'amount' => $followcost,
                    'paymentMethodToken' => $onwertoken
                ]);

                $res['success']=$result->success;
                if($result->success){

                    $transObject = new ParseObject("Transaction");
                    $transObject->set("transactionId",$result->transaction->id);
                    $transObject->set("amount",$followcost);
                    $transObject->set("consult",$consultid);
                    $transObject->set("state","FUNDED");
                    $transObject->set("type","APPOINTMENT");
                    $transObject->save();

                    $transId =$result->transaction->id;
                    $consult->set('followTransaction',$transId);

                } else {
                    foreach ($result->errors->deepAll() as $error) {
                        $res['code'] = $error->code;
                        $res['msg'] = $error->message;
                    }
                }

            }
        } else {
            // To be deleted, this result duplicate payment for consult does not have follow up appointment.
/*
            //new transaction
            $result = Braintree_Transaction::sale([
                'amount' => $followcost,
                'paymentMethodToken' => $onwertoken
            ]);

            $res['success']=$result->success;
            if($result->success){

                $transObject = new ParseObject("Transaction");
                $transObject->set("transactionId",$result->transaction->id);
                $transObject->set("amount",$followcost);
                $transObject->set("consult",$consultid);
                $transObject->set("state","FUNDED");
                $transObject->set("type","APPOINTMENT");
                $transObject->save();

                $transId = $result->transaction->id;
                $consult->set('followTransaction',$transId);

            } else {
                foreach ($result->errors->deepAll() as $error) {
                    $res['code'] = $error->code;
                    $res['msg'] = $error->message;
                }
            }
*/
        }

        echo json_encode($res);

        if($res['code'] != "SUCC"){
            exit;
        }

        $consult->save();

		if($owner->get("pushNotification") == true ) {

            $data['alert'] = "New appointment is booked.";
            $data['type']="book";
            $data['consult'] =$consultid;
            $data['content-available'] = 1;
            $data['vet'] = $this->session->userdata('sess_user_id');
            $data['pet'] = $consult->get('pet');

            $ownerId = $consult->get('owner');

            ParsePush::send(array(
                "channels" => [$ownerId],
                "data" => $data
            ));
		}

		if($owner->get("emailNotification") == true ) {
			$email = $this->session->userdata('sess_user_email');
			
			$petid = $consult->get("pet");
			$query= new ParseQuery("Pet");
			$pet= $query->get($petid);

			$mail = [];
			$mail["from"] = $email;
			$mail["to"] = $owner->get("email");
			$mail["subject"] = "New appointment is booked.";
			$mail["content"] = "Hello, " . $owner->get("firstName") . " " . $owner->get("lastName") . ".\n";
			$mail["content"] .= "New appointment about your pet '" . $pet->get("name") . "' is booked.\n";
			$mail["content"] .= "clinic: " . $clinic->get("name") . ", cost: " . $followcost . "\n";

			$results = ParseCloud::run("sendEmail", $mail);
		}
    }

    function finish(){
        
        $consultid= $this->input->post('consult');
        $status = $this->input->post('state');

        $query= new ParseQuery("Consult");
        $consult= $query->get($consultid);

        $state="CLOSED";
        if($status)
            $state="RESOLVED";

        $consult->set('state',$state);
        $zone= $this->session->userdata('sess_user_zone');
        $timezone= new DateTimeZone($zone);
        $date = new DateTime("now", $timezone);
        $utcdate= date_timezone_set($date, new DateTimeZone( 'UTC' ));
        $consult->set('finishedAt', $utcdate);
        $consult->save();

        $consultTransaction= $consult->get('consultTransaction');

        $res['code'] = "SUCC";
        $res['msg'] = "";

        if($consultTransaction){

            $query = new ParseQuery('Transaction');
            $query->equalTo('transactionId',$consultTransaction);
            $transaction = $query->first();

            if($transaction->get('state') == "FUNDED"){
                if($state == "RESOLVED")
                {
                    Braintree_Transaction::submitForSettlement($consultTransaction);
                    $transaction->set('state','SUBMITED');
                } else {
                    Braintree_Transaction::void($consultTransaction);
                    $transaction->set('state','VOID');
                }
                $transaction->save();
            }
        }

        $followTransaction = $consult->get('followTransaction');

        if($followTransaction){

            $query = new ParseQuery('Transaction');
            $query->equalTo('transactionId',$followTransaction);
            $transaction = $query->first();

            if($transaction->get('state') == "FUNDED"){
                Braintree_Transaction::void($followTransaction);
                $transaction->set('state','VOID');
            }

        }

        $data['alert']="Your consulations is resolved.";
        if($state == "CLOSED")
            $data['alert']="Your consulations is closed.";

        $data['type']="finish";
        $data['state']=$state;
        $data['consult'] =$consultid;
        $data['content-available'] = 1;
        $data['vet'] = $this->session->userdata('sess_user_id');
        $data['pet'] = $consult->get('pet');

        $ownerId = $consult->get('owner');

        $query = ParseUser::query();
        $owner = $query->get($ownerId);

		if($owner->get("pushNotification") == true ) 
		{
            ParsePush::send(array(
                "channels" => [$ownerId],
                "data" => $data
            ));
		}

		if($owner->get("emailNotification") == true ) {
			$email = $this->session->userdata('sess_user_email');
			
			$petid = $consult->get("pet");
			$query= new ParseQuery("Pet");
			$pet= $query->get($petid);

			$suffix = strtolower($state);

			$mail = [];
			$mail["from"] = $email;
			$mail["to"] = $owner->get("email");
			$mail["subject"] = "Your consulations is " . $suffix . ".\n";
			$mail["content"] = "Hello, " . $owner->get("firstName") . " " . $owner->get("lastName") . ".\n";
			$mail["content"] .= "Your consulations about your pet '" . $pet->get("name") . "' is " . $suffix . ".\n";
			$mail["content"] .= $suffix . " at: " . $date->format('Y-m-d H:i') . "\n";

			$results = ParseCloud::run("sendEmail", $mail);
		}		

        exit($state);
    }

    function update(){
        
        $consultid= $this->input->post('consult');
        $treat= $this->input->post('treat');
        $note= $this->input->post('note');

        $query= new ParseQuery("Consult");
        $consult= $query->get($consultid);

        $consult->set('treat',$treat);
        $consult->set('note',$note);
        $consult->save();

    }
    
    function do_exportpdf($petid, $consultId){
        
        /* get information for exporting. FMRGJ-KR*/
        $data_detail = $this->get_information_for_export($petid, $consultId);

        //load the view and saved it into $html variable
        $html=$this->load->view('export_pdf', $data_detail, true);

        //this the the PDF filename that user will get to download
        $pdfFilePath = $data_detail['ownername'] . '_report.pdf';

        //load mPDF library
        $this->load->library('m_pdf');

        $pdf = $this->m_pdf->load();
        //generate the PDF from the given html
        $pdf->WriteHTML($html);

        //download it.
        $pdf->Output($pdfFilePath, "D");
        
    }
    
    function do_printpdf($petid, $consultId){
        
        /* get information for exporting. FMRGJ-KR*/
        $data_detail = $this->get_information_for_export($petid, $consultId);
        $data_detail['print'] = 1;

        //load the view and saved it into $html variable
        $this->load->view('print_pdf', $data_detail);
        
    }
    
    function get_information_for_export($petId, $consultId){
            
        $query= new ParseQuery("Consult");
        
        try{
            // get row objectID of which is same as consultID from Consult tsable. FMRGJ-KR
            $consult= $query->get($consultId); 
        } catch (ParseException $ex) {
            echo $error->getCode();
            echo $error->getMessage();
        }

        /* get pet information. FMRGJ-KR*/
        $query= new ParseQuery("Pet");
        try{    
            // get row objectID of which is same as petId from Pet tsable. FMRGJ-KR
            $pet = $query->get($petId);
        } catch (ParseException $ex) {
            echo $error->getCode();
            echo $error->getMessage();
        }
        
        
        $temp = $pet->get('birth');
        $data['dob'] = ($temp != null) ? $temp->format('m/d/Y'): '';
        $data['name'] = $pet->get('name');
        $data['breed'] = $pet->get('breed');
        $data['type'] = $pet->get('petType');
        $data['weight'] = $pet->get('weight');
        $data['environment'] = $pet->get('environment');
        $data['sex'] = $pet->get('sex');
        $data['status'] = $pet->get('status');
        $temp = $pet->get('photo');
        $data['photo'] = ($temp != NULL) ? str_replace("http://", "//", $temp->getURL()) : null;

        // get vet full-name.  FMRGJ-KR
        $query = ParseUser::query();
        try{
            $query->equalTo('email', $this->session->userdata('sess_user_email'));
            $results = $query->find();
            if($results == null){
                $data['vet'] = null;
            }
            else {
                $result = $results[0];
                $data['vet']= $result->get('firstName') . ' ' . $result->get('lastName');
            }
                
        } catch (ParseException $ex) {
            echo $ex->getCode();
            echo $ex->getMessage();
        }
        
        
        // get thumb information.  FMRGJ-KR
        $query= new ParseQuery("Attach");
        try{
            $query->equalTo('consultId', $consultId);
            $results = $query->find();
            if ($results == null) {
                $data['thumbnails'] = null;
            }else{
                /*$attachrow = $results[0];
                if($attachrow == null){
                    $data['thumbnails'] = null;   
                }
                else {
                    $thumb = $attachrow->get('thumbnail');
                    $data['thumbnails'] = ($thumb != null) ? $thumb->getURL() : null;
                }*/
            
                for ($i = 0; $i < count($results); $i++) {
                    $thumb = $results[$i]->get('thumbnail');
                    if( $thumb != NULL )
                        $objects[$i] = str_replace("http://", "//", $thumb->getURL());
                    else
                        $objects[$i] = NULL;
                }
                $data['thumbnails'] = $objects;

            }
        } catch (ParseException $ex) {
            echo $ex->getCode();
            echo $ex->getMessage();
        }
        
        $data['summary'] = $consult->get('summary');

        /* get created information. FMRGJ-KR*/
        $datetime= $consult->getCreatedAt();
        $zone= $this->session->userdata('sess_user_zone');
        $date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));
        $tz=new DateTimeZone($zone);
        $heredate= date_timezone_set($datetime, $tz);
        $showdate = $heredate->format("m/d/Y h:i A");
        $data['created'] = $showdate;

        // get assigned information. FMRGJ-KR
        $datetime= $consult->get("openedAt");
        $zone= $this->session->userdata('sess_user_zone');
        $date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));
        $tz=new DateTimeZone($zone);
        $heredate= date_timezone_set($datetime, $tz);
        $showdate = $heredate->format("m/d/Y h:i A");
        $data['assigned'] = $showdate;

        // get appointment information. FMRGJ-KR
        $datetime= $consult->get('scheduledAt');
        if(!empty($datetime)){
            $zone= $this->session->userdata('sess_user_zone');
            $date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));
            $tz=new DateTimeZone($zone);
            $heredate= date_timezone_set($datetime, $tz);
            $showdate = $heredate->format("m/d/Y h:i A");
            $data['appointment'] = $showdate;
        } 
        else { 
            $data['appointment'] = 'N/A';         
        }

        // get resolved information. FMRGJ-KR
        if($consult->get('state') == 'RESOLVED') {
            try{
                $datetime= $consult->get("finishedAt");
                $zone= $this->session->userdata('sess_user_zone');
                $date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));

                $tz=new DateTimeZone($zone);
                $heredate= date_timezone_set($datetime, $tz);
                $showdate = $heredate->format("m/d/Y h:i A");

                $data['resolved'] = $showdate;
            } 
            catch(Exception $e) { } 
        } else { $data['resolved'] = "N/A";}

        /* get closed information. FMRGJ-KR*/
        if($consult->get('state') == 'CLOSED')
        {
            try{
                $datetime= $consult->get("finishedAt");
                $zone= $this->session->userdata('sess_user_zone');
                $date = DateTime::createFromFormat('m/d/Y h:i A T', $datetime->format("m/d/Y h:i A")." UTC",new DateTimeZone('UTC'));
                $tz=new DateTimeZone($zone);
                $heredate= date_timezone_set($datetime, $tz);
                $showdate = $heredate->format("m/d/Y h:i A");
                $data['closed'] = $showdate;
            } 
            catch(Exception $e) { } 
        } else { $data['closed'] = "N/A"; }

        /* get detailed information. FMRGJ-KR*/
        $data['detail'] = $consult->get('detail');

        /* get treatment information. FMRGJ-KR*/
        /*$state = $consult->get('state');
        if($state =="OPENED" ){
                $status=true;
        } else {
                $status=false;
        }
        if(!$status) {
                $data['treatment'] = "readonly";
        }
        else{
                $data['treatment'] = $consult->get('treat');
        }*/
        $temp = $consult->get('treat');
        $data['treatment'] = $temp != NULL ? $temp : 'NONE';

        /* get notes information. FMRGJ-KR*/
        $data['notes'] = $consult->get('note');

        /* get owner name. FMRGJ-KR */
        $query = ParseUser::query();
        $ownerid = $consult->get('owner');
        if($ownerid != NULL){
            $owner = $query->get($ownerid);
            if($owner != NULL){
                $owner->fetch();
                $data['ownername'] = $owner->get('firstName').' '.$owner->get('lastName');
            }
            else{
                $data['ownername'] = '';
            }
        }
        else{
            $data['ownername'] = '';
        }
        
        return $data;
    }
    
}