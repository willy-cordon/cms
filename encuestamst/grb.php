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
	$encreg 		= (isset($_POST['encreg'])) ? trim($_POST['encreg']) : 0;
	$encdescri 		= (isset($_POST['encdescri']))? trim($_POST['encdescri']) : '';
	$encpublic 		= (isset($_POST['encpublic']))? trim($_POST['encpublic']) : 'N';
	
	logerror("LOG ERROR ".$encreg);
	$estcodigo 		= 1;
	
	if($encdescri==''){
		$errcod=2;
		$errmsg='Falta el titulo';
	}	
	
	//Verifico si hay una encuesta ya en estado Publicado
	if($encpublic=='S'){
		$query = "SELECT ENCREG FROM ENC_CABE WHERE ENCREG = $encreg AND ENCPUBLIC='S' AND ESTCODIGO=1 ";
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$errcod=2;
			$errmsg='Ya existe una Encuesta Publicada';
		}
	}
	
	
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	//--------------------------------------------------------------------------------------------------------------
	
	$encreg		= VarNullBD($encreg			, 'N');
	$encdescri	= VarNullBD($encdescri		, 'S');
	$encpublic	= VarNullBD($encpublic		, 'S');
	
	if($encreg==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_ENCUESTA,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$encreg 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO ENC_CABE(ENCREG,ENCDESCRI,ENCFCHREG,ESTCODIGO,ENCPUBLIC)
					VALUES($encreg,$encdescri,CURRENT_TIMESTAMP,$estcodigo,$encpublic) ";
	}else{
		$query = " 	UPDATE ENC_CABE SET 
					ENCDESCRI=$encdescri,ENCFCHREG=CURRENT_TIMESTAMP,ESTCODIGO=$estcodigo,
					ENCPUBLIC=$encpublic
					WHERE ENCREG=$encreg ";
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
