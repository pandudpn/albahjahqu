<?php
//Response JSON.
$json = array();
 
//Check if member exists.
if (isset($_POST["email"]) && isset($_POST["device_id"]) && isset($_POST["device_type"])) {
    $device_id   = $_POST["device_id"];
    $device_type = $_POST["device_type"];
    $email       = $_POST["email"];
    // Check user details in db
    include_once './db_functions.php';
    // include_once './gcm.php';
 
    $db  = new DB_Functions();
    // $gcm = new GCM();
    $res = $db->checkUser($email,$device_id,$device_type);
    
    if(!$res){
        $res="";
        $data = array();
    }else{

        $data = array($res);
    }

    $arr  = array(
        "request_param" => "",
        "status"        => "success",
        "error_message" => "null",
        "data"          => $data,
        "next"          => ""
    );

} else {
    $arr  = array(
        "request_param" => "",
        "status"        => "error",
        "error_message" => "missing parameter",
        "data"          => "null",
        "next"           => ""
    );
    echo json_encode($arr);
}

?>