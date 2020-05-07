<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('vsllink.html');
	
	//Diccionario de idiomas
	DDIdioma($tmpl);
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodlog = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	//--------------------------------------------------------------------------------------------------------------
	$reureg = (isset($_POST['reureg']))? trim($_POST['reureg']) : 0;
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT REULINK
				FROM REU_CABE 
				WHERE REUREG=$reureg ";
	$Table = sql_query($query,$conn);
	$row = $Table->Rows[0];
	$reulink 	= trim($row['REULINK']);
	$tmpl->setVariable('reureg'		, $reureg);
	$tmpl->setVariable('reulink'	, $reulink);


	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	

?>	
