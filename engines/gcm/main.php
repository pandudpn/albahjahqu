<?php
	//header('Access-Control-Allow-Origin: https://example.com', false);
	//ini_set('display_errors', '1');
	if ( isset($_POST["email"]) && isset($_POST["device_id"]) && isset($_POST["device_type"]) && isset($_POST["name"]) && isset($_POST["regId"]) ) 
	{
		$fcmId = $_POST["regId"];
		include_once 'check_member.php';
		if(!empty($arr['data'])){
			if($arr['data'][0]['gcm_regid'] != $fcmId){
				//update reg id in database
				include_once 'update.php';
			}else{
				//else do nothing
				$data = array(array("message"=>"same regID"));
				$arr  = array(
			        "request_param"	=> "",
			        "status" 		=> "success",
			        "error_message" => "null",
			        "data" 			=> $data,
			        "next" 			=> ""
			    );
			    echo json_encode($arr);
			}
		}else{
			//register reg id in database
			include_once 'register.php';
		}
	}else{
		$arr  = array(
	        "request_param"	=> "",
	        "status" 		=> "error",
	        "error_message" => "missing parameter",
	        "data" 			=> $data,
	        "next" 			=> ""
	    );

	    echo json_encode($arr);
	}	
?>