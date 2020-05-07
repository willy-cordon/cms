<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mstpreg.html');

	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$encreg 	= (isset($_POST['encreg']))? trim($_POST['encreg']) : 0;
	$encpreitm 	= (isset($_POST['encpreitm']))? trim($_POST['encpreitm']) : 0;
	//logerror($encreg);
	//$estcodigo = 1; //Activo por defecto
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion

	if($encreg!=0 && $encpreitm!=0){
		$query = "	SELECT EP.ENCREG, EP.ENCPREITM, EP.ENCPREGUN, EP.ENCPRETIP, EP.ENCPREVAL,EP.ENCPREORD
					FROM ENC_PREG EP
					WHERE EP.ENCREG=$encreg AND EP.ENCPREITM=$encpreitm " ;
		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		$encreg 	= trim($row['ENCREG']);
		$encpreitm 	= trim($row['ENCPREITM']);
		$encpretip 	= trim($row['ENCPRETIP']);
		$encpregun 	= trim($row['ENCPREGUN']);
		$encpreval 	= trim($row['ENCPREVAL']);
		$encpreord 	= trim($row['ENCPREORD']);
		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('encreg'	, $encreg);
		$tmpl->setVariable('encpreitm'	, $encpreitm);
		$tmpl->setVariable('encpregun'	, $encpregun);
		$tmpl->setVariable('encpreval'	, $encpreval);
		$tmpl->setVariable('encpretipsel'.$encpretip , 'selected');
		$tmpl->setVariable('encpreord'	, $encpreord);
		$tmpl->parse('browser');

	}
	$tmpl->setVariable('encreg'	, $encreg);
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
