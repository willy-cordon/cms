<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/class.phpmailer.php';
	require_once GLBRutaFUNC.'/class.smtp.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod 	= 0;
	$errmsg 	= '';
	$err 		= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	
	$mailNameApp 		= 'EXPOAGRO';
	$sendMailUsuario 	= 'international@exponenciar.com.ar';
	$sendMailPass 		= 'Expo2020';
	$linkPlayStore		= 'https://play.google.com/store/apps/details?id=com.nextar.expoagro&hl=en_US';
	$linkAppStore		= 'https://apps.apple.com/mx/app/expoagro-2019/id1351820661';
	
	//--------------------------------------------------------------------------------------------------------------
	
	//$urlweb = 'http://localhost/webcoordinador/'; //DEV
	//$urlweb = 'http://EXPOAGROeventos.com/2019/argentina/17-congreso-credito/'; //PRD
	/*	
	<div style="text-align:center">
		<b style="font-family:arial,sans-serif;font-size:16px;">
			<font color="#274e13">Click <a href="'.$urlweb.'login"> aqui </a> para ingresar a tu perfil / Click <a href="'.$urlweb.'login"> here </a> to start completing your profile</font>
		</b>
	</div>
	*/
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$percodigo 	= (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = VarNullBD($percodigo , 'N');
	
	if($percodigo!=0){
		$query = " 	SELECT PERNOMBRE,PERAPELLI,PERCORREO FROM PER_MAEST WHERE PERCODIGO=$percodigo ";
		
		$Table = sql_query($query,$conn,$trans);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$pernombre = trim($row['PERNOMBRE']);
			$perapelli = trim($row['PERAPELLI']);
			$percorreo = trim($row['PERCORREO']);
		}
		
		//Elimino el registro
		$query = "UPDATE PER_MAEST SET ESTCODIGO=1 WHERE PERCODIGO=$percodigo ";
		$err = sql_execute($query,$conn,$trans);
		
		//Envio mail avisando la liberacion
		$mail = new PHPMailer();	
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->CharSet = 'UTF-8';		
		
		//Gmail		
		//$mail->SMTPSecure = 'tls';
		//$mail->Host = 'smtp.gmail.com';
		//$mail->Port = 587;
		
		//Outlook
		$mail->SMTPSecure = 'tls';
		$mail->Host = "smtp.live.com";
		$mail->Port = 587;
		
		$mail->Username 	= $sendMailUsuario;
		$mail->Password 	= $sendMailPass;
		
		$mail->FromName 	= $mailNameApp;
		$mail->From			= $sendMailUsuario;
		$mail->AddReplyTo($sendMailUsuario, $mailNameApp);
		$mail->IsHTML(true);    // set email format to HTML	
		
		$mail->AddAddress($percorreo, $pernombre.' '.$perapelli);
		$mail->Subject = 'Cuenta aprobada! | Approved account!';
		
		$mail->AddEmbeddedImage('../app-assets/img/logo-light.png', 'imglogo');
		$mail->AddEmbeddedImage('../app-assets/img/icoplaystore.png', 'icoplaystore');
		$mail->AddEmbeddedImage('../app-assets/img/icoappstore.png', 'icoappstore');
		
		$mail->Body ='<!DOCTYPE html>
						<html lang="en" class="loading">
							<head>
								<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
							</head>

						<body>
						<div style="text-align:center">
							<img src="cid:imglogo" alt="image.png" style="margin-right:0px;width:100px;height:auto;" data-image-whitelisted="" class="CToWUd">
							<br>
						</div>
						<div dir="ltr">
							<div style="text-align:center">
								<b style="font-family:arial,sans-serif;font-size:24px;">
									<font color="#b1d68b">Felicitaciones!</font>
								</b>
								<font color="#212121" style="font-family:arial,sans-serif;font-size:18px;">
									<div style="text-align:center">Su perfil fue aprobado para participar en la plataforma de negocios de <font color="#b1d68b">EXPOAGRO</font>.</div>
								</font>
							</div>
							
							<br>
							<br>
							
							<div style="text-align:center">
								<b style="font-family:arial,sans-serif;font-size:16px;">
									<font color="#dd9e71">Su NOMBRE DE USUARIO es el correo electrónico a cual recibió esta notificación.</font>
								</b>
							</div>
							
							<br>
							
							
							<div style="text-align:center">
								<font color="#212121" style="font-family:arial,sans-serif;font-size:15px;">
									<div style="text-align:center">Clasifique su perfil de búsqueda de productos de forma exhaustiva. Esto ayudará a que la plataforma le sugiera contrapartes, mejorando su experiencia de usuario.</div>
								</font>
							</div>
							
							<br>
														
							<br><br>
														
							<br><br>
							
							<div style="text-align:center">
								<b style="font-family:arial,sans-serif;font-size:24px;">
									<font color="#b1d68b">Congratulations!</font>
								</b>
								<font color="#212121" style="font-family:arial,sans-serif;font-size:18px;">
									<div style="text-align:center">Your profile has been approved and you&rsquo;re ready to start using the <font color="#b1d68b">EXPOAGRO&rsquo;s</font> business platform.</div>
								</font>
							</div>
							
							<br>
							<br>
							
							<div style="text-align:center">
								<b style="font-family:arial,sans-serif;font-size:16px;">
									<font color="#dd9e71">Your USER NAME is this email</font>
								</b>
							</div>
							
							<br>
							
							<div style="text-align:center">
								<font color="#212121" style="font-family:arial,sans-serif;font-size:15px;">
									<div style="text-align:center">Classify the products and services of your interest. This will help you improve your experience finding exactly what you are looking for. Try to be thorough during this process, this will make it easier for the platform to match you with suitable counterparts.</a></div>
								</font>
							</div>
							
							<br>
							
							
							<div style="text-align:center">
								<a href="'.$linkPlayStore.'" target="_blank">
									<img src="cid:icoplaystore" alt="icoplaystore.png" style="cursor:hand; cursor:pointer;margin-right:0px;width:100px;height:auto;" data-image-whitelisted="" class="CToWUd">
								<a>
								&nbsp;&nbsp;
								<a href="'.$linkAppStore.'" target="_blank">
									<img src="cid:icoappstore" alt="icoappstore.png" style="cursor:hand; cursor:pointer;margin-right:0px;width:100px;height:auto;" data-image-whitelisted="" class="CToWUd">
								</a>
							</div>
							
						</div>
						</body>
						</html>';
		
		/*

		<br><br>
		
		
		<br>
		
		<div style="text-align:center">
			<b style="font-family:arial,sans-serif;font-size:16px;">
				<font color="#212121">----------------------------------------------------</font>
			</b>
		</div>
		
		<br>
		
		<div style="text-align:center">
			<b style="font-family:arial,sans-serif;font-size:16px;">
				<font color="#212121">TENES PREGUNTAS? NECESITAS AYUDA? / ¿DO YOU HAVE ANY QUESTIONS? ¿DO NEED ASSISTANCE?</font>
			</b>
		</div>
		
		<br>
		
		<div style="text-align:center">
			<font color="#212121" style="font-family:arial,sans-serif;font-size:15px;">
				<div style="text-align:center">Contactanos a / Contact us at <a href="mailto:argentina@EXPOAGROpeople.com">argentina@EXPOAGROpeople.com</a></div>
			</font>
		</div>
		
		
		*/
		
		
		
		//$mail->Body = 'Click en el link para ingresar a Meeting Point <br><a href="http://localhost/webcoordinador/login">INGRESAR</a>';		
		$mail->Send();
	}
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Perfil liberado!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? TrMessage('Error al liberar el perfil!') : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
