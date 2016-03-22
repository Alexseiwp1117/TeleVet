<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require './vendor/autoload.php';
use Parse\ParseClient;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseUser;

ParseClient::initialize(PARSE_APP_KEY, PARSE_REST_KEY, PARSE_MASTER_KEY);

class Login extends MX_Controller{

    function __construct() {
            parent::__construct();
    }

    function index(){
        
            if($this->session->userdata('sess_logged_in')==true){
                    redirect('dashboard');
            }
            $data['page_title']='Login';

            session_start();
            if(empty($_COOKIE['email'])){
                    //login with userid
                    $this->load->view('login_id',$data);
            } else {
                    $email=  $_COOKIE['email'];
                    $data['email'] = $email;
                    $query= ParseUser::query();
                    $query->equalTo('email',strtolower($email));
                    $user=$query->first();

                    $data['user']=$user; 

                    $this->load->view('login',$data);
            }
    }

    /* function signup(){
     
            $email=$this->input->post('email');
            $namef=$this->input->post('namef');
            $namel=$this->input->post('namel');
            $phone=$this->input->post('phone');
            $password="demo";
            $userType="VET";
            $file=null;
            if ( isset( $_FILES['image'] ) ) {
                    $file = ParseFile::createFromData( file_get_contents( $_FILES['image']['tmp_name'] ), $_FILES['image']['name']  );
                    $file->save();
            }

            $user=new ParseUser();
            $user->set("username", $email);
            $user->set("email", $email);
            $user->set("firstName",$namef);
            $user->set('lastName', $namel);
            $user->set('password', $password);
            $user->set('userType', $userType);
            $user->set('phone', $phone);
            if(!empty($file))
                    $servant->set("photo",$file);

            try {
                    $user->signUp();
                    // Hooray! Let them use the app now.
                    $year = time() + 31536000;
                    setcookie('email', $email, $year);
                    $user = ParseUser::logIn($email, $password);

                    $session_data=array('sess_logged_in'=>true,'sess_user_email'=>$user->get('email'),'sess_user_id'=>$user->getObjectId());
                    $this->session->set_userdata($session_data);

                    redirect('dashboard');

            } catch (Exception $e) {
                    echo("alreday exists");
                    exit;
              // Show the error message somewhere and let the user try again.
              //echo "Error: " . $ex->getCode() . " " . $ex->getMessage();
            }
    }*/

    function do_login(){
        
            $email=strtolower(trim($this->input->post('email')));
            $password=trim($this->input->post('password'));
            try {
                    $user = ParseUser::logIn($email, $password);
                    /*$query = ParseUser::query();
                    $query->equalTo("email", strtolower($email)); 
                    
                    $results = $query->find();
                    
                    if ($results != NULL) {
                            $user = $results[0];*/
                        
                    if($user != NULL && $user->get('active') == true) {
                            $year = time() + 31536000;
                            setcookie('email', $email, $year);

                            $zone= $user->get('zone');
                            
                            if($zone == "PST" ){
                                    date_default_timezone_set(PST);
                            } else if($zone == "MST" ){
                                    date_default_timezone_set(MST);
                            } else if($zone == "CST" ){
                                    date_default_timezone_set(CST);
                            } else if($zone == "EST" ){
                                    date_default_timezone_set(EST);
                            } else{
                                    date_default_timezone_set(CST);
                            }

                            $session_data=array(
                                    'sess_logged_in'=>true,
                                    'sess_user_email'=>$user->get('email'),
                                    'sess_user_id'=>$user->getObjectId(),
                                    'sess_user_zone'=>$zone,
                            );
                            $this->session->set_userdata($session_data);
                            redirect('dashboard');
                    }
                    //}
                    else{
                            redirect('login/index');
                    }
                                    
            } catch (Exception $e) {
                    // The login failed. Check error to see why.
                    redirect('login/index');
                    //echo $e->getMessage();
            }
    }

    function forgetpassword(){
        $this->load->view('forgetpassword');
    }

    function formatpassword(){
        $email = $this->input->post('email');

        try {
          ParseUser::requestPasswordReset($email);
                // Password reset request was sent successfully
        } catch (ParseException $ex) {
          // Password reset failed, check the exception message
        }
        redirect('login/index');
    }

    function logout(){
    	
        //unsetting data from session and redirect to login page
    	$session_data=array('sess_logged_in'=>false);
        $this->session->set_userdata($session_data);

        ParseUser::logOut();
    	redirect('login');
    }

    function resetCookie(){
        $year = time() + 31536000;
        setcookie('email', "", $year);
        redirect('login');
    }
}