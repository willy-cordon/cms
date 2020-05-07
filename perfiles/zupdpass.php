<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	
	$codigoInicial = 1000;
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.PERCOMPAN,P.PERUSUACC,P.PERPASACC
				FROM PER_MAEST P
				WHERE PERCODIGO>=$codigoInicial ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$percodigo 	= trim($row['PERCODIGO']);
		$pernombre	= trim($row['PERNOMBRE']);
		$perapelli	= trim($row['PERAPELLI']);
		$percompan	= trim($row['PERCOMPAN']);
		$perusuacc	= trim($row['PERUSUACC']);
		$perpasacc	= trim($row['PERPASACC']);
		
		$perpasacc = md5('BenVido'.$perpasacc.'PassAcceso'.$perusuacc);
		$perpasacc = 'B#SD'.md5(substr($perpasacc,1,10).'BenVidO'.substr($perpasacc,5,8)).'E##$F';
		
		$query ="UPDATE PER_MAEST SET PERCOMENT=PERPASACC, PERPASACC='$perpasacc' WHERE PERCODIGO=$percodigo ";
		$err = sql_execute($query,$conn);
	}
	
	echo "Claves Encriptadas y Actualizaadas";
	sql_close($conn);
	
?>	
