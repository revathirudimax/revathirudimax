<?php

namespace App\Controllers\web;

use PDO;
use App\Models\User;

class HomepageController extends BaseController{

    // Show HomePage
    public function index($request, $response){

        $db = getDB();
        $query = "SELECT * FROM category";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $category = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $db = getDB();
        $query = "SELECT * FROM banner";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $banners = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $db = getDB();
        $query = "SELECT * FROM items WHERE homepage = 'YES'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $db = getDB();
        $query = "SELECT * FROM deliveryareas";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $areas = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        return $this->container->view->render($response, 'web/index.twig',[
           "category" => $category,
           "banners" => $banners,
           "items" => $items,
           "areas" => $areas
        ]);
    }

     // Show Contact Page
     public function category($request, $response, $args){

        $category = str_replace('-', ' ', $args['category']);

        $db = getDB();
        $query = "SELECT items.id, items.name, items.category, items.description, items.price, items.mrp, items.quantity, items.quantitytype, items.image, items.homepage, items.offer, items.instock FROM items LEFT JOIN category ON category.id = items.category WHERE category.categry =:category";
      
        $stmt = $db->prepare($query);
        $stmt -> bindParam(':category',$category, PDO::PARAM_STR);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $db = getDB();
        $query = "SELECT * FROM category";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $category = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        

        return $this->container->view->render($response, 'web/b.twig',[
            'items' => $items,
            'category' => $category
        ]);
    }

     public function product($request, $response, $args){

        $productid = $args['id'];
        $title = $args['title'];


        $db = getDB();
        $query = "SELECT * FROM category";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $category = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $db = getDB();
        $query = "SELECT items.id, items.name, items.category, items.description, items.price, items.mrp, items.quantity, items.quantitytype, items.image, items.homepage, items.offer, items.instock, category.categry FROM items LEFT JOIN category ON category.id = items.category WHERE items.id =:id";
        $stmt = $db->prepare($query);
        $stmt-> bindParam(':id', $productid, PDO::PARAM_STR);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;

        $db = getDB();
        $query = "SELECT items.id, items.name, items.category, items.description, items.price, items.mrp, items.quantity, items.quantitytype, items.image, items.homepage, items.offer, items.instock, category.categry FROM items LEFT JOIN category ON category.id = items.category ORDER BY RAND() LIMIT 8";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        return $this->container->view->render($response, 'web/product.twig',[
            'product' => $product,
            'items' => $items,
            'category' => $category
        ]);
    }

    public function newproducts($request, $response, $args){

        $tag = 'NEW';
        $db = getDB();
        $query = "SELECT items.id, items.name, items.category, items.description, items.price, items.mrp, items.quantity, items.quantitytype, items.image, items.homepage, items.offer, items.instock, category.categry FROM items LEFT JOIN category ON category.id = items.category LIMIT 30";
        $stmt = $db->prepare($query);
        $stmt-> bindParam(':tag', $tag, PDO::PARAM_STR);
        $stmt->execute();
        $product = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;


        $db = getDB();
        $query = "SELECT * FROM category";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $category = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        return $this->container->view->render($response, 'web/newproducts.twig',[
            'product' => $product,
            'category' => $category
        ]);
    }

    public function feproducts($request, $response, $args){

        $tag = 'FE';
        $db = getDB();
        $query = "SELECT items.id, items.name, items.category, items.description, items.price, items.mrp, items.quantity, items.quantitytype, items.image, items.homepage, items.offer, items.instock, category.categry FROM items LEFT JOIN category ON category.id = items.category ORDER BY RAND() LIMIT 50";
        $stmt = $db->prepare($query);
        $stmt-> bindParam(':tag', $tag, PDO::PARAM_STR);
        $stmt->execute();
        $product = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;


        $db = getDB();
        $query = "SELECT * FROM category";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $category = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        return $this->container->view->render($response, 'web/feproducts.twig',[
            'product' => $product,
            'category' => $category
        ]);
    }


    public function contactus($request, $response, $args){

        // $db = getDB();
        // $query = "SELECT * FROM category";
        // $stmt = $db->prepare($query);
        // $stmt->execute();
        // $category = $stmt->fetchAll(PDO::FETCH_OBJ);
        // $db = null;

        return $this->container->view->render($response, 'web/contact.twig',[
        ]);
    }

