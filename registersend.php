<?
        if(!isset($_SESSION))  session_start();
        // include($_SERVER["DOCUMENT_ROOT"].'/webcoordinador/func/zglobals.php'); //DEV
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/class.phpmailer.php';
	require_once GLBRutaFUNC.'/class.smtp.php';
	
	//$urlweb = 'http://localhost/webcoordinador/'; //DEV
	$urlweb = 'http://cmseventos.com/2019/argentina/17-congreso-credito/'; //PRD
	
	$pernombre 	= (isset($_POST['pernombre']))? trim($_POST['pernombre']) : '';
	$perapelli 	= (isset($_POST['perapelli']))? trim($_POST['perapelli']) : '';
	$percompan 	= (isset($_POST['percompan']))? trim($_POST['percompan']) : '';
	$percargo 	= (isset($_POST['percargo']))? trim($_POST['percargo']) : '';
	$pertipo 	= (isset($_POST['pertipo']))?  trim($_POST['pertipo']) : '0';
	$perclase 	= (isset($_POST['perclase']))? trim($_POST['perclase']) : '0';
	$perrubcod 	= (isset($_POST['perrubcod']))? trim($_POST['perrubcod']) : '0';
	$percorreo 	= (isset($_POST['percorreo']))? trim($_POST['percorreo']) : '';
	$pertelefo 	= (isset($_POST['pertelefo']))? trim($_POST['pertelefo']) : '';
	$perurlweb 	= (isset($_POST['perurlweb']))? trim($_POST['perurlweb']) : '';
	$perdirecc 	= (isset($_POST['perdirecc']))? trim($_POST['perdirecc']) : '';
	$perciudad 	= (isset($_POST['perciudad']))? trim($_POST['perciudad']) : '';
	$perestado 	= (isset($_POST['perestado']))? trim($_POST['perestado']) : '';
	$percodpos 	= (isset($_POST['percodpos']))? trim($_POST['percodpos']) : '';
	$paicodigo 	= (isset($_POST['paicodigo']))? trim($_POST['paicodigo']) : '0';
	$perparnom1 = (isset($_POST['perparnom1']))? trim($_POST['perparnom1']) : '';
	$perparape1 = (isset($_POST['perparape1']))? trim($_POST['perparape1']) : '';
	$perparcarg1 = (isset($_POST['perparcarg1']))? trim($_POST['perparcarg1']) : '';
	$perparnom2 = (isset($_POST['perparnom2']))? trim($_POST['perparnom2']) : '';
	$perparape2 = (isset($_POST['perparape2']))? trim($_POST['perparape2']) : '';
	$perparcarg2 = (isset($_POST['perparcarg2']))? trim($_POST['perparcarg2']) : '';
	$perparnom3 = (isset($_POST['perparnom3']))? trim($_POST['perparnom3']) : '';
	$perparape3 = (isset($_POST['perparape3']))? trim($_POST['perparape3']) : '';
	$perparcarg3 = (isset($_POST['perparcarg3']))? trim($_POST['perparcarg3']) : '';
	$percoment = (isset($_POST['percoment']))? trim($_POST['percoment']) : '';


	$perusuacc 	= (isset($_POST['perusuacc']))? trim($_POST['perusuacc']) : '';
	$perpasacc 	= (isset($_POST['perpasacc']))? trim($_POST['perpasacc']) : '';
	$encres=1;
	
	$perpasacc = md5('BenVido'.$perpasacc.'PassAcceso'.$perusuacc);
	$perpasacc = 'B#SD'.md5(substr($perpasacc,1,10).'BenVidO'.substr($perpasacc,5,8)).'E##$F';
	
	$param 		= md5('MEETINGPOINTPARAMETROCAMBIOCLAVE').md5('PARAMETROCAMBIOMEETINGPOINTCLAVE');
	
	$conn= sql_conectar();//Apertura de Conexion

	$errcod = 0;
	$errmsg = '';
//Verifiquemos que los datos del nuevo usuario no coincidan con uno ya existente
	$query= "SELECT PERCODIGO FROM PER_MAEST WHERE LOWER(PERUSUACC) = LOWER('$perusuacc')";
	
	$Table = sql_query($query,$conn);
	if ($Table->Rows_Count>0) {
		$errmsg='El usuario ya existe';
		$errcod=2;
	}

