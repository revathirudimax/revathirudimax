<?php

namespace App\Controllers\web;

use PDO;
use App\Models\User;

class GuestController extends BaseController{

    public function signin($request, $response){

        return $this->container->view->render($response, 'web/signin.twig');
    }

    public function signup($request, $response){

        return $this->container->view->render($response, 'web/signup.twig');
    }

    public function dosignup($request, $response){

        $fname = $request->getParam('fname');
        $email = $request->getParam('email');
        $mobile = $request->getParam('mobile');
        $password = $request->getParam('password');
        $created_at = time();

        $db = getDB();
        $query = "INSERT INTO users(fname, email, mobile, password, created_at)VALUES(:fname, :email, :mobile, :password, :created_at)";
        $sql= $db->prepare($query);
        $sql-> bindParam(':fname', $fname, PDO::PARAM_STR);
        $sql-> bindParam(':email', $email, PDO::PARAM_STR);
        $sql-> bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $sql-> bindParam(':password', $password, PDO::PARAM_STR);
        $sql-> bindParam(':created_at', $created_at, PDO::PARAM_STR);
        
        if($sql-> execute()){
            $auth = $this->auth->attempt($email,$password);

			if ($auth) {
				return $response->withRedirect($this->router->pathFor('homepage'));
			}else{

           
				$this->flash->addMessage('error', 'Invalid Details ! Please try again');
				return $response->withRedirect($this->router->pathFor('signin'));
			}
        }
        else{
            $this->flash->addMessage('error', 'Something Wrong. Please try again.');
            return $response->withRedirect($this->container->router->pathFor('signup'));
        }

        return $this->container->view->render($response, 'web/signup.twig');
    }

    public function verify($request, $response){

        return $this->container->view->render($response, 'web/verify.twig');
    }


    public function getclient($request, $response){

        $items = $request->getParam('items');
        $cart = array();

        foreach($items as $item){
            array_push($cart,$item['itemid']);
        }
        
        return $response->withJson($cart,200);
    }


}