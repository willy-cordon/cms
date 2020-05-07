<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	//--------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$mescodigo 		= (isset($_POST['mescodigo']))? trim($_POST['mescodigo']) : 0;
	$mesnumero 		= (isset($_POST['mesnumero']))? trim($_POST['mesnumero']) : '';
	$estcodigo 		= 1;
	
	if($mescodigo==''){
		$mescodigo=0;
	}
	if($mesnumero==''){
		$errcod=2;
		$errmsg='Complete el campo!';
	}	
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	//--------------------------------------------------------------------------------------------------------------
	$mescodigo		= VarNullBD($mescodigo		, 'N');
	$mesnumero		= VarNullBD($mesnumero		, 'S');
	$estcodigo		= VarNullBD($estcodigo		, 'N');
	
	if($mescodigo==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_MESAS,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$mescodigo 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO MES_MAEST(MESCODIGO,MESNUMERO,ESTCODIGO)
					VALUES($mescodigo,$mesnumero,$estcodigo) ";
	}else{
		$query = " 	UPDATE MES_MAEST SET 
					MESNUMERO=$mesnumero, ESTCODIGO=$estcodigo
					WHERE MESCODIGO=$mescodigo ";
	}
	$err = sql_execute($query,$conn,$trans);	
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Guardado correctamente!';      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Guardado correctamente!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
