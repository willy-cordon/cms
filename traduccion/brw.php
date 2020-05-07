<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');
	
	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	
	
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query="SELECT SUBSTRING(P.PEREMPDES FROM 1 FOR 80) AS PEREMPDES,PERCODIGO,PERCOMPAN
			FROM PER_MAEST P
			ORDER BY PERCODIGO";

	
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$perempdes 	= trim($row['PEREMPDES']);
		$percodigo 	= trim($row['PERCODIGO']);
		$percompan 	= trim($row['PERCOMPAN']);
	
		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('perempdes'	, $perempdes);
		$tmpl->setVariable('percodigo'	, $percodigo);
		$tmpl->setVariable('percompan'	, $percompan);
		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
