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
	$secsubcod 		= (isset($_POST['secsubcod']))? trim($_POST['secsubcod']) : 0;
	$secsubdes 		= (isset($_POST['secsubdes']))? trim($_POST['secsubdes']) : '';
	$secsubdesing	= (isset($_POST['secsubdesing']))? trim($_POST['secsubdesing']) : '';
	$seccodigo 		= (isset($_POST['seccodigo']))? trim($_POST['seccodigo']) : 0;

	$estcodigo 		= 1;
	
	if($secsubcod==''){
		$secsubcod=0;
	}
	if($secsubdes==''){
		$errcod=2;
		$errmsg='Falta el nombre del subsector';
	}	
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	//--------------------------------------------------------------------------------------------------------------
	$seccodigo		= VarNullBD($seccodigo		, 'N');
	$estcodigo		= VarNullBD($estcodigo		, 'N');
	$secsubcod		= VarNullBD($secsubcod		, 'N');
	$secsubdes		= VarNullBD($secsubdes		, 'S');
	$secsubdesing	= VarNullBD($secsubdesing	, 'S');
	
	
	if($secsubcod==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_SUBSECTOR,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$secsubcod 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO SEC_SUB(SECSUBCOD,SECSUBDES,SECSUBDESING,ESTCODIGO,SECCODIGO)
					VALUES($secsubcod,$secsubdes,$secsubdesing,$estcodigo,$seccodigo) ";
	}else{
		$query = " 	UPDATE SEC_SUB SET 
					SECSUBDES=$secsubdes, SECSUBDESING=$secsubdesing, SECCODIGO=$seccodigo
					WHERE SECSUBCOD=$secsubcod";  
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
