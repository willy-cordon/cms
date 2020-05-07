<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$seccodigo 		= (isset($_POST['seccodigo']))? trim($_POST['seccodigo']) : 0;
	$secdescri 		= (isset($_POST['secdescri']))? trim($_POST['secdescri']) : '';
	$secdesing 		= (isset($_POST['secdesing']))? trim($_POST['secdesing']) : '';
	$estcodigo 		= 1;
	
	if($seccodigo==''){
		$seccodigo=0;
	}
	if($secdescri==''){
		$errcod=2;
		$errmsg='Falta el nombre de sector';
	}	
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	//--------------------------------------------------------------------------------------------------------------
	$seccodigo		= VarNullBD($seccodigo		, 'N');
	$secdescri		= VarNullBD($secdescri		, 'S');
	$secdesing		= VarNullBD($secdesing		, 'S');
	$estcodigo		= VarNullBD($estcodigo		, 'N');
	
	if($seccodigo==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_SECTOR,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$seccodigo 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO SEC_MAEST(SECCODIGO,SECDESCRI,SECDESING,ESTCODIGO)
					VALUES($seccodigo,$secdescri,$secdesing,$estcodigo) ";
	}else{
		$query = " 	UPDATE SEC_MAEST SET 
					SECCODIGO=$seccodigo, SECDESCRI=$secdescri,SECDESING=$secdesing, ESTCODIGO=$estcodigo
					WHERE SECCODIGO=$seccodigo ";
	}
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
