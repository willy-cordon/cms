<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mst.html');

	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$encreg = (isset($_POST['encreg']))? trim($_POST['encreg']) : 0;
	//logerror("MST ".$encreg);
	//$estcodigo = 1; //Activo por defecto
	$encdescri = '';
	$encpublic = 'N';
	
	//--------------------------------------------------------------------------------------------------------------
	//Apertura de Conexion
	$conn= sql_conectar();

	//----Titulo de la encuesta----//
	if($encreg!=0){
		$query="SELECT ENCREG,ENCDESCRI,ENCFCHREG,ENCPUBLIC 
				FROM ENC_CABE 
				WHERE ENCREG=$encreg ";
		
		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		$encreg 	= trim($row['ENCREG']);
		$encdescri 	= trim($row['ENCDESCRI']);
		$encfchreg 	= trim($row['ENCFCHREG']);
		$encpublic 	= trim($row['ENCPUBLIC']);
	
		$tmpl->setVariable('encdescri'	, $encdescri);
		$tmpl->setVariable('encfchreg'	, $encfchreg);
	}
	$tmpl->setVariable('encpublic'.$encpublic, 'selected');
	
	$tmpl->setVariable('encreg'	, $encreg);
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
