<?php

require __DIR__.'/../vendor/autoload.php';
require 'config.php';

session_start();

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
    ]);


$container = $app->getContainer();

$container['auth'] = function ($container) {
    return new \App\Auth\Auth;
};

$container['emailServer'] = function ($container) {
    return new \App\Auth\EmailServer;
};

$container['sms'] = function ($container) {
    return new \App\Auth\SmsServer;
};


$container['flash'] = function ($container) {
    return new \Slim\Flash\Messages;
};
$container['currency'] = function ($container) {
    return CURRENCY;
};
$container['company'] = function ($container) {
    return COMPANY;
};
$container['mail'] = function ($container) {
    return MAIL;
};
$container['phone'] = function ($container) {
    return PHONE;
};
$container['BASEURL'] = function ($container) {
    return BASEURL;
};



$container['notFoundHandler'] = function ($container) {
    return function($request, $response) use ($container){
        $container->view->render($response, 'web/404.twig');
        return $response->withStatus(404);
    };
};

$container['username'] = function ($container) {
    if(isset($_SESSION['scurite-grocery-username'])){
        return $_SESSION['scurite-grocery-username'];
    }else{
        return "GUEST";
    }
};


$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ .'/../resourses/views', [
        'cache' => false,
    ]);

   $view->getEnvironment()->addGlobal('flash', $container->flash);
   $view->getEnvironment()->addGlobal('currency', $container->currency);
   $view->getEnvironment()->addGlobal('username', $container->username);
   $view->getEnvironment()->addGlobal('company', $container->company);
   $view->getEnvironment()->addGlobal('mail', $container->mail);
   $view->getEnvironment()->addGlobal('phone', $container->phone);
   

    $view->addExtension(new Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    return $view;
};

function getDB(){
        $dbhost=DB_SERVER;
        $dbuser=DB_USERNAME;
        $dbpass=DB_PASSWORD;
        $dbname=DB_DATABASE;
        $dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
        $dbConnection->exec("set names utf8");
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbConnection;
}

require __DIR__. '/../routes/web.php';