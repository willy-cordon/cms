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
	$encprereg 	= (isset($_POST['encprereg']))? trim($_POST['encprereg']) : 0;
	//logerror($enccodigo);
	//--------------------------------------------------------------------------------------------------------------
	$encprereg = VarNullBD($encprereg , 'N');
	
	if($encprereg!=0){
		//Elimino el registro
        $query="DELETE FROM ENC_PRES 
				 
        WHERE ENCPREREG=$encprereg ";

		
		$err = sql_execute($query,$conn,$trans);
	}
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = 'Presentacion eliminada';      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'ERROR' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';


	
?>	
