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
	$seccodigo = (isset($_POST['seccodigo']))? trim($_POST['seccodigo']) : 0;
	$estcodigo = 1; //Activo por defecto
	$secdescri = '';
	
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($seccodigo!=0){
		$query = "	SELECT SECCODIGO, SECDESCRI,SECDESING, ESTCODIGO
					FROM SEC_MAEST
					WHERE SECCODIGO=$seccodigo";

		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$seccodigo = trim($row['SECCODIGO']);
			$secdescri = trim($row['SECDESCRI']);
			$secdesing = trim($row['SECDESING']);
			$estcodigo = trim($row['ESTCODIGO']);
			
			$tmpl->setVariable('seccodigo'	, $seccodigo	);
			$tmpl->setVariable('secdescri'	, $secdescri	);
			$tmpl->setVariable('secdesing'	, $secdesing	);
			$tmpl->setVariable('estcodigo'	, $estcodigo	);
			
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
