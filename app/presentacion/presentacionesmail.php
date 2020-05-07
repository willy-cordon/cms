<?
	if(!isset($_SESSION))  session_start();
	//--------------------------------------------------------------------------------------------------------------
	include ('../../func/zglobals.php');
	include ('../../func/sigma.php');	
	include ('../../func/zdatabase.php');
    include ('../../func/zfvarias.php');
	include ('../../func/class.phpmailer.php');
	include ('../../func/class.smtp.php');
	
	define('SendMailUsuario', 'brasil@cmspeople.com');
	define('SendMailPass', 'rd79jh31');

	$errcod		= 0;
	$errmsg		= '';
	$err 		= 'ERROR';
	$valcode = substr(md5('OnLifeAccesoAppMobile'),0,10).substr(md5('MobileApp'),5,10).substr(md5('oNlIFEApplication'),8,20).md5('OnLifeAccesoApplication');
	
	$datainfo 	= (isset($_POST['datainfo']))? trim($_POST['datainfo']) : '';
	$opcion 	= (isset($_POST['opcion']))? trim($_POST['opcion']) : '';
	
	if($datainfo!=''&& $opcion!=''){
		
		switch($opcion){
			case 1: 
				$url = 'http://www.cmseventos.com/2019/brasil/business-revolution-2019/presentaciones/09H30-SALA-DADOS-ELIAS-SFEIR.pdf';
				$file = '09H30-SALA-DADOS-ELIAS-SFEIR.pdf';
				break;
			case 2: 
				$url = 'http://www.cmseventos.com/2019/brasil/business-revolution-2019/presentaciones/11H30-ENTENDER-COMO-CONSUMIDOR-MARIANE-CARDOSO-C&C.pdf';
				$file = '11H30-ENTENDER-COMO-CONSUMIDOR-MARIANE-CARDOSO-C& C.pdf';
				break;
			case 3: 
				$url = 'http://www.cmseventos.com/2019/brasil/business-revolution-2019/presentaciones/15H00-PAINEL-CASES-B2B-Da-Paschoal.pdf';
				$file = '15H00-PAINEL-CASES-B2B-Da-Paschoal.pdf';
				break;
			case 4: 
				$url = 'http://www.cmseventos.com/2019/brasil/business-revolution-2019/presentaciones/15H45-Alessandra-Freitas-B2B.pdf';
				$file = '15H45-Alessandra-Freitas-B2B.pdf';
				break;
			case 5: 
				$url = 'http://www.cmseventos.com/2019/brasil/business-revolution-2019/presentaciones/15h00-CMS-Painel-Meios-de-Pagamento-Boanerges-23out19-DRAFT2.pdf';
				$file = '15h00-CMS-Painel-Meios-de-Pagamento-Boanerges-23out19-DRAFT2.pdf';
				break;
			case 6: 
				$url = 'http://www.cmseventos.com/2019/brasil/business-revolution-2019/presentaciones/15h00-LIVIA-SCHRAMM.pdf';
				$file = '15h00-LIVIA-SCHRAMM.pdf';
				break;
			case 7: 
				$url = 'http://www.cmseventos.com/2019/brasil/business-revolution-2019/presentaciones/Desafios-e-Tendencias-da-Inadimplencia-no-Brasil-Lite.pdf';
				$file = 'Desafios-e-Tendencias-da-Inadimplencia-no-Brasil-Lite.pdf';
				break;
				
		}
		
		
		
		$datainfo = str_replace($valcode,'',$datainfo);
		$data = base64_decode($datainfo);
		
		$vdata = explode('||',$data);
		$percodigo = $vdata[0];//CODIGO DE PERFIL
		
		//--------------------------------------------------------------------------------------------------------------
		$conn= sql_conectar();//Apertura de Conexion
		
		$query = "SELECT PERCORREO FROM PER_MAEST WHERE PERCODIGO=$percodigo ";
		
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row = $Table->Rows[0];
			$percorreo 	= trim($row['PERCORREO']);
			
			if($percorreo!=''){
				$mail = new PHPMailer();	
				$mail->IsSMTP();
				$mail->SMTPAuth 	= true; 	
				
				$mail->CharSet = 'UTF-8';
				$mail->SMTPSecure = 'tls';
				$mail->Host = 'smtp.gmail.com';
				$mail->Port = 587;
				
				$mail->Username 	= SendMailUsuario;
				$mail->Password 	= SendMailPass;
				
				$mail->FromName 	= 'CMS';
				$mail->From			= SendMailUsuario;
				$mail->AddReplyTo(SendMailUsuario, 'CMS');
				
				$mail->IsHTML(true);    // set email format to HTML	
				
				$mail->AddAddress($percorreo, '');
				$mail->Subject = 'CMS - Apresentações!';
				$mail->AddEmbeddedImage('../../app-assets/img/logo-light.png', 'imglogo'); //cid:imglogo
				
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
											<font color="#2f67a0" style="font-family:arial,sans-serif;font-size:18px;">
												<div style="text-align:center"><a href="'.$url.'">APRESENTAÇÃO: '.$file.'</a></div>
											</font>
										</div>
										</body>
							</html>';
				$mail->Send();
			}
			
		}
		sql_close($conn);	
		
	}
	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
?>
