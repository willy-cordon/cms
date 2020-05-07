<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/class.phpmailer.php';
require_once GLBRutaFUNC . '/class.smtp.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma	




//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$errcod 	= 0;
$errmsg 	= '';
$err 		= 'SQLACCEPT';
$mail = new PHPMailer();




//--------------------------------------------------------------------------------------------------------------
$conn = sql_conectar(); //Apertura de Conexion
$trans	= sql_begin_trans($conn);


//Control de Datos
// $percodigo 	= (isset($_POST['percodigo'])) ? trim($_POST['percodigo']) : 0;
$permiso 	= (isset($_POST['permiso'])) ? trim($_POST['permiso']) : 0;
$setPermisoUsuarios = (isset($_POST['setusuarios'])) ? trim($_POST['setusuarios']) : 0;
$seleccion = (isset($_POST['seleccion'])) ? trim($_POST['seleccion']) : 0;
$opcion   = (isset($_POST['opcion'])) ? trim($_POST['opcion']) : 0;
$email = 0;
$envioEmail = 0;



//--------------------------------------------------------------------------------------------------------------
// $percodigo = VarNullBD($percodigo, 'N');



//ANCHOR APLICO PERMISOS A TRAVES DE LAS  LAS SELECCIONES------------------------------------------------------
if ($seleccion == 1) {

	switch ($opcion) {
		case 'D': //Permiso de Disponibilidad
			$updpermiso = ' PERUSADIS=1 ';
			break;
		case 'R': //Permiso de Solicitud de Reuniones
			$updpermiso = ' PERUSAREU=1';
			$email = 1;
			break;
		case 'M': //Permiso de Mensajeria
			// $updpermiso = ' PERUSAMSG=CASE WHEN COALESCE(PERUSAMSG,0)=1 THEN 0 ELSE 1 END ';
			$updpermiso = ' PERUSAMSG=1';
			break;
			//QUITAR PERMISOS 
		case 'QD': //Permiso de Disponibilidad

			$updpermiso = ' PERUSADIS = 0 ';
			break;
		case 'QR': //Permiso de Reuniones

			$updpermiso = ' PERUSAREU = 0';
			break;
		case 'QM': //Permiso de Mensajeria

			$updpermiso = ' PERUSAMSG = 0';
			break;
	}

	//------------------------------------------------------------------------------------
} else if ($seleccion == 0) {

	switch ($opcion) {
		case 'D': //Permiso de Disponibilidad
			$updpermiso = ' PERUSADIS=CASE WHEN COALESCE(PERUSADIS,0)=1 THEN 0 ELSE 1 END ';
			break;
		case 'R': //Permiso de Solicitud de Reuniones
			$email = 1;
			$updpermiso = ' PERUSAREU=CASE WHEN COALESCE(PERUSAREU,0)=1 THEN 0 ELSE 1 END ';
			break;
		case 'M': //Permiso de Mensajeria
			$updpermiso = ' PERUSAMSG=CASE WHEN COALESCE(PERUSAMSG,0)=1 THEN 0 ELSE 1 END ';
			break;
	}
}


//ANCHOR UPDATE A LA BASE DE DATOS 
foreach ($_POST['percodigo'] as $index => $data) {

	$percodigo = trim($data['id']);

	//Si permiso de reunion 
	if ($email == 1) {

		$query = "SELECT PERCODIGO, PERNOMBRE,PERAPELLI,PERCORREO FROM PER_MAEST WHERE PERUSAREU = 0 AND PERCODIGO = $percodigo";
		$Table = sql_query($query, $conn, $trans);

		$rows  = $Table->Rows_Count;

		if ($rows != -1) {
			$row = $Table->Rows[0];
			$pernombre = trim($row['PERNOMBRE']);
			$perapelli = trim($row['PERAPELLI']);
			$percorreo = trim($row['PERCORREO']);
			$mail->AddAddress($percorreo, $pernombre . ' ' . $perapelli);
			//Seteamos email a 3 porque al poner mail->send dentro del bucle va a enviar por cada iteracion.
			$envioEmail = 1;
		}
	}


	$query = "UPDATE PER_MAEST SET $updpermiso WHERE PERCODIGO = $percodigo";
	$err = sql_execute($query, $conn, $trans);
}

