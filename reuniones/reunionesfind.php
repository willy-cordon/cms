<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	
	$errcod 	= 0;
	$errmsg 	= '';
	$err 		= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT COUNT(*) AS CANTIDAD
				FROM REU_CABE R
				LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=R.PERCODSOL
				WHERE R.PERCODSOL=$percodigo AND R.REUESTADO=1 ";
	$Table = sql_query($query,$conn);
	$row = $Table->Rows[0];
	$cantEnviados= trim($row['CANTIDAD']);
	if($cantEnviados==0)	$cantEnviados='';
	
	//Reuniones recibidas y pendientes
	$query = "	SELECT COUNT(*) AS CANTIDAD
				FROM REU_CABE R
				LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=R.PERCODSOL
				WHERE  R.PERCODDST=$percodigo AND R.REUESTADO=1  ";
	$Table = sql_query($query,$conn);
	$row = $Table->Rows[0];
	$cantRecibidos= trim($row['CANTIDAD']);
	if($cantRecibidos==0)	$cantRecibidos='';
	
	echo '{"cantRecibidos":"'.$cantRecibidos.'","cantEnviados":"'.$cantEnviados.'"}';
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	
	
?>	
