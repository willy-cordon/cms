<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mstPre.html');
	$conn= sql_conectar();//Apertura de Conexion
	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	
	$encreg = (isset($_POST['encreg']))? trim($_POST['encreg']) : 0;
	
	//$estcodigo = 1; //Activo por defecto
	$encdescri = '';
	$encpublic = 'N';

	
	/* --------------------------- SECTION SET TITULO --------------------------- */
	$queryenc = "SELECT ENCDESCRI FROM ENC_CABE WHERE ENCREG = $encreg";
	$Tableenc = sql_query($queryenc,$conn);
	$rowenc = $Tableenc->Rows[0];
	$encdescri = $rowenc['ENCDESCRI'];
	$tmpl->setVariable('encdescri',$encdescri);
	
	//--------------------------------------------------------------------------
	
	$tmpl->setVariable('encreg'	, $encreg);


	//----ANCHOR TRAIGO TODAS LAS PRESENTACIONES QUE TENGAN EL ID DE LA ENCUESTA----//
	if($encreg!=0){
		$query="SELECT ENCPRENOM, ENCPREREG
				FROM ENC_PRES 
				WHERE ENCCOD=$encreg ";


	$Table = sql_query($query,$conn);

	for($i=0; $i<$Table->Rows_Count; $i++){
		
		$row = $Table->Rows[$i];
		
		$encprenom 	= trim($row['ENCPRENOM']);
		$encprereg  = trim($row['ENCPREREG']);
		
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('encprenom'	, $encprenom);
		$tmpl->setVariable('encprereg'	, $encprereg);
		$tmpl->parse('browser');	
	
	}}

	
	//--------------------------------------------------------------------------------------------------------------
	
	sql_close($conn);	
	$tmpl->show();
	
?>	
