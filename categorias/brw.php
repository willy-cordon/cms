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
		$where .= " AND C.CATDESCRI CONTAINING '$fltdescri' ";
	}
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT SUBSTRING(C.CATDESCRI FROM 1 FOR 30) AS CATDESCRI, SUBSTRING(S.SECSUBDES FROM 1 FOR 20) AS SECSUBDES, 
					M.SECDESCRI, C.CATCODIGO
				FROM CAT_MAEST C
				LEFT OUTER JOIN SEC_SUB S ON S.SECSUBCOD = C.SECSUBCOD
				LEFT OUTER JOIN SEC_MAEST M ON M.SECCODIGO = S.SECCODIGO
                WHERE C.ESTCODIGO<>3 $where
                ORDER BY C.CATDESCRI ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$catcodigo 	= trim($row['CATCODIGO']);
		$catdescri 	= trim($row['CATDESCRI']);
		$secsubdes 	= trim($row['SECSUBDES']);
		$secdescri 	= trim($row['SECDESCRI']);

		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('catcodigo'	, $catcodigo);
		$tmpl->setVariable('catdescri'	, $catdescri);
		$tmpl->setVariable('secsubdes'	, $secsubdes);	
		$tmpl->setVariable('secdescri'	, $secdescri);	
		
		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
