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
		$where .= " AND MSGTITULO CONTAINING '$fltdescri' ";
	}
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT MSGREG, MSGFCHREG, MSGTITULO, MSGDESCRI, MSGESTADO
				FROM MSG_CABE
				WHERE MSGESTADO<>3 $where
				ORDER BY MSGTITULO ";
	logerror($query);
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$msgreg 	= trim($row['MSGREG']);
		$msgfchreg 	= trim($row['MSGFCHREG']);
		$msgtitulo 	= trim($row['MSGTITULO']);
		$msgdescri 	= trim($row['MSGDESCRI']);
		$msgestado 	= trim($row['MSGESTADO']);

		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('msgreg'	, $msgreg);
		$tmpl->setVariable('msgfchreg'	, $msgfchreg);
		$tmpl->setVariable('msgtitulo'	, $msgtitulo);
		$tmpl->setVariable('msgdescri'	, $msgdescri);
		$tmpl->setVariable('msgestado'	, $msgestado);

		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
