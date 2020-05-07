<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/class.phpmailer.php';
	require_once GLBRutaFUNC.'/class.smtp.php';
			
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
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	logerror($_POST['pertipcod']);
	$msgreg 	= (isset($_POST['msgreg']))? trim($_POST['msgreg']) : 0;
	$perclase 	= (isset($_POST['perclase']))? trim($_POST['perclase']) : 0;
	$pertipo 	= (isset($_POST['pertipo']))? trim($_POST['pertipo']) : 0;

	logerror($pertipcod);

	//--------------------------------------------------------------------------------------------------------------
	$msgreg = VarNullBD($msgreg , 'N');
	$pertipo = VarNullBD($pertipo , 'N');
	$perclase = VarNullBD($perclase , 'N');

<<<<<<< .mine
	logerror($msgreg . ' '. $pertipcod);


||||||| .r781


=======
>>>>>>> .r788
	if ($msgreg!=0) {
		$usuario='';
		$mail='';
		$query="SELECT MSGTITULO,MSGDESCRI FROM MSG_CABE WHERE MSGREG=$msgreg";
		$Table = sql_query($query,$conn,$trans);
		$row = $Table->Rows[0];
		$msgtitulo 	= trim($row['MSGTITULO']);
		$msgdescri	= trim($row['MSGDESCRI']);
<<<<<<< .mine
		
	
		$query = " 	SELECT PERNOMBRE, PERAPELLI, PERCORREO FROM PER_MAEST WHERE ESTCODIGO<>3 AND PERTIPO=$pertipcod "; //PERTIPO=$pertipcod
||||||| .r781
		logerror($msgdescri);
	
		$query = " 	SELECT PERNOMBRE, PERAPELLI, PERCORREO FROM PER_MAEST WHERE ESTCODIGO<>3 AND PERCLASE=$pertipcod "; //PERTIPO=$pertipcod
=======
>>>>>>> .r788
		
		$where = '';
		if($perclase!=0){
			$where .= " AND P.PERCLASE=$perclase ";
		}
		if($pertipo!=0){
			$where .= " AND P.PERTIPO=$pertipo ";
		}
		
		$query = " 	SELECT PERNOMBRE, PERAPELLI, PERCORREO 
					FROM PER_MAEST P
					WHERE ESTCODIGO<>3 $where ";
					
		if($perclase==9999){ //Perfiles sin Reuniones Aceptadas
			$query = "	SELECT PERNOMBRE, PERAPELLI, PERCORREO 
						FROM REU_CABE R
						LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=R.PERCODDST
						WHERE R.REUESTADO=1 AND P.ESTCODIGO<>3  ";
		}
		
		$Table = sql_query($query,$conn,$trans);
		
		//Envio mail avisando la liberacion
		$mail = new PHPMailer();	
		$mail->IsSMTP();
		$mail->SMTPAuth 	= true; 	
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
		
		
		for($i=0; $i<$Table->Rows_Count; $i++){
			$row = $Table->Rows[$i];
			$pernombre	= trim($row['PERNOMBRE']);
			$perapelli = trim($row['PERAPELLI']);
			$percorreo 	= trim($row['PERCORREO']);
		
			$mail->AddBCC($percorreo, $pernombre.' '.$perapelli);
		}
	
		$mail->Subject = $msgtitulo;
		
		$mail->AddEmbeddedImage('../app-assets/img/logo-light.png', 'imglogo'); //cid:imglogo
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
									<font color="#212121">'.$msgtitulo.'</font>
								</b>	
							</div>
							<br>
							
							<div style="text-align:center">
								<b style="font-family:arial,sans-serif;font-size:16px;">
									<font color="#212121">'.$msgdescri.'</font>
								</b>
							</div>
							
							<div dir="ltr">
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
							
						</div>
						</body>
						</html>';
		
		
		$result = $mail->Send();
		
	}
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Su correo fue enviado!';      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Error al enviar!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
