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

	//$fltdescri = (isset($_POST['fltdescri']))? trim($_POST['fltdescri']):'';


	$conn= sql_conectar();//Apertura de Conexion

	$query="SELECT ENCREG,ENCDESCRI,ENCFCHREG,ENCPUBLIC
			FROM ENC_CABE 
			WHERE ESTCODIGO<>3 
			ORDER BY ENCDESCRI ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$encreg 	= trim($row['ENCREG']);
		$encdescri 	= trim($row['ENCDESCRI']);
		$encfchreg 	= trim($row['ENCFCHREG']);
		$encpublic 	= trim($row['ENCPUBLIC']);
		
		if($encpublic=='S'){
			$encpublic='SI';
		}else{
			$encpublic='NO';
		}
		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('encreg'	, $encreg);
		$tmpl->setVariable('encdescri'	, $encdescri);
		$tmpl->setVariable('encfchreg'	, $encfchreg);
		$tmpl->setVariable('encpublic'	, $encpublic);
		$tmpl->parse('browser');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);
	$tmpl->show();

?>
