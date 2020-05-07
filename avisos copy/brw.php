<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');

	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	
	$fltdescri = (isset($_POST['fltdescri']))? trim($_POST['fltdescri']):'';
	//Filtro de busqueda por titulo
	$where = '';
	if($fltdescri!=''){
		$where .= " AND AVITITULO CONTAINING '$fltdescri' ";
	}
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT AVIREG, AVITITULO, AVIURL, AVIDESCRIP, ESTCODIGO
				FROM AVI_MAEST
				WHERE ESTCODIGO<>3 $where
				ORDER BY AVITITULO ";
				
	//logerror($query);			
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$avireg 	= trim($row['AVIREG']);
		$avititulo 	= trim($row['AVITITULO']);
		$avidescrip = trim($row['AVIDESCRIP']);
		$aviurl     = trim($row['AVIURL']);

		//$aviimagen  = trim($row['AVIIMAGEN']);
		
		$tmpl->setCurrentBlock('browser');
		
		$tmpl->setVariable('avireg'	, $avireg);
		$tmpl->setVariable('avititulo'	, $avititulo);
		$tmpl->setVariable('avidescrip'	, $avidescrip);
		$tmpl->setvariable('aviurl', $aviurl);
		
		//$tmpl->setvariable('aviimagen',$aviimagen);
		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
