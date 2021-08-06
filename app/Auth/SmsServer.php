<?php

namespace App\Auth;
use PDO;

class SmsServer
{
    function TEXTLOCAL($number, $msg){

        $username = TEXTLOCAL_USERNAME;
        $hash = TEXTLOCAL_HASH;
        $sender = TEXTLOCAL_SENDER; 

        $test = "0";
        $numbers = $number; 
        $message = $msg;

        $message = urlencode($message);
        $data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
        $ch = curl_init('http://api.textlocal.in/send/?');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

    }

    public function getsms(){
        return 1;
    }
}