     public function contact($request, $response,$args){

        $sendername = $request->getParam('sendername');
        $emailaddress = $request->getParam('emailaddress');
        $sendersubject = $request->getParam('sendersubject');
        $sendermessage    = $request->getParam('sendermessage');
     

        $db = getDB();
        $query = "INSERT INTO contact(sendername,emailaddress,sendersubject,sendermessage)VALUES(:sendername,:emailaddress,:sendersubject,:sendermessage)";
        $sql= $db->prepare($query);
        $sql-> bindParam(':sendername', $sendername, PDO::PARAM_STR);
        $sql-> bindParam(':emailaddress', $emailaddress, PDO::PARAM_STR);
        $sql-> bindParam(':sendersubject', $sendersubject, PDO::PARAM_STR);
        $sql-> bindParam(':sendermessage', $sendermessage, PDO::PARAM_STR);
        if ($sql-> execute()){

             $this->flash->addMessage('success','Thanks for your feedback');
                 return $this->container->view->render($response, 'web/contact.twig');
        }
  else{
    $this->flash->addMessage('error', 'Something went to wrong');
                 return $this->container->view->render($response, 'web/contact.twig');
            }
    
        
    }



    public function about($request, $response, $args){

        $db = getDB();
        $query = "SELECT * FROM category";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $category = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        return $this->container->view->render($response, 'web/about.twig',[
            'category' => $category
        ]);
    }


    public function offers($request, $response, $args){

        $offers = "YES";

        $db = getDB();
        $query = "SELECT * FROM category";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $category = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $db = getDB();
        $query = "SELECT * FROM items WHERE offer=:offers";
        $stmt = $db->prepare($query);
        $stmt -> bindParam(':offers',$offers, PDO::PARAM_STR);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        return $this->container->view->render($response, 'web/offers.twig',[
            'items' => $items,
            'category' => $category
        ]);

    }


    public function faq($request, $response, $args){

        $db = getDB();
        $query = "SELECT * FROM category";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $category = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        return $this->container->view->render($response, 'web/faq.twig',[
            'category' => $category
        ]);
    }
    
    public function privacy($request, $response, $args){

        $db = getDB();
        $query = "SELECT * FROM category";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $category = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        return $this->container->view->render($response, 'web/privacy.twig',[
            'category' => $category
        ]);
    }
    
    public function terms($request, $response, $args){

        $db = getDB();
        $query = "SELECT * FROM category";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $category = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        return $this->container->view->render($response, 'web/terms.twig',[
            'category' => $category
        ]);
    }


    public function search($request, $response){

        $db = getDB();
        $query = "SELECT * FROM category";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $category = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $query = $request->getParam('query');

        $db = getDB();
        $sql = "SELECT * FROM items Where name like ?";
        $params = array("%$query%");
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $items = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        return $this->container->view->render($response, 'web/search.twig',[
            'items' => $items,
            'category' => $category
        ]);
    }

     public function display($request, $response, $args){

          $db = getDB();
        $query = "SELECT * FROM category";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $category = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;


         return $this->container->view->render($response, 'web/b.twig',[
           
            'category' => $category
        ]);
     }

public function addwishlist($request, $response,$args){

    $user_id = $this->auth->getUserId();
    $db = getDB();
    $query = "SELECT wishlist.id,wishlist.user_id,wishlist.itemid,items.id, items.name, items.category, 
    items.description, items.price, items.mrp, items.quantity, items.quantitytype, items.image, items.homepage, 
    items.offer, items.instock, category.categry, users.user_id, users.fname 
    FROM wishlist  
    INNER JOIN items ON items.id = wishlist.itemid 
    INNER JOIN users ON users.user_id = wishlist.user_id 
    INNER JOIN category ON category.id = items.category WHERE wishlist.user_id='$user_id'";
    $stmt = $db->prepare($query);
    $stmt-> bindParam(':tag', $tag, PDO::PARAM_STR);
    $stmt->execute();
    $product = $stmt->fetchAll(PDO::FETCH_OBJ);
    $db = null;

   

    $db = getDB();
    $query = "SELECT * FROM category";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $category = $stmt->fetchAll(PDO::FETCH_OBJ);
    $db = null;

    return $this->container->view->render($response, 'web/wishlist.twig',[
           
        'product' => $product,
            'category' => $category
    ]);

}


     public function wishlist($request, $response,$args){

        $user_id = $this->auth->getUserId();
        $itemid = $request->getParam('itemid');

                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
                                                      
        $db = getDB();
        $query = "INSERT INTO wishlist(user_id,itemid)VALUES(:user_id,:itemid)";
        $sql= $db->prepare($query);
        $sql-> bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $sql-> bindParam(':itemid', $itemid, PDO::PARAM_STR);
        $sql-> execute();
        return $this->container->view->render($response, 'web/index.twig');

        
     }

     public function removewishlist($request, $response, $args){
        $id = $request->getParam('id');
        $db = getDB();
        $query = "DELETE FROM wishlist WHERE id='$id'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $wishlist = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        return $this->container->view->render($response, 'web/wishlist.twig',[
            'wishlist' => $wishlist
        ]);
    }

     


}