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
	
	$query = "	SELECT PITCODIGO, PITNOMBRE, PITDES, PITCON
				FROM PIT_MAEST
				ORDER BY PITNOMBRE ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$pitcodigo 	= trim($row['PITCODIGO']);
		$pitnombre 	= trim($row['PITNOMBRE']);
		$pitcon 	= trim($row['PITCON']);
		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('pitcodigo'	, $pitcodigo);
		$tmpl->setVariable('pitnombre'	, $pitnombre);
		$tmpl->setVariable('pitcon'	, $pitcon);
		$tmpl->parse('browser');
	}


/* -------------------------- CALCULO VOTOS TOTALES ------------------------- */

$queryVotos = "SELECT SUM(PITCON) AS SUMA FROM PIT_MAEST";
$TableVotos = sql_query($queryVotos,$conn);
$rowsuma= $TableVotos->Rows[0];

$tmpl->setVariable('votos',$rowsuma['SUMA']);


/* ------------------------------------ X ----------------------------------- */

	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
