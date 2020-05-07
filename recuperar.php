<?
	if(!isset($_SESSION))  session_start();
	include($_SERVER["DOCUMENT_ROOT"].'/webcoordinador/func/zglobals.php'); //DEV
	//include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/class.phpmailer.php';
	require_once GLBRutaFUNC.'/class.smtp.php';
	
		//$urlweb = 'http://localhost/webcoordinador/'; //DEV
	$urlweb = 'http://cmseventos.com/2019/argentina/17-congreso-credito/'; //PRD
	
	$percorreo 	= (isset($_POST['percorreo']))? trim($_POST['percorreo']) : '';
	
	//Nombre del Evento
	if(isset($_SESSION['PARAMETROS']['SisNombreEvento'])){
		$tmpl->setVariable('SisNombreEvento', $_SESSION['PARAMETROS']['SisNombreEvento']);	
	}
	 
    #buscar la bd con la base de datos
    #query
    #usuario de PassAcceso
    #cod =1
    #clave new 
    #contraseña de nueva y el usuario lo pasas por el md5 la contraseña 
    #rowcount esta en 0 esta correao errado
    
	$conn= sql_conectar();//Apertura de Conexion
    
	$errcod = 0;
	$errmsg = '';
    
    $query="SELECT PERCODIGO,PERNOMBRE,PERAPELLI,PERCORREO,PERUSUACC
			FROM PER_MAEST WHERE '$percorreo' = PERCORREO";
    
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
        $row = $Table->Rows[$i];
        $percodigo 	= trim($row['PERCODIGO']);
		$pernombre 	= trim($row['PERNOMBRE']);
		$perapelli 	= trim($row['PERAPELLI']);
        $percorreo 	= trim($row['PERCORREO']);
		$perusuacc 	= trim($row['PERUSUACC']);

		//Genero una clave aleatoria nueva
		$longitud = 6;
        $newpass = substr(MD5(rand(5, 100)), 0, $longitud);

        $perpasacc = md5('BenVido'.$newpass.'PassAcceso'.$perusuacc);
		$perpasacc = md5(substr($perpasacc,1,10).'BenVidO'.substr($perpasacc,5,8));
        
        //Actualizo la contraseña nueva
		$query = "UPDATE PER_MAEST SET PERPASACC='$perpasacc' WHERE PERCODIGO=$percodigo ";
		$err = sql_execute($query,$conn);
		
        $mail = new PHPMailer();	
        $mail->IsSMTP();
        $mail->SMTPAuth 	= true; 	
        
        $mail->SMTPSecure = 'tls';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        
        $mail->Username 	= "argentina@cmspeople.com";
			$mail->Password 	= "argentina*cms";	
			//$mail->Username 	= "international@exponenciar.com.ar";
			//$mail->Password 	= "Ingreso2019";
			
			$mail->FromName 	= 'CMS';
			$mail->From			= "argentina@cmspeople.com";
			$mail->AddReplyTo('argentina@cmspeople.com', 'CMS');
        
        $mail->IsHTML(true);    // set email format to HTML	
        
        $mail->AddAddress($percorreo, $pernombre.' '.$perapelli);
       
        $mail->Subject = 'Tu nueva clave de acceso  | The new password';
        $mail->AddEmbeddedImage('app-assets/img/logo-light.png', 'imglogo'); //cid:imglogo
        
        $mail->Body ='<!DOCTYPE html>
                        <html lang="en" class="loading">
                            <head>
                                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                            </head>
    
                        <body>
                        <div style="text-align:center">
                            <img src="cid:imglogo" alt="image.png" style="margin-right:0px" data-image-whitelisted="" class="CToWUd">
                            <br>
                        </div>
                        <div dir="ltr">
                            <div style="text-align:center">
                                <b style="font-family:arial,sans-serif;font-size:16px;">
                                    <font color="#ff9900">Su nueva contraseña es: '.$newpass.'</font>
                                </b>
                                <font color="#212121" style="font-family:arial,sans-serif;font-size:16px;">
                                    <div style="text-align:center">Por favor haga clic en enlace para poder loguearse</div>
                                </font>
                            </div>
                            
                            <br>
                            
                            <div style="text-align:center">
                                <b style="font-family:arial,sans-serif;font-size:16px;">
                                    <font color="#ff9900">Your new password is: '.$newpass.'</font>
                                </b>
                                <font color="#212121" style="font-family:arial,sans-serif;font-size:16px;">
                                    <div style="text-align:center">Please click on link to login</div>
                                </font>
                            </div>
                            
                            <br><br>
                            
                            <div style="text-align:center">
                                <b style="font-family:arial,sans-serif;font-size:16px;">
                                    <font color="#274e13"><a href="'.$urlweb.'">Ingresar / Login</a></font>
                                </b>
                            </div>
                            
                        </div>
                        </body>
                        </html>';
        
        //$mail->Body = 'Click en el link de confirmacion <br><a href="http://localhost/webcoordinador/registerconf?N='.$param.'">Confirmar Registracion</a>';		
        sql_close($conn);
        $mail->Send();
	}

    header('Location: recuperarwait.html');
?>	
