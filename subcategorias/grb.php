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
	$catsubcod 		= (isset($_POST['catsubcod']))? trim($_POST['catsubcod']) : 0;
	$catsubdes 		= (isset($_POST['catsubdes']))? trim($_POST['catsubdes']) : '';
	$catsubdesing	= (isset($_POST['catsubdesing']))? trim($_POST['catsubdesing']) : '';
	$catcodigo 		= (isset($_POST['catcodigo']))? trim($_POST['catcodigo']) : 0;

	$estcodigo 		= 1;
	
	if($catsubcod==''){
		$catsubcod=0;
	}
	if($catsubdes==''){
		$errcod=2;
		$errmsg='Falta el nombre de la subcategoria';
	}	
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	//--------------------------------------------------------------------------------------------------------------
	$catsubcod		= VarNullBD($catsubcod		, 'N');
	$estcodigo		= VarNullBD($estcodigo		, 'N');
	$catsubdes		= VarNullBD($catsubdes		, 'S');
	$catsubdesing	= VarNullBD($catsubdesing	, 'S');
	$catcodigo		= VarNullBD($catcodigo		, 'N');
	
	
	
	if($catsubcod==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_SUBCATEGORIA,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$catsubcod 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO CAT_SUB(CATSUBCOD,CATSUBDES,CATSUBDESING,ESTCODIGO,CATCODIGO)
					VALUES($catsubcod,$catsubdes,$catsubdesing,$estcodigo,$catcodigo) ";
	}else{
		$query = " 	UPDATE CAT_SUB SET 
					CATSUBDES=$catsubdes,CATSUBDESING=$catsubdesing, CATCODIGO=$catcodigo
					WHERE CATSUBCOD=$catsubcod";  
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
