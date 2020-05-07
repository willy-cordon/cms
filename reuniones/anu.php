<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/sendfcmmessage.php';
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$percodigo 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$reureg = (isset($_POST['reureg']))? trim($_POST['reureg']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	$reureg = VarNullBD($reureg	, 'N');
	
	if($reureg!=0){
		//Busco la reunion, para poder tomar a quien comunicar la notificacion
		$query = " 	SELECT PERCODSOL,PERCODDST
					FROM REU_CABE 
					WHERE REUREG=$reureg ";
		
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$percodsol = trim($row['PERCODSOL']);
			$percoddst = trim($row['PERCODDST']);
		}
		
		if($percoddst==$percodigo){
			$percoddst = $percodsol;
		}
		
		$query = " 	UPDATE REU_CABE SET REUESTADO=3,REUFCHCAN=CURRENT_TIMESTAMP WHERE REUREG=$reureg ";
		$err = sql_execute($query,$conn,$trans);
		
		$query = " 	UPDATE REU_SOLI SET REUESTADO=3 WHERE REUREG=$reureg ";
		$err = sql_execute($query,$conn,$trans);
		
		//Inserto Notificacion de Cancelacion
		$query = " INSERT INTO NOT_CABE (NOTREG, NOTFCHREG, NOTTITULO, NOTFCHLEI, PERCODDST, NOTESTADO, PERCODORI, REUREG, NOTCODIGO)
					VALUES (GEN_ID(G_NOTIFICACION,1), CURRENT_TIMESTAMP, 'Reunion cancelada', NULL, $percoddst, 1, $percodigo, $reureg, 3); ";
		$err = sql_execute($query,$conn,$trans);
		
		
		//Envio una notifiacion mobile
		//-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
		//Busco si el destino tiene mobile
		$query = "	SELECT N.ID,N.PROVIDER
					FROM NOT_REGI N 
					WHERE N.PERCODIGO=$percoddst ";
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
			
			$titulo 	= 'Reunion cancelada';
			$message 	= "$perapelli $pernombre de $percompan ha cancelado la reunion.";
			
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
	}
		
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Reunion cancelada!';      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Error al cancelar la reunión!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
