<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 if (isset($_SERVER['HTTP_ORIGIN'])) {
  header("Access-Control-Allow-Origin:*");
  header("Access-Control-Allow-Headers:accept, content-type");
  header("Access-Control-Allow-Methods:GET, POST, OPTIONS");
 }

 // Access-Control headers are received during OPTIONS requests
 if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
   header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
   header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

  exit(0);
 }

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

class Payment extends MX_Controller{

    function __construct() {
        parent::__construct();
    }

	public function create_customer(){

		$fname=$this->input->post("firstName");
		$lname=$this->input->post("lastName");
		$email=$this->input->post("email");
		$phone=$this->input->post("phone");

		$result = Braintree_Customer::create([
			'firstName' => $fname,
			'lastName' => $lname,
			'email' => $email,
			'phone' => $phone
		]);

		$res['success'] = false;
		$res['msg'] = "";
		$res['customerId'] = "";
				
		if($result->success){
			$res['success'] = true;
			$res['msg'] = "";
			$res['customerId'] = $result->customer->id;
		}
		echo json_encode($res);
	}

	public function update_customer(){
		$customerid=$this->input->post("customerId");
		$fname=$this->input->post("firstName");
		$lname=$this->input->post("lastName");
		$email=$this->input->post("email");
		$phone=$this->input->post("phone");

		if(empty($customerid)){
			$res['success'] = false;
			$res['msg'] = "Invalid customerId";
			$res['customerId'] ="";
			echo json_encode($res);
			exit;
		}
		$res['success'] = false;
		$res['msg'] = "";
		$updateResult = Braintree_Customer::update(
			$customerid,
			[
				'firstName' => $fname,
				'lastName' => $lname,
				'email' => $email,
				'phone' => $phone
			]
		);

		if($updateResult->success){
			$res['success'] = true;
			$res['msg'] = "";
		}
		echo json_encode($res);
	}

//transaction
	public function sale(){

		$amount= $this->input->post('amount');
		$token = $this->input->post('token');
		//$token = "73bqkm";

		//$amount="1234";

		$res=array();
		//test value
		
		if(empty($amount)){
			$res['success'] = false;
			$res['msg'] = "Amount required.";
			$res['transactionId'] = "";
			echo json_encode($res);
			exit;
		}

		//sale
		$result = Braintree_Transaction::sale([
			'amount' => $amount,
			'paymentMethodToken' => $token
		]);

		$res['success']=$result->success;
		if($result->success){
			$res['msg']="";
			$res['transactionId']=$result->transaction->id;
		} else {
			foreach ($result->errors->deepAll() as $error) {
				$res['code'] = $error->code;
				$res['msg'] = $error->message;
			}
			$res['transactionId']="";
		}
		echo json_encode($res);
	}

	public function create_paymentmethod(){
		$customerId = $this->input->post('customerId');
		$nonce = $this->input->post('nonce');

		$result = Braintree_PaymentMethod::create([
			'customerId' => $customerId,
			'paymentMethodNonce' => $nonce,
			'options' => [
				'verifyCard' => true
			]
		]);

		$res['success'] = true;
		$res['msg'] = "";

		if($result->success){
			$res['success'] = true;
			$res['msg'] = "";
			$res['token'] = $result->paymentMethod->token;			
		} else{

			$res['success'] = false;
			$res['token'] = "";

			foreach ($result->errors->deepAll() as $error) {
				$res['errorcode'] = $error->code;
				$res['errortext'] = $error->message;
			}
			
		}

		echo json_encode($res);
		exit;
	}

	public function update_paymentmethod(){
		$token = $this->input->post('token');
		$nonce = $this->input->post('nonce');
		$customerId = $this->input->post('customerId');

//		$nonce="85e42362-da8e-4c3c-89e8-7136f4ed7ef9";
//		$nonce = 'fake-valid-nonce';
//		$token="fvcx7b";

/*		$result = Braintree_PaymentMethod::update($token, [
			'paymentMethodNonce' => $nonce,
			'options' => [
				'verifyCard' => true,
				'makeDefault' => true
			]
		]);
*/
		$result = Braintree_Customer::update($customerId, array(
			'creditCard' => array(
			'paymentMethodNonce' => $nonce,
			'options' => array(
				'updateExistingToken' => $token,
				'verifyCard' => true
				)
			)
		));

		$res['success'] = true;
		$res['msg'] = "";

		if($result->success){

			$res['success'] = true;
			$res['msg'] = "";
			$res['token'] = $result->paymentMethod->token;
		} else{

			$res['success'] = false;
			
			foreach($result->errors->deepAll() AS $error) {
			  $res['errortext'] = $error->message;
			}
			
			$res['msg'] = "";
			$res['token'] = "";

		}

		echo json_encode($res);
		exit;
	}

