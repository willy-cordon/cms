<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mst.html');
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$mescodigo = (isset($_POST['mescodigo']))? trim($_POST['mescodigo']) : 0;
	$estcodigo = 1; //Activo por defecto
	$secnumero = '';
	
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($mescodigo!=0){
		$query = "	SELECT MESCODIGO, MESNUMERO, ESTCODIGO
					FROM MES_MAEST
					WHERE MESCODIGO=$mescodigo";

		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$mescodigo = trim($row['MESCODIGO']);
			$mesnumero = trim($row['MESNUMERO']);
			$estcodigo = trim($row['ESTCODIGO']);
			
			$tmpl->setVariable('mescodigo'	, $mescodigo	);
			$tmpl->setVariable('mesnumero'	, $mesnumero	);
			$tmpl->setVariable('estcodigo'	, $estcodigo	);
			
		}
	}
	
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
