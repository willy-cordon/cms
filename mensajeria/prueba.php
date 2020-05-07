<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC . '/sigma.php';
	require_once GLBRutaFUNC . '/zdatabase.php';
	require_once GLBRutaFUNC . '/zfvarias.php';
	require_once GLBRutaFUNC . '/class.phpmailer.php';
	require_once GLBRutaFUNC . '/class.smtp.php';

	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod 	= 0;
	$errmsg 	= '';
	$err 		= 'SQLACCEPT';

	//--------------------------------------------------------------------------------------------------------------
	$conn = sql_conectar(); //Apertura de Conexion
	$trans	= sql_begin_trans($conn);

	//Control de Datos

	//ANCHOR GENERAMOS HORA ACTUAL 
	$currentDate = date("Y-m-d");
	$time = date("h:ia");
	$time24hours = date('H:i', strtotime($time));
	/////////////////////////////////////////////


    $currentDate		= VarNullBD($currentDate		, 'S');
	$time24hours		= VarNullBD($time24hours		, 'S');


	//ANCHOR  SELECCION DEL MENSAJE QUE COINCIDA CON LA FECHA Y HORA
	$query = "SELECT MSGTITULO,MSGDESCRI,MSGPER FROM MSG_CABE WHERE MSGSEND = 1 AND MSGDATE = $currentDate AND MSGTIME = $time24hours";
    $Table = sql_query($query, $conn, $trans);
    
    if($Table->Rows_Count != -1){


        $row = $Table->Rows[0];
        
        $msgtitulo 	= trim($row['MSGTITULO']);
        $msgdescri	= trim($row['MSGDESCRI']);
        $msgper =  trim($row['MSGPER']);
        //------------------------------------------------------------------------------------------------------------------



        $query = " 	SELECT PERNOMBRE, PERAPELLI, PERCORREO FROM PER_MAEST WHERE ESTCODIGO<>3 AND PERCLASE=$msgper "; //PERTIPO=$pertipcod

        $Table = sql_query($query, $conn, $trans);

        //Envio mail avisando la liberacion
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth 	= true;

        $mail->CharSet = 'UTF-8';
        $mail->SMTPSecure = 'tls';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;

        $mail->Username 	= "brasil@cmspeople.com";
        $mail->Password 	= "rd79jh31";
        $LinkPlayStore = 'https://play.google.com/store/apps/details?id=com.nextar.btbox';
        $LinkAppStore = 'https://apps.apple.com/us/app/btbox/id1487949733';


        $mail->FromName 	= 'CMS BRASIL';
        $mail->From			= "brasil@cmspeople.com";
        $mail->AddReplyTo('brasil@cmspeople.com', 'CMS BRASIL');
        $mail->IsHTML(true);    // set email format to HTML	


        for ($i = 0; $i < $Table->Rows_Count; $i++) {
            $row = $Table->Rows[$i];
            $pernombre	= trim($row['PERNOMBRE']);
            $perapelli = trim($row['PERAPELLI']);
            $percorreo 	= trim($row['PERCORREO']);

            $mail->AddBCC($percorreo, $pernombre . ' ' . $perapelli);
        }

        $mail->Subject = $msgtitulo;

        $mail->AddEmbeddedImage('../app-assets/img/logo-light.png', 'imglogo'); //cid:imglogo
        $mail->AddEmbeddedImage('../app-assets/img/icoplaystore.png', 'icoplaystore');
        $mail->AddEmbeddedImage('../app-assets/img/icoappstore.png', 'icoappstore');

        $mail->Body = '<!DOCTYPE html>
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
                                            <font color="#212121">' . $msgtitulo . '</font>
                                        </b>	
                                    </div>
                                    <br>

                                    <div style="text-align:center">
                                        <b style="font-family:arial,sans-serif;font-size:16px;">
                                            <font color="#212121">' . $msgdescri . '</font>
                                        </b>
                                    </div>

                                    <div dir="ltr">
                                    <div style="text-align:center">
                                        <a href="' . $LinkPlayStore . '" target="_blank">
                                            <img src="cid:icoplaystore" alt="icoplaystore.png" style="cursor:hand; cursor:pointer;margin-right:0px;width:100px;height:auto;" data-image-whitelisted="" class="CToWUd">
                                        <a>
                                        &nbsp;&nbsp;
                                        <a href="' . $LinkAppStore . '" target="_blank">
                                            <img src="cid:icoappstore" alt="icoappstore.png" style="cursor:hand; cursor:pointer;margin-right:0px;width:100px;height:auto;" data-image-whitelisted="" class="CToWUd">
                                        </a>
                                    </div>
                                </div>

                                </div>
                                </body>
                                </html>';


        $result = $mail->Send();
        
    }else {
        $errcod =  2;

    }

    if ($err == 'SQLACCEPT' && $errcod == 0) {
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Su correo fue enviado!';
	} else {
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg == '') ? 'Error al enviar!' : $errmsg;
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);
	echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';
        

    
    


	//--------------------------------------------------------------------------------------------------------------


	?>	
