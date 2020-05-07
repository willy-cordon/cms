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
	$catcodigo 		= (isset($_POST['catcodigo']))? trim($_POST['catcodigo']) : 0;
	$catdescri 		= (isset($_POST['catdescri']))? trim($_POST['catdescri']) : '';
	$catdesing 		= (isset($_POST['catdesing']))? trim($_POST['catdesing']) : '';
	$secsubcod 		= (isset($_POST['secsubcod']))? trim($_POST['secsubcod']) : 0;

	$estcodigo 		= 1;
	
	if($catcodigo==''){
		$catcodigo=0;
	}
	if($catdescri==''){
		$errcod=2;
		$errmsg='Falta el nombre de la categoria';
	}	
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	//--------------------------------------------------------------------------------------------------------------
	$catcodigo		= VarNullBD($catcodigo		, 'N');
	$estcodigo		= VarNullBD($estcodigo		, 'N');
	$secsubcod		= VarNullBD($secsubcod		, 'N');
	$catdescri		= VarNullBD($catdescri		, 'S');
	$catdesing		= VarNullBD($catdesing		, 'S');
	
	
	if($catcodigo==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_CATEGORIA,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$catcodigo 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO CAT_MAEST(CATCODIGO,CATDESCRI,CATDESING,ESTCODIGO,SECSUBCOD)
					VALUES($catcodigo,$catdescri,$catdesing,$estcodigo,$secsubcod) ";
	}else{
		$query = " 	UPDATE CAT_MAEST SET 
					CATDESCRI=$catdescri,CATDESING=$catdesing, SECSUBCOD=$secsubcod
					WHERE CATCODIGO=$catcodigo";  
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