//Verificamos que el email no este registrando exceptuando si el usuario es admin o no
	$percorreoString  = VarNullBD($percorreo,  'S');
	$query = "SELECT PERCORREO, PERADMIN FROM PER_MAEST WHERE PERADMIN IS NULL AND PERCORREO = $percorreoString";
	$Table = sql_query($query,$conn);
	if ($Table->Rows_Count>0) {
		$errmsg='Ya existe un usuario con ese email';
		$errcod=2;
	}

	if ($errcod == 0) {
			//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_PERFILES,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn);
		$RowId		= $TblId->Rows[0];			
		$percodigo 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO PER_MAEST(PERCODIGO,PERNOMBRE,PERAPELLI,ESTCODIGO,PERCOMPAN,PERRUBCOD,PERCORREO,PERCIUDAD,PERESTADO,
											PERCODPOS,PERTELEFO,PERURLWEB,PERUSUACC,PERPASACC,PERDIRECC,PERCARGO,
											PAICODIGO,PERTIPO,PERCLASE,PERPARNOM1,PERPARAPE1,PERPARCARG1,
											PERPARNOM2,PERPARAPE2,PERPARCARG2,PERPARNOM3,PERPARAPE3,PERPARCARG3,PERCOMENT,TIMREG,ENCRES)
					VALUES($percodigo,'$pernombre','$perapelli',9,'$percompan','$perrubcod','$percorreo','$perciudad','$perestado','$percodpos',
							'$pertelefo','$perurlweb','$perusuacc','$perpasacc','$perdirecc','$percargo',
							$paicodigo,$pertipo,$perclase,'$perparnom1','$perparape1','$perparcarg1',
							'$perparnom2','$perparape2','$perparcarg2','$perparnom3','$perparape3','$perparcarg3','$percoment',86,$encres) ";
		//logerror($query);					
		$err = sql_execute($query,$conn);
		sql_close($conn);
		
		$param .= "::$percodigo";
		
		$mail = new PHPMailer();	
		$mail->IsSMTP();
		$mail->SMTPAuth 	= true; 	
		
		$mail->SMTPSecure = 'tls';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 587;
		
		$mail->Username 	= "argentina@cmspeople.com";
		$mail->Password 	= "argentina*cms";	
		
		$mail->FromName 	= 'Polo IT';
		$mail->From			= "argentina@cmspeople.com";
		$mail->AddReplyTo('argentina@cmspeople.com', 'Polo IT');
		
		$mail->IsHTML(true);    // set email format to HTML	
		
		$mail->AddAddress($percorreo, $pernombre.' '.$perapelli);
		$mail->Subject = 'Confirma tu cuenta! | Confirm your account!';
		$mail->AddEmbeddedImage('app-assets/img/logo-light.png', 'imglogo'); //cid:imglogo
		
		$mail->Body ='<!DOCTYPE html>
						<html lang="en" class="loading">
							<head>
								<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
							</head>

						<body>
						<div style="text-align:center">
							<img src="cid:imglogo" alt="image.png" style="margin-right:0px" data-image-whitelisted="" class="CToWUd">
							<!--app-assets/img/logo-light.png
							-->
							<br>
						</div>
						<div dir="ltr">
							<div style="text-align:center">
								<b style="font-family:arial,sans-serif;font-size:16px;">
									<font color="2f67a0">¡Felicidades! Está un paso más cerca de reunirse con sus socios comerciales ideales</font>
								</b>
								<font color="#212121" style="font-family:arial,sans-serif;font-size:16px;">
									<div style="text-align:center">Por favor haga clic en enlace para confirmar su registro:</div>
								</font>
							</div>
							
							<br>
							
							<div style="text-align:center">
								<b style="font-family:arial,sans-serif;font-size:16px;">
									<font color="#2f67a0">¡Congratulations! You are one step closer to meeting with your ideal business partners.</font>
								</b>
								<font color="#212121" style="font-family:arial,sans-serif;font-size:16px;">
									<div style="text-align:center">Please click on the following link to confirm your registration:</div>
								</font>
							</div>
							
							<br><br>
							
							<div style="text-align:center">
								<b style="font-family:arial,sans-serif;font-size:16px;">
									<font color="#274e13"><a href="'.$urlweb.'registerconf?N='.$param.'">Confirma tu cuenta / Confirm your account</a></font>
								</b>
							</div>
							
						</div>
						</body>
						</html>';
		
		//$mail->Body = 'Click en el link de confirmacion <br><a href="http://localhost/webcoordinador/registerconf?N='.$param.'">Confirmar Registracion</a>';		
		$mail->Send();
	}
	
	
	
	
	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
