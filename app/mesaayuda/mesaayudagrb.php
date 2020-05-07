<?
	//--------------------------------------------------------------------------------------------------------------
	include ('../../func/zglobals.php');
	include ('../../func/sigma.php');	
	include ('../../func/zdatabase.php');
    include ('../../func/zfvarias.php');
	include ('../../func/class.phpmailer.php');
	include ('../../func/class.smtp.php');
	
	$errmsg 	= '';
	//Obtenemos los valores del perfil logueado
	$percodigo = (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;
	
	if($percodigo!=0){
		//Obtenemos la descripción
		$descri= $_POST['descri'];
		//Variable de los formatos seleccionados	
		$formatos='';
		//Si no se envia nada no entra al foreach
		if (isset($_POST['opcion'])) {
		//Recibimos por post los datos ingresados, y los recorremos 
			$numero=1;	
			foreach ($_POST['opcion'] as $valor) {
				$formatos=$formatos.'<br> '.$numero++.'.- '.$valor;
			}
		}
			
		//Apertura de Conexion
		$conn= sql_conectar();
		
		$query="SELECT P.MESCODIGO,P.PERNOMBRE,P.PERAPELLI,P.PERCOMPAN,M.MESNUMERO 
				FROM PER_MAEST P
				LEFT OUTER JOIN MES_MAEST M ON M.MESCODIGO=P.MESCODIGO
				WHERE P.PERCODIGO=$percodigo";
			 
		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		$pernombre 	= trim($row['PERNOMBRE']);
		$perapelli 	= trim($row['PERAPELLI']);
		$mescodigo 	= trim($row['MESCODIGO']);
		$percompan 	= trim($row['PERCOMPAN']);
		$mesnumero 	= trim($row['MESNUMERO']);
	
		sql_close($conn);	//Cierre de conexión

	//Guardamos en una variable los correos a los que va dirigido los mails
		//$correo="vnethge@exponenciar.com.ar";
		//$correo1="mesadeayuda4@exponenciar.com.ar";
		$correo="daniel.lopez@benvidosistemas.com";
		$correo1="lopezdale@gmail.com";

		//Envio de mail
		$mail = new PHPMailer();	
		$mail->IsSMTP();
		$mail->SMTPAuth = true; 	
		
		$mail->SMTPSecure = 'tls';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 587;
		
		//$mail->Username 	= "expoagro.negocios@gmail.com";
		//$mail->Password 	= "ExpoAgro2019*";	
		
		$mail->Username 	= "international@exponenciar.com.ar";
		$mail->Password 	= "Ingreso2019";	
		
		$mail->FromName 	= 'EXPOAGRO';
		$mail->From			= "expoagro.negocios@gmail.com";
		$mail->AddReplyTo('international@exponenciar.com.ar', 'EXPOAGRO');
		$mail->IsHTML(true);    // set email format to HTML	
		
		$mail->AddAddress($correo);
		$mail->AddAddress($correo1);
		$mail->Subject = "EXPOAGRO - TICKET";
		
		//$mail->AddEmbeddedImage('../app-assets/img/logo-light.png', 'imglogo'); //cid:imglogo
		
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
							<div style="text-align:center">
								<b style="font-family:arial,sans-serif;font-size:24px;">
									<font color="#ff9900">Peticiones solicitadas por '.$pernombre.','.$perapelli.' de '.$percompan.'</font>
								</b>
								<font color="#212121" style="font-family:arial,sans-serif;font-size:18px;">
									<div style="text-align:center"></div>
								</font>
							</div>
							
							<br>
							<br>
							
							<div style="text-align:center">
								<b style="font-family:arial,sans-serif;font-size:16px;">
									<font color="#212121">Los campos seleccionados fueron: '.$formatos.'</font>
								</b>
							</div>
							
							<br>
							
							<div style="text-align:center">
								<font color="#212121" style="font-family:arial,sans-serif;font-size:15px;">
									<div style="text-align:center">Con la siguiente descripcion: <b>'.$descri.'</b></div>
								</font>
							</div>
							
							<br>
							<div style="text-align:center">
								<font color="#212121" style="font-family:arial,sans-serif;font-size:15px;">
									<div style="text-align:center">se encuentra en la mesa: <b>'.$mesnumero.'<b></div>
								</font>
							</div>
							
							<br>
				
						</div>
						</body>
						</html>';
		
		$mail->Send();
	}

	echo 'SOLICITUD DE AYUDA ENVIADA!!!';

?>	
