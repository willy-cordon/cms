<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			

	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Datos recibidos
	
	$perdesing 		= (isset($_POST['perdesing']))? trim($_POST['perdesing']) : 0;
	$percodigo 		= (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;
	
	$percodigo = VarNullBD($percodigo		, 'N');
	$perdesing = VarNullBD($perdesing		, 'S');
	
	//Actualizamos solo la descripción de la empresa elegida
		$query = "UPDATE PER_MAEST SET 
				  PERDESING=$perdesing WHERE PERCODIGO=$percodigo";
	$err = sql_execute($query,$conn,$trans);	
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Guardado correctamente!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? TrMessage('Guardado correctamente!') : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
	