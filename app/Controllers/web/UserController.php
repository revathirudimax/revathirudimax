<?php

namespace App\Controllers\web;

use PDO;

class UserController extends BaseController{

    public function auth($request, $response, $args){
        
        $auth = $this->auth->attempt(
            $request->getParam('email'),
            $request->getParam('password')
        );

        if ($auth) {
            return $response->withRedirect($this->router->pathFor('homepage'));
        }else{
            $this->flash->addMessage('error', 'Invalid Details ! Please try again');
            return $response->withRedirect($this->router->pathFor('signin'));
        }
    }

 
    public function logout($request, $response)
    {
        $this->auth->logout();

        return $response->withRedirect($this->router->pathFor('homepage'));
    }

}