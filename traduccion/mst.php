<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mst.html');

	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;
	
	
	
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	$query="SELECT PEREMPDES,PERCODIGO
			FROM PER_MAEST WHERE PERCODIGO=$percodigo
			ORDER BY PERIDIOMA";

		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		$perempdes 	= trim($row['PEREMPDES']);
		$percodigo 	= trim($row['PERCODIGO']);

		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('perempdes'	, $perempdes);
		$tmpl->setVariable('percodigo'	, $percodigo);
		
		$tmpl->parse('browser');
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
