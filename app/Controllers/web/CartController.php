<?php

namespace App\Controllers\web;

use PDO;

class CartController extends BaseController{

    public function checkout($request, $response){

        $items = json_decode($request->getParam('cart-items-input'));

        $total = 0.00;
        $subtotal = 0.00;
        $saving = 0.00;
        $shipping = SHIPPING;
        $coupon = 0.00;
        $mycart = [];

        $cart = array();

        foreach($items as $item){
            array_push($cart,$item->itemid);
        }

        $array = str_repeat('?,', count($cart) - 1) . '?';

        $db = getDB();
        $query = "SELECT id,name,price,mrp,quantity,quantitytype,image FROM items WHERE id IN($array)";
        $stmt = $db->prepare($query);
        $stmt->execute($cart);
        $Uitems = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;


        foreach($Uitems as $uit){
            foreach($items as $item){
                if($item->itemid == $uit->id){
                    $subtotal += (float)$uit->price * (float)$item->qty;
                    $saving = $saving + (($uit->mrp - $uit->price) * $item->qty);
                    array_push( $mycart ,array("name"=>$uit->name, "price"=>$uit->price, "quantity"=>$uit->quantity, "quantitytype"=>$uit->quantitytype, "image"=>$uit->image, "qty"=>$item->qty));
            }
        }
    }

       $total = $subtotal + $shipping - $coupon;

        return $this->container->view->render($response, 'web/checkout.twig',[
            "items" => $mycart,
            "total" => $total,
            "saving" => $saving,
            "shipping" => $shipping,
            "coupon" => $coupon,
            "subtotal" => $subtotal
        ]);
    }


    public function placeorder($request, $response){

        // VARIABLES 
        $total = 0.00;
        $subtotal = 0.00;
        $saving = 0.00;
        $shipping = SHIPPING;
        $coupon = 0.00;

        // CAPTURE REQUEST
        $paytype = $request->getParam('pm');
        $items = $request->getParam('items');
        $userData = $request->getParam('userData');
        $deliveryData = $request->getParam('deliveryData');

        // DELIVERY TIME
        $date = $deliveryData[0]['date'];
        $time = $deliveryData[1]['time'];

        // DELIVERY ADDRESS DATA
        $fname = $userData[0]['value'];
        $mobile = $userData[1]['value'];
        $city = $userData[2]['value'];
        $area = $userData[3]['value'];
        $address = $userData[4]['value'];
        
        $mycart = [];

        $cart = array();

        foreach($items as $item){
            array_push($cart,$item['itemid']);
        }


        //FETCH LATEST PRICE
        $array = str_repeat('?,', count($cart) - 1) . '?';
        $db = getDB();
        $query = "SELECT id,name,price,mrp,quantity,quantitytype FROM items WHERE id IN($array)";
        $stmt = $db->prepare($query);
        $stmt->execute($cart);
        $Uitems = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;


        // FETCH PRICE AND PUSH IN MYCART 
        foreach($Uitems as $uit){
            foreach($items as $item){
                if($item['itemid'] == $uit->id){
                    $subtotal += (float)$uit->price * (float)$item['qty'];
                    $saving = $saving + (($uit->mrp - $uit->price) * $item['qty']);
                    array_push( $mycart ,array("name"=>$uit->name, "price"=>$uit->price, "quantity"=>$uit->quantity, "quantitytype"=>$uit->quantitytype, "qty"=>$item['qty']));
            }
        }
    }


        $total = $subtotal + $shipping - $coupon;
        $dtype = $date .' , '. $time;

        $user_id = $this->auth->getUserId();
        $lat = "";
        $lng = "";
        $couponname = "";
        $payid = "";


        /*

        echo 'USERID -> '.$user_id . '  ';
        echo 'FNAME -> '.$fname . '  ';
        echo 'MOBILE -> '.$mobile . '  ';
        echo 'CITY -> '.$city . '  ';
        echo 'AREA -> '.$area . '  ';
        echo 'ADDRESS -> '.$address . '  ';
        echo 'PAYMENTTYPE -> '.$paytype . '  ';
        echo 'PAYID -> '.$payid . '  ';
        echo 'TOTAL -> '.$total . '  ';
        echo 'SHIPPING -> '.$shipping . '  ';
        echo 'DTYPE -> '.$dtype . '  ';
        echo 'LAT -> '.$lat . '  ';
        echo 'LNG -> '.$lng . '  ';
        echo 'COUPON -> '.$couponname . '  ';
        echo 'COUPONPRICE -> '.$coupon . '  ';

        */


        $db = getDB();
        $sqlorder = "INSERT INTO orders(user_id, fname, city, complex, address, mobile, paymenttype, payid, total, shipping, coupon, couponprice, dtype, lat, lng)VALUES(:userid, :fname, :city, :area, :address, :mobile, :paymenttype, :payid, :total, :shipping, :coupon, :couponprice, :dtype, :lat, :lng)";
        $stmtorder = $db->prepare($sqlorder);
        $stmtorder->bindParam("userid", $user_id,PDO::PARAM_STR);
        $stmtorder->bindParam("fname", $fname,PDO::PARAM_STR);
        $stmtorder->bindParam("mobile", $mobile,PDO::PARAM_STR);
        $stmtorder->bindParam("city", $city,PDO::PARAM_STR);
        $stmtorder->bindParam("area", $area,PDO::PARAM_STR);
        $stmtorder->bindParam("address", $address,PDO::PARAM_STR);
        $stmtorder->bindParam("paymenttype", $paytype,PDO::PARAM_STR);
        $stmtorder->bindParam("payid", $payid,PDO::PARAM_STR);
        $stmtorder->bindParam("total", $total,PDO::PARAM_STR);
        $stmtorder->bindParam("shipping", $shipping,PDO::PARAM_STR);
        $stmtorder->bindParam("dtype", $dtype,PDO::PARAM_STR);
        $stmtorder->bindParam("lat", $lat,PDO::PARAM_STR);
        $stmtorder->bindParam("lng", $lng,PDO::PARAM_STR);
        $stmtorder->bindParam("coupon", $couponname,PDO::PARAM_STR);
        $stmtorder->bindParam("couponprice", $coupon,PDO::PARAM_STR);
        $stmtorder->execute();

        if($db->lastInsertId()){

            $lastid = $db->lastInsertId();

            foreach ($mycart as $item) {

                $db = getDB();
                $sqlitem = "INSERT INTO orderslist (orderid,itemname,itemquantity,itemquantitytype, Mquantity, itemprice,itemtotal) VALUES (:orderid,:itemname,:itemquantity,:itemquantitytype, :Mquantity, :itemprice,:itemtotal)";   
                $stmtitem = $db->prepare($sqlitem);
                $stmtitem->bindParam("orderid", $lastid, PDO::PARAM_STR);
                $stmtitem->bindParam("itemname", $item['name'], PDO::PARAM_STR);
                $stmtitem->bindParam("itemquantity",$item['quantity'], PDO::PARAM_STR);
                $stmtitem->bindParam("itemquantitytype",$item['quantitytype'], PDO::PARAM_STR);
                $stmtitem->bindParam("Mquantity",$item['qty'], PDO::PARAM_STR);
                $stmtitem->bindParam("itemprice", $item['price'], PDO::PARAM_STR);
                $stmtitem->bindParam("itemtotal", $item['price'], PDO::PARAM_STR);
                $stmtitem->execute();
    
    
            }
            
            $order =  openssl_encrypt($lastid, CIPHER, ENCIPHER, 0, CIPHERIV);

            $status = array('status' => 'success', 'order' => $order);

            echo json_encode($status);


        }
        


      
        
    }


