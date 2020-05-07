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
	
	
	
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "SELECT EXPREG, EXPNOMBRE, EXPWEB, EXPMAIL, EXPSTAND, EXPRUBROS, EXPPOSX, EXPPOSY, ESTCODIGO
				FROM EXP_MAEST
				WHERE ESTCODIGO<>3
				ORDER BY EXPNOMBRE ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$expreg 	= trim($row['EXPREG']);
		$expnombre 	= trim($row['EXPNOMBRE']);
		$expweb 	= trim($row['EXPWEB']);
		$expmail 	= trim($row['EXPMAIL']);
		$expstand 	= trim($row['EXPSTAND']);
		$exprubros 	= trim($row['EXPRUBROS']);
		$expposx 	= trim($row['EXPPOSX']);
		$expposy 	= trim($row['EXPPOSY']);
	
		
		//Asignamos los datos para cargar desde el templatee
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('expreg'		, $expreg);
		$tmpl->setVariable('expnombre'	, $expnombre);
		$tmpl->setVariable('expweb'		, $expweb);
		$tmpl->setVariable('expmail'	, $expmail);
		$tmpl->setVariable('exprubros'	, $exprubros);
		$tmpl->setVariable('expposx'	, $expposx);
		$tmpl->setVariable('expposy'	, $expposy);
		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
