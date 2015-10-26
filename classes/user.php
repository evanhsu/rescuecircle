<?php
//require_once("../classes/mydb_class.php");

class user {
    var $id;                    // An Integer, referring to the database id for this user (users.id)
    var $firstname;
    var $lastname;
    var $fullname;              // firstname + lastname
    var $username;              // An alias for 'email'
    var $email;
    var $encrypted_password;    // Password is stored as a hash
    var $salt;
    var $crew_id;               // Every user is affilitated with a specific Crew


    function __constructor() {
        //CONSTRUCTOR
        //Generate and store a randomly-generate password salt
        $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randStringLen = 32;

        $randString = "";
        for ($i = 0; $i < $randStringLen; $i++) {
            $randString .= $charset[mt_rand(0, strlen($charset) - 1)];
        }

        $this->salt = $randString;
        
    } // END __constructor()

    function set($var, $value) {
        switch($var) {
/*
        case('id'):
            if(!$this->var_is_int($value)) {
                throw new Exception('Person ID must be an integer');
            }
            else $this->id = $value;
            break;
*/
        case('firstname'):
            if(!preg_match('/^[a-z\d_]{1,30}$/i', $value)) {
                throw new Exception('Firstname must be 1 - 30 letters');
            }
            else {
                $this->firstname = $value;
                $this->fullname = $this->firstname . " " . $this->lastname;
            }
            break;
            
        case('lastname'):
            if(!preg_match('/^[a-z\d_]{1,30}$/i', $value)) {
                throw new Exception('Lastname must be 1 - 30 letters');
            }
            else {
                $this->lastname = $value;
                $this->fullname = $this->firstname . " " . $this->lastname;
            }
            break;
        
        case('email'):
            $this->email = $value;
            break;

        case('password'):
            $this->encrypted_password = sha1($value . $this->salt);
            break;

        case('crew_id'):
            $this->crew_id = $value;
            break;
        } //End switch
    } //End function set()

    function get($var) {
        switch($var) {
            case('firstname'):
                return $this->firstname;
                break;

            case('lastname'):
                return $this->lastname;
                break;

            case('fullname'):
                return $this->fullname;
                break;

            case('username'):
                return $this->email;
                break;

            case('email'):
                return $this->email;
                break;

            case('crew_id'):
                return $this->crew_id;
                break;

        }// End switch
    }//End function get()
}//End class User
?>


