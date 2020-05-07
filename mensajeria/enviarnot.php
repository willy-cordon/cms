<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/sendfcmmessage.php';
	require_once GLBRutaFUNC.'/sendiosmessage.php';
	//--------------------------------------------------------------------------------------------------------------
	$errcod 		= 0;
	$errmsg			= '';	
	$err			= 'SQLACCEPT';	
	//--------------------------------------------------------------------------------------------------------------
	//Control de Datos
	$msgreg 	= (isset($_POST['msgreg']))? trim($_POST['msgreg']) : 0;
	$pertipo 	= (isset($_POST['pertipo']))? trim($_POST['pertipo']) : 0;
	$perclase 	= (isset($_POST['perclase']))? trim($_POST['perclase']) : 0;

	$pertipo = VarNullBD($pertipo , 'N');
	$perclase = VarNullBD($perclase , 'N');

	//--------------------------------------------------------------------------------------------------------------	
	$conn		= sql_conectar();//Apertura de Conexion
	$trans		= sql_begin_trans($conn);
	//--------------------------------------------------------------------------------------------------------------	
	if ($msgreg!=0) {
		$query="SELECT MSGTITULO,MSGDESCRI 
				FROM MSG_CABE 
				WHERE MSGREG=$msgreg";
		$Table = sql_query($query,$conn,$trans);
		$row = $Table->Rows[0];
		$msgtitulo 	= trim($row['MSGTITULO']);
		$msgdescri	= trim($row['MSGDESCRI']);
		$msgdescri = str_replace("&quot;",'"',$msgdescri);
		
		//Si la notificacion es para un departamento, busco el usuario
		$target = array();
		$data =  array('title'=>$msgtitulo,
					   'badge_number'=>1,
					   'server_message'=>'',
					   'text'=>$msgdescri,
					   'id'=>$msgreg);

		$where = '';
		if($perclase!=0){
			$where .= " AND P.PERCLASE=$perclase ";
		}
		if($pertipo!=0){
			$where .= " AND P.PERTIPO=$pertipo ";
		}

		$query = "	SELECT N.ID ,N.PROVIDER
					FROM NOT_REGI N  
					LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=N.PERCODIGO
					WHERE P.ESTCODIGO<>3 $where ";
	
	
		//Levanto los IDs - ANDROID
		if($perclase==9999){ //Perfiles sin Reuniones Aceptadas
			$query = "	SELECT N.ID ,N.PROVIDER
						FROM REU_CABE R
						INNER JOIN NOT_REGI N ON N.PERCODIGO=R.PERCODDST
						LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=N.PERCODIGO
						WHERE R.REUESTADO=1 AND P.ESTCODIGO<>3  ";
		}
		
		$Table = sql_query($query,$conn);
		$cantenv = 0;
		for($i=0; $i < $Table->Rows_Count; $i++){
			$row= $Table->Rows[$i];			
			$id	= trim($row['ID']);
			$provider	= trim($row['PROVIDER']);
			if($provider=='FCM'){ //Android
				//array_push($target,'e7VuEoyqyWY:APA91bG59eJaaXBUJx9NooMbJTLGlx2HdvfmffuNkXUb-YWctb5aMGJbk6N6ZqfBRAYEtHAy2mDTLBavutB2U4EX0qen01BiuVbCOk3oNkyITip9yGasueginBUu50TXEepXK1EvAsP7');
				array_push($target,$id);
				$cantenv++;
				
				if($cantenv >= 1000){
					sendFCMMessage($data,$target);
					$target = null;
					$target = array();
					$cantenv = 0;
				}
			}else{ //IOS
				//sendIOSMessage($msgtitulo.'::::'.$msgdescri, $id);
				sendIOSMessage($msgdescri, $id);
			}
		}
		if($cantenv != 0){
			sendFCMMessage($data,$target);
		}
	
	
		//Levanto los IDs - IOS
		/*$query = "SELECT ID FROM NOT_REGI WHERE PROVIDER='APNS' ";
		$Table = sql_query($query,$conn);
		$cantenv = 0;
		for($i=0; $i < $Table->Rows_Count; $i++){
			$row= $Table->Rows[$i];			
			$id	= trim($row['ID']);
			
			sendIOS($notdescri, $id);		
			$cantenv++;
		}*/
	}
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Su notificacion fue enviada!';      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Error al enviar!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';

	
?>
