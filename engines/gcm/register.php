<?php 
// response json
$json = array();
 
/**
 * Registering a user device
 * Store reg id in users table
 */

if (isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["regId"]) && isset($_POST["device_id"]) && isset($_POST["device_type"])) {
    $name        = $_POST["name"];
    $email       = $_POST["email"];
    $gcm_regid   = $_POST["regId"]; // FCM Registration ID
    $deviceId    = $_POST["device_id"];
    $deviceType  = "android";
    if($_POST["device_type"])
        $deviceType  = $_POST["device_type"];

    // Store user details in db
    include_once './db_functions.php';
 
    $db  = new DB_Functions();

    $res = $db->storeUser($name, $email, $gcm_regid, $deviceId, $deviceType);

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
    echo json_encode($arr);

} else {
    $arr  = array(
        "request_param" => "",
        "status"        => "error",
        "error_message" => "missing parameter",
        "data"          => $data,
        "next"          => ""
    );
    echo json_encode($arr);
}

?>