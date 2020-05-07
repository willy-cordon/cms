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
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	
	$fltdescri = (isset($_POST['fltdescri']))? trim($_POST['fltdescri']):'';
	
	$where = '';
	if($fltdescri!=''){
		$where .= " AND SECDESCRI CONTAINING '$fltdescri' ";
	}
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT IDICODIGO,  IDITRAESP , IDITRAVAL, IDITRAITM
				FROM IDI_TRAD
			
				ORDER BY IDITRAITM ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$idicodigo 	= trim($row['IDICODIGO']);
		$iditraesp 	= trim($row['IDITRAESP']);
		$iditraval 	= trim($row['IDITRAVAL']);
		$iditraitm 	= trim($row['IDITRAITM']);
		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('idicodigo'	, $idicodigo);
		$tmpl->setVariable('iditraesp'	, $iditraesp);
		$tmpl->setVariable('iditraval'	, $iditraval);
		$tmpl->setVariable('iditraitm'	, $iditraitm);
		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
