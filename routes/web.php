<?php

use \App\Controllers\web\HomepageController;
use \App\Controllers\web\GuestController;
use \App\Controllers\web\UserController;
use \App\Controllers\web\CartController;
use \App\Controllers\web\AjaxController;
use \App\Middleware\AuthMiddleware;


$app->group('/u',function(){
    $this->get('/logout', UserController::class.':logout')->setName('logout');
    $this->post('/push', CartController::class.':push');
    $this->post('/checkout', CartController::class.':checkout')->setName('checkout');
    $this->post('/placeorder', CartController::class.':placeorder')->setName('placeorder');
    $this->get('/od/{id}', CartController::class.':orderdone')->setName('orderdone');
    $this->get('/myorders', CartController::class.':myorders')->setName('myorders');
    $this->get('/invoice/{id}', CartController::class.':invoice')->setName('invoice');
})->add(new AuthMiddleware($container));

$app->get('/', HomepageController::class.':index')->setName('homepage');
$app->get('/new-products', HomepageController::class.':newproducts')->setName('newproducts');
$app->get('/featured-products', HomepageController::class.':feproducts')->setName('feproducts');
$app->get('/contactus', HomepageController::class.':contactus')->setName('contactus');
$app->post('/contactus', HomepageController::class.':contact');
$app->get('/aboutus', HomepageController::class.':about')->setName('about');
$app->get('/offers', HomepageController::class.':offers')->setName('offers');
$app->get('/faq', HomepageController::class.':faq')->setName('faq');
$app->get('/privacy', HomepageController::class.':privacy')->setName('privacy');
$app->get('/terms', HomepageController::class.':terms')->setName('terms');
$app->get('/category/{category}', HomepageController::class.':category')->setName('homepage');
$app->get('/pd/{id}/{title}', HomepageController::class.':product');
$app->get('/search', HomepageController::class.':search');
$app->get('/signin', GuestController::class.':signin')->setName('signin');
$app->post('/signin', UserController::class.':auth');
$app->get('/signup', GuestController::class.':signup')->setName('signup');
$app->post('/signup', GuestController::class.':dosignup');
$app->get('/verify', GuestController::class.':verify')->setName('verify');
$app->post('/getclient', GuestController::class.':getclient');
$app->post('/getotp', AjaxController::class.':getotp');
$app->get('/sendemail', CartController::class.':sendemail');
$app->get('/b', HomepageController::class.':display');
$app->get('/addwishlist', HomepageController::class.':addwishlist')->setName('addwishlist');
$app->post('/wishlist', HomepageController::class.':wishlist')->setName('wishlist');
$app->post('/removewishlist', HomepageController::class.':removewishlist')->setName('removewishlist');





