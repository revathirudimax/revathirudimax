<?php

namespace App\Auth;
use PDO;

class Auth
{
    public function check()
    {
        if(strlen($_SESSION['scurite-grocery-useremail'])==0){
            return false;
        }
        else{
            return true;
        }
    }

    public function attempt($email, $password)
    {

        $passwordHash = $password;

        $db = getDB();
        $query = "SELECT user_id,fname,email FROM users WHERE email = (:email) AND password = (:password)";
        $stmt = $db->prepare($query);
        $stmt-> bindParam(':email', $email, PDO::PARAM_STR);
        $stmt-> bindParam(':password', $passwordHash, PDO::PARAM_STR);
        $stmt->execute();
        $Data = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;

        if($stmt->rowCount() > 0){
            $_SESSION['scurite-grocery-useremail'] = openssl_encrypt($Data->email, CIPHER, ENCIPHER, 0, CIPHERIV);
            $_SESSION['scurite-grocery-username'] = $Data->fname;
            $_SESSION['scurite-grocery-userid'] = $Data->user_id;
            return true;
        }
        else{
            return false;
        }

        return false;
    }


    public function logout()
    {
        unset($_SESSION['scurite-grocery-useremail']);
        unset($_SESSION['scurite-grocery-username']);
        unset($_SESSION['scurite-grocery-userid']);
    }

    public function client(){
        if(strlen(isset($_SESSION['scurite-grocery-useremail']))==0){
            return false;
        }
        else{
            return array("client"=>$_SESSION['scurite-grocery-useremail'], "domain"=>DOMAIN);
        }
    }


    public function getUserId(){
        return $_SESSION['scurite-grocery-userid'];
    }

}