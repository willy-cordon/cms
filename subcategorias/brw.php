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
	
	$where = '';
	if($fltdescri!=''){
		$where .= " AND CS.CATSUBDES CONTAINING '$fltdescri' ";
	}
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT CS.CATSUBDES, CM.CATDESCRI, SS.SECSUBDES, SM.SECDESCRI, CS.CATSUBCOD
				FROM CAT_SUB CS
				LEFT OUTER JOIN CAT_MAEST CM ON CM.CATCODIGO = CS.CATCODIGO				
				LEFT OUTER JOIN SEC_SUB SS ON SS.SECSUBCOD = CM.SECSUBCOD
				LEFT OUTER JOIN SEC_MAEST SM ON SM.SECCODIGO = SS.SECCODIGO	
                WHERE CS.ESTCODIGO<>3 $where
                ORDER BY CS.CATSUBDES ";
				
				
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$catsubcod 	= trim($row['CATSUBCOD']);
		$catsubdes 	= trim($row['CATSUBDES']);		
		$catdescri 	= trim($row['CATDESCRI']);
		$secsubdes 	= trim($row['SECSUBDES']);
		$secdescri 	= trim($row['SECDESCRI']);
		

		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('catsubcod'	, $catsubcod);
		$tmpl->setVariable('catsubdes'	, $catsubdes);		
		$tmpl->setVariable('catdescri'	, $catdescri);
		$tmpl->setVariable('secsubdes'	, $secsubdes);	
		$tmpl->setVariable('secdescri'	, $secdescri);	
		
		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