if ($envioEmail == 1) {
	$mail->IsSMTP();
	$mail->SMTPAuth     = true;
	$mail->SMTPSecure = 'tls';
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 587;
	$mail->Username     = "argentina@EXPOAGROpeople.com";
	$mail->Password     = "argentina*EXPOAGRO";
	$mail->FromName     = 'Polo IT';
	$mail->From            = "argentina@EXPOAGROpeople.com";
	$mail->AddReplyTo('argentina@EXPOAGROpeople.com', 'Polo IT');
	$mail->IsHTML(true);    // set email format to HTML	
	$mail->Subject = 'Ya podes concretar reuniones';
	$mail->AddEmbeddedImage('../app-assets/img/logo-light.png', 'imglogo'); //cid:imglogo
	//$urlweb = 'http://localhost/webcoordinador/'; //DEV
	$urlweb = 'http://EXPOAGROeventos.com/2019/argentina/17-congreso-credito/'; //PRD
	$mail->Body = '<!DOCTYPE html>
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
			<b style="font-family:arial,sans-serif;font-size:24px;">
				<font color="#2f67a0">Felicitaciones!</font>
			</b>
			<font color="#212121" style="font-family:arial,sans-serif;font-size:18px;">
				<div style="text-align:center">Su perfil fue aprobado para participar en la plataforma de negocios virtual del EXPOAGRO de la Ciudad de Buenos Aires.</div>
			</font>
		</div>
		
		<br>
		<br>
		
		<div style="text-align:center">
			<b style="font-family:arial,sans-serif;font-size:16px;">
				<font color="#da5060">Para tener una mejor experiencia durante el evento, por favor toma nota de los siguientes puntos:</font>
			</b>
		</div>
		
		<br>
		
		<div style="text-align:center">
			<font color="#212121" style="font-family:arial,sans-serif;font-size:15px;">
				<div style="text-align:center">1. Carga tu foto y completa tu perfil.</div>
			</font>
		</div>
		
		<br>
		
		<div style="text-align:center">
			<font color="#212121" style="font-family:arial,sans-serif;font-size:15px;">
				<div style="text-align:center">2. Clasifique su perfil de búsqueda de productos de forma exhaustiva. Esto ayudará a que la plataforma le sugiera contrapartes, mejorando su experiencia de usuario.<a href="https://www.youtube.com/watch?v=Ddk0lXQmEW0&amp=&feature=youtu.be">(Instructivo).</a></div>
			</font>
		</div>
		
		<br>
		
		 <div style="text-align:center">
			<font color="#212121" style="font-family:arial,sans-serif;font-size:15px;">
				<div style="text-align:center">3. Bloquee algunos horarios de su agenda en los cuales no se encuentre disponible.<a href="https://www.youtube.com/watch?v=c25BtrswQwM&amp;feature=youtu.be">(Instructivo).</a></div>
			</font>
		</div>
		
		<br><br>
		
		<div style="text-align:center">
			<font color="#212121" style="font-family:arial,sans-serif;font-size:15px;">
				<div style="text-align:center">4. A partir de hoy va a poder empezar a generar reuniones, tenga su cuenta preparada.<a href="https://www.youtube.com/watch?v=z2WNKSkz8j8&amp=&feature=youtu.be">(Instructivo).</a></div>
			</font>
		</div>
		
		<br><br>
		
		<div style="text-align:center">
			<b style="font-family:arial,sans-serif;font-size:24px;">
				<font color="#2f67a0">Congratulations!</font>
			</b>
			<font color="#212121" style="font-family:arial,sans-serif;font-size:18px;">
				<div style="text-align:center">Your profile has been approved and you&#39;re ready to start using the Buenos Aires’ IT Cluster business platform.</div>
			</font>
		</div>
		
		<br>
		<br>
		
		<div style="text-align:center">
			<b style="font-family:arial,sans-serif;font-size:16px;">
				<font color="#da5060">To have the best experience possible using our online tool, please take note of the following recommendations:</font>
			</b>
		</div>
		
		<br>
		
		<div style="text-align:center">
			<font color="#212121" style="font-family:arial,sans-serif;font-size:15px;">
				<div style="text-align:center">1. Upload your photo and complete your profile.</div>
			</font>
		</div>
		
		<br>
		
		<div style="text-align:center">
			<font color="#212121" style="font-family:arial,sans-serif;font-size:15px;">
				<div style="text-align:center">2. Classify the products and services of your interest. This will help you improve your experience finding exactly what you are looking for. Try to be thorough during this process, this will make it easier for the platform to match you with suitable counterparts.<a href="https://www.youtube.com/watch?v=Ddk0lXQmEW0&amp=&feature=youtu.be">(Guide).</a></div>
			</font>
		</div>
		
		<br>
		
		 <div style="text-align:center">
			<font color="#212121" style="font-family:arial,sans-serif;font-size:15px;">
				<div style="text-align:center">3. Manage your schedule from the app and block those spaces in which you dont want to have any meetings (such as lunch breaks).<a href="https://www.youtube.com/watch?v=c25BtrswQwM&amp;feature=youtu.be">(Guide).</a></div>
			</font>
		</div>
		
		<br><br>
		
		<div style="text-align:center">
			<font color="#212121" style="font-family:arial,sans-serif;font-size:15px;">
				<div style="text-align:center">4. You can start generating meetings, so be sure to have your profile ready to go!<a href="https://www.youtube.com/watch?v=z2WNKSkz8j8&amp=&feature=youtu.be">(Guide).</a></div>
			</font>
		</div>
		
		<br><br>
		
		<div style="text-align:center">
			<b style="font-family:arial,sans-serif;font-size:16px;">
				<font color="#274e13">Click <a href="' . $urlweb . 'login"> aqui </a> para ingresar a tu perfil / Click <a href="' . $urlweb . 'login"> here </a> to start completing your profile</font>
			</b>
		</div>
		
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
		
	</div>
	</body>
	</html>';
	$mail->Send();
}


//------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------
if ($err == 'SQLACCEPT' && $errcod == 0) {
	sql_commit_trans($trans);
	$errcod = 0;
	$errmsg = TrMessage('Permiso actualizado!');
} else {
	sql_rollback_trans($trans);
	$errcod = 2;
	$errmsg = ($errmsg == '') ? TrMessage('Error al actualizar el permiso!') : $errmsg;
}
//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';

?>	

