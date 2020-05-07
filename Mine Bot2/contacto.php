<?php


include ('mailer/class.phpmailer.php');
include ('mailer/class.smtp.php');

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];
$to      = 'contacto@onlife.com.ar';


//Envio de mail
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true;

$mail->SMTPSecure = 'tls';
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->Username 	= "contacto@btoolbox.com";
$mail->Password 	= "12345678";

$mail->FromName 	= $name;
$mail->From			= $email;
// $mail->AddReplyTo($mail, 'Yo');
$mail->IsHTML(true);    // set email format to HTML

$mail->AddAddress($to);
$mail->Subject = "Contacto";

$mail->Body ='<!DOCTYPE html>
                <html lang="en" class="loading">
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                    </head>

                <body>
                <div style="text-align:center">

                    <br>
                </div>
                <div dir="ltr">
                    <div>
                        <p style="font-family:arial,sans-serif;font-size:20px;">
                            <font color="black">Contacto realizado por: '.$name.', con el siguiente email: '.$email.'</font>
                        </p>
                        <p style="font-family:arial,sans-serif;font-size:20px;">Escribio el siguiente mensaje: '.$message.'</p>
                        <font color="#212121" style="font-family:arial,sans-serif;font-size:18px;">
                            <div style="text-align:center"></div>
                        </font>
                    </div>

                </div>
                </body>
                </html>';

$mail->Send();

header('Location: index.html')
?>