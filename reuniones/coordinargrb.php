<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/sendfcmmessage.php';
	require_once GLBRutaFUNC.'/sendiosmessage.php';
	require_once GLBRutaFUNC.'/class.phpmailer.php';
	require_once GLBRutaFUNC.'/class.smtp.php';
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$percodigo 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : ''; //Codigo de perfil solicitante (logueado)
	$percodsol 	= (isset($_POST['percodsol']))? trim($_POST['percodsol']) : 0; //Codigo de perfil al que se solicita
	$reuestado	= 1; //Reunion sin Confirmar
	$reureg		= 0;
	//--------------------------------------------------------------------------------------------------------------
	$percodigo 	= VarNullBD($percodigo	, 'N');
	
	//Busco si existe una reunion soliciada y sin confirmar
	$query = "	SELECT R.REUREG
				FROM REU_CABE R 
				WHERE R.PERCODSOL=$percodsol AND R.PERCODDST=$percodigo AND R.REUESTADO=1 ";
	$TableReu = sql_query($query,$conn);

	if($TableReu->Rows_Count>0){
		$reureg = trim($TableReu->Rows[0]['REUREG']);
	}
	
	if($percodsol!=0){

		//Seleccionamos los datos de el solicitado
			$querysol ="SELECT MESCODIGO, PERTIPO,PERCORREO 
						FROM PER_MAEST  
						WHERE PERCODIGO=$percodsol" ;
			$Table = sql_query($querysol,$conn);
			$row= $Table->Rows[0];
			$mesasol 	= trim($row['MESCODIGO']);
			$tiposol 	= trim($row['PERTIPO']);
			$correosol 	= trim($row['PERCORREO']);

		//Seleccionamos los datos del solicitante
			$querydest ="SELECT MESCODIGO, PERTIPO 
						 FROM PER_MAEST  
						 WHERE PERCODIGO=$percodigo" ;
			$Table = sql_query($querydest,$conn);
			$row= $Table->Rows[0];
			$mesadest 	= trim($row['MESCODIGO']);
			$tipodest = trim($row['PERTIPO']);

		//Iniciamos variable para guardar los datos segun los tipos de casos
			$mesacod = '';

		//Inicio de las condiciones if
			if ($tipodest || $tiposol == 5) {
				if ($tipodest == 5) {					//	Invitado Ronda de Negocios

					$mesacod = $mesadest;          	     		
							
				}else{
					$mesacod = $mesasol;				//Invitado Ronda de Negocios
									  								
				}
			}elseif ($tipodest && $tiposol == 1) {

				$mesacod = $mesadest;					   //Expositor queda la mesa que acepta

			}else{
				if ($tipodest == 1) {
					
					$mesacod = $mesadest;					//Expositor
										
				}else{
					$mesacod = $mesasol;					//Expositor

				}
			}
			if($mesacod=='') $mesacod=0;
		

		$cambioHorario =false;
		foreach($_POST['dataCoordinar'] as $ind => $data){
			$fecha 	= $data['fecha'];
			$hora 	= $data['hora'];
			
			$reufecha 	= ConvFechaBD($fecha);
			$reuhora 	= VarNullBD($hora  , 'S');
			
			
			
			//Verifico si hubo un cambio de horario-dia
			$query = "	SELECT REUFECHA,REUHORA
						FROM REU_SOLI 
						WHERE REUREG=$reureg AND REUFECHA=$reufecha AND REUHORA=$reuhora AND REUESTADO=1 ";
			$TableChk = sql_query($query,$conn);
			if($TableChk->Rows_Count>0){
				$cambioHorario=false;
				$query = "	UPDATE REU_SOLI SET REUESTADO=2
							WHERE REUREG=$reureg AND REUFECHA=$reufecha AND REUHORA=$reuhora AND REUESTADO=1 ";
				$err = sql_execute($query,$conn,$trans);
			}else{
				$cambioHorario=true;
				$query = "	INSERT INTO REU_SOLI(REUREG,REUFECHA,REUHORA,REUESTADO)
							VALUES($reureg,$reufecha,$reuhora,2)";
				$err = sql_execute($query,$conn,$trans);
			}
		}
		
		//Elimino los horarios solicitados, para dejar el confirmado
		$query = "DELETE FROM REU_SOLI WHERE REUREG=$reureg AND REUESTADO=1 ";
		$err = sql_execute($query,$conn,$trans);
		//Actualización dde datos
		$query=" UPDATE REU_CABE SET REUFECHA=$reufecha, REUHORA=$reuhora, MESCODIGO=$mesacod, REUESTADO=2, AGEREG=NULL WHERE REUREG=$reureg "; //Se actualiza el mescodigo en base a los condicionales impuestos.
		$err = sql_execute($query,$conn,$trans);
		
		
		$msgCambioHorario ='Reunion confirmada';
	
		if($cambioHorario){
			$msgCambioHorario ='Reunion confirmada con cambio de Horario ';
		}
		
		//Inserto Notificacion de Aceptacion
		$query = " INSERT INTO NOT_CABE (NOTREG, NOTFCHREG, NOTTITULO, NOTFCHLEI, PERCODDST, NOTESTADO, PERCODORI, REUREG, NOTCODIGO)
					VALUES (GEN_ID(G_NOTIFICACION,1), CURRENT_TIMESTAMP, '$msgCambioHorario', NULL, $percodsol, 1, $percodigo, $reureg, 2); ";
		$err = sql_execute($query,$conn,$trans);
		
		
		//Envio una notifiacion mobile
		//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
		//Busco si el destino tiene mobile
		$query = "	SELECT N.ID,N.PROVIDER 
					FROM NOT_REGI N 
					WHERE N.PERCODIGO=$percodsol ";
		$TableMobil = sql_query($query,$conn);
		if($TableMobil->Rows_Count>0){
			$id = trim($TableMobil->Rows[0]['ID']);
			$provider = trim($TableMobil->Rows[0]['PROVIDER']);
			
			//Busco los datos de la empresa que solicita la reunion
			$query = "	SELECT PERNOMBRE,PERAPELLI,PERCOMPAN
						FROM PER_MAEST
						WHERE PERCODIGO=$percodigo";
			$TableOrigen = sql_query($query,$conn);
			$pernombre = trim($TableOrigen->Rows[0]['PERNOMBRE']);
			$perapelli = trim($TableOrigen->Rows[0]['PERAPELLI']);
			$percompan = trim($TableOrigen->Rows[0]['PERCOMPAN']);
			
			$titulo 	= $msgCambioHorario;
			$message 	= "$perapelli $pernombre de $percompan ha confirmado la solicitud de reunion.";
			
			if($provider=='FCM'){
				$target = array();
				array_push($target,$id);
				$data =  array('title'=>$titulo,
							   'badge_number'=>1,
							   'server_message'=>'',
							   'text'=>$message,
							   'id'=>$reureg);
							   
				sendFCMMessage($data,$target);
			}else{
				sendIOSMessage($message, $id);
			}
		}
		//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
		
		//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
		//Envio Mail de Reunion Solicitada al Destino
		if($correosol!=''){
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
			
			$mail->AddAddress($correosol, '');
			$mail->Subject = 'Reunion Confirmada! | Meeting confirmed!';
			$mail->AddEmbeddedImage('../app-assets/img/logo-light.png', 'imglogo'); //cid:imglogo
			
			//$pernombre = $_SESSION[GLBAPPPORT.'PERNOMBRE'];
			//$perapelli = $_SESSION[GLBAPPPORT.'PERAPELLI'];
			$percompan = $_SESSION[GLBAPPPORT.'PERCOMPAN'];
			
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
										<font color="#2f67a0" style="font-family:arial,sans-serif;font-size:18px;">
											<div style="text-align:center">La empresa '.$percompan.' ha confirmado la solicitud de reunion.</div>
										</font>
										<font color="#da5060" style="font-family:arial,sans-serif;font-size:18px;">
											<div style="text-align:center">The company '.$percompan.' has accepted your meeting</div>
										</font>
									</div>
									</body>
						</html>';
			$mail->Send();
		}
		//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
	}
		
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Reunion aceptada!';      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Error al aceptar la reunion!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
