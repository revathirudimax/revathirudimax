<?php

namespace App\Controllers\web;

use PDO;
use App\Models\User;

class AjaxController extends BaseController{

    public function getotp($request, $response){

        $mobile = $request->getParam('mobile');
        $msg = "You OTP";

        $this->sms->getotp($mobile);
    }

    

}