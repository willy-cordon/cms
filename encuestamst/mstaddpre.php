<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mstaddpre.html');
	$conn= sql_conectar();//Apertura de Conexion
	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	
	$encprereg = (isset($_POST['encreg']))? trim($_POST['encreg']) : 0;
	$encreg = (isset($_POST['prueba']))? trim($_POST['prueba']) : 0;
	logerror('PEPE'. $encprereg + "  " + $encreg);


	if($encprereg != 0){
	$query = "SELECT ENCPRENOM, ENCPREFIL, ENCCOD, ENCPREREG FROM ENC_PRES WHERE ENCPREREG = $encprereg";
	$Tableenc = sql_query($query,$conn);
	$rowenc = $Tableenc->Rows[0];

	$encprenom = $rowenc['ENCPRENOM'];
	$encprefil = $rowenc['ENCPREFIL'];
	$enccod = $rowenc['ENCCOD'];
	$encprereg = $rowenc['ENCPREREG'];
	$tmpl->setVariable('enprenom',$encprenom);
	$tmpl->setVariable('encprefil',$encprefil);
	$tmpl->setVariable('encreg', $enccod);
	$tmpl->setVariable('encprereg', $encprereg);
	
	
}else{
	$tmpl->setVariable('encreg', $encreg);
}


//$estcodigo = 1; //Activo por defecto
$encdescri = '';
$encpublic = 'N';
	
	//--------------------------------------------------------------------------------------------------------------
	


	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
