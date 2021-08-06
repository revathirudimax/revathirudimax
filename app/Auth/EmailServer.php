<?php

namespace App\Auth;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailServer
{

    public function orderconirm($email,$userData){

        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = SMTP::DEBUG_OFF;                    
            $mail->isSMTP();                                            
            $mail->Host       = EMAIL_HOST;                   
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = EMAIL_USERNAME;                   
            $mail->Password   = EMAIL_PASSWORD;                              
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;      
            $mail->Port       = 587;                                  
            $mail->setFrom(EMAIL_USERNAME, COMPANY);  

            $mail->addAddress($email);  
        
            $mail->isHTML(true);                                  
            $mail->Subject = 'About Your Grocery';
            $mail->Body    = $this->getTemplate($userData);
        
            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    

    public function getTemplate($userData){

        $template = '<div style="text-align: center;">

            <img src="https://raw.githubusercontent.com/ajayrandhawa/template/5d9d9a0e67e5f51914fc5abcd1877d2f43bde4dd/dark-logo-1.svg" />
            <h2>Order Successfully Placed </h2>
            <p>Thank you for your Order</p>
            <p><b>Dear '.$userData->$name.'</b></p>
            <div>
                <div>
                    <h4>Your order will be Deliver to this address </h4>
                </div>

                <ul style="list-style-type: none;">
                    <li><p> '.$userData->$address.'</p></li>
                    <li><p><b>Phone Number </b>: <span>'.$userData->$phone.'</span></p></li>
                    <li><p><b>Payment Method </b>: <span'.$userData->$method.'</span></p></li>
                </ul>
                <div>
                    <div>Stay Home Stay Safe</div>
                </div>
                <br>
                <div>
                    <div>Any Help? </div>
                </div>
            </div>
    </div>';

        return $template;
        
            ;
    }

}