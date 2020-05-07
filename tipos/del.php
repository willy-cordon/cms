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
	$pertipo 	= (isset($_POST['pertipo']))? trim($_POST['pertipo']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	$pertipo = VarNullBD($pertipo , 'N');
	
	if($pertipo!=0){
		//Elimino el registro
		$query = "UPDATE PER_TIPO SET ESTCODIGO=3 WHERE PERTIPO=$pertipo ";
		$err = sql_execute($query,$conn,$trans);
	}
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Sector eliminado!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? TrMessage('Error al eliminar el sector!') : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
