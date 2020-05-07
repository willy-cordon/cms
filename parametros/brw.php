<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');

	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT PARREG,PARCODIGO,PARVALOR,PARORDEN
				FROM PAR_MAEST
				ORDER BY PARCODIGO,PARORDEN, PARREG ";



	//logerror($query);
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$parreg 	= trim($row['PARREG']);
		$parcodigo 	= trim($row['PARCODIGO']);
		$parvalor  	= trim($row['PARVALOR']);
		$parorden   = trim($row['PARORDEN']);
		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('parreg' 	, $parreg);
		$tmpl->setVariable('parcodigo' 	, $parcodigo);
		$tmpl->setVariable('parvalor'  	, $parvalor);
		$tmpl->setvariable('parorden'   , $parorden);
		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
