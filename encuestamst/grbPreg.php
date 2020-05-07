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
	$encreg 		= (isset($_POST['encreg']))? trim($_POST['encreg']) : 0;
	$encpreitm 		= (isset($_POST['encpreitm']))? trim($_POST['encpreitm']) : 0;
	$encpretip 		= (isset($_POST['encpretip']))? trim($_POST['encpretip']) : 0;
	$encpregun 		= (isset($_POST['encpregun']))? trim($_POST['encpregun']) : '';
	$encpreval      = (isset($_POST['encpreval']))? trim($_POST['encpreval']):'';
	$encpreord      = (isset($_POST['encpreord']))? trim($_POST['encpreord']):'';
	$estcodigo 		= 1;
	

	if($encpreitm==''){
		$encpreitm=0;
	}

	if($encpretip==2 && trim($encpreval)==''){
		$errcod=2;
		$errmsg='Falta completa el valor. (Ej: Opcion1, Opcion2, Opcion3) ';
	}
	
	if($encpretip==3 && trim($encpreval)==''){
		$errcod=2;
		$errmsg='Falta completa el valor. (Ej: 5) ';
	}

	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	
	//--------------------------------------------------------------------------------------------------------------
	$encreg			= VarNullBD($encreg			, 'N');
	$encpreitm		= VarNullBD($encpreitm		, 'N');
	$encpretip		= VarNullBD($encpretip		, 'N');
	$encpregun		= VarNullBD($encpregun		, 'S');
	$encpreval		= VarNullBD($encpreval		, 'S');
	$encpreord		= VarNullBD($encpreord		, 'N');
	
	if($encpreitm==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_PREGUNTAS,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$encpreitm 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO ENC_PREG(ENCREG,ENCPREITM,ENCPREGUN,ENCPRETIP,ENCPREVAL,ESTCODIGO,ENCPREORD)
					VALUES($encreg,$encpreitm,$encpregun,$encpretip,$encpreval,$estcodigo,$encpreord) ";
	}else{
		$query = " 	UPDATE ENC_PREG SET 
					ENCPREGUN=$encpregun,ENCPRETIP=$encpretip,ENCPREVAL=$encpreval,ESTCODIGO=$estcodigo,ENCPREORD=$encpreord
					WHERE ENCREG=$encreg AND ENCPREITM=$encpreitm ";
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
