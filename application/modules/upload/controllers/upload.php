<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

class Upload extends MX_Controller{

	function __construct() {
		parent::__construct();
    }

	function index(){

		//$name=substr(md5(time()),-6);

		$url=base_url()."src/uploads/";

		if ( isset( $_FILES['attach'] ) ) {
			//$name = $name.$_FILES['attach']['name'];
			$name = $_FILES['attach']['name'];
			move_uploaded_file($_FILES['attach']['tmp_name'],FCPATH."src/uploads/".$name);
			echo $url.$name;
			exit;
		} else {
			echo "Invalid file";
			exit;
		}

		echo "error";
		exit;
		
	}
}