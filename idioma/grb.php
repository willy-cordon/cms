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
	$iditraesp 		= (isset($_POST['iditraesp']))? trim($_POST['iditraesp']) : 0;
	$iditraval 		= (isset($_POST['iditraval']))? trim($_POST['iditraval']) : '';
	$iditraitm 		= (isset($_POST['iditraitm']))? trim($_POST['iditraitm']) : '';
	$idioma 		= (isset($_POST['idioma']))? trim($_POST['idioma']) : '';
	$estcodigo 		= 1;
	

	//--------------------------------------------------------------------------------------------------------------
	$iditraesp		= VarNullBD($iditraesp		, 'S');
	$iditraval		= VarNullBD($iditraval		, 'S');
	$idioma			= VarNullBD($idioma			, 'S');
	$iditraitm		= VarNullBD($iditraitm		, 'N');
	
	
	if($iditraitm==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(GEN_IDITRAITM,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$iditraitm 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO IDI_TRAD(IDICODIGO,IDITRAESP,IDITRAVAL,IDITRAITM)
					VALUES($idioma,$iditraesp,$iditraval,$iditraitm) ";
	}else{
		$query = " 	UPDATE IDI_TRAD SET 
					IDICODIGO=$idioma, IDITRAESP=$iditraesp,IDITRAVAL=$iditraval
					WHERE IDITRAITM=$iditraitm ";
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
