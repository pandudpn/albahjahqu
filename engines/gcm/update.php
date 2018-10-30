<?php 
// response json
$json = array();
/**
 * Updating a user device
 * Store reg id in users table
 */
if (isset($_POST["email"]) && isset($_POST["regId"]) && isset($_POST["device_id"]) && isset($_POST["device_type"])) {
    $email       = $_POST["email"];
    $device_id   = $_POST["device_id"];
    $device_type = $_POST["device_type"];
    $gcm_regid   = $_POST["regId"]; // FCM Registration ID
    // Store user details in db
    include_once './db_functions.php';
 
    $db  = new DB_Functions();

    $res = $db->updateUser($email, $device_id, $device_type, $gcm_regid);

    $data = array(array("message" => "update true"));
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
        "data"          => "null",
        "next"          => ""
    );

    echo json_encode($arr);
}

function update($email, $device_id, $device_type, $gcm_regid){
    include_once './db_functions.php';
    $db  = new DB_Functions();
    $res = $db->updateUser($email, $device_id, $device_type, $gcm_regid);

    return $res;
}

?>