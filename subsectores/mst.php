<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mst.html');

	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$secsubcod = (isset($_POST['secsubcod']))? trim($_POST['secsubcod']) : 0;
	$estcodigo = 1; //Activo por defecto
	$secdescri = '';
	$seccodigo = 0;
	logerror($seccodigo);
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion


	
	if($secsubcod!=0){
		$query = "	SELECT SECSUBCOD, SECSUBDES,SECSUBDESING, ESTCODIGO, SECCODIGO
					FROM SEC_SUB
					WHERE SECSUBCOD=$secsubcod";

		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$secsubcod 		= trim($row['SECSUBCOD']);
			$secsubdes 		= trim($row['SECSUBDES']);
			$secsubdesing 	= trim($row['SECSUBDESING']);
			$estcodigo 		= trim($row['ESTCODIGO']);
			$seccodigo 		= trim($row['SECCODIGO']);

			
			$tmpl->setVariable('secsubcod'		, $secsubcod	);
			$tmpl->setVariable('secsubdes'		, $secsubdes	);
			$tmpl->setVariable('secsubdesing'	, $secsubdesing	);
			$tmpl->setVariable('estcodigo'		, $estcodigo	);
			
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------
	//Listado de SectoreS
	
	$query = "	SELECT SECCODIGO,SECDESCRI
				FROM SEC_MAEST
				WHERE ESTCODIGO <>3 
				ORDER BY SECCODIGO";
	
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count;$i++){
		$row= $Table->Rows[$i];
		$seccod = trim($row['SECCODIGO']);
		$secdes = trim($row['SECDESCRI']);
		
		$tmpl->setCurrentBlock('sectores');
		$tmpl->setVariable('seccodigo'	, $seccod 	);
		$tmpl->setVariable('secdescri'	, $secdes 	);
		//logerror($seccod);
		if($seccodigo==$seccod){
			$tmpl->setVariable('secselected', 'selected' );
		}
		
		$tmpl->parseCurrentBlock('sectores');
	}
	//--------------------------------------------------------------------------------------------------------------
	
	
	sql_close($conn);	
	$tmpl->show();
	
?>	
