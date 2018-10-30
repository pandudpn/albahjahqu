<?php

class DB_Functions {

    private $db;
    private $con;

    //put your code here:
    //constructor
    function __construct() {
        include_once './db_connect.php';
        // connecting to database
        $this->db = new DB_Connect();
        $this->con = $this->db->connect();
    }

    // destructor
    function __destruct() {

    }

    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $email, $gcm_regid, $deviceId, $deviceType) {
        // insert user into database
        $exists   = mysqli_query($this->con, "SELECT * FROM gcm_users WHERE gcm_regid = '$gcm_regid' and email = '$email'") or die(mysqli_error($this->con));
        $num_rows = mysqli_num_rows($exists);
        if($num_rows == 0){
            $result = mysqli_query($this->con, "INSERT INTO gcm_users(device_id, device_type, name, email, gcm_regid) VALUES( '$deviceId', '$deviceType', '$name', '$email', '$gcm_regid')") or die(mysqli_error($this->con));
            // check for successful store
            if ($result) {
                // get user details
                $id     = mysqli_insert_id($this->con); // last inserted id
                $result = mysqli_query($this->con, "SELECT * FROM gcm_users WHERE id = $id") or die(mysqli_error($this->con));
                // return user details
                if (mysqli_num_rows($result) > 0) {
                    return mysqli_fetch_array($result,MYSQLI_ASSOC);
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * updating new user when token different in same device
     * returns value
     */
    public function updateUser($email,$device_id,$device_type,$gcm_regid){
        $result = mysqli_query($this->con, "UPDATE gcm_users SET gcm_regid= '$gcm_regid' WHERE email='$email' and device_id='$device_id' and device_type='$device_type'") or die(mysqli_error($this->con));

        if($result){
            return $result;
        }else{
            return false;

        }    


    }
    /**
     * check user if it has been stored in database
     * returns user detail
     */

    public function checkUser($email,$device_id,$device_type){
        $result = mysqli_query($this->con, "select * from gcm_users where email = '$email' and device_id = '$device_id' and device_type = '$device_type'") or die(mysqli_error($this->con));
        return mysqli_fetch_array($result,MYSQLI_ASSOC);

    }

    /**
     * Getting all users
     */
    public function getAllUsers() {
        $result = mysqli_query($this->con, "select * FROM gcm_users") or die(mysqli_error($this->con));
        return $result;
    }

}

?>