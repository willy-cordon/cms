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
		$where .= " AND S.SECSUBDES CONTAINING '$fltdescri' ";
	}
	
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT S.SECSUBCOD, S.SECSUBDES, M.SECDESCRI
                FROM SEC_SUB S
                LEFT OUTER JOIN SEC_MAEST M ON M.SECCODIGO=S.SECCODIGO
                WHERE S.ESTCODIGO<>3 $where
                ORDER BY S.SECSUBDES ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$secsubcod 	= trim($row['SECSUBCOD']);
		$secsubdes 	= trim($row['SECSUBDES']);
		$secdescri 	= trim($row['SECDESCRI']);

		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('secsubcod'	, $secsubcod);
		$tmpl->setVariable('secsubdes'	, $secsubdes);
		$tmpl->setVariable('secdescri'	, $secdescri);		
		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