	public function client_token(){
		$customerid = $this->input->post('customerId');
		file_put_contents("/tmp/televet.txt",$customerid."adfadf");
		$res=array();

		$clientToken = Braintree_ClientToken::generate([
			"customerId" => $customerid
		]);

		$res['success'] = false;
		$res['msg'] = "Generate failed.Please try again.";
		$res['client_token'] = "";

		if(!empty($clientToken)){
			$res['success'] = true;
			$res['msg']="";
			$res['client_token'] = $clientToken;
		}
		echo json_encode($res);
	}

	public function cancel_release(){
		$transaction_id= $this->input->post("transaction_id");
		
		if(empty($transaction_id)){
			$res['success'] = false;
			$res['msg'] = "transaction_id required";
			$res['data'] = "";
			echo json_encode($res);
			exit;
		}

		$transaction = Braintree_Transaction::find($transaction_id);
		$escrowStatus=$transaction->escrowStatus;

		$result = Braintree_Transaction::cancelRelease($transaction_id);
		$res['success'] = $result->success;
		//$res['msg'] = "";
		//$res['data'] = "";
		echo json_encode($res);

	}

	public function settlement_void(){
		$transaction_id= $this->input->post("transaction_id");
		$transaction_id = 'adfadfadfsdf';
		if(empty($transaction_id)){
			$res['success'] = false;
			$res['msg'] = "transaction_id required";
			echo json_encode($res);
			exit;
		}

		try {
			$result = Braintree_Transaction::void($transaction_id);
			$res['success'] = $result->success;
			$res['msg'] = "";

		} catch( Exception $e){
			$res['success'] = false;
			$res['msg'] = "Failed to void";
		}

		echo json_encode($res);
	}

	public function settlement_submit(){
		$transaction_id= $this->input->post("transactionId");

		if(empty($transaction_id)){
			$res['success'] = false;
			$res['msg'] = "transaction id required";
			$res['data'] = "";
			echo json_encode($res);
			exit;
		}

		$result = Braintree_Transaction::submitForSettlement($transaction_id);

		$res['success'] = $result->success;
		if($res['success']){
			//$res['msg'] = "";
			//$res['data'] = "";
		} else {
			//var_dump($result->errors);
			//$res['msg']=$result->errors;
		}
		echo json_encode($res);
		
	}

	public function settlement_refund(){
		$transaction_id= $this->input->post("transaction_id");
		$amount= $this->input->post("amount");
		
		if(empty($transaction_id)){
			$res['success'] = false;
			$res['msg'] = "transaction_id required";
			$res['data'] = "";
			echo json_encode($res);
			exit;
		}

		if($amount){
			$result = Braintree_Transaction::refund($transaction_id,$amount);
		} else {
			$result = Braintree_Transaction::refund($transaction_id);
		}

		$res['success'] = $result->success;
		if($res['success']){
			$res['msg'] = "";
			$res['data'] = "";
		} else {
			//print_r($result->errors->errors);
			//$res['code']=$result->errors->errors->code;
			//$res['msg']=$result->errors->errors->message;
		}
		echo json_encode($res);

	}

	public function release_escrow(){
		$transaction_id= $this->input->post("transaction_id");
		
		if(empty($transaction_id)){
			$res['success'] = false;
			$res['msg'] = "transaction_id required";
			$res['data'] = "";
			echo json_encode($res);
			exit;
		}

		$result = Braintree_Transaction::releaseFromEscrow($transaction_id);

		$res['success'] = $result->success;
		if($res['success']){
			//$res['msg'] = "";
			//$res['data'] = "";
		} else {
		}
		echo json_encode($res);

	}

	public function hold_escrow(){
		$transaction_id= $this->input->post("transaction_id");
		
		if(empty($transaction_id)){
			$res['success'] = false;
			$res['msg'] = "transaction_id required";
			$res['data'] = "";
			echo json_encode($res);
			exit;
		}

		$result = Braintree_Transaction::holdInEscrow($transaction_id);

		$res['success'] = $result->success;
		if($res['success']){
			//$res['msg'] = "";
			//$res['data'] = "";
		} else {
		}
		echo json_encode($res);
	}

	public function index(){
		echo("Page Not Found 404");
		
	}

	function help(){

		$message = $this->input->post('message');
		$subject = $this->input->post('subject');
		$ownerid= $this->input->post('owner');

		$query= ParseUser::query();
		$me= $query->get($ownerid);

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
		$this->email->subject($subject);
		$this->email->message($message);
		
		if($this->email->send()){
		}else{
		}

		$this->email->clear();

	}

}