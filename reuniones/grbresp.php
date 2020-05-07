<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	
	$percodigo 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$reureg 	= (isset($_POST['reureg']))? trim($_POST['reureg']) : 0;
	$encreg 	= (isset($_POST['encreg']))? trim($_POST['encreg']) : 0;
	$errcod		= 0;
	$errmsg		= '';
	$err 		= 'ERROR';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	foreach($_POST['preguntas'] as $index => $data){
		$encpreitm = trim($data['encpreitm']);
		$encvalres = trim($data['encvalres']);
		
		$query = "	INSERT INTO ENC_RESP (REUREG, ENCREG, ENCPREITM, PERCODIGO, ENCVALRES, ESTCODIGO) 
					VALUES ($reureg, $encreg, $encpreitm, $percodigo, '$encvalres', 1)";
		$err = sql_execute($query,$conn,$trans);
	}
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
