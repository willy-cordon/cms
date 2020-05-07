<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod 	= 0;
	$errmsg 	= '';
	$err 		= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$secsubcod 	= (isset($_POST['secsubcod']))? trim($_POST['secsubcod']) : 0;
	$seccodigo 	= (isset($_POST['seccodigo']))? trim($_POST['seccodigo']) : 0;

	//--------------------------------------------------------------------------------------------------------------
	$secsubcod = VarNullBD($secsubcod , 'N');
	$seccodigo = VarNullBD($seccodigo , 'N');	
	
	if($secsubcod!=0){
		//Elimino el registro
		$query = "UPDATE SEC_SUB SET ESTCODIGO=3 WHERE SECSUBCOD=$secsubcod ";
		$err = sql_execute($query,$conn,$trans);
	}
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Subsector eliminado!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? TrMessage('Error al eliminar el subsector!') : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
