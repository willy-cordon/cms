<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	$pertipo = trim($_POST['pertipo']);
	
	$jsonData = '[';
	//Cargo los Subsectores segun los sectores seleccionados
	$query = "	SELECT PERCLASE,PERCLADES
				FROM PER_CLASE
				WHERE PERTIPO=$pertipo ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$perclase 		= trim($row['PERCLASE']);
		$perclades 		= trim($row['PERCLADES']);
		
		$jsonData .= '{	"perclase":"'.$perclase.'",
						"perclades":"'.$perclades.'"},';
	}
	if($Table->Rows_Count>0){
		$jsonData = substr($jsonData,0,strlen($jsonData)-1);
	}
	$jsonData .= ']';
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);
	
	echo $jsonData;
?>	