    public function orderdone($request, $response, $args){

        $orderid = openssl_decrypt($args['id'], CIPHER, ENCIPHER, 0, CIPHERIV);

        $db = getDB();
        $query = "SELECT * FROM orders WHERE orderid =:orderid";
        $stmt = $db->prepare($query);
        $stmt->bindParam("orderid", $orderid, PDO::PARAM_STR);
        $stmt->execute();
        $orders = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;

        return $this->container->view->render($response, 'web/orderplaced.twig',[
            'order' => $orders
        ]);

    }


    public function myorders($request, $response){

        $userid = $this->auth->getUserId();

        $db = getDB();
        $query = "SELECT * FROM orders WHERE user_id =:userid ORDER BY orderid DESC";
        $stmt = $db->prepare($query);
        $stmt->bindParam("userid", $userid, PDO::PARAM_STR);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        return $this->container->view->render($response, 'web/myorders.twig',[
            'orders' => $orders
        ]);

    }


    public function invoice($request, $response, $args){

        $orderid = $args['id'];
        $userid =  $this->auth->getUserId();

        $db = getDB();
        $query = "SELECT * FROM orders WHERE user_id =:userid AND orderid =:orderid";
        $stmt = $db->prepare($query);
        $stmt->bindParam("userid", $userid, PDO::PARAM_STR);
        $stmt->bindParam("orderid", $orderid, PDO::PARAM_STR);
        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;


        $db = getDB();
        $query = "SELECT * FROM orderslist WHERE orderid =:orderid";
        $stmt = $db->prepare($query);
        $stmt->bindParam("orderid", $order->orderid, PDO::PARAM_STR);
        $stmt->execute();
        $orderlist = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        return $this->container->view->render($response, 'web/invoice.twig',[
            'order' => $order,
            'orderlist' => $orderlist
        ]);

    }

    

}
