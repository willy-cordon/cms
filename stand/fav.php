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
	
	//Control de Datos
	$percodigo 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$percodfav = (isset($_POST['percodfav']))? trim($_POST['percodfav']) : 0;
	logerror($percodfav);
	//--------------------------------------------------------------------------------------------------------------
	
	if($percodfav!=0){
		//Busco si ya esta marcado como favorito
		$query = " SELECT PERCODFAV FROM PER_FAVO WHERE PERCODIGO=$percodigo AND PERCODFAV=$percodfav";
		
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			//Elimino la relacion
			$query = " 	DELETE FROM PER_FAVO WHERE PERCODIGO=$percodigo AND PERCODFAV=$percodfav ";
			$err = sql_execute($query,$conn);
			
		}else{
			//Creo la relacion de favorito
			$query = " 	INSERT INTO PER_FAVO (PERCODIGO, PERCODFAV)
						VALUES ($percodigo, $percodfav)";
			$err = sql_execute($query,$conn);
			logerror($err);
		}
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
